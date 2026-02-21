@extends(Request::routeIs('staff.*') ? 'staff.layouts.master' : 'admin.layouts.master')

@section('title', 'Guest Management')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    
    <style>
        .stat-card {
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        .stat-card .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
        }
        .img-circle {
            border-radius: 50%;
        }
        .vip-platinum { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .vip-gold { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
        .vip-silver { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
    </style>
@endpush

@section('page-title')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-users"></i> Guest Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ Request::routeIs('staff.*') ? route('staff.dashboard') : route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Guests</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="card stat-card bg-gradient-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-white-50">Total Guests</p>
                            <h3 class="stat-value text-white mb-0">{{ number_format($stats['total_guests']) }}</h3>
                        </div>
                        <div class="icon-box bg-white-10">
                            <i class="fas fa-users text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card stat-card bg-gradient-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-white-50">VIP Guests</p>
                            <h3 class="stat-value text-white mb-0">{{ number_format($stats['vip_guests']) }}</h3>
                        </div>
                        <div class="icon-box bg-white-10">
                            <i class="fas fa-crown text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card stat-card bg-gradient-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-white-50">New This Month</p>
                            <h3 class="stat-value text-white mb-0">{{ number_format($stats['new_this_month']) }}</h3>
                        </div>
                        <div class="icon-box bg-white-10">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card stat-card bg-gradient-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-white-50">Avg. Rating</p>
                            <h3 class="stat-value text-white mb-0">
                                <i class="fas fa-star"></i> {{ number_format($stats['avg_rating'], 1) }}
                            </h3>
                        </div>
                        <div class="icon-box bg-white-10">
                            <i class="fas fa-star text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filters</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>VIP Status</label>
                        <select class="form-control" id="filter_vip_status">
                            <option value="">All Statuses</option>
                            <option value="regular">Regular</option>
                            <option value="silver">Silver</option>
                            <option value="gold">Gold</option>
                            <option value="platinum">Platinum</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Nationality</label>
                        <select class="form-control" id="filter_nationality">
                            <option value="">All Nationalities</option>
                            @foreach($nationalities as $nationality)
                                <option value="{{ $nationality }}">{{ $nationality }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-info btn-block" id="applyFilters">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-secondary btn-block" id="resetFilters">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Guest List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Guests List</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" id="createNewGuest">
                    <i class="fas fa-plus"></i> Add New Guest
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped data-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="60">Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>VIP Status</th>
                        <th>Bookings</th>
                        <th>Total Spent</th>
                        <th>Last Visit</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade" id="guestModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white" id="modalHeading"></h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="guestForm" name="guestForm" class="form-horizontal">
                        <input type="hidden" name="id" id="guest_id">
                        
                        <!-- Personal Information -->
                        <h5 class="text-muted mb-3"><i class="fas fa-user"></i> Personal Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <div class="input-group date" id="dob_picker" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="date_of_birth" name="date_of_birth" data-target="#dob_picker" placeholder="Select date">
                                        <div class="input-group-append" data-target="#dob_picker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <!-- Address & Identity -->
                        <h5 class="text-muted mb-3"><i class="fas fa-id-card"></i> Address & Identity</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input type="text" class="form-control" id="country" name="country">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nationality">Nationality</label>
                                    <input type="text" class="form-control" id="nationality" name="nationality">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_type">ID Type</label>
                                    <select class="form-control" id="id_type" name="id_type">
                                        <option value="">Select ID Type</option>
                                        <option value="passport">Passport</option>
                                        <option value="national_id">National ID</option>
                                        <option value="driver_license">Driver's License</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_number">ID Number</label>
                                    <input type="text" class="form-control" id="id_number" name="id_number">
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <!-- VIP & Notes -->
                        <h5 class="text-muted mb-3"><i class="fas fa-crown"></i> VIP Status & Notes</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vip_status">VIP Status</label>
                                    <select class="form-control" id="vip_status" name="vip_status">
                                        <option value="regular">Regular</option>
                                        <option value="silver">Silver</option>
                                        <option value="gold">Gold</option>
                                        <option value="platinum">Platinum</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_contact_name">Emergency Contact Name</label>
                                    <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_contact_phone">Emergency Contact Phone</label>
                                    <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Internal Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add internal notes about this guest..."></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveBtn">Save Guest</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables & Plugins -->
    <script src="{{asset('backend')}}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{asset('backend')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize DateTime Picker for Date of Birth
            $('#dob_picker').datetimepicker({
                format: 'YYYY-MM-DD',
                viewMode: 'years',
                icons: {
                    time: 'fas fa-clock',
                    date: 'fas fa-calendar',
                    up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-calendar-check',
                    clear: 'fas fa-trash',
                    close: 'fas fa-times'
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ Request::routeIs('staff.*') ? route('staff.guests.index') : route('admin.guests.index') }}",
                    data: function (d) {
                        d.vip_status = $('#filter_vip_status').val();
                        d.nationality = $('#filter_nationality').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'profile_image', name: 'profile_image', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone', orderable: false},
                    {data: 'vip_status', name: 'vip_status', orderable: false},
                    {data: 'total_bookings', name: 'total_bookings', orderable: false, searchable: false},
                    {data: 'total_spent', name: 'total_spent', orderable: false, searchable: false},
                    {data: 'last_visit', name: 'last_visit', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // Apply filters
            $('#applyFilters').click(function(){
                table.draw();
            });

            // Reset filters
            $('#resetFilters').click(function(){
                $('#filter_vip_status').val('');
                $('#filter_nationality').val('');
                table.draw();
            });

            // Create new guest
            $('#createNewGuest').click(function () {
                $('#guest_id').val('');
                $('#guestForm').trigger("reset");
                $('#modalHeading').html('<i class="fas fa-user-plus"></i> Add New Guest');
                $('#guestModal').modal('show');
            });

            // Edit guest
            $('body').on('click', '.editGuest', function () {
                var guest_id = $(this).data('id');
                var baseUrl = "{{ Request::routeIs('staff.*') ? route('staff.guests.index') : route('admin.guests.index') }}";
                $.get(baseUrl + "/" + guest_id + "/edit", function (data) {
                    $('#modalHeading').html('<i class="fas fa-edit"></i> Edit Guest');
                    $('#guestModal').modal('show');
                    $('#guest_id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    $('#address').val(data.address);
                    $('#city').val(data.city);
                    $('#country').val(data.country);
                    $('#id_type').val(data.id_type);
                    $('#id_number').val(data.id_number);
                    $('#date_of_birth').val(data.date_of_birth);
                    $('#gender').val(data.gender);
                    $('#nationality').val(data.nationality);
                    $('#vip_status').val(data.vip_status);
                    $('#notes').val(data.notes);
                    $('#emergency_contact_name').val(data.emergency_contact_name);
                    $('#emergency_contact_phone').val(data.emergency_contact_phone);
                });
            });

            // Submit form
            $('#guestForm').submit(function (e) {
                e.preventDefault();
                $('#saveBtn').html('<i class="fas fa-spinner fa-spin"></i> Saving...');

                var formData = new FormData(this);
                var id = $('#guest_id').val();
                
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                
                var baseRoute = "{{ Request::routeIs('staff.*') ? 'staff' : 'admin' }}";
                var url = id ? "/" + baseRoute + "/guests/" + id : "/" + baseRoute + "/guests";
                
                if(id){
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    data: formData,
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#guestForm').trigger("reset");
                        $('#guestModal').modal('hide');
                        table.draw();
                        $('#saveBtn').html('Save Guest');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.success,
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Guest');
                        var errorMsg = 'Something went wrong!';
                        if(data.responseJSON && data.responseJSON.message){
                             errorMsg = data.responseJSON.message;
                        }
                        if(data.responseJSON && data.responseJSON.errors){
                            errorMsg = '';
                            $.each(data.responseJSON.errors, function(key, value){
                                errorMsg += value[0] + '<br>';
                            });
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMsg,
                        });
                    }
                });
            });

            // Delete guest
            $('body').on('click', '.deleteGuest', function () {
                var deleteUrl = $(this).data("url");

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete this guest!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: deleteUrl,
                            success: function (data) {
                                table.draw();
                                Swal.fire({
                                   icon: 'success',
                                   title: 'Deleted!',
                                   text: data.success,
                                   timer: 2000,
                                   showConfirmButton: false,
                                   toast: true,
                                   position: 'top-end'
                                });
                            },
                            error: function (data) {
                                console.log('Error:', data);
                                var errorMsg = data.responseJSON?.error || 'Something went wrong!';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: errorMsg,
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
