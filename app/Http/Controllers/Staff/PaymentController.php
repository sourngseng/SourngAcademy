<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display payments for a booking
     */
    public function index(Booking $booking)
    {
        $payments = $booking->payments()->with('processedBy')->orderBy('created_at', 'desc')->get();
        return view('staff.payments.index', compact('booking', 'payments'));
    }

    /**
     * Show payment form
     */
    public function create(Booking $booking)
    {
        $paymentMethods = Payment::PAYMENT_METHODS;
        return view('staff.payments.create', compact('booking', 'paymentMethods'));
    }

    /**
     * Store a new payment
     */
    public function store(Request $request, Booking $booking)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $booking->remaining_balance,
            'payment_method' => 'required|in:' . implode(',', array_keys(Payment::PAYMENT_METHODS)),
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
            'transaction_id' => $request->transaction_id,
            'reference_number' => Payment::generateReferenceNumber(),
            'notes' => $request->notes,
            'paid_at' => now(),
        ]);

        // Update booking payment status
        $booking->updatePaymentStatus();

        return redirect()->route('staff.bookings.show', $booking->id)
            ->with('success', 'Payment of $' . number_format($request->amount, 2) . ' recorded successfully!');
    }

    /**
     * Show payment receipt
     */
    public function show(Booking $booking, Payment $payment)
    {
        return view('staff.payments.show', compact('booking', 'payment'));
    }
}
