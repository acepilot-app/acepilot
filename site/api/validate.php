<?php
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed = ['https://acepilot.app', 'http://localhost'];
if (in_array($origin, $allowed)) {
    header("Access-Control-Allow-Origin: $origin");
}
header('Access-Control-Allow-Methods: POST, GET');

// License database — flat file, upgrade to DB when needed
$licensesFile = __DIR__ . '/../licenses.json';

if (!file_exists($licensesFile)) {
    http_response_code(500);
    echo json_encode(['valid' => false, 'error' => 'License database not found']);
    exit;
}

$data = json_decode(file_get_contents($licensesFile), true);
$licenses = $data['licenses'] ?? [];

// GET /api/validate.php?key=xxx — validate a license key
$key = $_GET['key'] ?? $_POST['key'] ?? '';

if (empty($key)) {
    http_response_code(400);
    echo json_encode(['valid' => false, 'error' => 'No key provided']);
    exit;
}

foreach ($licenses as $license) {
    if ($license['key'] === $key) {
        // Check expiry
        if ($license['expires'] !== 'never') {
            $expiryDate = strtotime($license['expires']);
            if ($expiryDate && $expiryDate < time()) {
                echo json_encode([
                    'valid' => false,
                    'error' => 'License expired',
                    'expired' => $license['expires']
                ]);
                exit;
            }
        }

        echo json_encode([
            'valid' => true,
            'plan' => $license['plan'],
            'type' => $license['type']
        ]);
        exit;
    }
}

echo json_encode(['valid' => false, 'error' => 'Invalid license key']);
