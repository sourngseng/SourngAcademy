@extends('guest.layouts.master')

@section('title', 'Make Payment')

@push('styles')
<style>
    .payment-method-card {
        border: 2px solid #dee2e6;
        border-radius: 15px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }
    .payment-method-card:hover {
        border-color: #667eea;
        background: #f8f9ff;
    }
    .payment-method-card.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    }
    .payment-method-card i {
        font-size: 36px;
        margin-bottom: 10px;
    }
    .payment-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 25px;
    }
    .amount-input {
        font-size: 24px;
        font-weight: bold;
        text-align: center;
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-credit-card mr-2"></i>Make Payment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('guest.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guest.bookings.show', $booking->id) }}">Booking #{{ $booking->id }}</a></li>
                    <li class="breadcrumb-item active">Payment</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <form action="{{ route('guest.payments.store', $booking->id) }}" method="POST" id="paymentForm">
            @csrf
            
            <!-- Payment Amount -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-dollar-sign text-success"></i> Payment Amount</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Amount to Pay (USD)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" name="amount" id="amount" 
                                   class="form-control amount-input @error('amount') is-invalid @enderror" 
                                   value="{{ old('amount', $booking->remaining_balance) }}"
                                   min="0.01" max="{{ $booking->remaining_balance }}" step="0.01" required>
                        </div>
                        @error('amount')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        <small class="text-muted mt-2 d-block">
                            Maximum payable: ${{ number_format($booking->remaining_balance, 2) }}
                        </small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount({{ $booking->remaining_balance / 2 }})">
                            Pay 50% (${{ number_format($booking->remaining_balance / 2, 2) }})
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAmount({{ $booking->remaining_balance }})">
                            Pay Full (${{ number_format($booking->remaining_balance, 2) }})
                        </button>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-wallet text-primary"></i> Select Payment Method</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($paymentMethods as $key => $name)
                        <div class="col-md-3 col-6 mb-3">
                            <div class="payment-method-card {{ old('payment_method') == $key ? 'selected' : '' }}" data-method="{{ $key }}">
                                <i class="fas fa-{{ $key == 'credit_card' ? 'credit-card' : ($key == 'debit_card' ? 'credit-card' : ($key == 'bank_transfer' ? 'university' : 'qrcode')) }} text-primary"></i>
                                <h6 class="mb-0">{{ $name }}</h6>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="payment_method" id="payment_method" value="{{ old('payment_method', 'credit_card') }}" required>
                    @error('payment_method')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Submit -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-success btn-lg btn-block" id="submitBtn">
                        <i class="fas fa-lock"></i> Pay Securely
                    </button>
                    <p class="text-center text-muted mt-3 mb-0">
                        <i class="fas fa-shield-alt"></i> Your payment is secure and encrypted
                    </p>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-4">
        <!-- Payment Summary -->
        <div class="payment-summary mb-4">
            <h5 class="mb-4"><i class="fas fa-receipt"></i> Booking Summary</h5>
            <div class="d-flex justify-content-between mb-2">
                <span>Booking #</span>
                <strong>{{ $booking->id }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Check-in</span>
                <strong>{{ $booking->check_in->format('M d, Y') }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Check-out</span>
                <strong>{{ $booking->check_out->format('M d, Y') }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Nights</span>
                <strong>{{ $booking->check_in->diffInDays($booking->check_out) }}</strong>
            </div>
            <hr style="border-color: rgba(255,255,255,0.3);">
            <div class="d-flex justify-content-between mb-2">
                <span>Total Price</span>
                <strong>${{ number_format($booking->total_price, 2) }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Already Paid</span>
                <strong>${{ number_format($booking->paid_amount, 2) }}</strong>
            </div>
            <hr style="border-color: rgba(255,255,255,0.3);">
            <div class="d-flex justify-content-between">
                <span class="h5 mb-0">Balance Due</span>
                <strong class="h4 mb-0">${{ number_format($booking->remaining_balance, 2) }}</strong>
            </div>
        </div>

        <!-- Room Info -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bed"></i> Room(s)</h6>
            </div>
            <div class="card-body">
                @foreach($booking->rooms as $room)
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ $room->roomType->room_type_name ?? 'Room' }} #{{ $room->room_number }}</span>
                    <strong>${{ number_format($room->pivot->price ?? $room->price_per_night, 2) }}/night</strong>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Payment method selection
        $('.payment-method-card').on('click', function() {
            $('.payment-method-card').removeClass('selected');
            $(this).addClass('selected');
            $('#payment_method').val($(this).data('method'));
        });
        
        // Initial selection
        $('.payment-method-card[data-method="' + $('#payment_method').val() + '"]').addClass('selected');
    });
    
    function setAmount(amount) {
        $('#amount').val(amount.toFixed(2));
    }
</script>
@endpush
