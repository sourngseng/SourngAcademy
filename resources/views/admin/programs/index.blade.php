@extends('admin.layouts.master')

@section('title', 'Manage Programs')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
    .program-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }
    .program-card:hover {
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

    .modal-content {
        border-radius: 15px;
        overflow: hidden;
        border: none;
    }
    .img-hover-zoom {
        transition: transform 0.3s ease;
    }
    .img-hover-zoom:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-graduation-cap mr-2"></i> Academic Programs</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.programs.create') }}" class="btn btn-primary btn-lg px-4 shadow">
                    <i class="fas fa-plus-circle mr-2"></i> Add New Program
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
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-graduation-cap"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Programs</span>
                    <span class="info-box-number" id="total-programs">0</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-star"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Popular Programs</span>
                    <span class="info-box-number" id="popular-programs">0</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-award text-white"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Degree Levels</span>
                    <span class="info-box-number" id="total-levels">0</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary program-card shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center px-3 mb-3">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-list-ul mr-1"></i> Program Catalog</h3>
                    </div>
                    
                    <div class="row px-3 pb-3 border-bottom mx-0 mb-3 bg-light rounded pt-3 mx-3 shadow-sm border">
                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <label class="small font-weight-bold text-muted text-uppercase mb-1">
                                    <i class="fas fa-award text-primary mr-1"></i> 1. Select Degree Level
                                </label>
                                <select id="filter_degree_level" class="form-control select2-filter" data-placeholder="Choose Degree Level">
                                    <option value="">All Academic Levels</option>
                                    @foreach($degreeLevels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <label class="small font-weight-bold text-muted text-uppercase mb-1">
                                    <i class="fas fa-university text-info mr-1"></i> 2. Select Campus
                                </label>
                                <select id="filter_campus" class="form-control select2-filter" data-placeholder="Choose Campus">
                                    <option value="">All Campuses</option>
                                    @foreach($campuses as $campus)
                                        <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button id="reset_filters" class="btn btn-outline-secondary btn-block shadow-sm">
                                <i class="fas fa-sync-alt mr-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="programsTable" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">Thumbnail</th>
                                <th width="30%">Program Details</th>
                                <th width="20%">Academic Info</th>
                                <th width="15%">Department</th>
                                <th width="10%">Popular</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Details Modal -->
    <div class="modal fade" id="programDetailModal" tabindex="-1" role="dialog" aria-labelledby="programModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="programModalLabel">Program Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div id="modal-image-container" class="border rounded p-1 shadow-sm mb-3">
                                <img id="modal-image" src="" class="img-fluid rounded img-hover-zoom" style="max-height: 250px; width: 100%; object-fit: cover;">
                            </div>
                            <div class="info-block p-3 bg-light rounded shadow-sm">
                                <p class="mb-2"><strong><i class="fas fa-award text-warning mr-2"></i> Degree:</strong> <span id="modal-level"></span></p>
                                <p class="mb-2"><strong><i class="far fa-clock text-info mr-2"></i> Duration:</strong> <span id="modal-duration"></span></p>
                                <p class="mb-2"><strong><i class="fas fa-th-list text-success mr-2"></i> Credits:</strong> <span id="modal-credits"></span></p>
                                <p class="mb-0"><strong><i class="fas fa-university text-primary mr-2"></i> Campus:</strong> <span id="modal-campus"></span></p>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <h4 id="modal-program-name" class="text-primary font-weight-bold mb-1"></h4>
                            <p id="modal-program-code" class="badge badge-info mb-3"></p>
                            
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block">Academic Structure</label>
                                <div class="d-flex align-items-center border-left border-primary pl-3">
                                    <div>
                                        <div id="modal-faculty" class="font-weight-bold"></div>
                                        <div id="modal-department" class="text-muted small"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block">Description</label>
                                <div id="modal-description" class="text-justify bg-light p-3 rounded border" style="max-height: 200px; overflow-y: auto;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <div class="mr-auto" id="modal-popular-badge"></div>
                    <button type="button" class="btn btn-secondary border-0 shadow-sm" data-dismiss="modal">Close</button>
                    <a href="" id="modal-edit-btn" class="btn btn-warning shadow-sm"><i class="fas fa-edit mr-1"></i> Edit Program</a>
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
    // Initialize Select2 for filters
    $('.select2-filter').select2({
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%'
    });

    var table = $('#programsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.programs.index') }}",
            data: function (d) {
                d.campus_id = $('#filter_campus').val();
                d.degree_level_id = $('#filter_degree_level').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {data: 'program_details', name: 'name'},
            {data: 'academic_info', name: 'degree_level_id'},
            {data: 'department_name', name: 'department.name'},
            {data: 'popular', name: 'popular', orderable: false, searchable: false},
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
                $('#total-programs').text(json.stats.total);
                $('#popular-programs').text(json.stats.popular);
                $('#total-levels').text(json.stats.total_levels);
            }
        }
    });

    // Filtering logic for Select2
    $('.select2-filter').on('change', function(){
        table.draw();
    });

    // Reset Filters
    $('#reset_filters').click(function(){
        $('.select2-filter').val('').trigger('change');
    });

    // View Program Details
    $(document).on('click', '.view-program-details', function() {
        var id = $(this).data('id');
        var url = "{{ route('admin.programs.show', ':id') }}";
        url = url.replace(':id', id);

        // Reset & Open Modal
        $('#programModalLabel').text('Loading Details...');
        $('#modal-image-container').hide();
        $('#modal-description').html('<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>');
        
        $('#programDetailModal').modal('show');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#programModalLabel').text('Program Code: ' + (response.code || 'N/A'));
                $('#modal-program-name').text(response.name);
                $('#modal-program-code').text(response.code || 'NO CODE');
                
                if(response.image) {
                    $('#modal-image').attr('src', response.image);
                    $('#modal-image-container').show();
                }

                $('#modal-level').text(response.level);
                $('#modal-duration').text(response.duration);
                $('#modal-credits').text(response.credits);
                $('#modal-campus').text(response.campus);
                $('#modal-faculty').text(response.faculty);
                $('#modal-department').text(response.department);
                $('#modal-description').html(response.description || '<em class="text-muted">No description provided.</em>');

                if(response.is_popular) {
                    $('#modal-popular-badge').html('<span class="badge badge-danger px-3 py-2"><i class="fas fa-star mr-1"></i> POPULAR PROGRAM</span>');
                } else {
                    $('#modal-popular-badge').html('');
                }

                $('#modal-edit-btn').attr('href', "{{ route('admin.programs.edit', ':id') }}".replace(':id', response.id));
            },
            error: function() {
                $('#modal-description').html('<div class="alert alert-danger">Error loading program data.</div>');
            }
        });
    });

    // Toggle Popular
    $(document).on('change', '.toggle-popular', function() {
        let id = $(this).data('id');
        let isChecked = $(this).is(':checked');
        let url = "{{ route('admin.programs.toggle-popular', ':id') }}".replace(':id', id);
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: response.message
                    });
                }
            }
        });
    });

    // Handle Delete
    $(document).on('click', '.delete-btn', function(){
        let id = $(this).data('id');
        let url = $(this).data('url');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
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
            title: 'Duplicate this Program?',
            text: "This will create a copy of the program and redirect you to edit it.",
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
