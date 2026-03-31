<?php
/**
 * AcePilot Session Sync API
 * CLI pushes session summaries after each session completes.
 *
 * POST /api/sessions.php          — push session data (requires license key in header)
 * GET  /api/sessions.php          — list sessions (requires auth session)
 * GET  /api/sessions.php?id=xxx   — get session detail
 */

session_set_cookie_params([
    'lifetime' => 86400 * 30,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed = ['https://acepilot.app', 'http://localhost'];
if (in_array($origin, $allowed)) {
    header("Access-Control-Allow-Origin: $origin");
}
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-License-Key');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$licensesFile = __DIR__ . '/../licenses.json';
$sessionsDir = __DIR__ . '/../data/sessions/';

/**
 * Authenticate via license key header (for CLI) or session cookie (for dashboard)
 * Returns email on success, null on failure
 */
function authenticateRequest() {
    global $licensesFile;

    // Try session cookie first (dashboard)
    if (isset($_SESSION['user']['email'])) {
        return $_SESSION['user']['email'];
    }

    // Try license key header (CLI)
    $key = $_SERVER['HTTP_X_LICENSE_KEY'] ?? '';
    if (!$key) return null;

    $data = json_decode(file_get_contents($licensesFile), true);
    foreach ($data['licenses'] ?? [] as $license) {
        if ($license['key'] === $key) {
            if ($license['expires'] !== 'never') {
                $expiryDate = strtotime($license['expires']);
                if ($expiryDate && $expiryDate < time()) return null;
            }
            return $license['email'];
        }
    }
    return null;
}

$email = authenticateRequest();
if (!$email) {
    http_response_code(401);
    echo json_encode(['error' => 'Authentication required']);
    exit;
}

// Sanitize email for filesystem
$userDir = $sessionsDir . md5(strtolower($email)) . '/';
if (!is_dir($userDir)) {
    mkdir($userDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CLI pushes session data
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['project'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Session data with project name required']);
        exit;
    }

    $session = [
        'id' => uniqid('ses_', true),
        'project' => $input['project'],
        'mode' => $input['mode'] ?? 'unknown',
        'focus' => $input['focus'] ?? null,
        'tasks_done' => intval($input['tasks_done'] ?? 0),
        'tasks_total' => intval($input['tasks_total'] ?? 0),
        'tasks_blocked' => intval($input['tasks_blocked'] ?? 0),
        'tasks_concerns' => intval($input['tasks_concerns'] ?? 0),
        'decisions_count' => intval($input['decisions_count'] ?? 0),
        'knowledge_entries' => intval($input['knowledge_entries'] ?? 0),
        'cycle_time_avg' => $input['cycle_time_avg'] ?? null,
        'tests_passed' => intval($input['tests_passed'] ?? 0),
        'tests_total' => intval($input['tests_total'] ?? 0),
        'tests_created' => intval($input['tests_created'] ?? 0),
        'pr_url' => $input['pr_url'] ?? null,
        'objective' => $input['objective'] ?? null,
        'objective_id' => $input['objective_id'] ?? null,
        'summary' => $input['summary'] ?? '',
        'timestamp' => date('c'),
        'duration_min' => $input['duration_min'] ?? null
    ];

    $filename = date('Ymd-His') . '-' . $session['id'] . '.json';
    file_put_contents($userDir . $filename, json_encode($session, JSON_PRETTY_PRINT));

    echo json_encode(['success' => true, 'session_id' => $session['id']]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;

    if ($id) {
        // Validate id format: ses_ prefix + hex/dots only (uniqid output)
        if (!preg_match('/^ses_[0-9a-f.]+$/i', $id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid session ID format']);
            exit;
        }
        // Get specific session
        $files = glob($userDir . '*' . $id . '*.json');
        if (empty($files)) {
            http_response_code(404);
            echo json_encode(['error' => 'Session not found']);
            exit;
        }
        echo file_get_contents($files[0]);
    } else {
        // List all sessions, newest first
        $project = $_GET['project'] ?? null;
        $files = glob($userDir . '*.json');
        rsort($files); // newest first (filenames are date-prefixed)

        $sessions = [];
        $limit = intval($_GET['limit'] ?? 50);
        $count = 0;

        foreach ($files as $file) {
            if ($count >= $limit) break;
            $session = json_decode(file_get_contents($file), true);
            if ($project && $session['project'] !== $project) continue;

            // Return summary, not full session
            $sessions[] = [
                'id' => $session['id'],
                'project' => $session['project'],
                'mode' => $session['mode'],
                'focus' => $session['focus'],
                'tasks_done' => $session['tasks_done'],
                'tasks_total' => $session['tasks_total'],
                'tasks_blocked' => $session['tasks_blocked'],
                'timestamp' => $session['timestamp'],
                'pr_url' => $session['pr_url'],
                'objective' => $session['objective']
            ];
            $count++;
        }

        // Aggregate projects with enriched metrics
        $projectFiles = glob($userDir . '*.json');
        $projects = [];
        foreach ($projectFiles as $file) {
            $s = json_decode(file_get_contents($file), true);
            $p = $s['project'];
            if (!isset($projects[$p])) {
                $projects[$p] = [
                    'name' => $p,
                    'sessions' => 0,
                    'tasks_done' => 0,
                    'tasks_blocked' => 0,
                    'tasks_concerns' => 0,
                    'decisions' => 0,
                    'knowledge_entries' => 0,
                    'tests_created' => 0,
                    'prs' => 0,
                    'last_mode' => null,
                    'last_session' => $s['timestamp']
                ];
            }
            $projects[$p]['sessions']++;
            $projects[$p]['tasks_done'] += $s['tasks_done'] ?? 0;
            $projects[$p]['tasks_blocked'] += $s['tasks_blocked'] ?? 0;
            $projects[$p]['tasks_concerns'] += $s['tasks_concerns'] ?? 0;
            $projects[$p]['decisions'] += $s['decisions_count'] ?? 0;
            $projects[$p]['tests_created'] += $s['tests_created'] ?? 0;
            if (!empty($s['pr_url'])) $projects[$p]['prs']++;
            if ($s['timestamp'] > $projects[$p]['last_session']) {
                $projects[$p]['last_session'] = $s['timestamp'];
                $projects[$p]['last_mode'] = $s['mode'] ?? null;
                $projects[$p]['knowledge_entries'] = max($projects[$p]['knowledge_entries'], $s['knowledge_entries'] ?? 0);
            }
        }

        echo json_encode([
            'sessions' => $sessions,
            'projects' => array_values($projects),
            'total_sessions' => count($projectFiles)
        ]);
    }
}
