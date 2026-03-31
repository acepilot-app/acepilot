<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://acepilot.app');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email']);
    exit;
}

$csvFile = __DIR__ . '/../waitlist.csv';
$isNew = !file_exists($csvFile);

$fp = fopen($csvFile, 'a');
if ($fp === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Storage error']);
    exit;
}

if ($isNew) {
    fputcsv($fp, ['email', 'date', 'source']);
}

fputcsv($fp, [$email, date('Y-m-d H:i:s'), 'acepilot.app']);
fclose($fp);

// Send notification email
$to = 'paulo@acepilot.app';
$subject = 'AcePilot waitlist signup';
$message = "New waitlist signup:\n\nEmail: $email\nDate: " . date('Y-m-d H:i:s') . "\n";
$headers = 'From: noreply@acepilot.app';
@mail($to, $subject, $message, $headers);

echo json_encode(['success' => true, 'message' => 'Added to waitlist']);
