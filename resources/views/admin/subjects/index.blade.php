@extends('admin.layouts.master')

@section('title', 'Manage Subjects')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
    .subject-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }
    .subject-card:hover {
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
                <h1><i class="fas fa-book mr-2"></i> Academic Subjects</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary btn-lg px-4 shadow">
                    <i class="fas fa-plus-circle mr-2"></i> Add New Subject
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
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Subjects</span>
                    <span class="info-box-number" id="total-subjects">0</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-coins text-white"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Credits</span>
                    <span class="info-box-number" id="total-credits">0</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-star text-white"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Core Subjects</span>
                    <span class="info-box-number" id="core-subjects">0</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary subject-card shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center px-3 mb-3">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-list-ul mr-1"></i> Subject Catalog</h3>
                    </div>
                    
                    <div class="row px-3 pb-3 border-bottom mx-0 mb-3 bg-light rounded pt-3 mx-3 shadow-sm border">
                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <label class="small font-weight-bold text-muted text-uppercase mb-1">
                                    <i class="fas fa-university text-info mr-1"></i> 1. Select Campus
                                </label>
                                <select id="filter_campus" class="form-control select2-filter" data-placeholder="Choose Campus">
                                    <option value="">All Campuses</option>
                                    @foreach($campuses as $campus)
                                        <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <label class="small font-weight-bold text-muted text-uppercase mb-1">
                                    <i class="fas fa-graduation-cap text-primary mr-1"></i> 2. Select Program
                                </label>
                                <select id="filter_program" class="form-control select2-filter" data-placeholder="Choose Program">
                                    <option value="">All Programs</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}">{{ $program->getTranslatedName() }}</option>
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
                    <table id="subjectsTable" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">Thumbnail</th>
                                <th width="30%">Subject Content</th>
                                <th width="20%">Academic Info</th>
                                <th width="20%">Associated Program</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subject Details Modal -->
<div class="modal fade" id="subjectDetailModal" tabindex="-1" role="dialog" aria-labelledby="subjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title font-weight-bold" id="subjectModalLabel">Subject Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row no-gutters">
                    <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-3" id="modal-image-container">
                        <img src="" id="modal-image" class="img-fluid rounded shadow-sm border" style="max-height: 300px; object-fit: cover;">
                    </div>
                    <div class="col-md-7 p-4">
                        <h3 class="font-weight-bold text-dark mb-1" id="modal-subject-name">Subject Name</h3>
                        <div class="text-primary font-weight-bold mb-3" id="modal-subject-code">CODE</div>
                        
                        <div class="row mb-4">
                            <div class="col-6 mb-2">
                                <small class="text-muted d-block">Credits</small>
                                <span class="font-weight-bold" id="modal-credit"><i class="fas fa-coins text-warning mr-1"></i>-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted d-block">Type</small>
                                <span class="font-weight-bold" id="modal-type">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted d-block">Program</small>
                                <span class="font-weight-bold" id="modal-program"><i class="fas fa-graduation-cap text-info mr-1"></i>-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted d-block">Campus</small>
                                <span class="font-weight-bold" id="modal-campus"><i class="fas fa-university text-danger mr-1"></i>-</span>
                            </div>
                        </div>

                        <div class="description-box bg-light p-3 rounded" style="max-height: 150px; overflow-y: auto;">
                            <label class="small font-weight-bold text-uppercase text-muted">Description</label>
                            <p id="modal-description" class="mb-0 text-secondary"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Gallery Section -->
                <div class="px-4 py-3 border-top bg-white" id="modal-gallery-section" style="display:none;">
                    <label class="font-weight-bold text-muted mb-3">Gallery Overview</label>
                    <div class="row" id="modal-gallery"></div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary px-4 shadow-sm" data-dismiss="modal">Close</button>
                <a href="#" id="modal-edit-btn" class="btn btn-primary px-4 shadow-sm"><i class="fas fa-edit mr-1"></i> Edit Subject</a>
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
    // Initialize Select2 for filters
    $('.select2-filter').select2({
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%'
    });

    var table = $('#subjectsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.subjects.index') }}",
            data: function (d) {
                d.campus_id = $('#filter_campus').val();
                d.program_id = $('#filter_program').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {data: 'subject_details', name: 'name'},
            {data: 'academic_info', name: 'credit'},
            {data: 'program_name', name: 'program.name'},
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
                $('#total-subjects').text(json.stats.total);
                $('#total-credits').text(json.stats.credits);
                $('#core-subjects').text(json.stats.core);
            }
        }
    });

    // Filtering logic
    $('.select2-filter').on('change', function(){
        table.draw();
    });

    // Reset Filters
    $('#reset_filters').click(function(){
        $('.select2-filter').val('').trigger('change');
    });

    // View Subject Details
    $(document).on('click', '.view-subject-details', function() {
        var id = $(this).data('id');
        var url = "{{ route('admin.subjects.show', ':id') }}";
        url = url.replace(':id', id);

        // Reset & Open Modal
        $('#subjectModalLabel').text('Loading Details...');
        $('#modal-image-container').hide();
        $('#modal-description').html('<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>');
        $('#modal-gallery-section').hide();
        $('#modal-gallery').html('');
        
        $('#subjectDetailModal').modal('show');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#subjectModalLabel').text('Subject Detail: ' + response.name);
                $('#modal-subject-name').text(response.name);
                $('#modal-subject-code').text(response.code);
                
                if(response.image) {
                    $('#modal-image').attr('src', response.image);
                    $('#modal-image-container').show();
                }

                $('#modal-credit').html('<i class="fas fa-coins text-warning mr-1"></i>' + response.credit);
                $('#modal-type').text(response.type);
                $('#modal-program').html('<i class="fas fa-graduation-cap text-info mr-1"></i>' + response.program);
                $('#modal-campus').html('<i class="fas fa-university text-danger mr-1"></i>' + response.campus);
                $('#modal-description').html(response.description || '<em class="text-muted">No description provided.</em>');

                // Gallery
                if(response.gallery && response.gallery.length > 0) {
                    $('#modal-gallery-section').show();
                    response.gallery.forEach(function(imgUrl) {
                        $('#modal-gallery').append(`
                            <div class="col-md-3 mb-3">
                                <img src="${imgUrl}" class="img-fluid rounded border shadow-sm img-hover-zoom" style="height: 100px; width: 100%; object-fit: cover; cursor: pointer;">
                            </div>
                        `);
                    });
                }

                $('#modal-edit-btn').attr('href', "{{ route('admin.subjects.edit', ':id') }}".replace(':id', response.id));
            },
            error: function() {
                $('#modal-description').html('<div class="alert alert-danger">Error loading subject data.</div>');
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
});
</script>
@endpush
