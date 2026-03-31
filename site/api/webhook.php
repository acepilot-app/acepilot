<?php
/**
 * AcePilot Stripe Webhook
 * Handles checkout.session.completed → generates license key → stores in licenses.json
 *
 * Stripe sends POST to: https://acepilot.app/api/webhook.php
 * Configure in Stripe Dashboard → Webhooks → Add endpoint
 * Events: checkout.session.completed, customer.subscription.deleted
 */

$config = require __DIR__ . '/config.php';
$webhookSecret = $config['stripe']['webhook_secret'];
$stripeKey = $config['stripe']['secret_key'];

// Read raw body for signature verification
$payload = file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

// Verify webhook signature
$elements = [];
foreach (explode(',', $sigHeader) as $part) {
    [$key, $value] = explode('=', trim($part), 2);
    $elements[$key] = $value;
}

$timestamp = $elements['t'] ?? '';
$signature = $elements['v1'] ?? '';

if (!$timestamp || !$signature) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid signature header']);
    exit;
}

$signedPayload = "$timestamp.$payload";
$expectedSig = hash_hmac('sha256', $signedPayload, $webhookSecret);

if (!hash_equals($expectedSig, $signature)) {
    http_response_code(400);
    echo json_encode(['error' => 'Signature verification failed']);
    exit;
}

// Check timestamp (reject if older than 5 minutes)
if (abs(time() - (int)$timestamp) > 300) {
    http_response_code(400);
    echo json_encode(['error' => 'Timestamp too old']);
    exit;
}

$event = json_decode($payload, true);
$type = $event['type'] ?? '';

$licensesFile = __DIR__ . '/../licenses.json';

switch ($type) {
    case 'checkout.session.completed':
        handleCheckoutComplete($event['data']['object'], $licensesFile, $stripeKey);
        break;

    case 'customer.subscription.deleted':
        handleSubscriptionCanceled($event['data']['object'], $licensesFile);
        break;

    default:
        // Acknowledge but ignore other events
        break;
}

http_response_code(200);
echo json_encode(['received' => true]);

// --- Handlers ---

function handleCheckoutComplete($session, $licensesFile, $stripeKey) {
    $email = $session['customer_details']['email'] ?? $session['customer_email'] ?? '';
    $customerId = $session['customer'] ?? '';
    $subscriptionId = $session['subscription'] ?? '';
    $sessionId = $session['id'] ?? '';
    $plan = $session['metadata']['plan'] ?? 'pro_monthly';

    if (!$email) {
        error_log("AcePilot webhook: no email in session $sessionId");
        return;
    }

    // Generate license key
    $key = 'ap_' . bin2hex(random_bytes(16));

    // Load existing licenses
    $data = file_exists($licensesFile)
        ? json_decode(file_get_contents($licensesFile), true)
        : ['licenses' => []];

    // Check if email already has a license (reactivation)
    $found = false;
    foreach ($data['licenses'] as &$license) {
        if ($license['email'] === $email && $license['type'] !== 'founder') {
            $license['key'] = $key;
            $license['plan'] = 'pro';
            $license['stripe_customer'] = $customerId;
            $license['stripe_subscription'] = $subscriptionId;
            $license['stripe_session'] = $sessionId;
            $license['updated'] = date('Y-m-d');
            $license['expires'] = 'subscription';
            $found = true;
            break;
        }
    }
    unset($license);

    if (!$found) {
        $data['licenses'][] = [
            'key' => $key,
            'email' => $email,
            'plan' => 'pro',
            'type' => 'stripe',
            'stripe_customer' => $customerId,
            'stripe_subscription' => $subscriptionId,
            'stripe_session' => $sessionId,
            'billing_cycle' => $plan,
            'created' => date('Y-m-d'),
            'expires' => 'subscription',
            'notes' => "Stripe checkout $sessionId",
        ];
    }

    file_put_contents($licensesFile, json_encode($data, JSON_PRETTY_PRINT));
    error_log("AcePilot: license $key created for $email (session $sessionId)");
}

function handleSubscriptionCanceled($subscription, $licensesFile) {
    $subscriptionId = $subscription['id'] ?? '';

    if (!$subscriptionId || !file_exists($licensesFile)) return;

    $data = json_decode(file_get_contents($licensesFile), true);

    foreach ($data['licenses'] as &$license) {
        if (($license['stripe_subscription'] ?? '') === $subscriptionId) {
            $license['expires'] = date('Y-m-d', strtotime('+3 days'));
            $license['notes'] = ($license['notes'] ?? '') . ' | Canceled ' . date('Y-m-d');
            break;
        }
    }
    unset($license);

    file_put_contents($licensesFile, json_encode($data, JSON_PRETTY_PRINT));
    error_log("AcePilot: subscription $subscriptionId canceled, grace period set");
}
