@extends('admin.layouts.master')

@section('title', 'Manage Pages')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
    .page-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }
    .page-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    #pagesTable_wrapper .dt-buttons {
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
                <h1><i class="fas fa-file-alt mr-2"></i> Pages Management</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-lg px-4 shadow">
                    <i class="fas fa-plus-circle mr-2"></i> Create New Page
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
            <div class="card card-outline card-primary page-card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title">List of All Pages</h3>
                </div>
                <div class="card-body">
                    <table id="pagesTable" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="5%">Icon</th>
                                <th width="20%">Title</th>
                                <th width="15%">Slug</th>
                                <th width="10%">Translations</th>
                                <th width="5%">Order</th>
                                <th width="12%">Location</th>
                                <th width="8%">Status</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
    $(function() {
        var table = $('#pagesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.pages.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { 
                    data: 'icon_preview', 
                    name: 'icon', 
                    orderable: false, 
                    searchable: false,
                    render: function(data) {
                        return '<div class="text-center">' + data + '</div>';
                    }
                },
                { data: 'title_display', name: 'title', class: 'font-weight-bold' },
                { data: 'slug', name: 'slug' },
                { data: 'translations', name: 'translations', orderable: false, searchable: false },
                { data: 'sort_order', name: 'sort_order' },
                { data: 'locations', name: 'locations', orderable: false, searchable: false },
                { data: 'status', name: 'is_active' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[5, 'asc']],
            responsive: true,
            autoWidth: false,
            dom: 'Bfrtip',
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });

        // Delete Button
        $(document).on('click', '.delete-btn', function() {
            var url = $(this).data('url');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
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
                            _token: $('meta[name="csrf-token"]').attr('content') // Better way if possible, but token in header usually set
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                response.success,
                                'success'
                            );
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                             Swal.fire(
                                'Error!',
                                'Something went wrong.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
