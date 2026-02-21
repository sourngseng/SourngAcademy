<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class AcledaService
{
    protected $baseUrl;
    protected $loginId;
    protected $password;
    protected $merchantId;
    protected $secret;

    public function __construct()
    {
        $this->baseUrl = Setting::get('acleda_base_url');
        if (empty($this->baseUrl)) {
            $this->baseUrl = 'https://epaymentuat.acledabank.com.kh/HAPPYCOFARM/XPAYConnectorServiceInterfaceImplV2/XPAYConnectorServiceInterfaceImplV2RS/openSessionV2';
        }
        
        $this->loginId = Setting::get('acleda_login_id');
        $this->password = Setting::get('acleda_password');
        $this->merchantId = Setting::get('acleda_merchant_id');
        $this->secret = trim(Setting::get('acleda_secret'));
    }

    /**
     * Open a payment session to get QR Value
     */
    public function openSession($booking, $amount, $transactionId)
    {
        // Dynamic endpoint based on loginId or full URL from settings
        // if (strpos($this->baseUrl, 'XPAYConnectorServiceInterfaceImplV2') !== false) {
        //      $endpoint = $this->baseUrl;
        // } else {
        //      $endpoint = $this->baseUrl ;//. '/' . $this->loginId . '/XPAYConnectorServiceInterfaceImplV2/XPAYConnectorServiceInterfaceImplV2RS/openSessionV2';
        // }
        $endpoint = 'https://epaymentuat.acledabank.com.kh/HAPPYCOFARM/XPAYConnectorServiceInterfaceImplV2/XPAYConnectorServiceInterfaceImplV2RS/openSessionV2';    

        $expiryTime = 5; // minutes (per ACLEDA sample)
        $item = "1";
        
        // Prepare transaction details
        // Prepare transaction details
        // Strictly matching the requested structure
        $purchaseAmount = rtrim(rtrim(number_format($amount, 2, '.', ''), '0'), '.');

        $xpayTransaction = [
            "txid" => $transactionId,
            "purchaseAmount" => $purchaseAmount,
            "purchaseCurrency" => "USD",
            "purchaseDate" => now()->format('d-m-Y'),
            "purchaseDesc" => "mobile",
            "invoiceid" => (string) $booking->id,
            "item" => $item,
            "quantity" => "1",
            "expiryTime" => (string) $expiryTime,
            "operationType" => "5"
        ];

        // Generate Hash (signature)
        // Pattern: loginId + password + merchantID + txid + purchaseAmount + currency + purchaseDate + purchaseDesc + invoiceid + item + quantity + expiryTime + operationType + secret
        $signatureString = $this->loginId . $this->password . $this->merchantId . 
                           $xpayTransaction['txid'] . 
                           $xpayTransaction['purchaseAmount'] . 
                           $xpayTransaction['purchaseCurrency'] . 
                           $xpayTransaction['purchaseDate'] . 
                           $xpayTransaction['purchaseDesc'] . 
                           $xpayTransaction['invoiceid'] . 
                           $xpayTransaction['item'] . 
                           $xpayTransaction['quantity'] . 
                           $xpayTransaction['expiryTime'] . 
                           $xpayTransaction['operationType'] . 
                           $this->secret; 

        Log::info('ACLEDA Signature String: ' . $signatureString);
        Log::info('ACLEDA Secret Length: ' . strlen($this->secret));

        // Hash must be UPPERCASE for ACLEDA API
        $hash = strtoupper(hash('sha512', $signatureString));

        $payload = [
            "loginId" => $this->loginId,
            "password" => $this->password,
            "merchantID" => $this->merchantId,
            "hash" => $hash,
            "xpayTransaction" => $xpayTransaction
        ];
        
        Log::info('ACLEDA RAW REQUEST:', $payload); // Ensure we see this in logs



        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($endpoint, $payload);
            Log::info('ACLEDA Response:', $response->json());
            
            return $response->json();
        } catch (\Exception $e) {
            Log::error('ACLEDA API Error: ' . $e->getMessage());
            return ['result' => ['code' => -1, 'errorDetails' => $e->getMessage()]];
        }
    }

    /**
     * Check transaction status
     */
    public function getTxnStatus($paymentTokenId)
    {
        // Dynamic endpoint
        // Check if baseUrl already has path or needs construction
        if (strpos($this->baseUrl, 'XPAYConnectorServiceInterfaceImplV2') !== false) {
             // If user entered full URL for openSession, we need to adapt it for getTxnStatus
             // Replace 'openSessionV2' with 'getTxnStatus'
             $endpoint = str_replace('openSessionV2', 'getTxnStatus', $this->baseUrl);
        } else {
             $endpoint = $this->baseUrl . '/' . $this->loginId . '/XPAYConnectorServiceInterfaceImplV2/XPAYConnectorServiceInterfaceImplV2RS/getTxnStatus';
        }

        // Hash pattern: loginId + password + loginId (as merchantName) + merchantID + paymentTokenid + secret
        // Note: The example in prompt Step 4 implies merchantName is used. 
        // We will assume merchantName = loginId.
        
        $merchantName = $this->loginId; 
        
        $signatureString = $this->loginId . $this->password . $merchantName . $this->merchantId . $paymentTokenId . $this->secret;
        $hash = hash('sha512', $signatureString);
        
        $payload = [
            "loginId" => $this->loginId,
            "password" => $this->password,
            "merchantName" => $merchantName,
            "hash" => $hash,
            "merchantId" => $this->merchantId,
            "paymentTokenid" => $paymentTokenId
        ];
        
        try {
            Log::info('ACLEDA Status Request:', $payload);
            $response = Http::post($endpoint, $payload);
            Log::info('ACLEDA Status Response:', $response->json());
            
            return $response->json();
        } catch (\Exception $e) {
            Log::error('ACLEDA Status Error: ' . $e->getMessage());
            return ['code' => -1, 'errorDetails' => $e->getMessage()];
        }
    }
}
