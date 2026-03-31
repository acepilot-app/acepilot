<?php
/**
 * AcePilot Stripe Checkout
 * Creates a Checkout Session and redirects the user to Stripe.
 *
 * GET /api/checkout.php?plan=pro_monthly
 * GET /api/checkout.php?plan=pro_yearly
 */

$config = require __DIR__ . '/config.php';
$stripe = $config['stripe'];
$appUrl = $config['app']['url'];

$plan = $_GET['plan'] ?? 'pro_monthly';

$priceMap = [
    'pro_monthly' => $stripe['price_pro_monthly'],
    'pro_yearly'  => $stripe['price_pro_yearly'],
];

if (!isset($priceMap[$plan])) {
    http_response_code(400);
    echo 'Invalid plan. Use: pro_monthly or pro_yearly';
    exit;
}

$priceId = $priceMap[$plan];

// Create Stripe Checkout Session via API
$ch = curl_init('https://api.stripe.com/v1/checkout/sessions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_USERPWD => $stripe['secret_key'] . ':',
    CURLOPT_POSTFIELDS => http_build_query([
        'mode' => 'subscription',
        'payment_method_types[0]' => 'card',
        'line_items[0][price]' => $priceId,
        'line_items[0][quantity]' => 1,
        'success_url' => "$appUrl/success.html?session_id={CHECKOUT_SESSION_ID}",
        'cancel_url' => "$appUrl/#pricing",
        'allow_promotion_codes' => 'true',
        'subscription_data[metadata][product]' => 'acepilot_pro',
        'subscription_data[metadata][plan]' => $plan,
    ]),
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(500);
    error_log("Stripe checkout error: $response");
    echo 'Payment system error. Please try again or contact support@acepilot.app';
    exit;
}

$session = json_decode($response, true);

if (!isset($session['url'])) {
    http_response_code(500);
    error_log("Stripe checkout no URL: $response");
    echo 'Payment system error. Please try again.';
    exit;
}

// Verify redirect is to Stripe (prevent open redirect if API is compromised)
if (!str_starts_with($session['url'], 'https://checkout.stripe.com/')) {
    http_response_code(500);
    error_log("Stripe returned unexpected redirect URL: " . $session['url']);
    echo 'Payment system error. Please try again.';
    exit;
}

header('Location: ' . $session['url']);
exit;
