@extends('admin.layouts.master')

@section('title', 'Manage Faculties')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
    .faculty-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }
    .faculty-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    #facultiesTable_wrapper .dt-buttons {
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
                <h1><i class="fas fa-sitemap mr-2"></i> Faculties</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.faculties.create') }}" class="btn btn-primary btn-lg px-4 shadow">
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
        <!-- Campus Filter -->
        <div class="col-12 mb-3">
            <div class="d-flex flex-nowrap overflow-auto py-2 px-1" style="gap: 10px; -webkit-overflow-scrolling: touch;">
                <button type="button" class="btn btn-outline-primary active shadow-sm filter-campus flex-shrink-0" data-id="">
                    All Campuses
                </button>
                @foreach($campuses as $campus)
                    <button type="button" class="btn btn-outline-secondary shadow-sm filter-campus flex-shrink-0" data-id="{{ $campus->id }}">
                        {{ $campus->getTranslatedName() }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="col-12">
            <div class="card card-outline card-primary faculty-card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title">List of All Faculties</h3>
                </div>
                <div class="card-body">
                    <table id="facultiesTable" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Thumbnail</th>
                                <th>Code</th>
                                <th>Faculty Name</th>
                                <th>Campus</th>
                                <th>Dean Name</th>
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
    var selectedCampusId = '';

    var table = $('#facultiesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.faculties.index') }}",
            data: function (d) {
                d.campus_id = selectedCampusId;
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {
                data: 'code', 
                name: 'code',
                render: function(data) {
                    return data ? '<span class="badge badge-info">' + data + '</span>' : '-';
                }
            },
            {data: 'name', name: 'name', class: 'font-weight-bold'},
            {data: 'campus_name', name: 'campus.name_en'}, // Consider updating search logic for translations if needed
            {data: 'dean_name', name: 'dean_name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        responsive: true,
        autoWidth: false,
        dom: 'Bfrtip',
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });

    // Handle Campus Filter
    $('.filter-campus').click(function() {
        $('.filter-campus').removeClass('btn-outline-primary active').addClass('btn-outline-secondary');
        $(this).removeClass('btn-outline-secondary').addClass('btn-outline-primary active');
        
        selectedCampusId = $(this).data('id');
        table.draw();
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

    // Handle Duplicate
    $(document).on('click', '.duplicate-btn', function(){
        let url = $(this).data('url');
        
        Swal.fire({
            title: 'Duplicate this Faculty?',
            text: "This will create a copy of the faculty and redirect you to edit it.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, duplicate it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Determine if we should use AJAX or Form Submit. 
                // Since duplicate method validates and redirects, a Form Submit is simplest to handle the redirection.
                let form = $('<form action="' + url + '" method="POST">' +
                    '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                    '</form>');
                $('body').append(form);
                form.submit();
            }
        });
    });
});
</script>
@endpush
