@extends('admin.layouts.master')

@section('title', 'Create Invoice')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create New Invoice</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.invoices.index') }}">Invoices</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">New Fee Structure Invoice</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.invoices.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="student_id">Select Student</label>
                                <select name="student_id" id="student_id" class="form-control select2" required>
                                    <option value="">-- Select Student --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}"
                                            data-avatar="{{ $student->profile_image ? asset($student->profile_image) : asset('backend/dist/img/user2-160x160.jpg') }}"
                                            data-name="{{ $student->name }}"
                                            data-email="{{ $student->email }}"
                                            data-gender="{{ $student->gender ?? 'N/A' }}"
                                            data-phone="{{ $student->phone ?? 'N/A' }}">
                                            {{ $student->name }} ({{ $student->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Due Date</label>
                                <div class="input-group date" id="due_date_picker" data-target-input="nearest">
                                    <input type="text" name="due_date" class="form-control datetimepicker-input" data-target="#due_date_picker" value="{{ \Carbon\Carbon::now()->addDays(14)->format('Y-m-d') }}" required/>
                                    <div class="input-group-append" data-target="#due_date_picker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Student Info Card (Full Width) -->
                    <div class="row">
                        <div class="col-12">
                            <div id="student-info-card" class="card shadow-sm border-left-primary mb-4" style="display: none; border-left: 3px solid #007bff;">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <img id="info-avatar" src="" class="img-circle elevation-2 mr-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <div>
                                            <h5 id="info-name" class="m-0 font-weight-bold text-dark">Student Name</h5>
                                            <div class="row mt-2">
                                                <div class="col-auto">
                                                    <div class="text-muted small">
                                                        <i class="fas fa-venus-mars mr-1"></i> <span id="info-gender">Male</span>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="text-muted small">
                                                        <i class="fas fa-phone mr-1"></i> <span id="info-phone">012345678</span>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="text-muted small">
                                                        <i class="fas fa-envelope mr-1"></i> <span id="info-email">email@example.com</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                            <tr id="item-row-0">
                                                <td>
                                                    <input type="text" name="items[0][description]" class="form-control form-control-sm" placeholder="e.g. Tuition Fee Semester 1" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[0][amount]" class="form-control form-control-sm item-amount" step="0.01" min="0" placeholder="0.00" onchange="calculateTotal()" required>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-xs" onclick="removeRow(0)"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right font-weight-bold">Total:</td>
                                                <td class="font-weight-bold" id="total-amount">$0.00</td>
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
                                        placeholder="e.g. Please transfer to ABA Bank
Account: 000 123 456
Account Name: SOURNG University
Include your invoice number as reference."
                                    >{{ old('payment_notes') }}</textarea>
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
                                        placeholder="e.g. 1. Payment must be made within the due date.
2. Late payments incur a 5% monthly penalty.
3. Disputes must be raised within 7 days of invoice date."
                                    >{{ old('payment_conditions') }}</textarea>
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
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-file-invoice mr-1"></i> Create Invoice
                    </button>
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-default float-right">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Initialize DateTimePicker
        $('#due_date_picker').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        // Student Select Change Event
        $('#student_id').on('change', function() {
            var selectedOption = $(this).find(':selected');
            if (selectedOption.val()) {
                var avatar = selectedOption.data('avatar');
                var name = selectedOption.data('name');
                var gender = selectedOption.data('gender');
                var phone = selectedOption.data('phone');
                var email = selectedOption.data('email');

                $('#info-avatar').attr('src', avatar);
                $('#info-name').text(name);
                $('#info-gender').text(gender);
                $('#info-phone').text(phone);
                $('#info-email').text(email);
                
                $('#student-info-card').slideDown();
            } else {
                $('#student-info-card').slideUp();
            }
        });
    });

    let itemIndex = 1;

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

    // ── Character counters ────────────────────────────────────────────────────
    function updateCounter(textareaId, counterId) {
        const ta  = document.getElementById(textareaId);
        const ctr = document.getElementById(counterId);
        if (ta && ctr) {
            ta.addEventListener('input', function() {
                ctr.textContent = this.value.length + ' / 2000';
                ctr.style.color = this.value.length > 1900 ? '#dc3545' : '';
            });
            // Init on load
            ctr.textContent = ta.value.length + ' / 2000';
        }
    }
    updateCounter('payment_notes', 'notes-counter');
    updateCounter('payment_conditions', 'conditions-counter');

    // ── Preset default conditions ─────────────────────────────────────────────
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
        // Trigger counter update
        ta.dispatchEvent(new Event('input'));
    });
</script>
@endpush
@endsection
