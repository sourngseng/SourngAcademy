@extends('admin.layouts.master')

@section('title', 'Manage Departments')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
    .department-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }
    .department-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .info-box {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .info-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .img-hover-zoom {
        transition: transform 0.3s ease;
    }
    .img-hover-zoom:hover {
        transform: scale(1.05);
    }
    .modal-content {
        border-radius: 15px;
        overflow: hidden;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .modal-header {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }
    /* Scrollable Tabs */
    #campus-filter-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none;  /* IE and Edge */
        border-bottom: 2px solid #dee2e6;
    }
    #campus-filter-tabs::-webkit-scrollbar {
        display: none; /* Chrome, Safari and Opera */
    }
    #campus-filter-tabs .nav-link {
        white-space: nowrap;
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        font-weight: 500;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }
    #campus-filter-tabs .nav-link.active {
        background-color: transparent;
        color: #007bff;
        border-bottom: 3px solid #007bff;
    }
    #campus-filter-tabs .nav-link:hover {
        color: #007bff;
        border-bottom: 3px solid #e9ecef;
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-sitemap mr-2"></i> Departments</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.departments.create') }}" class="btn btn-primary btn-lg px-4 shadow">
                    <i class="fas fa-plus-circle mr-2"></i> Add New
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Info Boxes -->
    <div class="row mb-3">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Departments</span>
                    <span class="info-box-number" id="total-departments">0</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-tie"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">With Head of Dept.</span>
                    <span class="info-box-number" id="departments-with-hod">0</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-layer-group text-white"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Faculties</span>
                    <span class="info-box-number" id="total-faculties">0</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary department-card shadow-sm">
                <div class="card-header bg-white p-0 pt-3 border-bottom-0">
                    <div class="px-3 mb-2 d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><i class="fas fa-list-ul mr-1"></i> Department List</h3>
                    </div>
                    <ul class="nav nav-tabs px-3" id="campus-filter-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-all" data-toggle="tab" href="#all" role="tab" data-campus-id="">
                                <i class="fas fa-layer-group text-primary"></i> All Campuses
                            </a>
                        </li>
                        @foreach($campuses as $campus)
                            <li class="nav-item">
                                <a class="nav-link" id="tab-{{ $campus->id }}" data-toggle="tab" href="#campus-{{ $campus->id }}" role="tab" data-campus-id="{{ $campus->id }}">
                                    <i class="fas fa-university text-info"></i> {{ $campus->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-body">
                    <table id="departmentsTable" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Code</th>
                                <th>Department Name</th>
                                <th>Faculty</th>
                                <th>Head of Department</th>
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

    <!-- Department Details Modal -->
    <div class="modal fade" id="departmentDetailModal" tabindex="-1" role="dialog" aria-labelledby="departmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="departmentModalLabel">Department Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 text-center mb-3 mb-md-0">
                            <div id="modal-image-container" class="border rounded p-1 shadow-sm" style="display:none;">
                                <img id="modal-image" src="" class="img-fluid rounded img-hover-zoom" style="max-height: 300px; width: 100%; object-fit: cover;">
                            </div>
                            <div class="mt-3 text-left pl-2">
                                <p class="mb-1"><i class="fas fa-envelope text-primary mr-2"></i> <span id="modal-email"></span></p>
                                <p class="mb-1"><i class="fas fa-phone text-success mr-2"></i> <span id="modal-phone"></span></p>
                                <p class="mb-0"><i class="far fa-clock text-warning mr-2"></i> <span id="modal-hours"></span></p>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div id="modal-info">
                                <!-- Details loaded via AJAX -->
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <ul class="nav nav-tabs" id="deptModalTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="desc-tab" data-toggle="tab" href="#desc" role="tab" aria-controls="desc" aria-selected="true">Description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="mission-tab" data-toggle="tab" href="#mission" role="tab" aria-controls="mission" aria-selected="false">Mission</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="vision-tab" data-toggle="tab" href="#vision" role="tab" aria-controls="vision" aria-selected="false">Vision</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="goal-tab" data-toggle="tab" href="#goal" role="tab" aria-controls="goal" aria-selected="false">Goal</a>
                                </li>
                            </ul>
                            <div class="tab-content p-3 border border-top-0 rounded-bottom bg-light" id="deptModalTabContent">
                                <div class="tab-pane fade show active" id="desc" role="tabpanel" aria-labelledby="desc-tab"></div>
                                <div class="tab-pane fade" id="mission" role="tabpanel" aria-labelledby="mission-tab"></div>
                                <div class="tab-pane fade" id="vision" role="tabpanel" aria-labelledby="vision-tab"></div>
                                <div class="tab-pane fade" id="goal" role="tabpanel" aria-labelledby="goal-tab"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
<!-- SweetAlert2 from master -->

<script>
$(function () {
    var table = $('#departmentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.departments.index') }}",
            data: function (d) {
                d.campus_id = $('#campus-filter-tabs a.active').data('campus-id');
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'feature_image', name: 'feature_image', orderable: false, searchable: false},
            {
                data: 'code', 
                name: 'code',
                render: function(data) {
                    return data ? '<span class="badge badge-info">' + data + '</span>' : '-';
                }
            },
            {data: 'name', name: 'name', class: 'font-weight-bold'},
            {data: 'faculty_name', name: 'faculty.id'}, // Using faculty.id or faculty.name, but search logic might key on this name
            {data: 'hod_name', name: 'headOfDepartment.name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        responsive: true,
        autoWidth: false,
        dom: 'Bfrtip',
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        drawCallback: function(settings) {
            var api = this.api();
            var json = api.ajax.json();
            
            if(json && json.stats) {
                $('#total-departments').text(json.stats.total);
                $('#departments-with-hod').text(json.stats.with_hod);
                $('#total-faculties').text(json.stats.total_faculties);
            }
        }
    });

    $('#campus-filter-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        table.draw();
    });

    // View Department Details
    $(document).on('click', '.view-department-details', function() {
        var id = $(this).data('id');
        var url = "{{ route('admin.departments.show', ':id') }}";
        url = url.replace(':id', id);

        // Reset Modal
        $('#departmentModalLabel').text('Loading...');
        $('#modal-image-container').hide();
        $('#modal-info').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>');
        $('#desc, #mission, #vision, #goal').html('');
        $('#modal-email, #modal-phone, #modal-hours').text('');

        $('#departmentDetailModal').modal('show');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#departmentModalLabel').text('Department: ' + response.name);
                
                // Image handling
                if(response.image) {
                    $('#modal-image').attr('src', response.image);
                    $('#modal-image-container').show();
                } else {
                    $('#modal-image-container').hide();
                }

                // Info
                $('#modal-email').text(response.email || 'N/A');
                $('#modal-phone').text(response.phone || 'N/A');
                $('#modal-hours').text(response.working_hours || 'N/A');

                // Build Info HTML
                var html = `
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Name:</th>
                                <td class="font-weight-bold text-primary">${response.name || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Code:</th>
                                <td><span class="badge badge-secondary">${response.code || 'N/A'}</span></td>
                            </tr>
                            <tr>
                                <th>Faculty:</th>
                                <td>${response.faculty}</td>
                            </tr>
                            <tr>
                                <th>Head of Dept:</th>
                                <td>${response.hod}</td>
                            </tr>
                        </table>
                    </div>
                `;
                $('#modal-info').html(html);

                // Tabs content with default text
                $('#desc').html(response.description || '<em class="text-muted">No description available.</em>');
                $('#mission').html(response.mission || '<em class="text-muted">No mission statement available.</em>');
                $('#vision').html(response.vision || '<em class="text-muted">No vision statement available.</em>');
                $('#goal').html(response.goal || '<em class="text-muted">No goal statement available.</em>');
            },
            error: function() {
                $('#modal-info').html('<div class="alert alert-danger">Failed to load details.</div>');
            }
        });
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
