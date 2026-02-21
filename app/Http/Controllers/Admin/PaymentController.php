<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display payments index for a booking
     */
    public function index(Booking $booking)
    {
        $payments = $booking->payments()->with('processedBy')->orderBy('created_at', 'desc')->get();
        return view('admin.payments.index', compact('booking', 'payments'));
    }

    /**
     * Show payment form
     */
    public function create(Booking $booking)
    {
        $paymentMethods = Payment::PAYMENT_METHODS;
        return view('admin.payments.create', compact('booking', 'paymentMethods'));
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

        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', 'Payment of $' . number_format($request->amount, 2) . ' recorded successfully!');
    }

    /**
     * Show payment details
     */
    public function show(Booking $booking, Payment $payment)
    {
        return view('admin.payments.show', compact('booking', 'payment'));
    }

    /**
     * Refund a payment
     */
    public function refund(Request $request, Booking $booking, Payment $payment)
    {
        if ($payment->status !== 'completed') {
            return back()->with('error', 'Only completed payments can be refunded.');
        }

        $request->validate([
            'refund_reason' => 'required|string|max:500',
        ]);

        // Create refund record
        $refundPayment = Payment::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'amount' => -$payment->amount, // Negative amount for refund
            'payment_method' => $payment->payment_method,
            'status' => 'refunded',
            'reference_number' => Payment::generateReferenceNumber(),
            'notes' => 'Refund for ' . $payment->reference_number . ': ' . $request->refund_reason,
            'paid_at' => now(),
        ]);

        // Mark original payment as refunded
        $payment->update(['status' => 'refunded']);

        // Update booking payment status
        $booking->updatePaymentStatus();

        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', 'Payment refunded successfully!');
    }

    /**
     * All payments report
     */
    public function report(Request $request)
    {
        $query = Payment::with(['booking', 'processedBy'])
            ->where('status', 'completed');

        // Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('paid_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('paid_at', '<=', $request->end_date);
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->orderBy('paid_at', 'desc')->paginate(20);
        $totalAmount = $query->sum('amount');
        $paymentMethods = Payment::PAYMENT_METHODS;

        return view('admin.payments.report', compact('payments', 'totalAmount', 'paymentMethods'));
    }
}
