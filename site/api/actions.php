<?php
/**
 * AcePilot Human Actions API
 * When AcePilot can't do something itself (Stripe setup, DNS config, PR approval),
 * it creates a human action item. Users see these on the dashboard and mark them done.
 * AcePilot picks up completions on the next session.
 *
 * POST   /api/actions.php              — create action (from CLI)
 * GET    /api/actions.php              — list actions
 * GET    /api/actions.php?status=pending — list pending actions only
 * POST   /api/actions.php?action=update — mark done/dismiss
 * GET    /api/actions.php?action=pending_count — quick count for badge
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
$actionsDir = __DIR__ . '/../data/actions/';

function authenticateRequest() {
    global $licensesFile;
    if (isset($_SESSION['user']['email'])) {
        return $_SESSION['user']['email'];
    }
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

$userDir = $actionsDir . md5(strtolower($email)) . '/';
if (!is_dir($userDir)) {
    mkdir($userDir, 0755, true);
}

$action = $_GET['action'] ?? '';

// CSRF check for browser-session POSTs (not CLI with X-License-Key)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SERVER['HTTP_X_LICENSE_KEY'])) {
    $csrfHeader = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (empty($_SESSION['csrf_token']) || $csrfHeader !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
    // Update action status (mark done, dismiss, snooze)
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? '';

    $files = glob($userDir . '*.json');
    $found = false;
    foreach ($files as $file) {
        $item = json_decode(file_get_contents($file), true);
        if ($item['id'] === $id) {
            $item['status'] = $input['status'] ?? $item['status'];
            if (isset($input['note'])) $item['resolution_note'] = $input['note'];
            $item['resolved_at'] = ($input['status'] === 'done' || $input['status'] === 'dismissed')
                ? date('c') : null;
            file_put_contents($file, json_encode($item, JSON_PRETTY_PRINT));
            echo json_encode(['success' => true, 'action' => $item]);
            $found = true;
            break;
        }
    }
    if (!$found) {
        http_response_code(404);
        echo json_encode(['error' => 'Action not found']);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CLI creates a human action
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['title'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Action title required']);
        exit;
    }

    $item = [
        'id' => uniqid('act_', true),
        'title' => trim($input['title']),
        'description' => trim($input['description'] ?? ''),
        'project' => $input['project'] ?? null,
        'task_id' => $input['task_id'] ?? null,       // which TASKS.md entry is blocked
        'link' => $input['link'] ?? null,              // URL the user needs to visit
        'priority' => $input['priority'] ?? 'medium',  // high, medium, low
        'category' => $input['category'] ?? 'manual',  // manual, approval, config, payment, deploy
        'status' => 'pending',                          // pending, done, dismissed, snoozed
        'resolution_note' => null,
        'created' => date('c'),
        'resolved_at' => null
    ];

    $filename = date('Ymd-His') . '-' . $item['id'] . '.json';
    file_put_contents($userDir . $filename, json_encode($item, JSON_PRETTY_PRINT));

    echo json_encode(['success' => true, 'action' => $item]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if ($action === 'pending_count') {
        // Quick count for dashboard badge
        $files = glob($userDir . '*.json');
        $count = 0;
        foreach ($files as $file) {
            $item = json_decode(file_get_contents($file), true);
            if ($item['status'] === 'pending') $count++;
        }
        echo json_encode(['pending' => $count]);

    } elseif ($action === 'check') {
        // CLI checks if any actions were completed since last session
        $since = $_GET['since'] ?? null;
        $files = glob($userDir . '*.json');
        $completed = [];
        foreach ($files as $file) {
            $item = json_decode(file_get_contents($file), true);
            if ($item['status'] === 'done' && $item['resolved_at']) {
                if (!$since || $item['resolved_at'] > $since) {
                    $completed[] = [
                        'id' => $item['id'],
                        'title' => $item['title'],
                        'task_id' => $item['task_id'],
                        'project' => $item['project'],
                        'resolved_at' => $item['resolved_at'],
                        'resolution_note' => $item['resolution_note']
                    ];
                }
            }
        }
        echo json_encode(['completed' => $completed]);

    } else {
        // List actions
        $statusFilter = $_GET['status'] ?? null;
        $projectFilter = $_GET['project'] ?? null;
        $files = glob($userDir . '*.json');
        rsort($files);

        $actions = [];
        $pendingCount = 0;
        foreach ($files as $file) {
            $item = json_decode(file_get_contents($file), true);
            if ($item['status'] === 'pending') $pendingCount++;
            if ($statusFilter && $item['status'] !== $statusFilter) continue;
            if ($projectFilter && $item['project'] !== $projectFilter) continue;
            $actions[] = $item;
        }

        echo json_encode([
            'actions' => $actions,
            'pending_count' => $pendingCount
        ]);
    }
}
