@extends('guest.layouts.master')

@section('title', 'Write a Review')

@push('styles')
<style>
    .rating-group {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .rating-group input {
        display: none;
    }
    .rating-group label {
        cursor: pointer;
        font-size: 28px;
        color: #ddd;
        transition: all 0.2s ease;
        padding: 0 2px;
    }
    .rating-group label:hover,
    .rating-group label:hover ~ label,
    .rating-group input:checked ~ label {
        color: #ffc107;
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-star text-warning mr-2"></i>Write a Review</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('guest.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guest.bookings.index') }}">My Bookings</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guest.bookings.show', $booking->id) }}">Booking #{{ $booking->id }}</a></li>
                    <li class="breadcrumb-item active">Review</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')

<div class="row">
    <div class="col-md-8">
        <div class="card guest-card">
            <div class="card-body">
                <form action="{{ route('guest.bookings.review.submit', $booking->id) }}" method="POST">
                    @csrf
                    
                    <!-- Overall Rating -->
                    <div class="form-group">
                        <label class="d-block"><strong>Overall Rating</strong> <span class="text-danger">*</span></label>
                        <div class="rating-group">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="overall_rating" id="star{{ $i }}" value="{{ $i }}" {{ old('overall_rating') == $i ? 'checked' : '' }} required>
                                <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
                            @endfor
                        </div>
                        @error('overall_rating')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>

                    <!-- Detailed Ratings -->
                    <h5 class="mb-3">Rate Your Experience (Optional)</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Staff Service</label>
                                <div class="rating-group rating-sm">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="staff_rating" id="staff{{ $i }}" value="{{ $i }}" {{ old('staff_rating') == $i ? 'checked' : '' }}>
                                        <label for="staff{{ $i }}" style="font-size: 20px;"><i class="fas fa-star"></i></label>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cleanliness</label>
                                <div class="rating-group rating-sm">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="cleanliness_rating" id="clean{{ $i }}" value="{{ $i }}" {{ old('cleanliness_rating') == $i ? 'checked' : '' }}>
                                        <label for="clean{{ $i }}" style="font-size: 20px;"><i class="fas fa-star"></i></label>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Comfort</label>
                                <div class="rating-group rating-sm">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="comfort_rating" id="comfort{{ $i }}" value="{{ $i }}" {{ old('comfort_rating') == $i ? 'checked' : '' }}>
                                        <label for="comfort{{ $i }}" style="font-size: 20px;"><i class="fas fa-star"></i></label>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Location</label>
                                <div class="rating-group rating-sm">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="location_rating" id="loc{{ $i }}" value="{{ $i }}" {{ old('location_rating') == $i ? 'checked' : '' }}>
                                        <label for="loc{{ $i }}" style="font-size: 20px;"><i class="fas fa-star"></i></label>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Value for Money</label>
                                <div class="rating-group rating-sm">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="value_rating" id="value{{ $i }}" value="{{ $i }}" {{ old('value_rating') == $i ? 'checked' : '' }}>
                                        <label for="value{{ $i }}" style="font-size: 20px;"><i class="fas fa-star"></i></label>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Review Text -->
                    <div class="form-group">
                        <label><strong>Review Title</strong></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" placeholder="Summarize your experience in a few words">
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label><strong>Your Review</strong> <span class="text-danger">*</span></label>
                        <textarea name="review_text" rows="5" class="form-control @error('review_text') is-invalid @enderror" 
                                  placeholder="Tell us about your experience. What did you like? What could be improved?" required>{{ old('review_text') }}</textarea>
                        @error('review_text')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="text-muted">Minimum 10 characters</small>
                    </div>

                    <button type="submit" class="btn btn-gradient btn-lg">
                        <i class="fas fa-paper-plane"></i> Submit Review
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Booking Summary -->
        <div class="card guest-card">
            <div class="card-header">
                <strong><i class="fas fa-info-circle"></i> Your Stay</strong>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <i class="fas fa-calendar text-primary"></i>
                    <strong>{{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}</strong>
                </p>
                <p class="mb-2">
                    <i class="fas fa-moon text-primary"></i>
                    {{ $booking->check_in->diffInDays($booking->check_out) }} night(s)
                </p>
                <p class="mb-0">
                    <i class="fas fa-bed text-primary"></i>
                    @foreach($booking->rooms as $room)
                        {{ $room->roomType->room_type_name ?? 'Room' }} #{{ $room->room_number }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </p>
            </div>
        </div>

        <!-- Tips -->
        <div class="card guest-card mt-4">
            <div class="card-header">
                <strong><i class="fas fa-lightbulb"></i> Tips for a Great Review</strong>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Be specific about what you liked</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Mention helpful staff by name</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Describe the room and amenities</li>
                    <li class="mb-0"><i class="fas fa-check text-success mr-2"></i> Share constructive feedback</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
