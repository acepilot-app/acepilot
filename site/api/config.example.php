<?php
/**
 * AcePilot API Configuration
 * Copy this to config.php and fill in your keys.
 * NEVER commit config.php — it contains secrets.
 */

return [
    'stripe' => [
        'secret_key'      => 'sk_live_xxxxxxxxxxxx',  // Stripe Dashboard → API keys
        'publishable_key' => 'pk_live_xxxxxxxxxxxx',
        'webhook_secret'  => 'whsec_xxxxxxxxxxxx',    // Stripe Dashboard → Webhooks
        'price_pro_monthly'  => 'price_xxxxxxxxxxxx',  // Stripe Dashboard → Products → Pro Monthly
        'price_pro_yearly'   => 'price_xxxxxxxxxxxx',  // Stripe Dashboard → Products → Pro Yearly
    ],
    'admin' => [
        'key' => 'CHANGE-ME-use-php-r-echo-bin2hex-random_bytes-32',  // Run: php -r "echo bin2hex(random_bytes(32));"
    ],
    'app' => [
        'url'          => 'https://acepilot.app',
        'support_email' => 'support@acepilot.app',
    ],
];
