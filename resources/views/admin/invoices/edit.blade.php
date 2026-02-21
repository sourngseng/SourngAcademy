@extends('admin.layouts.master')

@section('title', 'Edit Invoice')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Invoice #{{ $invoice->invoice_number }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.invoices.index') }}">Invoices</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Editing Details</h3>
            </div>
            <form action="{{ route('admin.invoices.update', $invoice->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Student</label>
                                <input type="text" class="form-control" value="{{ $invoice->student->name }} ({{ $invoice->student->email }})" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input type="date" name="due_date" class="form-control" id="due_date" value="{{ $invoice->due_date->format('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">Invoice Items</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" id="add-item-btn"><i class="fas fa-plus"></i> Add Item</button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm" id="invoice-items-table">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th style="width: 200px">Amount ($)</th>
                                                <th style="width: 50px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoice->items as $index => $item)
                                            <tr id="item-row-{{ $index }}">
                                                <td>
                                                    <input type="text" name="items[{{ $index }}][description]" class="form-control form-control-sm" value="{{ $item->description }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[{{ $index }}][amount]" class="form-control form-control-sm item-amount" step="0.01" min="0" value="{{ $item->amount }}" onchange="calculateTotal()" required>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-xs" onclick="removeRow({{ $index }})"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right font-weight-bold">Total:</td>
                                                <td class="font-weight-bold" id="total-amount">${{ number_format($invoice->total_amount, 2) }}</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    {{-- ── Notes & Conditions ─────────────────────────────────── --}}
                    <div class="row mt-2">

                        {{-- Note for Payment --}}
                        <div class="col-md-6">
                            <div class="card card-outline card-info mb-0">
                                <div class="card-header py-2">
                                    <h3 class="card-title">
                                        <i class="fas fa-sticky-note mr-1 text-info"></i>
                                        Note for Payment
                                    </h3>
                                    <div class="card-tools">
                                        <span class="text-muted small" id="notes-counter">0 / 2000</span>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <textarea
                                        name="payment_notes"
                                        id="payment_notes"
                                        class="form-control border-0"
                                        rows="5"
                                        maxlength="2000"
                                        placeholder="e.g. Please transfer to ABA Bank..."
                                    >{{ old('payment_notes', $invoice->payment_notes) }}</textarea>
                                </div>
                                <div class="card-footer py-1 bg-light">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Visible to student on their invoice (bank details, contact, etc.)
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Conditions of Payment --}}
                        <div class="col-md-6">
                            <div class="card card-outline card-warning mb-0">
                                <div class="card-header py-2">
                                    <h3 class="card-title">
                                        <i class="fas fa-file-contract mr-1 text-warning"></i>
                                        Conditions of Payment
                                    </h3>
                                    <div class="card-tools">
                                        <span class="text-muted small" id="conditions-counter">0 / 2000</span>
                                        <button type="button" class="btn btn-xs btn-outline-secondary ml-2" id="preset-conditions-btn" title="Insert default conditions">
                                            <i class="fas fa-magic mr-1"></i> Default
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <textarea
                                        name="payment_conditions"
                                        id="payment_conditions"
                                        class="form-control border-0"
                                        rows="5"
                                        maxlength="2000"
                                        placeholder="e.g. 1. Payment must be made within the due date..."
                                    >{{ old('payment_conditions', $invoice->payment_conditions) }}</textarea>
                                </div>
                                <div class="card-footer py-1 bg-light">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Terms & conditions printed on the invoice for the student.
                                    </small>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /row notes & conditions --}}

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning btn-lg px-5">
                        <i class="fas fa-save mr-1"></i> Update Invoice
                    </button>
                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-default float-right">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemIndex = {{ count($invoice->items) }};

    document.getElementById('add-item-btn').addEventListener('click', function() {
        const tableBody = document.querySelector('#invoice-items-table tbody');
        const newRow = document.createElement('tr');
        newRow.id = `item-row-${itemIndex}`;
        newRow.innerHTML = `
            <td>
                <input type="text" name="items[${itemIndex}][description]" class="form-control form-control-sm" placeholder="Description" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][amount]" class="form-control form-control-sm item-amount" step="0.01" min="0" placeholder="0.00" onchange="calculateTotal()" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-xs" onclick="removeRow(${itemIndex})"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tableBody.appendChild(newRow);
        itemIndex++;
    });

    function removeRow(index) {
        // Prevent deleting the last row if it's the only one
        const rows = document.querySelectorAll('#invoice-items-table tbody tr');
        if (rows.length > 1) {
            document.getElementById(`item-row-${index}`).remove();
            calculateTotal();
        } else {
            alert("At least one item is required.");
        }
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-amount').forEach(input => {
            const val = parseFloat(input.value);
            if (!isNaN(val)) {
                total += val;
            }
        });
        document.getElementById('total-amount').innerText = '$' + total.toFixed(2);
    }

    // ── Character counters
    function updateCounter(textareaId, counterId) {
        const ta  = document.getElementById(textareaId);
        const ctr = document.getElementById(counterId);
        if (ta && ctr) {
            ta.addEventListener('input', function() {
                ctr.textContent = this.value.length + ' / 2000';
                ctr.style.color = this.value.length > 1900 ? '#dc3545' : '';
            });
            ctr.textContent = ta.value.length + ' / 2000';
        }
    }
    updateCounter('payment_notes', 'notes-counter');
    updateCounter('payment_conditions', 'conditions-counter');

    // ── Preset default conditions
    document.getElementById('preset-conditions-btn').addEventListener('click', function() {
        const defaultConditions = `1. Payment must be made on or before the due date specified on this invoice.
2. Late payments will incur a penalty of 1% per month on the outstanding balance.
3. All payments must be accompanied by the invoice number as reference.
4. This invoice is valid for 30 days from the date of issue.
5. Any disputes regarding this invoice must be raised within 7 days of receipt.
6. For payment inquiries, contact the Finance Office during working hours.`;
        const ta = document.getElementById('payment_conditions');
        if (!ta.value.trim()) {
            ta.value = defaultConditions;
        } else if (confirm('Replace existing conditions with default template?')) {
            ta.value = defaultConditions;
        }
        ta.dispatchEvent(new Event('input'));
    });
</script>
@endpush
@endsection
