@extends('admin.layouts.master')

@section('title', 'Guest Profile - ' . $guest->name)

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    
    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: translate(100px, -150px);
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.3);
            object-fit: cover;
            background: rgba(255,255,255,0.2);
        }
        .profile-avatar-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: bold;
        }
        .vip-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        .vip-platinum { background: rgba(255,255,255,0.2); }
        .vip-gold { background: rgba(255,193,7,0.3); }
        .vip-silver { background: rgba(255,255,255,0.15); }
        .vip-regular { background: rgba(255,255,255,0.1); }
        
        .stat-mini-card {
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        .stat-mini-card h4 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .stat-mini-card p {
            margin: 0;
            font-size: 12px;
            opacity: 0.8;
        }
        
        .nav-tabs-custom .nav-link {
            border: none;
            padding: 12px 20px;
            color: #6c757d;
            font-weight: 500;
            border-bottom: 3px solid transparent;
        }
        .nav-tabs-custom .nav-link.active {
            color: #667eea;
            border-bottom-color: #667eea;
            background: transparent;
        }
        .nav-tabs-custom .nav-link:hover {
            color: #667eea;
        }
        
        .info-item {
            margin-bottom: 15px;
        }
        .info-item label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .info-item p {
            font-size: 15px;
            font-weight: 500;
            color: #333;
            margin: 0;
        }
        
        .timeline-item {
            position: relative;
            padding-left: 30px;
            padding-bottom: 20px;
            border-left: 2px solid #e9ecef;
        }
        .timeline-item:last-child {
            border-left: 2px solid transparent;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #667eea;
            border: 2px solid white;
        }
        .timeline-item.completed::before {
            background: #28a745;
        }
        .timeline-item.cancelled::before {
            background: #dc3545;
        }
        
        .review-card {
            border-left: 4px solid #667eea;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .rating-stars {
            color: #ffc107;
        }
    </style>
@endpush

@section('page-title')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-user"></i> Guest Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.guests.index') }}">Guests</a></li>
                        <li class="breadcrumb-item active">{{ $guest->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <!-- Profile Header -->
    <div class="profile-header mb-4">
        <div class="row align-items-center">
            <div class="col-auto">
                @if($guest->profile_image)
                    <img src="{{ asset($guest->profile_image) }}" class="profile-avatar" alt="{{ $guest->name }}">
                @else
                    <div class="profile-avatar-placeholder">
                        {{ strtoupper(substr($guest->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="col">
                <h2 class="mb-1">{{ $guest->name }}</h2>
                <p class="mb-2 opacity-75">
                    <i class="fas fa-envelope mr-2"></i>{{ $guest->email }}
                    @if($guest->guestProfile && $guest->guestProfile->phone)
                        <span class="mx-2">•</span>
                        <i class="fas fa-phone mr-2"></i>{{ $guest->guestProfile->phone }}
                    @endif
                </p>
                <span class="vip-badge vip-{{ $guest->guestProfile->vip_status ?? 'regular' }}">
                    @php
                        $icons = ['platinum' => 'fa-gem', 'gold' => 'fa-crown', 'silver' => 'fa-medal', 'regular' => 'fa-user'];
                        $status = $guest->guestProfile->vip_status ?? 'regular';
                    @endphp
                    <i class="fas {{ $icons[$status] }}"></i>
                    {{ ucfirst($status) }} Member
                </span>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.guests.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <a href="javascript:void(0)" class="btn btn-light ml-2" onclick="window.print()">
                    <i class="fas fa-print"></i>
                </a>
            </div>
        </div>
        
        <!-- Mini Stats -->
        <div class="row mt-4">
            <div class="col-md-3 col-6">
                <div class="stat-mini-card">
                    <h4>{{ $stats['total_stays'] }}</h4>
                    <p>Total Stays</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-mini-card">
                    <h4>{{ $stats['total_nights'] }}</h4>
                    <p>Total Nights</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-mini-card">
                    <h4>${{ number_format($stats['total_spent'], 2) }}</h4>
                    <p>Total Spent</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-mini-card">
                    <h4>
                        @if($stats['avg_rating_given'])
                            <i class="fas fa-star text-warning"></i> {{ number_format($stats['avg_rating_given'], 1) }}
                        @else
                            -
                        @endif
                    </h4>
                    <p>Avg. Rating Given</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Content -->
    <div class="card">
        <div class="card-header p-0">
            <ul class="nav nav-tabs nav-tabs-custom" id="guestTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab">
                        <i class="fas fa-info-circle"></i> Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="bookings-tab" data-toggle="tab" href="#bookings" role="tab">
                        <i class="fas fa-calendar-check"></i> Booking History
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab">
                        <i class="fas fa-star"></i> Reviews ({{ $stats['reviews_count'] }})
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="guestTabsContent">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-user"></i> Personal Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="info-item">
                                                <label>Full Name</label>
                                                <p>{{ $guest->name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item">
                                                <label>Email</label>
                                                <p>{{ $guest->email }}</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item">
                                                <label>Phone</label>
                                                <p>{{ $guest->guestProfile->phone ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item">
                                                <label>Date of Birth</label>
                                                <p>{{ $guest->guestProfile && $guest->guestProfile->date_of_birth ? $guest->guestProfile->date_of_birth->format('M d, Y') : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item">
                                                <label>Gender</label>
                                                <p>{{ $guest->guestProfile ? ucfirst($guest->guestProfile->gender ?? '-') : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item">
                                                <label>Nationality</label>
                                                <p>{{ $guest->guestProfile->nationality ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address & Identity -->
                        <div class="col-md-6">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-id-card"></i> Address & Identity</h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-item">
                                        <label>Address</label>
                                        <p>{{ $guest->guestProfile->address ?? '-' }}</p>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="info-item">
                                                <label>City</label>
                                                <p>{{ $guest->guestProfile->city ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item">
                                                <label>Country</label>
                                                <p>{{ $guest->guestProfile->country ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item">
                                                <label>ID Type</label>
                                                <p>{{ $guest->guestProfile ? ucfirst(str_replace('_', ' ', $guest->guestProfile->id_type ?? '-')) : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item">
                                                <label>ID Number</label>
                                                <p>{{ $guest->guestProfile->id_number ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Favorite Room Types -->
                        <div class="col-md-6">
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-heart"></i> Favorite Room Types</h5>
                                </div>
                                <div class="card-body">
                                    @if($favoriteRoomTypes->count() > 0)
                                        @foreach($favoriteRoomTypes as $roomType)
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span><i class="fas fa-bed text-primary mr-2"></i> {{ $roomType['type'] }}</span>
                                                <span class="badge badge-primary">{{ $roomType['count'] }} stays</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted mb-0">No booking history yet</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Internal Notes -->
                        <div class="col-md-6">
                            <div class="card card-outline card-secondary">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-sticky-note"></i> Internal Notes</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $guest->guestProfile->notes ?? 'No notes added.' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        @if($guest->guestProfile && ($guest->guestProfile->emergency_contact_name || $guest->guestProfile->emergency_contact_phone))
                        <div class="col-md-12">
                            <div class="card card-outline card-danger">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-phone-alt"></i> Emergency Contact</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="info-item">
                                                <label>Contact Name</label>
                                                <p>{{ $guest->guestProfile->emergency_contact_name ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item">
                                                <label>Contact Phone</label>
                                                <p>{{ $guest->guestProfile->emergency_contact_phone ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Bookings Tab -->
                <div class="tab-pane fade" id="bookings" role="tabpanel">
                    <table class="table table-bordered table-striped" id="bookingsTable">
                        <thead>
                            <tr>
                                <th>Dates</th>
                                <th>Rooms</th>
                                <th>Nights</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Review</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    @if($guest->reviews->count() > 0)
                        @foreach($guest->reviews as $review)
                            <div class="review-card">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <div class="rating-stars mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->overall_rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                            <span class="ml-2 text-dark font-weight-bold">{{ $review->overall_rating }}/5</span>
                                        </div>
                                        @if($review->title)
                                            <h5 class="mb-1">{{ $review->title }}</h5>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <span class="badge badge-{{ $review->status_badge_color }}">{{ ucfirst($review->status) }}</span>
                                        <small class="d-block text-muted mt-1">{{ $review->created_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                                <p class="mb-2">{{ $review->review_text }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt"></i> 
                                    Booking: {{ $review->booking->check_in->format('M d') }} - {{ $review->booking->check_out->format('M d, Y') }}
                                </small>
                                
                                @if($review->admin_response)
                                    <div class="mt-3 p-3 bg-white rounded border-left border-primary" style="border-left-width: 3px !important;">
                                        <strong><i class="fas fa-reply text-primary"></i> Hotel Response:</strong>
                                        <p class="mb-0 mt-1">{{ $review->admin_response }}</p>
                                        <small class="text-muted">Responded {{ $review->responded_at->diffForHumans() }}</small>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-star fa-4x text-muted mb-3"></i>
                            <p class="text-muted">This guest hasn't written any reviews yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables & Plugins -->
    <script src="{{asset('backend')}}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>

    <script type="text/javascript">
        $(function () {
            // Initialize bookings DataTable when tab is shown
            var bookingsTable;
            
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                if (e.target.id === 'bookings-tab' && !bookingsTable) {
                    bookingsTable = $('#bookingsTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('admin.guests.booking-history', $guest->id) }}",
                        columns: [
                            {data: 'dates', name: 'check_in'},
                            {data: 'rooms_list', name: 'rooms_list', orderable: false},
                            {data: 'nights', name: 'nights', orderable: false},
                            {data: 'total_price', name: 'total_price'},
                            {data: 'status_badge', name: 'status'},
                            {data: 'payment_badge', name: 'payment_status'},
                            {data: 'has_review', name: 'has_review', orderable: false},
                        ],
                        order: [[0, 'desc']]
                    });
                }
            });
        });
    </script>
@endpush
