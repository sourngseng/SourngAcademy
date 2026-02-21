@extends('guest.layouts.master')

@section('title', 'Book Room')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-calendar-plus mr-2"></i>Book Your Stay</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if($isAuthenticated && $isGuest)
                        <li class="breadcrumb-item"><a href="{{ route('guest.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('guest.bookings.index') }}">My Bookings</a></li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    @endif
                    <li class="breadcrumb-item active">New Booking</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    <!-- Booking Form -->
    <div class="col-md-8">
        <div class="card guest-card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit text-primary"></i> Booking Details</h3>
            </div>
            <div class="card-body">
                @if(!$isAuthenticated)
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Please login or register to complete your booking.</strong>
                        <div class="mt-2">
                            <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}" class="btn btn-sm btn-primary mr-2">
                                <i class="fas fa-sign-in-alt mr-1"></i>Login
                            </a>
                            <a href="{{ route('register') }}?redirect={{ urlencode(request()->fullUrl()) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-user-plus mr-1"></i>Register
                            </a>
                        </div>
                    </div>
                @elseif(!$isGuest)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Only guest accounts can make bookings.</strong> You are currently logged in as {{ Auth::user()->role }}.
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('guest.bookings.store') }}" method="POST" id="bookingForm">
                    @csrf
                    
                    @if($room)
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        
                        <!-- Selected Room Info -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-bed fa-2x mr-3"></i>
                                <div>
                                    <h5 class="mb-1">{{ $room->roomType->name ?? 'Room' }} - Room {{ $room->room_number }}</h5>
                                    <p class="mb-0">${{ number_format($room->price_per_night, 2) }} per night</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Room Selection -->
                        <div class="form-group">
                            <label for="room_id"><i class="fas fa-door-open"></i> Select Room <span class="text-danger">*</span></label>
                            <select name="room_id" id="room_id" class="form-control @error('room_id') is-invalid @enderror" required>
                                <option value="">Choose a room...</option>
                                @foreach($roomTypes as $type)
                                    <optgroup label="{{ $type->name }} - ${{ number_format($type->base_price, 2) }}/night">
                                        {{-- Rooms will be loaded via AJAX based on dates --}}
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="check_in"><i class="fas fa-calendar-check"></i> Check-in Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="check_in" 
                                       id="check_in" 
                                       class="form-control @error('check_in') is-invalid @enderror" 
                                       value="{{ old('check_in', $checkIn) }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('check_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="check_out"><i class="fas fa-calendar-times"></i> Check-out Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="check_out" 
                                       id="check_out" 
                                       class="form-control @error('check_out') is-invalid @enderror" 
                                       value="{{ old('check_out', $checkOut) }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       required>
                                @error('check_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="guests"><i class="fas fa-users"></i> Number of Guests <span class="text-danger">*</span></label>
                        <select name="guests" id="guests" class="form-control @error('guests') is-invalid @enderror" required>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ old('guests', $guests) == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}</option>
                            @endfor
                        </select>
                        @error('guests')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="special_requests"><i class="fas fa-comment-alt"></i> Special Requests</label>
                        <textarea name="special_requests" 
                                  id="special_requests" 
                                  class="form-control @error('special_requests') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Any special requirements? (e.g., early check-in, extra bed, dietary restrictions)">{{ old('special_requests') }}</textarea>
                        @error('special_requests')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="terms" required>
                            <label class="custom-control-label" for="terms">
                                I agree to the <a href="#" data-toggle="modal" data-target="#termsModal">Terms & Conditions</a> and <a href="#" data-toggle="modal" data-target="#policyModal">Cancellation Policy</a>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block" {{ (!$isAuthenticated || !$isGuest) ? 'disabled' : '' }}>
                        <i class="fas fa-lock mr-2"></i>Proceed to Payment
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Booking Summary -->
    <div class="col-md-4">
        <div class="card guest-card sticky-top" style="top: 20px;">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0"><i class="fas fa-receipt"></i> Booking Summary</h3>
            </div>
            <div class="card-body">
                @if($room)
                    <!-- Room Image -->
                    @if($room->images && $room->images->count() > 0)
                        <img src="{{ asset('storage/' . $room->images->first()->image_path) }}" 
                             alt="{{ $room->roomType->name ?? 'Room' }}" 
                             class="img-fluid rounded mb-3">
                    @else
                        <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Room" 
                             class="img-fluid rounded mb-3">
                    @endif

                    <h5>{{ $room->roomType->name ?? 'Room' }}</h5>
                    <p class="text-muted mb-2">Room {{ $room->room_number }}</p>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-calendar-check text-success"></i> Check-in:</span>
                        <strong id="summaryCheckIn">{{ $checkIn ? \Carbon\Carbon::parse($checkIn)->format('M d, Y') : '-' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-calendar-times text-danger"></i> Check-out:</span>
                        <strong id="summaryCheckOut">{{ $checkOut ? \Carbon\Carbon::parse($checkOut)->format('M d, Y') : '-' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-moon text-info"></i> Nights:</span>
                        <strong id="summaryNights">{{ $nights }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-users text-warning"></i> Guests:</span>
                        <strong id="summaryGuests">{{ $guests }}</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>${{ number_format($room->price_per_night, 2) }} × <span id="nightsCount">{{ $nights }}</span> nights</span>
                        <strong id="subtotal">${{ number_format($totalPrice, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Taxes & Fees</span>
                        <span>Included</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0">Total</h5>
                        <h4 class="mb-0 text-primary" id="totalPrice">${{ number_format($totalPrice, 2) }}</h4>
                    </div>

                    <input type="hidden" id="pricePerNight" value="{{ $room->price_per_night }}">
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Select dates and room to see booking summary</p>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex align-items-center text-muted small">
                    <i class="fas fa-shield-alt mr-2 text-success"></i>
                    <span>Secure booking • Best price guaranteed</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms & Conditions</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>1. Booking Confirmation</h6>
                <p>Your booking is confirmed upon receipt of payment. A confirmation email will be sent to your registered email address.</p>
                
                <h6>2. Check-in / Check-out</h6>
                <p>Standard check-in time is 2:00 PM and check-out time is 12:00 PM. Early check-in or late check-out may be available upon request.</p>
                
                <h6>3. Payment</h6>
                <p>Full payment is required at the time of booking. We accept major credit cards and online payment methods.</p>
                
                <h6>4. Identification</h6>
                <p>Guests must present valid photo identification at check-in.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancellation Policy Modal -->
<div class="modal fade" id="policyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancellation Policy</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <strong>Free cancellation</strong> up to 24 hours before check-in
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-exclamation-circle text-warning mr-2"></i>
                        <strong>50% refund</strong> for cancellations within 24 hours of check-in
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-times-circle text-danger mr-2"></i>
                        <strong>No refund</strong> for no-shows or same-day cancellations
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const pricePerNight = parseFloat($('#pricePerNight').val()) || 0;

    function updateSummary() {
        const checkIn = $('#check_in').val();
        const checkOut = $('#check_out').val();
        const guests = $('#guests').val();

        if (checkIn && checkOut) {
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));

            if (nights > 0) {
                const total = pricePerNight * nights;

                $('#summaryCheckIn').text(checkInDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }));
                $('#summaryCheckOut').text(checkOutDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }));
                $('#summaryNights').text(nights);
                $('#nightsCount').text(nights);
                $('#subtotal').text('$' + total.toFixed(2));
                $('#totalPrice').text('$' + total.toFixed(2));
            }
        }

        $('#summaryGuests').text(guests);
    }

    $('#check_in, #check_out, #guests').on('change', updateSummary);

    // Ensure check-out is after check-in
    $('#check_in').on('change', function() {
        const checkIn = new Date($(this).val());
        const minCheckOut = new Date(checkIn);
        minCheckOut.setDate(minCheckOut.getDate() + 1);
        
        const minCheckOutStr = minCheckOut.toISOString().split('T')[0];
        $('#check_out').attr('min', minCheckOutStr);
        
        if ($('#check_out').val() && new Date($('#check_out').val()) <= checkIn) {
            $('#check_out').val(minCheckOutStr);
        }
        
        updateSummary();
    });
});
</script>
@endpush
