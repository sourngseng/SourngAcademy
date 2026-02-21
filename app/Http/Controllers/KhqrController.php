<?php

namespace App\Http\Controllers;

use App\Services\KhqrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KhqrController extends Controller
{
    protected $khqrService;

    public function __construct(KhqrService $khqrService)
    {
        $this->khqrService = $khqrService;
    }

    public function index()
    {
        return response()->json(['status' => 'Bakong KHQR API Running (Laravel)']);
    }

    public function generateQr(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric',
                'currency' => 'nullable|string|in:USD,KHR',
            ]);

            $amount = $request->input('amount');
            $currency = $request->input('currency', 'USD');
            
            // Format amount to exactly 2 decimal places
            $formattedAmount = number_format($amount, 2, '.', '');
            
            // Generate bill number similar to Python example
            $billNo = "TRX-" . time();

            // Default values from settings or fallback to Python example defaults
            $bankAccount = \App\Models\Setting::get('khqr_bakong_account_id', 'phal_chanouksa@bkrt');
            $merchantName = \App\Models\Setting::get('khqr_merchant_name', 'Phal Chanouksa');
            $merchantCity = \App\Models\Setting::get('khqr_merchant_city', 'Phnom Penh');
            $storeLabel = \App\Models\Setting::get('khqr_store_label', 'Ouksa Cafe');
            $phoneNumber = \App\Models\Setting::get('khqr_phone_number', '85515942954');
            $terminalLabel = 'webpos';

            $qrString = $this->khqrService->createQr(
                $bankAccount,
                $merchantName,
                $merchantCity,
                $amount,
                $currency,
                $storeLabel,
                $phoneNumber,
                $billNo,
                $terminalLabel,
                false // Static = false
            );

            $md5 = $this->khqrService->generateMd5($qrString);

            return response()->json([
                'qr' => $qrString,
                'md5' => $md5,
                'amount' => $formattedAmount,
                'currency' => strtoupper($currency)
            ]);

        } catch (\Exception $e) {
            Log::error("KHQR Generation Error: " . $e->getMessage());
            return response()->json(['detail' => $e->getMessage()], 500);
        }
    }

    public function checkTransaction(Request $request)
    {
        try {
            $md5 = $request->input('md5');
            // Note: Actual Bakong check_payment requires API token and integration.
            // Returning a mock response or implementing if BakongService existed.
            // Python example implies calling khqr.check_payment(md5).
            
            // For now, return a success status simulation as we don't have Bakong API credentials/library.
            return response()->json(['status' => [
                'code' => 0,
                'message' => 'Transaction check simulated (requires Bakong API integration)'
            ]]);
        } catch (\Exception $e) {
            return response()->json(['detail' => $e->getMessage()], 500);
        }
    }

    public function khqrImage(Request $request)
    {
        try {
            $qr = $request->input('qr');
            if (!$qr) {
                return response()->json(['detail' => 'QR string required'], 400);
            }

            // Generate SVG or PNG base64
            // Using simple-qrcode library
            $image = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(300)->generate($qr);
            $base64 = 'data:image/png;base64,' . base64_encode($image);

            return response()->json(['image' => $base64]);
        } catch (\Exception $e) {
            return response()->json(['detail' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle ACLEDA Merchant Callback
     * 
     * Request Body:
     * {
     *   "_amount": "10000",
     *   "_transactionid": "...",
     *   "_paymentresult": "SUCCESS",
     *   "_paymenttokenid": "...",
     *   "_resultCode": 0
     * }
     */
    public function acledaCallback(Request $request)
    {
        try {
            Log::info('ACLEDA Callback Received:', $request->all());

            $transactionId = $request->input('_transactionid');
            $resultCode = $request->input('_resultCode');
            $paymentResult = $request->input('_paymentresult');
            $paymentTokenId = $request->input('_paymenttokenid');

            if (!$transactionId) {
                return response('Transaction ID missing', 400);
            }

            // Find payment by reference number (which we sent as txid)
            $payment = \App\Models\Payment::where('reference_number', $transactionId)->first();

            if (!$payment) {
                Log::error("ACLEDA Callback: Payment not found for txid: {$transactionId}");
                return response('Payment not found', 404);
            }

            if ($resultCode == 0 || strtoupper($paymentResult) === 'SUCCESS') {
                if ($payment->status !== 'completed') {
                    $payment->update([
                        'status' => 'completed',
                        'paid_at' => now(),
                        'transaction_id' => $paymentTokenId, // Store bank's reference
                        'notes' => "Confirmed via ACLEDA Callback (Token: $paymentTokenId)",
                        'payment_details' => array_merge($payment->payment_details ?? [], [
                            'callback_received' => true,
                            'callback_data' => $request->all()
                        ])
                    ]);

                    // Update Booking Payment Status
                    if ($payment->booking) {
                        $payment->booking->updatePaymentStatus();
                    }
                    
                    Log::info("ACLEDA Callback: Payment {$payment->id} confirmed.");
                } else {
                    Log::info("ACLEDA Callback: Payment {$payment->id} already completed.");
                }
            } else {
                // Handle failed payment if needed
                Log::warning("ACLEDA Callback: Payment failed for txid: {$transactionId}, Result: {$paymentResult}");
                if ($payment->status === 'pending') {
                       $payment->update([
                        'status' => 'failed',
                        'notes' => "ACLEDA Callback Failed: $paymentResult",
                        'payment_details' => array_merge($payment->payment_details ?? [], [
                            'callback_received' => true,
                            'callback_failed' => true,
                            'callback_data' => $request->all()
                        ])
                    ]);
                }
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('ACLEDA Callback Error: ' . $e->getMessage());
            return response('Server Error', 500);
        }
    }
}
