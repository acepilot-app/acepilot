<?php
/**
 * License Lookup by Stripe Session ID
 * Used by success page to retrieve the generated license key.
 *
 * GET /api/license-lookup.php?session_id=cs_xxx
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://acepilot.app');

$sessionId = $_GET['session_id'] ?? '';

if (!$sessionId || !preg_match('/^cs_/', $sessionId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid session ID']);
    exit;
}

$licensesFile = __DIR__ . '/../licenses.json';

if (!file_exists($licensesFile)) {
    http_response_code(404);
    echo json_encode(['error' => 'No licenses found']);
    exit;
}

$data = json_decode(file_get_contents($licensesFile), true);

foreach ($data['licenses'] as $license) {
    if (($license['stripe_session'] ?? '') === $sessionId) {
        echo json_encode([
            'key' => $license['key'],
            'email' => $license['email'],
            'plan' => $license['plan'],
        ]);
        exit;
    }
}

// Webhook may not have fired yet — tell client to retry
http_response_code(202);
echo json_encode(['pending' => true, 'message' => 'License is being generated. Retry in a few seconds.']);
