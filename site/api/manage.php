<?php
/**
 * Redirect to Stripe Customer Portal
 * Lets users manage their subscription (cancel, update card, view invoices).
 * Requires authenticated session — uses session email, not query param.
 *
 * GET /api/manage.php (must be logged in)
 */

session_set_cookie_params([
    'lifetime' => 86400 * 30,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

$config = require __DIR__ . '/config.php';
$stripeKey = $config['stripe']['secret_key'];
$appUrl = $config['app']['url'];

// Require authenticated session — no unauthenticated access
if (!isset($_SESSION['user']['email'])) {
    http_response_code(401);
    echo 'Please log in to manage your subscription.';
    exit;
}

$email = $_SESSION['user']['email'];

// Find Stripe customer by email
$licensesFile = __DIR__ . '/../licenses.json';
$customerId = null;

if (file_exists($licensesFile)) {
    $data = json_decode(file_get_contents($licensesFile), true);
    foreach ($data['licenses'] as $license) {
        if ($license['email'] === $email && isset($license['stripe_customer'])) {
            $customerId = $license['stripe_customer'];
            break;
        }
    }
}

if (!$customerId) {
    http_response_code(404);
    echo 'No subscription found for this email. Contact support@acepilot.app if you need help.';
    exit;
}

// Create Stripe Customer Portal session
$ch = curl_init('https://api.stripe.com/v1/billing_portal/sessions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_USERPWD => $stripeKey . ':',
    CURLOPT_POSTFIELDS => http_build_query([
        'customer' => $customerId,
        'return_url' => "$appUrl/dashboard/",
    ]),
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(500);
    error_log("Stripe portal error: $response");
    echo 'Unable to load subscription management. Contact support@acepilot.app';
    exit;
}

$session = json_decode($response, true);

if (!isset($session['url'])) {
    http_response_code(500);
    echo 'Unable to load subscription management.';
    exit;
}

// Verify redirect is to Stripe (prevent open redirect)
if (!str_starts_with($session['url'], 'https://billing.stripe.com/')) {
    http_response_code(500);
    error_log("Stripe returned unexpected portal URL: " . $session['url']);
    echo 'Unable to load subscription management.';
    exit;
}

header('Location: ' . $session['url']);
exit;
