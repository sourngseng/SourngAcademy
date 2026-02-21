<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #007bff;
        }
        
        .company-info h1 {
            font-size: 28px;
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: #666;
            font-size: 12px;
        }
        
        .invoice-details {
            text-align: right;
        }
        
        .invoice-number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .invoice-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
        }
        
        .status-draft { background: #e9ecef; color: #6c757d; }
        .status-issued { background: #d1ecf1; color: #0c5460; }
        .status-paid { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-overdue { background: #fff3cd; color: #856404; }
        
        .billing-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        
        .billing-to, .invoice-info {
            width: 48%;
        }
        
        .billing-to h3, .invoice-info h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        
        .billing-to p, .invoice-info p {
            margin-bottom: 5px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th {
            background: #007bff;
            color: #fff;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .items-table th:last-child,
        .items-table th:nth-child(2),
        .items-table th:nth-child(3) {
            text-align: right;
        }
        
        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .items-table td:last-child,
        .items-table td:nth-child(2),
        .items-table td:nth-child(3) {
            text-align: right;
        }
        
        .items-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }
        
        .totals-table {
            width: 300px;
        }
        
        .totals-table tr td {
            padding: 8px 0;
        }
        
        .totals-table tr td:last-child {
            text-align: right;
            font-weight: 600;
        }
        
        .totals-table .total-row {
            font-size: 18px;
            border-top: 2px solid #333;
            padding-top: 15px;
        }
        
        .totals-table .total-row td {
            padding-top: 15px;
        }
        
        .notes-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .notes-section h4 {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .notes-section p {
            color: #666;
            font-size: 13px;
        }
        
        .footer {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 25px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .invoice-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        🖨️ Print Invoice
    </button>
    
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>{{ config('app.name', 'Hotel MIS') }}</h1>
                <p>Your Premium Hotel Destination</p>
            </div>
            <div class="invoice-details">
                <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                <span class="invoice-status status-{{ $invoice->status }}">
                    {{ ucfirst($invoice->status) }}
                </span>
            </div>
        </div>

        <!-- Billing Info -->
        <div class="billing-section">
            <div class="billing-to">
                <h3>Bill To</h3>
                <p><strong>{{ $invoice->booking->user->name ?? 'Guest' }}</strong></p>
                <p>{{ $invoice->booking->user->email ?? '' }}</p>
                <p>{{ $invoice->booking->user->phone ?? '' }}</p>
            </div>
            <div class="invoice-info">
                <h3>Invoice Details</h3>
                <p><strong>Invoice Date:</strong> {{ $invoice->issue_date->format('F d, Y') }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->due_date->format('F d, Y') }}</p>
                <p><strong>Booking #:</strong> {{ $invoice->booking_id }}</p>
                <p><strong>Stay:</strong> {{ $invoice->booking->check_in->format('M d') }} - {{ $invoice->booking->check_out->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Line Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @if($invoice->line_items)
                    @foreach($invoice->line_items as $item)
                    <tr>
                        <td>{{ $item['description'] }}</td>
                        <td>{{ $item['quantity'] }} night(s)</td>
                        <td>${{ number_format($item['unit_price'], 2) }}</td>
                        <td><strong>${{ number_format($item['total'], 2) }}</strong></td>
                    </tr>
                    @endforeach
                @else
                    @foreach($invoice->booking->rooms as $room)
                    <tr>
                        <td>{{ $room->roomType->room_type_name ?? 'Room' }} #{{ $room->room_number }}</td>
                        <td>{{ $invoice->booking->nights }} night(s)</td>
                        <td>${{ number_format($room->pivot->price ?? $room->price_per_night, 2) }}</td>
                        <td><strong>${{ number_format(($room->pivot->price ?? $room->price_per_night) * $invoice->booking->nights, 2) }}</strong></td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td>${{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                @if($invoice->tax_amount > 0)
                <tr>
                    <td>Tax ({{ $invoice->tax_rate }}%):</td>
                    <td>${{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
                @endif
                @if($invoice->discount_amount > 0)
                <tr style="color: #dc3545;">
                    <td>Discount{{ $invoice->discount_description ? ' (' . $invoice->discount_description . ')' : '' }}:</td>
                    <td>-${{ number_format($invoice->discount_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td><strong>TOTAL:</strong></td>
                    <td><strong>${{ number_format($invoice->total_amount, 2) }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- Notes -->
        @if($invoice->notes || $invoice->terms)
        <div class="notes-section">
            @if($invoice->notes)
            <h4>Notes</h4>
            <p>{{ $invoice->notes }}</p>
            @endif
            
            @if($invoice->terms)
            <h4 style="margin-top: 15px;">Terms & Conditions</h4>
            <p>{{ $invoice->terms }}</p>
            @endif
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing {{ config('app.name', 'Hotel MIS') }}!</p>
            <p>For any inquiries, please contact our front desk.</p>
        </div>
    </div>
</body>
</html>
