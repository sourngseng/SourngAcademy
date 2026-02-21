@extends('guest.layouts.master')

@section('title', 'Scan to Pay')

@push('styles')
<style>
    .khqr-container {
        font-family: 'Inter', sans-serif;
        max-width: 400px;
        margin: 0 auto;
    }
    .acleda-logo {
        height: 60px;
        object-fit: contain;
        margin-bottom: 20px;
    }
    .khqr-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        position: relative;
    }
    .khqr-header {
        background-color: #E31B23; /* KHQR Red */
        color: white;
        height: 70px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    /* The white notch in the bottom right of the header */
    .khqr-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 0 25px 25px;
        border-color: transparent transparent white transparent;
    }
    .khqr-logo-text {
        font-weight: 800;
        font-size: 24px;
        letter-spacing: 1px;
    }
    .merchant-name {
        font-weight: 700;
        font-size: 18px;
        color: #000;
        margin-top: 20px;
        text-transform: uppercase;
    }
    .amount-display {
        font-size: 36px;
        font-weight: 700;
        color: #000;
        line-height: 1;
        margin-bottom: 15px;
    }
    .currency-label {
        font-size: 16px;
        font-weight: 600;
        color: #000;
        vertical-align: middle;
    }
    .dashed-divider {
        border-top: 2px dashed #ddd;
        margin: 0 20px;
    }
    .qr-area {
        padding: 30px;
        text-align: center;
    }
    .qr-instruction {
        font-size: 12px;
        color: #6c757d;
        text-align: center;
        margin-top: 15px;
        margin-bottom: 30px;
        line-height: 1.5;
    }
    .status-badge {
        font-size: 14px;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center pt-4 pb-5" style="background-color: #f4f6f9; min-height: 80vh;">
    <div class="col-12 text-center">
        <!-- ACLEDA Logo Placeholder -->
        <img src="https://www.acledabank.com.kh/assets/images/logo-en.svg" alt="ACLEDA Bank" class="acleda-logo" onerror="this.onerror=null; this.src='https://placehold.co/200x60?text=ACLEDA+BANK';">
        
        <div class="khqr-container">
            <div class="khqr-card">
                <!-- Red Header -->
                <div class="khqr-header">
                    <span class="khqr-logo-text">KHQR</span>
                </div>

                <!-- Info Section -->
                <div class="text-center px-4">
                    <h5 class="merchant-name">{{ \App\Models\Setting::get('acleda_login_id') ?? \App\Models\Setting::get('khqr_merchant_name', 'HAPPY AND CO FARM') }}</h5>
                    <div class="amount-display">
                        {{ number_format($payment->amount, 2) }} <span class="currency-label">USD</span>
                    </div>
                </div>

                <!-- Dashed Line -->
                <div class="dashed-divider"></div>

                <!-- QR Code Section -->
                <div class="qr-area">
                    <div class="d-inline-block bg-white p-2">
                        {{-- Use square style with high error correction for better scanning --}}
                        {!! QrCode::format('svg')->size(220)->errorCorrection('H')->margin(1)->generate($qrString) !!}
                    </div>
                    {{-- Debug: Show QR string for verification --}}
                    @if(config('app.debug'))
                    <div class="mt-2">
                        <small class="text-muted" style="word-break: break-all; font-size: 9px;">{{ Str::limit($qrString, 80) }}</small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Footer / Status -->
           <div class="mt-4 text-center">
                <div id="statusMessage" class="status-badge mb-3">
                    <span class="text-muted"><i class="fas fa-spinner fa-pulse mr-1"></i> Waiting for payment...</span>
                </div>
                
                <p class="qr-instruction">
                    Scan with ACLEDA Mobile, or other Mobile<br>Banking App supporting KHQR
                </p>

                <!-- Timer / Manual Actions -->
                <div class="text-center mt-2">
                    <small class="text-danger d-block mb-3"><i class="far fa-clock"></i> Expires in 15:00</small>
                    
                    <form action="{{ route('guest.payments.check-status', [$booking->id, $payment->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-check"></i> I have paid
                        </button>
                        <a href="{{ route('guest.bookings.show', $booking->id) }}" class="btn btn-sm btn-link text-muted ml-2">Cancel</a>
                    </form>
                </div>
           </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var checkStatusUrl = "{{ route('guest.payments.check-status', [$booking->id, $payment->id]) }}";
        // ... (Include logic if preferred to keep scripts here, but cleaner to keep them in the file)
    });
</script>
<!-- Re-injecting the full script logic correctly below -->
<script>
    $(document).ready(function() {
        var checkStatusUrl = "{{ route('guest.payments.check-status', [$booking->id, $payment->id]) }}";
        var redirectUrl = "{{ route('guest.payments.show', [$booking->id, $payment->id]) }}";
        var pollingInterval = 5000; 
        var maxAttempts = 180; 
        var attempts = 0;

        function checkStatus() {
            attempts++;
            if (attempts > maxAttempts) return;

            $.ajax({
                url: checkStatusUrl,
                type: 'POST',
                data: { _token: "{{ csrf_token() }}", is_polling: 1 },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#statusMessage').html('<span class="text-success font-weight-bold"><i class="fas fa-check-circle"></i> Payment Successful! Redirecting...</span>');
                        window.location.href = response.redirect_url;
                    }
                },
                complete: function() {
                    setTimeout(checkStatus, pollingInterval);
                }
            });
        }

        setTimeout(checkStatus, 3000);
    });
</script>
@endpush
