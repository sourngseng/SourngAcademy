@extends('admin.layouts.master')

@section('title', 'Room Types List')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{asset('backend')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endpush

@section('page-title')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Room Types</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Room Types</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Room Types List</h3>
        <div class="card-tools">
            <button id="bulkDeleteBtn" class="btn btn-danger btn-sm mr-2" style="display:none;">
                <i class="fas fa-trash"></i> Delete Selected (<span id="selectedCount">0</span>)
            </button>
            {{-- TODO: Add modal form for creating room types --}}
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="roomTypesTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="30"><input type="checkbox" id="selectAll"></th>
                    <th>ID</th>
                    <th>Room Type Name</th>
                    <th>Description</th>
                    <th>Price Per Night</th>
                    <th>Max Occupancy</th>
                    <th>Created At</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be populated by DataTables via AJAX -->
            </tbody>
            <tfoot>
                <tr>
                    <th><input type="checkbox" id="selectAllFooter"></th>
                    <th>ID</th>
                    <th>Room Type Name</th>
                    <th>Description</th>
                    <th>Price Per Night</th>
                    <th>Max Occupancy</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
@endsection

@push('scripts')
<!-- DataTables  & Plugins -->
<script src="{{asset('backend')}}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{asset('backend')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('backend')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('backend')}}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{asset('backend')}}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{asset('backend')}}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="{{asset('backend')}}/plugins/jszip/jszip.min.js"></script>
<script src="{{asset('backend')}}/plugins/pdfmake/pdfmake.min.js"></script>
<script src="{{asset('backend')}}/plugins/pdfmake/vfs_fonts.js"></script>
<script src="{{asset('backend')}}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="{{asset('backend')}}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="{{asset('backend')}}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- SweetAlert2 -->
<script src="{{asset('backend')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
$(document).ready(function() {
    // Show success message if exists
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
            toast: true,
            position: 'top-end'
        });
    @endif

    // Show error message if exists
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK'
        });
    @endif

    // Initialize DataTable
    var table = $('#roomTypesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.roomtypes.index') }}",
            type: 'GET'
        },
        columns: [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'id', name: 'id'},
            {data: 'room_type_name', name: 'room_type_name'},
            {data: 'description', name: 'description'},
            {data: 'price_per_night', name: 'price_per_night'},
            {data: 'max_occupancy', name: 'max_occupancy'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[1, 'desc']],
        responsive: true,
        lengthChange: true,
        autoWidth: false
    });

    // Select All Checkbox
    $('#selectAll, #selectAllFooter').on('click', function() {
        var isChecked = $(this).prop('checked');
        $('.select-item').prop('checked', isChecked);
        $('#selectAll, #selectAllFooter').prop('checked', isChecked);
        updateBulkDeleteButton();
    });

    // Individual checkbox
    $('#roomTypesTable').on('change', '.select-item', function() {
        updateBulkDeleteButton();
        var totalCheckboxes = $('.select-item').length;
        var checkedCheckboxes = $('.select-item:checked').length;
        $('#selectAll, #selectAllFooter').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // Update bulk delete button visibility
    function updateBulkDeleteButton() {
        var selectedIds = [];
        $('.select-item:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length > 0) {
            $('#bulkDeleteBtn').show();
            $('#selectedCount').text(selectedIds.length);
        } else {
            $('#bulkDeleteBtn').hide();
        }
    }

    // Bulk Delete
    $('#bulkDeleteBtn').on('click', function() {
        var selectedIds = [];
        $('.select-item:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Selection',
                text: 'Please select at least one room type to delete.',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Delete ' + selectedIds.length + ' room type(s)?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.roomtypes.bulk-delete') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: selectedIds
                    },
                    success: function(response) {
                        table.ajax.reload();
                        $('#selectAll, #selectAllFooter').prop('checked', false);
                        $('#bulkDeleteBtn').hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000,
                            toast: true,
                            position: 'top-end'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to delete room types. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    // Single Delete functionality with SweetAlert
    $('#roomTypesTable').on('click', '.delete-btn', function() {
        var deleteUrl = $(this).data('url');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message || 'Room type has been deleted.',
                            showConfirmButton: false,
                            timer: 2000,
                            toast: true,
                            position: 'top-end'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to delete room type. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
