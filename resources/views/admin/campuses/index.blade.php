@extends('admin.layouts.master')

@section('title', 'Manage Campuses')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
    .campus-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }
    .campus-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    #campusesTable_wrapper .dt-buttons {
        margin-bottom: 15px;
    }
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-university mr-2"></i> University Campuses</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.campuses.create') }}" class="btn btn-primary btn-lg px-4 shadow">
                    <i class="fas fa-plus-circle mr-2"></i> Add New
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary campus-card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title">List of All Campuses</h3>
                </div>
                <div class="card-body">
                    <table id="campusesTable" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Logo/Icon</th>
                                <th>Campus Name</th>
                                <th>Code</th>
                                <th>Short Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables  & Plugins -->
<script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('backend/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('backend/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    var table = $('#campusesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.campuses.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {
                data: 'feature_image', 
                name: 'feature_image', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    if (data) {
                        return '<div class="text-center"><img src="' + '{{ asset('') }}' + data + '" class="img-thumbnail shadow-sm" style="height: 50px; width: 50px; object-fit: cover; border-radius: 8px;"></div>';
                    }
                    return '<div class="text-center"><i class="fas fa-university fa-2x text-primary shadow-sm rounded-circle p-2 bg-light"></i></div>';
                }
            },
            {data: 'name', name: 'name_en', class: 'font-weight-bold'},
            {data: 'code', name: 'code'},
            {data: 'short_name', name: 'short_name'},
            {data: 'is_active', name: 'is_active'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        responsive: true,
        autoWidth: false,
        dom: 'Bfrtip',
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });

    // Handle Delete
    $(document).on('click', '.delete-btn', function(){
        let id = $(this).data('id');
        let url = $(this).data('url');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "All associated data will be archived!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Deleted!', response.success, 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
