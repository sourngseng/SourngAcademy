@extends('admin.layouts.master')

@section('title', 'Manage Classrooms')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
    .classroom-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    .classroom-card:hover { /* Only apply hover effect to specific cards if needed, maybe not the main list card */
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .info-box {
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .filter-card {
        border-radius: 12px;
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-chalkboard mr-2 text-primary"></i> Classrooms Management</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle mr-1"></i> Add New Classroom
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    
    <!-- Info Boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-door-open"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Classrooms</span>
                    <span class="info-box-number" id="total-classrooms">{{ $stats['total'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chair"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Capacity</span>
                    <span class="info-box-number" id="total-capacity">{{ number_format($stats['total_capacity']) }} <small>Seats</small></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-flask text-white"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Labs</span>
                    <span class="info-box-number" id="total-labs">{{ $stats['labs'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Active Rooms</span>
                    <span class="info-box-number" id="active-rooms">{{ $stats['active'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card filter-card shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3" data-card-widget="collapse" style="cursor: pointer;">
                    <h3 class="card-title text-muted"><i class="fas fa-filter mr-1"></i> Advanced Filters</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body pt-0 bg-light">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="small text-muted">Campus</label>
                                    <select name="campus_id" class="form-control form-control-sm">
                                        <option value="">All Campuses</option>
                                        @foreach($campuses as $campus)
                                            <option value="{{ $campus->id }}">{{ $campus->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="small text-muted">Room Type</label>
                                    <select name="type" class="form-control form-control-sm">
                                        <option value="">All Types</option>
                                        <option value="Classroom">Classroom</option>
                                        <option value="Lecture Hall">Lecture Hall</option>
                                        <option value="Lab">Lab</option>
                                        <option value="Online">Online</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="small text-muted">Status</label>
                                    <select name="status" class="form-control form-control-sm">
                                        <option value="">All Statuses</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="btn-group w-100">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search mr-1"></i> Filter</button>
                                    <button type="button" id="resetFilter" class="btn btn-default btn-sm"><i class="fas fa-undo mr-1"></i> Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card classroom-card shadow">
                <div class="card-header bg-white border-0">
                    <h3 class="card-title font-weight-bold">Classrooms List</h3>
                </div>
                <div class="card-body">
                    <table id="classroomTable" class="table table-hover table-striped w-100">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">Image</th>
                                <th width="20%">Room Info</th>
                                <th>Description</th>
                                <th width="10%">Type</th>
                                <th width="15%">Location</th>
                                <th width="10%">Capacity</th>
                                <th width="10%">Status</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Classroom Details Modal -->
    <div class="modal fade" id="classroomDetailModal" tabindex="-1" role="dialog" aria-labelledby="classroomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="classroomModalLabel">Classroom Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 text-center mb-3 mb-md-0">
                            <div id="modal-image-container" class="border rounded p-1 shadow-sm" style="display:none;">
                                <img id="modal-image" src="" class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: cover;">
                            </div>
                            <div id="modal-no-image" class="bg-light d-flex align-items-center justify-content-center border rounded shadow-sm" style="height: 250px; width: 100%; display:none;">
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-4x mb-2"></i>
                                    <p>No Image Available</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div id="modal-info">
                                <!-- Details loaded via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Potential Edit Button could be added here dynamically -->
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
        var table = $('#classroomTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.classrooms.index') }}",
                data: function(d) {
                    d.campus_id = $('select[name="campus_id"]').val();
                    d.type = $('select[name="type"]').val();
                    d.status = $('select[name="status"]').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'image', name: 'image', orderable: false, searchable: false},
                {data: 'name', name: 'name'}, // Shows translated name and number
                {data: 'details', name: 'details', orderable: false, searchable: false}, // Translated & limited description
                {data: 'type', name: 'type'},
                {data: 'location', name: 'location'},
                {data: 'capacity', name: 'capacity'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
            language: {
                processing: '<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i>'
            },
            drawCallback: function(settings) {
                var api = this.api();
                var json = api.ajax.json();
                
                if(json && json.stats) {
                    $('#total-classrooms').text(json.stats.total);
                    $('#total-capacity').html(new Intl.NumberFormat().format(json.stats.total_capacity) + ' <small>Seats</small>');
                    $('#total-labs').text(json.stats.labs);
                    $('#active-rooms').text(json.stats.active);
                }
            }
        }); //.buttons().container().appendTo('#classroomTable_wrapper .col-md-6:eq(0)');
        
        // Append buttons cleanly
        // Using dom option or appending manually often conflicts. AdminLTE styles work best with specific dom order.
        // Let's refine the dom option for better layout if needed, or stick to simplest wrapping.
        // The default integration is usually fine, just ensure proper styling.
        
        // Filter Form Submit
        $('#filterForm').on('submit', function(e){
            e.preventDefault();
            table.draw();
        });

        // Reset Filter
        $('#resetFilter').on('click', function(){
            $('#filterForm')[0].reset();
            table.draw();
        });

        // View Classroom Details
        $(document).on('click', '.view-classroom-details', function() {
            var id = $(this).data('id');
            var url = "{{ route('admin.classrooms.show', ':id') }}";
            url = url.replace(':id', id);

            // Show loading or clear previous data
            $('#classroomModalLabel').text('Loading...');
            $('#modal-image').attr('src', '');
            $('#modal-image-container').hide();
            $('#modal-no-image').hide().removeClass('d-flex');
            $('#modal-info').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>');
            
            $('#classroomDetailModal').modal('show');

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#classroomModalLabel').text('Classroom: ' + response.room_number);
                    
                    // Image handling
                    if(response.image) {
                        $('#modal-image').attr('src', response.image);
                        $('#modal-image-container').show();
                        $('#modal-no-image').hide().removeClass('d-flex'); 
                    } else {
                        $('#modal-image-container').hide();
                        $('#modal-no-image').addClass('d-flex').show();
                    }

                    // Build Info HTML
                    var html = `
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Name:</th>
                                    <td>${response.name || '<em class="text-muted">No name provided</em>'}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td><span class="badge badge-info">${response.type}</span></td>
                                </tr>
                                <tr>
                                    <th>Campus:</th>
                                    <td>${response.campus ? response.campus.name_en : 'N/A'}</td>
                                </tr>
                                <tr>
                                    <th>Location:</th>
                                    <td>${response.building || 'N/A'} - ${response.floor || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <th>Capacity:</th>
                                    <td><i class="fas fa-chair text-muted mr-1"></i> ${response.capacity || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td><span class="badge badge-${response.status === 'Active' ? 'success' : 'secondary'}">${response.status}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="mt-3">
                            <h5 class="text-primary border-bottom pb-2">Description</h5>
                            <p class="text-justify">${response.description || '<em class="text-muted">No description available.</em>'}</p>
                        </div>
                    `;
                    $('#modal-info').html(html);
                },
                error: function() {
                    $('#modal-info').html('<div class="alert alert-danger">Failed to load details.</div>');
                }
            });
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
                            _token: $('meta[name="csrf-token"]').attr('content')
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
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
