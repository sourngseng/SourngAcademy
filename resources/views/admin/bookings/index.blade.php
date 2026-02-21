@extends('admin.layouts.master')

@section('title', 'All Bookings')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>All Bookings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Bookings</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Booking List</h3>
        <div class="card-tools">
            <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create Booking
            </a>
            <a href="{{ route('admin.bookings.calendar') }}" class="btn btn-info btn-sm ml-2">
                <i class="fas fa-calendar"></i> Calendar View
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
             <div class="col-md-3">
                 <select id="statusFilter" class="form-control">
                     <option value="all">All Statuses</option>
                     <option value="pending">Pending</option>
                     <option value="confirmed">Confirmed</option>
                     <option value="checked_in">Checked In</option>
                     <option value="checked_out">Checked Out</option>
                     <option value="cancelled">Cancelled</option>
                 </select>
             </div>
        </div>
        <table id="bookingsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Guest</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Rooms</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('backend')}}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{asset('backend')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('backend')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#bookingsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.bookings.index') }}",
            data: function(d) {
                d.status = $('#statusFilter').val();
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'guest_name', name: 'user.name'},
            {data: 'check_in', name: 'check_in'},
            {data: 'check_out', name: 'check_out'},
            {data: 'rooms', name: 'rooms.room_number', orderable: false},
            {data: 'total_price', name: 'total_price'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[0, 'desc']]
    });

    $('#statusFilter').change(function(){
        table.draw();
    });
});
</script>
@endpush
