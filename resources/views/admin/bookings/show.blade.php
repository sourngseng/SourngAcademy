@extends('admin.layouts.master')

@section('title', 'Booking Details')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Booking Details #{{ $booking->id }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Bookings</a></li>
                    <li class="breadcrumb-item active">View #{{ $booking->id }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reservation Information</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $booking->status == 'confirmed' || $booking->status == 'checked_in' ? 'success' : 'warning' }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Guest Name:</strong> <br>
                            {{ $booking->user->name ?? 'Guest' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Check In:</strong> <br>
                            {{ $booking->check_in->format('D, M d, Y') }}
                        </div>
                        <div class="col-md-4">
                            <strong>Check Out:</strong> <br>
                            {{ $booking->check_out->format('D, M d, Y') }}
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-4">
                         <div class="col-md-12">
                             <h5>Rooms Reserved</h5>
                             <table class="table table-sm table-bordered">
                                 <thead>
                                     <tr>
                                         <th>Room Number</th>
                                         <th>Type</th>
                                         <th>Price Per Night</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     @foreach($booking->rooms as $room)
                                     <tr>
                                         <td>{{ $room->room_number }}</td>
                                         <td>{{ $room->roomType->room_type_name ?? 'N/A' }}</td>
                                         <td>${{ number_format($room->pivot->price, 2) }}</td>
                                     </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <strong>Special Requests:</strong>
                            <p class="text-muted">{{ $booking->special_requests ?? 'None' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
             <div class="card card-primary card-outline">
                 <div class="card-body box-profile">
                     <h3 class="profile-username text-center">Summary</h3>
                     <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Nights</b> <a class="float-right">{{ $booking->check_in->diffInDays($booking->check_out) }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Total Price</b> <a class="float-right text-primary text-bold">${{ number_format($booking->total_price, 2) }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Paid</b> <a class="float-right text-success">${{ number_format($booking->paid_amount, 2) }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Balance</b> <a class="float-right {{ $booking->remaining_balance > 0 ? 'text-danger' : 'text-success' }} text-bold">${{ number_format($booking->remaining_balance, 2) }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> 
                            <span class="float-right badge badge-{{ $booking->payment_status == 'paid' ? 'success' : ($booking->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </li>
                    </ul>
                    
                    @if($booking->remaining_balance > 0)
                    <a href="{{ route('admin.payments.create', $booking->id) }}" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-dollar-sign"></i> Record Payment
                    </a>
                    @endif
                    
                    @if($booking->payments->count() > 0)
                    <a href="{{ route('admin.payments.index', $booking->id) }}" class="btn btn-outline-info btn-block mb-2">
                        <i class="fas fa-history"></i> Payments ({{ $booking->payments->count() }})
                    </a>
                    @endif
                     
                     <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-primary btn-block"><b>Edit Booking</b></a>
                    
                    {{-- Invoice Management --}}
                    @if($booking->invoice)
                    <a href="{{ route('admin.invoices.show', $booking->invoice->id) }}" class="btn btn-outline-success btn-block mt-2">
                        <i class="fas fa-file-invoice"></i> View Invoice ({{ $booking->invoice->invoice_number }})
                    </a>
                    @else
                    <a href="{{ route('admin.invoices.create', ['booking_id' => $booking->id]) }}" class="btn btn-success btn-block mt-2">
                        <i class="fas fa-file-invoice"></i> Generate Invoice
                    </a>
                    @endif
                    
                    <a href="{{ route('admin.bookings.invoice', $booking->id) }}" class="btn btn-outline-secondary btn-block mt-2" target="_blank">
                        <i class="fas fa-print"></i> Print Quick Receipt
                    </a>
                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                         <button type="submit" class="btn btn-danger btn-block">Cancel Booking</button>
                    </form>
                 </div>
             </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function printInvoice() {
    var invoiceUrl = "{{ route('admin.bookings.invoice', $booking->id) }}";
    var win = window.open(invoiceUrl, '_blank');
    win.onload = function() {
        setTimeout(function() {
            win.print();
        }, 500);
    };
}
</script>
@endpush
