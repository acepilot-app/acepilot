<?php
/**
 * AcePilot Dashboard Auth API
 * Login with email + license key. Session stored server-side.
 *
 * POST /api/auth.php?action=login   — { email, key }
 * POST /api/auth.php?action=logout
 * GET  /api/auth.php?action=check   — returns session info
 */

session_set_cookie_params([
    'lifetime' => 86400 * 30, // 30 days
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
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$action = $_GET['action'] ?? '';
$licensesFile = __DIR__ . '/../licenses.json';

switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'POST required']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $key = trim($input['key'] ?? '');

        if (!$email || !$key) {
            http_response_code(400);
            echo json_encode(['error' => 'Email and license key required']);
            exit;
        }

        $data = json_decode(file_get_contents($licensesFile), true);
        $licenses = $data['licenses'] ?? [];
        $matched = null;

        foreach ($licenses as $license) {
            if ($license['key'] === $key && strtolower($license['email']) === strtolower($email)) {
                // Check expiry
                if ($license['expires'] !== 'never') {
                    $expiryDate = strtotime($license['expires']);
                    if ($expiryDate && $expiryDate < time()) {
                        http_response_code(403);
                        echo json_encode(['error' => 'License expired']);
                        exit;
                    }
                }
                $matched = $license;
                break;
            }
        }

        if (!$matched) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid email or license key']);
            exit;
        }

        // Create session + CSRF token
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['user'] = [
            'email' => $matched['email'],
            'plan' => $matched['plan'],
            'type' => $matched['type'],
            'key_prefix' => substr($matched['key'], 0, 8) . '...',
            'logged_in' => time()
        ];

        echo json_encode([
            'success' => true,
            'user' => $_SESSION['user'],
            'csrf_token' => $_SESSION['csrf_token']
        ]);
        break;

    case 'logout':
        session_destroy();
        echo json_encode(['success' => true]);
        break;

    case 'check':
        if (isset($_SESSION['user'])) {
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            echo json_encode([
                'authenticated' => true,
                'user' => $_SESSION['user'],
                'csrf_token' => $_SESSION['csrf_token']
            ]);
        } else {
            echo json_encode(['authenticated' => false]);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action. Use: login, logout, check']);
}
