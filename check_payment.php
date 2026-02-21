<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check ACLEDA settings
echo "=== ACLEDA Settings ===" . PHP_EOL;
echo "Login ID: " . App\Models\Setting::get('acleda_login_id') . PHP_EOL;
echo "Merchant ID: " . App\Models\Setting::get('acleda_merchant_id') . PHP_EOL;
echo "Secret: " . App\Models\Setting::get('acleda_secret') . PHP_EOL;
echo "Secret Length: " . strlen(App\Models\Setting::get('acleda_secret')) . PHP_EOL;
echo PHP_EOL;

// Check KHQR settings (Bakong fallback)
echo "=== KHQR Bakong Settings ===" . PHP_EOL;
echo "Bakong Account ID: " . App\Models\Setting::get('khqr_bakong_account_id') . PHP_EOL;
echo "Merchant Name: " . App\Models\Setting::get('khqr_merchant_name') . PHP_EOL;
echo PHP_EOL;

// Test local KHQR generation
$khqrService = new App\Services\KhqrService();
$testQr = $khqrService->createQr(
    App\Models\Setting::get('khqr_bakong_account_id', 'phal_chanouksa@bkrt'),
    App\Models\Setting::get('khqr_merchant_name', 'Test Merchant'),
    'Phnom Penh',
    1.00,
    'USD',
    'Test Store',
    '85512345678',
    'TRX-TEST',
    'webpos',
    false
);

echo "=== Generated Local KHQR ===" . PHP_EOL;
echo $testQr . PHP_EOL;
echo PHP_EOL;

echo "=== Working ACLEDA QR Value ===" . PHP_EOL;
echo "00020101021230410009khqr@aclb0114000108111111110206ACLEDA3937001185512484695010170313177005015045152045411530384054041.005802KH5917HAPPY AND CO FARM6010Phnom Penh624301242026020216304935122314390211092233533119934001317700246504510113177002495045163042BCE" . PHP_EOL;

