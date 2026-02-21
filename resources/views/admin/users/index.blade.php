@extends('admin.layouts.master')

@section('title', 'Manage Users')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{asset('backend')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-users"></i> Manage Users</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<!-- Stats Overview -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="info-box bg-gradient-primary">
            <span class="info-box-icon"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Users</span>
                <span class="info-box-number">{{ \App\Models\User::count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-gradient-danger">
            <span class="info-box-icon"><i class="fas fa-user-shield"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Admins</span>
                <span class="info-box-number">{{ \App\Models\User::where('role', 'admin')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-gradient-info">
            <span class="info-box-icon"><i class="fas fa-user-tie"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Staff</span>
                <span class="info-box-number">{{ \App\Models\User::where('role', 'staff')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-gradient-secondary">
            <span class="info-box-icon"><i class="fas fa-user"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Guests</span>
                <span class="info-box-number">{{ \App\Models\User::where('role', 'guest')->count() }}</span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">System Users</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#userModal" id="createNewUser">
                <i class="fas fa-plus"></i> Add New User
            </button>
        </div>
    </div>
    <div class="card-body">
        <table id="usersTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="userForm">
                @csrf
                <input type="hidden" id="user_id" name="user_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHeading">Add New</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" id="role" name="role" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" data-description="{{ $role->description }}">
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> <span id="roleDescription">Select a role to see its description</span>
                        </small>
                    </div>
                    <div class="form-group">
                        <label>Password <small class="text-muted" id="passwordHelp">(Required for new users)</small></label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('backend')}}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{asset('backend')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('backend')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('backend')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
$(document).ready(function() {
    // Update role description when role changes
    $('#role').on('change', function() {
        var description = $(this).find('option:selected').data('description');
        $('#roleDescription').text(description || 'No description available');
    });

    // Check if we should open the create modal (from redirect)
    @if(session('open_modal_create'))
        $('#createNewUser').trigger('click');
        $('#userModal').modal('show');
    @endif
    
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.users.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'role', name: 'role'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[0, 'desc']]
    });

    $('#createNewUser').click(function () {
        $('#userForm').trigger("reset");
        $('#user_id').val('');
        $('#modalHeading').html("<i class='fas fa-user-plus'></i> Add New");
        $('#passwordHelp').text("(Required)");
        $('#password').prop('required', true);
        $('#roleDescription').text('Select a role to see its description');
    });

    $('body').on('click', '.edit-btn', function () {
        var user_id = $(this).data('id');
        $.get("{{ route('admin.users.index') }}" +'/' + user_id +'/edit', function (data) {
            $('#modalHeading').html("<i class='fas fa-user-edit'></i> Edit User: " + data.name);
            $('#saveBtn').val("edit-user");
            $('#userModal').modal('show');
            $('#user_id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#role').val(data.role).trigger('change');
            $('#password').prop('required', false);
            $('#passwordHelp').text("(Leave blank to keep current)");
        })
    });

    $('#userForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var user_id = $('#user_id').val();
        var url = user_id ? "{{ route('admin.users.index') }}/" + user_id : "{{ route('admin.users.store') }}";
        
        if(user_id) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            data: formData,
            url: url,
            type: "POST",
            contentType: false,
            processData: false,
            success: function (data) {
                $('#userForm').trigger("reset");
                $('#userModal').modal('hide');
                table.draw();
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.success,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function (data) {
                var errors = data.responseJSON.errors;
                var errorMessage = '';
                $.each(errors, function(key, value) {
                    errorMessage += value[0] + '\n';
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: errorMessage
                });
            }
        });
    });

    $('body').on('click', '.delete-btn', function () {
        var deleteUrl = $(this).data("url");
        Swal.fire({
            title: 'Are you sure?',
            text: "This user will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete!',
            cancelButtonText: '<i class="fas fa-times"></i> Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: deleteUrl,
                    data: { _token: "{{ csrf_token() }}"},
                    success: function (data) {
                        table.draw();
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.success,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function (data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.responseJSON.error || 'Something went wrong'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
