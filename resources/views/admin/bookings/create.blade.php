@extends('admin.layouts.master')

@section('title', 'Create Booking')

@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('backend')}}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{asset('backend')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Booking</h1>
            </div>
            <div class="col-sm-6">
                 <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Bookings</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Booking Form -->
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">New Booking Details</h3>
                </div>
                <form action="{{ route('admin.bookings.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                         <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Check In Date</label>
                                    <div class="input-group date" id="checkin_datepicker" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#checkin_datepicker" name="check_in" id="check_in" required placeholder="Select Check-in Date">
                                        <div class="input-group-append" data-target="#checkin_datepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Check Out Date</label>
                                    <div class="input-group date" id="checkout_datepicker" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#checkout_datepicker" name="check_out" id="check_out" required placeholder="Select Check-out Date">
                                        <div class="input-group-append" data-target="#checkout_datepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Select Room Type</label>
                            <select class="form-control" id="room_type_id">
                                <option value="">-- Select Room Type to Check Availability --</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->price_per_night }} $/night)</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                             <button type="button" class="btn btn-info btn-sm" id="checkAvailabilityBtn">
                                Check Availability
                            </button>
                        </div>

                        <div class="form-group" id="availableRoomsSection" style="display:none;">
                            <label>Available Rooms</label>
                            <div class="row" id="roomList">
                                <!-- Rooms will be loaded here -->
                            </div>
                            <small class="text-danger" id="noRoomsMsg" style="display:none;">No rooms available for the selected dates.</small>
                        </div>

                        <div class="form-group">
                            <label>Guest</label>
                            <select class="form-control select2" name="user_id" required>
                                <option value="">-- Select Guest --</option>
                                @foreach($guests as $guest)
                                    <option value="{{ $guest->id }}">{{ $guest->name }} ({{ $guest->email }})</option>
                                @endforeach
                            </select>
                             <small><a href="{{ route('admin.users.index') }}">Add new guest</a> if not listed.</small>
                        </div>

                        <div class="form-group">
                            <label>Special Requests</label>
                            <textarea class="form-control" name="special_requests" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create Booking</button>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-default float-right">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Summary/Info -->
        <div class="col-md-4">
             <div class="card card-info">
                 <div class="card-header">
                     <h3 class="card-title">Instructions</h3>
                 </div>
                 <div class="card-body">
                     <p>1. Select Check-in and Check-out dates.</p>
                     <p>2. Select a Room Type and click "Check Availability".</p>
                     <p>3. Select one or more available rooms.</p>
                     <p>4. Select a Guest.</p>
                     <p>5. Submit to create a confirmed booking.</p>
                 </div>
             </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('backend')}}/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(function () {
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Initialize DateTime Pickers
        $('#checkin_datepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            minDate: moment().startOf('day'),
            icons: {
                time: 'fas fa-clock',
                date: 'fas fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'fas fa-trash',
                close: 'fas fa-times'
            }
        });

        $('#checkout_datepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            minDate: moment().startOf('day'),
            useCurrent: false,
            icons: {
                time: 'fas fa-clock',
                date: 'fas fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'fas fa-trash',
                close: 'fas fa-times'
            }
        });

        // Link the two date pickers
        $('#checkin_datepicker').on('change.datetimepicker', function(e) {
            $('#checkout_datepicker').datetimepicker('minDate', e.date);
        });

        $('#checkout_datepicker').on('change.datetimepicker', function(e) {
            $('#checkin_datepicker').datetimepicker('maxDate', e.date);
        });

        $('#checkAvailabilityBtn').click(function() {
            var checkIn = $('#check_in').val();
            var checkOut = $('#check_out').val();
            var roomTypeId = $('#room_type_id').val();

            if(!checkIn || !checkOut || !roomTypeId) {
                alert('Please select dates and room type first.');
                return;
            }

            $.ajax({
                url: "{{ route('admin.bookings.check-availability') }}",
                data: {
                    check_in: checkIn,
                    check_out: checkOut,
                    room_type_id: roomTypeId
                },
                success: function(rooms) {
                    $('#availableRoomsSection').show();
                    $('#roomList').empty();
                    
                    if(rooms.length > 0) {
                        $('#noRoomsMsg').hide();
                        rooms.forEach(function(room) {
                            var html = `
                                <div class="col-md-4 mb-2">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="room_${room.id}" name="room_ids[]" value="${room.id}">
                                        <label for="room_${room.id}" class="custom-control-label">
                                            Room ${room.room_number}
                                        </label>
                                    </div>
                                </div>
                            `;
                            $('#roomList').append(html);
                        });
                    } else {
                        $('#noRoomsMsg').show();
                    }
                }
            });
        });
    });
</script>
@endpush
