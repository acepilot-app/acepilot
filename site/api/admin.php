<?php
// Admin endpoint — protected by admin key
// Usage: POST /api/admin.php with admin_key + action
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

$config = require __DIR__ . '/config.php';
$adminKey = $config['admin']['key'] ?? '';
$licensesFile = __DIR__ . '/../licenses.json';

if (empty($adminKey)) {
    http_response_code(500);
    echo json_encode(['error' => 'Admin key not configured']);
    exit;
}

$providedKey = $_POST['admin_key'] ?? '';
if ($providedKey !== $adminKey) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';
$data = json_decode(file_get_contents($licensesFile), true);
$licenses = $data['licenses'] ?? [];

switch ($action) {
    case 'list':
        echo json_encode(['licenses' => $licenses]);
        break;

    case 'create':
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $plan = $_POST['plan'] ?? 'pro';
        $type = $_POST['type'] ?? 'paid';
        $expires = $_POST['expires'] ?? 'never';

        if (!$email) {
            http_response_code(400);
            echo json_encode(['error' => 'Valid email required']);
            exit;
        }

        // Generate key: plan-type-random
        $key = $plan . '-' . $type . '-' . bin2hex(random_bytes(8));

        $newLicense = [
            'key' => $key,
            'email' => $email,
            'plan' => $plan,
            'type' => $type,
            'created' => date('Y-m-d'),
            'expires' => $expires,
            'notes' => ''
        ];

        $licenses[] = $newLicense;
        $data['licenses'] = $licenses;
        file_put_contents($licensesFile, json_encode($data, JSON_PRETTY_PRINT));

        echo json_encode(['created' => $newLicense]);
        break;

    case 'revoke':
        $key = $_POST['key'] ?? '';
        $found = false;
        foreach ($licenses as $i => $license) {
            if ($license['key'] === $key) {
                unset($licenses[$i]);
                $found = true;
                break;
            }
        }
        if ($found) {
            $data['licenses'] = array_values($licenses);
            file_put_contents($licensesFile, json_encode($data, JSON_PRETTY_PRINT));
            echo json_encode(['revoked' => $key]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Key not found']);
        }
        break;

    case 'stats':
        $total = count($licenses);
        $byPlan = [];
        $byType = [];
        foreach ($licenses as $l) {
            $byPlan[$l['plan']] = ($byPlan[$l['plan']] ?? 0) + 1;
            $byType[$l['type']] = ($byType[$l['type']] ?? 0) + 1;
        }
        echo json_encode(['total' => $total, 'by_plan' => $byPlan, 'by_type' => $byType]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action. Use: list, create, revoke, stats']);
}
