<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $booking->id }} - {{ $hotelName }}</title>
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
            background: #f5f5f5;
        }

        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hotel-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .hotel-logo {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hotel-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .hotel-logo-placeholder {
            font-size: 32px;
            font-weight: bold;
            color: #fff;
        }

        .hotel-details h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .hotel-details p {
            font-size: 13px;
            opacity: 0.9;
        }

        .invoice-badge {
            text-align: right;
        }

        .invoice-badge h2 {
            font-size: 32px;
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .invoice-badge .invoice-number {
            font-size: 16px;
            opacity: 0.9;
            margin-top: 5px;
        }

        .invoice-body {
            padding: 40px;
        }

        .invoice-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 30px;
        }

        .invoice-info-box {
            flex: 1;
        }

        .invoice-info-box h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #888;
            letter-spacing: 1px;
            margin-bottom: 10px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 5px;
            display: inline-block;
        }

        .invoice-info-box p {
            margin-bottom: 3px;
        }

        .invoice-info-box .highlight {
            font-weight: 600;
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-confirmed { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-checked_in { background: #cce5ff; color: #004085; }
        .status-checked_out { background: #e2e3e5; color: #383d41; }
        .status-cancelled { background: #f8d7da; color: #721c24; }

        .payment-paid { background: #d4edda; color: #155724; }
        .payment-unpaid { background: #f8d7da; color: #721c24; }
        .payment-partial { background: #fff3cd; color: #856404; }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .invoice-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }

        .invoice-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .invoice-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .invoice-table tbody tr:hover {
            background: #f9f9f9;
        }

        .invoice-table .text-right {
            text-align: right;
        }

        .invoice-summary {
            display: flex;
            justify-content: flex-end;
        }

        .summary-box {
            width: 300px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row.total {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
            border-top: 2px solid #667eea;
            margin-top: 10px;
            padding-top: 15px;
        }

        .special-requests {
            margin-top: 30px;
            padding: 20px;
            background: #fff9db;
            border-left: 4px solid #f0c000;
            border-radius: 5px;
        }

        .special-requests h4 {
            color: #856404;
            margin-bottom: 10px;
        }

        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px dashed #ddd;
            text-align: center;
            color: #888;
            font-size: 12px;
        }

        .invoice-footer p {
            margin-bottom: 5px;
        }

        .invoice-footer .thank-you {
            font-size: 18px;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 15px;
        }

        /* Print styles */
        @media print {
            body {
                background: #fff;
            }
            
            .invoice-container {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
            
            .invoice-header {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .invoice-table thead {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }

        /* Action buttons */
        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <!-- Action Buttons -->
    <div class="action-buttons no-print">
        <button class="btn btn-primary" onclick="window.print()">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
            </svg>
            Print Invoice
        </button>
        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-secondary">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Back
        </a>
    </div>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="hotel-info">
                <div class="hotel-logo">
                    @if($hotelLogo)
                        <img src="{{ asset($hotelLogo) }}" alt="{{ $hotelName }}">
                    @else
                        <span class="hotel-logo-placeholder">{{ strtoupper(substr($hotelName, 0, 2)) }}</span>
                    @endif
                </div>
                <div class="hotel-details">
                    <h1>{{ $hotelName }}</h1>
                    @if($hotelAddress)
                        <p>{{ $hotelAddress }}</p>
                    @endif
                    @if($hotelPhone || $hotelEmail)
                        <p>
                            @if($hotelPhone) Tel: {{ $hotelPhone }} @endif
                            @if($hotelPhone && $hotelEmail) | @endif
                            @if($hotelEmail) Email: {{ $hotelEmail }} @endif
                        </p>
                    @endif
                </div>
            </div>
            <div class="invoice-badge">
                <h2>Invoice</h2>
                <p class="invoice-number">#INV-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <div class="invoice-info-row">
                <!-- Guest Information -->
                <div class="invoice-info-box">
                    <h3>Bill To</h3>
                    <p class="highlight">{{ $booking->user->name ?? 'Guest' }}</p>
                    <p>{{ $booking->user->email ?? '' }}</p>
                    @if($booking->user && $booking->user->guestProfile)
                        @if($booking->user->guestProfile->phone)
                            <p>{{ $booking->user->guestProfile->phone }}</p>
                        @endif
                        @if($booking->user->guestProfile->address)
                            <p>{{ $booking->user->guestProfile->address }}</p>
                        @endif
                        @if($booking->user->guestProfile->city || $booking->user->guestProfile->country)
                            <p>{{ $booking->user->guestProfile->city }}{{ $booking->user->guestProfile->city && $booking->user->guestProfile->country ? ', ' : '' }}{{ $booking->user->guestProfile->country }}</p>
                        @endif
                    @endif
                </div>

                <!-- Booking Details -->
                <div class="invoice-info-box">
                    <h3>Booking Details</h3>
                    <p><span class="highlight">Check-in:</span> {{ $booking->check_in->format('D, M d, Y') }}</p>
                    <p><span class="highlight">Check-out:</span> {{ $booking->check_out->format('D, M d, Y') }}</p>
                    <p><span class="highlight">Nights:</span> {{ $nights }} night{{ $nights > 1 ? 's' : '' }}</p>
                </div>

                <!-- Status -->
                <div class="invoice-info-box">
                    <h3>Status</h3>
                    <p style="margin-bottom: 10px;">
                        <span class="status-badge status-{{ $booking->status }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </p>
                    <p>
                        <span class="status-badge payment-{{ $booking->payment_status }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </p>
                    <p style="margin-top: 15px;"><span class="highlight">Date:</span> {{ $booking->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Rooms Table -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Room</th>
                        <th>Type</th>
                        <th class="text-right">Price/Night</th>
                        <th class="text-right">Nights</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->rooms as $index => $room)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>Room {{ $room->room_number }}</strong></td>
                            <td>{{ $room->roomType->room_type_name ?? 'Standard' }}</td>
                            <td class="text-right">${{ number_format($room->pivot->price, 2) }}</td>
                            <td class="text-right">{{ $nights }}</td>
                            <td class="text-right"><strong>${{ number_format($room->pivot->price * $nights, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Summary -->
            <div class="invoice-summary">
                <div class="summary-box">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>${{ number_format($booking->total_price, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (0%)</span>
                        <span>$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Discount</span>
                        <span>$0.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>${{ number_format($booking->total_price, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Special Requests -->
            @if($booking->special_requests)
                <div class="special-requests">
                    <h4>📝 Special Requests</h4>
                    <p>{{ $booking->special_requests }}</p>
                </div>
            @endif

            <!-- Footer -->
            <div class="invoice-footer">
                <p class="thank-you">Thank you for choosing {{ $hotelName }}!</p>
                <p>We hope you had a wonderful stay and look forward to welcoming you again.</p>
                @if($hotelWebsite)
                    <p>Visit us: {{ $hotelWebsite }}</p>
                @endif
                <p style="margin-top: 10px;">This is a computer-generated invoice. No signature required.</p>
            </div>
        </div>
    </div>
</body>
</html>
