@extends('admin.layouts.master')

@section('title', 'Hero Slides')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <style>
        .slide-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }
        .slide-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
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
                <h1><i class="fas fa-images mr-2"></i> Hero Slides</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.slides.create') }}" class="btn btn-primary btn-lg px-4 shadow">
                    <i class="fas fa-plus-circle mr-2"></i> Add New Slide
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
            <div class="card card-outline card-primary slide-card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title">List of All Slides</h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped data-table">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Image</th>
                                <th width="30%">Title</th>
                                <th width="20%">Subtitle</th>
                                <th width="10%">Order</th>
                                <th width="10%">Status</th>
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
</div>
@endsection

@push('scripts')
    <!-- DataTables & Plugins -->
    <script src="{{asset('backend')}}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{asset('backend')}}/plugins/jszip/jszip.min.js"></script>
    <script src="{{asset('backend')}}/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{{asset('backend')}}/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.slides.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {
                        data: 'image', 
                        name: 'image', 
                        orderable: false, 
                        searchable: false,
                        render: function(data) {
                            return '<div class="text-center">' + data + '</div>';
                        }
                    },
                    {data: 'title', name: 'title', class: 'font-weight-bold'},
                    {data: 'subtitle', name: 'subtitle'},
                    {data: 'order', name: 'order'},
                    {data: 'status', name: 'is_active'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[4, 'asc']], // Order by 'Order' column by default
                responsive: true,
                autoWidth: false,
                dom: 'Bfrtip',
                buttons: ["copy", "csv", "excel", "pdf", "print"]
            });

            // Duplicate Action
            $('body').on('click', '.duplicateSlide', function (e) {
                e.preventDefault();
                var duplicateUrl = $(this).data("url");

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to duplicate this slide!",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, duplicate it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: duplicateUrl,
                            success: function (data) {
                                table.draw();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Duplicated!',
                                    text: data.success,
                                    timer: 2000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            },
                            error: function (data) {
                                console.log('Error:', data);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Something went wrong!',
                                });
                            }
                        });
                    }
                });
            });

            // Delete Action
            $('body').on('click', '.deleteSlide', function (e) {
                e.preventDefault();
                var deleteUrl = $(this).data("url");

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
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Something went wrong!',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
