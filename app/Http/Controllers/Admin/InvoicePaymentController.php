<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Finance\Models\Invoice;
use App\Modules\Finance\Models\Payment;
use App\Modules\Core\Models\CampusPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoicePaymentController extends Controller
{
    /**
     * Record a payment against an invoice
     */
    public function store(Request $request, $invoiceId)
    {
        $invoice = Invoice::with(['student', 'campus'])->findOrFail($invoiceId);

        if ($invoice->status === 'Paid' || $invoice->status === 'Cancelled') {
            return back()->with('error', 'Cannot add payment to a paid or cancelled invoice.');
        }

        $remaining = $invoice->total_amount - $invoice->paid_amount;

        $request->validate([
            'payment_method_id'   => 'required|exists:campus_payment_methods,id',
            'amount'              => "required|numeric|min:0.01|max:{$remaining}",
            'transaction_reference' => 'nullable|string|max:255',
            'proof_image'         => 'nullable|image|mimes:jpg,jpeg,png,gif,pdf|max:5120',
            'notes'               => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $method = CampusPaymentMethod::findOrFail($request->payment_method_id);

            $proofPath = null;
            if ($request->hasFile('proof_image')) {
                $proofPath = 'storage/' . $request->file('proof_image')->store('payments/proof', 'public');
            }

            // Create payment record
            Payment::create([
                'invoice_id'            => $invoice->id,
                'student_id'            => $invoice->student_id,
                'amount'                => $request->amount,
                'payment_method'        => $method->name . ' (' . $method->type . ')',
                'transaction_reference' => $request->transaction_reference,
                'proof_image'           => $proofPath,
                'status'                => 'pending', // admin verifies
                'verified_by'           => null,
                'verified_at'           => null,
            ]);

            // Update invoice paid_amount & status
            $newPaid = $invoice->paid_amount + $request->amount;
            $newStatus = 'Unpaid';
            if ($newPaid >= $invoice->total_amount) {
                $newStatus = 'Paid';
            } elseif ($newPaid > 0) {
                $newStatus = 'Partially Paid';
            }

            $invoice->update([
                'paid_amount' => $newPaid,
                'status'      => $newStatus,
            ]);

            DB::commit();

            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', "Payment of $" . number_format($request->amount, 2) . " recorded! Status: {$newStatus}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }

    /**
     * Verify / approve a payment
     */
    public function verify($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        $payment->update([
            'status'      => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Payment verified successfully.');
    }

    /**
     * Reject a payment (revert invoice amounts)
     */
    public function reject(Request $request, $paymentId)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $payment = Payment::with('invoice')->findOrFail($paymentId);
        $invoice = $payment->invoice;

        DB::beginTransaction();
        try {
            // Refund the amount from invoice
            $newPaid = max(0, $invoice->paid_amount - $payment->amount);
            $newStatus = $newPaid >= $invoice->total_amount ? 'Paid'
                : ($newPaid > 0 ? 'Partially Paid' : 'Unpaid');

            $invoice->update(['paid_amount' => $newPaid, 'status' => $newStatus]);
            $payment->update(['status' => 'rejected']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error rejecting payment.');
        }

        return back()->with('success', 'Payment rejected and invoice reverted.');
    }
}
