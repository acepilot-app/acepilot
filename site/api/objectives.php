<?php
/**
 * AcePilot CEO Objectives API
 * Create, list, update, and track CEO mode objectives.
 *
 * GET    /api/objectives.php              — list objectives
 * GET    /api/objectives.php?id=xxx       — get objective detail
 * POST   /api/objectives.php              — create objective
 * POST   /api/objectives.php?action=update — update objective
 * GET    /api/objectives.php?action=pending — get next pending objective (for CLI pull)
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
$objectivesDir = __DIR__ . '/../data/objectives/';

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

$userDir = $objectivesDir . md5(strtolower($email)) . '/';
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
    // Update existing objective
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? '';

    if (!preg_match('/^obj_[0-9a-f.]+$/i', $id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid objective ID format']);
        exit;
    }

    $files = glob($userDir . '*' . $id . '*.json');
    if (empty($files)) {
        http_response_code(404);
        echo json_encode(['error' => 'Objective not found']);
        exit;
    }

    $objective = json_decode(file_get_contents($files[0]), true);

    // Updatable fields
    $updatable = ['status', 'pr_description', 'strategy', 'tasks', 'assessment', 'pr_url', 'sessions'];
    foreach ($updatable as $field) {
        if (isset($input[$field])) {
            $objective[$field] = $input[$field];
        }
    }
    $objective['updated'] = date('c');

    file_put_contents($files[0], json_encode($objective, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true, 'objective' => $objective]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create new objective
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['objective'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Objective text required']);
        exit;
    }

    $objective = [
        'id' => uniqid('obj_', true),
        'objective' => trim($input['objective']),
        'project' => $input['project'] ?? null,
        'status' => 'pending', // pending → in_progress → completed → assessed
        'pr_description' => null, // Working Backwards target PR
        'strategy' => null,       // Strategy from researcher
        'tasks' => [],            // Task list generated
        'assessment' => null,     // Post-check result
        'pr_url' => null,         // Final PR URL
        'sessions' => [],         // Session IDs linked to this objective
        'created' => date('c'),
        'updated' => date('c')
    ];

    $filename = date('Ymd-His') . '-' . $objective['id'] . '.json';
    file_put_contents($userDir . $filename, json_encode($objective, JSON_PRETTY_PRINT));

    echo json_encode(['success' => true, 'objective' => $objective]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;

    if ($action === 'pending') {
        // CLI pulls next pending objective
        $files = glob($userDir . '*.json');
        sort($files); // oldest first
        foreach ($files as $file) {
            $obj = json_decode(file_get_contents($file), true);
            if ($obj['status'] === 'pending') {
                echo json_encode(['objective' => $obj]);
                exit;
            }
        }
        echo json_encode(['objective' => null]);

    } elseif ($id) {
        // Get specific objective
        if (!preg_match('/^obj_[0-9a-f.]+$/i', $id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid objective ID format']);
            exit;
        }
        $files = glob($userDir . '*' . $id . '*.json');
        if (empty($files)) {
            http_response_code(404);
            echo json_encode(['error' => 'Objective not found']);
            exit;
        }
        echo json_encode(json_decode(file_get_contents($files[0]), true));

    } else {
        // List all objectives
        $files = glob($userDir . '*.json');
        rsort($files);

        $objectives = [];
        foreach ($files as $file) {
            $obj = json_decode(file_get_contents($file), true);
            $objectives[] = [
                'id' => $obj['id'],
                'objective' => $obj['objective'],
                'project' => $obj['project'],
                'status' => $obj['status'],
                'pr_url' => $obj['pr_url'],
                'sessions' => count($obj['sessions'] ?? []),
                'created' => $obj['created'],
                'updated' => $obj['updated']
            ];
        }

        echo json_encode(['objectives' => $objectives]);
    }
}
