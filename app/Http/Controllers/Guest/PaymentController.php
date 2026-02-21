<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display payments for own booking
     */
    public function index(Booking $booking)
    {
        // Ensure guest owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $payments = $booking->payments()->completed()->orderBy('created_at', 'desc')->get();
        return view('guest.payments.index', compact('booking', 'payments'));
    }

    /**
     * Show payment form for guest
     */
    public function create(Booking $booking)
    {
        // Ensure guest owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if booking can be paid
        if ($booking->payment_status === 'paid') {
            return redirect()->route('guest.bookings.show', $booking->id)
                ->with('info', 'This booking is already fully paid.');
        }

        $paymentMethods = [
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'khqr' => 'KHQR',
        ];

        return view('guest.payments.create', compact('booking', 'paymentMethods'));
    }

    /**
     * Process guest payment (simulated)
     */
    /**
     * Process guest payment
     */
    public function store(Request $request, Booking $booking)
    {
        // Ensure guest owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $booking->remaining_balance,
            'payment_method' => 'required|in:credit_card,debit_card,bank_transfer,khqr',
        ]);

        $referenceNumber = Payment::generateReferenceNumber();

        // Check for KHQR Payment (ACLEDA or Standard)
        if ($request->payment_method === 'khqr') {
            $qrString = null;
            $paymentType = 'khqr_manual';
            $sessionId = null;
            $paymentTokenId = null;

            // Try ACLEDA API if enabled
            if (\App\Models\Setting::get('acleda_enabled') == '1') {
                $acledaService = new \App\Services\AcledaService();
                $response = $acledaService->openSession($booking, $request->amount, $referenceNumber);

                if (isset($response['result']) && $response['result']['code'] === 0) {
                    $qrString = $response['result']['qrValue'];
                    $paymentType = 'khqr_acleda';
                    $sessionId = $response['result']['sessionid'] ?? null;
                    $paymentTokenId = $response['result']['xTran']['paymentTokenid'] ?? null;
                } else {
                    // Fallback to local KHQR if ACLEDA fails, so user still sees a QR code.
                    \Illuminate\Support\Facades\Log::error('ACLEDA Session Failed, falling back to local KHQR. details: ' . json_encode($response));
                    
                    $khqrService = new \App\Services\KhqrService();
                    $bakongId = \App\Models\Setting::get('khqr_bakong_account_id', 'phal_chanouksa@bkrt');
                    
                    // Fallback to ACLEDA Login ID if KHQR Merchant Name is not set
                    $defaultMerchantName = \App\Models\Setting::get('acleda_login_id', 'HAPPYCOFARM');
                    $merchantName = \App\Models\Setting::get('khqr_merchant_name', $defaultMerchantName);
                    
                    $merchantCity = \App\Models\Setting::get('khqr_merchant_city', 'Phnom Penh');
                    $storeLabel = \App\Models\Setting::get('khqr_store_label', 'Ouksa Cafe');
                    $phoneNumber = \App\Models\Setting::get('khqr_phone_number', '85515942954');
                    $terminalLabel = 'webpos';
                    $billNo = "TRX-" . time();

                    $qrString = $khqrService->createQr(
                        $bakongId,
                        $merchantName,
                        $merchantCity,
                        $request->amount,
                        'USD',
                        $storeLabel,
                        $phoneNumber,
                        $billNo,
                        $terminalLabel,
                        false
                    );
                    
                    $paymentType = 'khqr_manual'; // Since ACLEDA failed, manual confirmation is needed
                    session()->flash('warning', 'ACLEDA connection failed. Using standard KHQR. Payment verification will be manual.');
                }
            } else {
                // Use KhqrService to generate proper KHQR string
                $khqrService = new \App\Services\KhqrService();
                
                $bakongId = \App\Models\Setting::get('khqr_bakong_account_id', 'phal_chanouksa@bkrt');
                $merchantName = \App\Models\Setting::get('khqr_merchant_name', 'Phal Chanouksa');
                $merchantCity = \App\Models\Setting::get('khqr_merchant_city', 'Phnom Penh');
                $storeLabel = \App\Models\Setting::get('khqr_store_label', 'Ouksa Cafe');
                $phoneNumber = \App\Models\Setting::get('khqr_phone_number', '85515942954');
                $terminalLabel = 'webpos';
                
                // Generate Dynamic KHQR (Standard)
                // Bill Number acts as transaction reference
                $billNo = "TRX-" . time();
                
                $qrString = $khqrService->createQr(
                    $bakongId,
                    $merchantName,
                    $merchantCity,
                    $request->amount,
                    'USD',
                    $storeLabel,
                    $phoneNumber,
                    $billNo,
                    $terminalLabel,
                    false
                );
            }

            // Create Pending Payment
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'payment_method' => 'khqr',
                'status' => 'pending',
                'reference_number' => $referenceNumber,
                'notes' => 'KHQR Payment Initiated',
                'payment_details' => [
                    'payment_type' => $paymentType,
                    'session_id' => $sessionId,
                    'payment_token_id' => $paymentTokenId,
                    'qr_string' => $qrString,
                ],
            ]);

            return view('guest.payments.khqr', [
                'booking' => $booking,
                'payment' => $payment,
                'qrString' => $qrString
            ]);
        }

        // Standard Simulated Payment (Credit Card / Other)
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'completed', // In production, this might be 'pending' until confirmed
            'reference_number' => $referenceNumber,
            'notes' => 'Online payment by guest',
            'paid_at' => now(),
            'payment_details' => [
                'payment_type' => 'online_simulated',
                'ip_address' => $request->ip(),
            ],
        ]);

        // Update booking payment status
        $booking->updatePaymentStatus();

        return redirect()->route('guest.bookings.show', $booking->id)
            ->with('success', 'Payment of $' . number_format($request->amount, 2) . ' processed successfully! Thank you.');
    }

    public function checkStatus(Request $request, Booking $booking, Payment $payment)
    {
        if ($booking->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
            }
            abort(403);
        }

        if ($payment->status === 'completed') {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success', 
                    'message' => 'Payment already completed',
                    'redirect_url' => route('guest.payments.show', [$booking->id, $payment->id])
                ]);
            }
            return redirect()->route('guest.payments.show', [$booking->id, $payment->id]);
        }

        $paymentTokenId = $payment->payment_details['payment_token_id'] ?? null;
        $paymentType = $payment->payment_details['payment_type'] ?? null;

        // Manual KHQR Check
        if ($paymentType === 'khqr_manual') {
            if ($request->ajax()) {
                // For manual KHQR, polling should just return pending until user clicks the button
                // or if we had a way to check bank account via webhook (which we don't for manual).
                return response()->json(['status' => 'pending', 'message' => 'Waiting for manual confirmation']);
            }

            // If not AJAX (User clicked "I have completed payment" button)
            $payment->update([
                'status' => 'completed', 
                'paid_at' => now(),
                'notes' => 'Manual KHQR Confirmed by User'
            ]);
            $booking->updatePaymentStatus();

            return redirect()->route('guest.payments.show', [$booking->id, $payment->id]);
        }

        // ACLEDA API Check
        if (!$paymentTokenId) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Invalid payment session'], 400);
            }
            return back()->with('error', 'Invalid payment session.');
        }

        $acledaService = new \App\Services\AcledaService();
        $response = $acledaService->getTxnStatus($paymentTokenId);

        if (isset($response['code']) && $response['code'] === 0) {
            // Success
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'transaction_id' => $response['coreRefNum'] ?? null,
                'notes' => 'ACLEDA Payment Successful. Ref: ' . ($response['coreRefNum'] ?? 'N/A'),
                'payment_details' => array_merge($payment->payment_details ?? [], [
                    'acleda_status_response' => $response,
                    'payer_account' => $response['senderAccount'] ?? null,
                    'payer_name' => $response['senderName'] ?? null,
                    'receiver_account' => $response['receiverAccount'] ?? null,
                ])
            ]);

            $booking->updatePaymentStatus();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success', 
                    'message' => 'Payment verified successfully!',
                    'redirect_url' => route('guest.payments.show', [$booking->id, $payment->id])
                ]);
            }

            return redirect()->route('guest.payments.show', [$booking->id, $payment->id])
                ->with('success', 'Payment verified successfully!');
        } else {
            if ($request->ajax()) {
                // If code is not 0, it means payment is not yet completed (or failed)
                // We return 'pending' so the frontend keeps polling
                return response()->json(['status' => 'pending', 'message' => 'Payment not yet received']);
            }
            return back()->with('error', 'Payment not yet completed. Please try again after scanning the QR code.');
        }
    }

    /**
     * Show payment receipt
     */
    public function show(Booking $booking, Payment $payment)
    {
        // Ensure guest owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('guest.payments.show', compact('booking', 'payment'));
    }
}
