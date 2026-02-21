<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Finance\Models\Invoice;
use App\Modules\Finance\Models\FeeStructure;
use App\Modules\Finance\Models\InvoiceItem;
use App\Modules\Academic\Models\Program;
use App\Modules\Academic\Models\AcademicYear;
use App\Modules\Academic\Models\Semester;
use App\Modules\Core\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of all invoices
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['student', 'items']);

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search by invoice number or student name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('student', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Calculate statistics
        $stats = [
            'total' => Invoice::count(),
            'unpaid' => Invoice::where('status', 'Unpaid')->count(),
            'paid' => Invoice::where('status', 'Paid')->count(),
            'overdue' => Invoice::where('status', 'Overdue')->count(),
            'total_revenue' => Invoice::sum('paid_amount'),
            'pending_amount' => Invoice::whereIn('status', ['Unpaid', 'Partially Paid', 'Overdue'])->sum(DB::raw('total_amount - paid_amount')),
        ];

        return view('admin.invoices.index', compact('invoices', 'stats'));
    }

    /**
     * Show create invoice form
     */
    public function create(Request $request)
    {
        $students = User::where('role', 'student')->where('status', 'active')->get();
        $feeStructures = FeeStructure::all(); // In a real app, filter by active academic year
        
        return view('admin.invoices.create', compact('students', 'feeStructures'));
    }

    /**
     * Store a new invoice
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id'         => 'required|exists:users,id',
            'due_date'           => 'required|date',
            'items'              => 'required|array|min:1',
            'items.*.description'=> 'required|string',
            'items.*.amount'     => 'required|numeric|min:0',
            'payment_notes'      => 'nullable|string|max:2000',
            'payment_conditions' => 'nullable|string|max:2000',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += $item['amount'];
            }

            // Create Invoice
            $invoice = Invoice::create([
                'student_id'         => $request->student_id,
                'invoice_number'     => 'INV-' . date('Ymd') . '-' . mt_rand(1000, 9999),
                'total_amount'       => $totalAmount,
                'paid_amount'        => 0,
                'due_date'           => $request->due_date,
                'status'             => 'Unpaid',
                'campus_id'          => Auth::user()->campus_id,
                'generated_by'       => Auth::id(),
                'payment_notes'      => $request->payment_notes,
                'payment_conditions' => $request->payment_conditions,
            ]);

            // Create Invoice Items
            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id'       => $invoice->id,
                    'description'      => $item['description'],
                    'amount'           => $item['amount'],
                    'fee_structure_id' => $item['fee_structure_id'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', 'Invoice generated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified invoice
     */
    public function show($id)
    {
        $invoice = Invoice::with(['student', 'items', 'payments', 'generatedBy', 'campus'])->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        if ($invoice->status == 'Paid' || $invoice->status == 'Cancelled') {
            return back()->with('error', 'Cannot edit paid or cancelled invoices.');
        }
        
        $students = User::where('role', 'student')->where('status', 'active')->get();
        return view('admin.invoices.edit', compact('invoice', 'students'));
    }

    /**
     * Update the invoice
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->status == 'Paid' || $invoice->status == 'Cancelled') {
            return back()->with('error', 'Cannot edit paid or cancelled invoices.');
        }

        $request->validate([
            'due_date'           => 'required|date',
            'items'              => 'required|array|min:1',
            'items.*.description'=> 'required|string',
            'items.*.amount'     => 'required|numeric|min:0',
            'payment_notes'      => 'nullable|string|max:2000',
            'payment_conditions' => 'nullable|string|max:2000',
        ]);

        try {
            DB::beginTransaction();

            // Calculate new total
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += $item['amount'];
            }

            // Update Invoice
            $invoice->update([
                'due_date'           => $request->due_date,
                'total_amount'       => $totalAmount,
                'payment_notes'      => $request->payment_notes,
                'payment_conditions' => $request->payment_conditions,
            ]);

            // Sync Items: Delete old ones and create new ones
            $invoice->items()->delete();

            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id'       => $invoice->id,
                    'description'      => $item['description'],
                    'amount'           => $item['amount'],
                    'fee_structure_id' => $item['fee_structure_id'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', 'Invoice updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Destroy the invoice
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice->status != 'Unpaid' && $invoice->status != 'Cancelled') {
             return back()->with('error', 'Cannot delete invoice with payments.');
        }
        
        $invoice->delete();
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
