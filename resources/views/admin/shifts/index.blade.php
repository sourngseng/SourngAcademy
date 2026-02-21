@extends('admin.layouts.master')

@section('title', 'Shifts Management')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="container-fluid pt-4">
    <div class="row mb-4">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Shifts Management</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('admin.shifts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Shift
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="shifts-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 15%;">Campus</th>
                        <th style="width: 20%;">Shift Name</th>
                        <th style="width: 40%;">Sessions</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 15%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data loaded via Ajax -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- DataTables -->
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        $(function () {
            $('#shifts-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.shifts.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'campus_id', name: 'campus.name', defaultContent: 'All Campuses'},
                    {data: 'name', name: 'name'},
                    {data: 'sessions', name: 'sessions', orderable: false, searchable: false},
                    {data: 'is_active', name: 'is_active'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });

        function confirmDuplicate(button) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to duplicate this shift?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, duplicate it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(button).closest('form').submit();
                }
            })
        }

        function confirmDelete(button) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(button).closest('form').submit();
                }
            })
        }
    </script>
@endpush
