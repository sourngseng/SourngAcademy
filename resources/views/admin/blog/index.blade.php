@extends('admin.layouts.master')

@section('title', 'Blog Posts')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <style>
        .info-box {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            border-radius: .25rem;
            padding: 1rem;
            min-height: 80px;
            display: flex;
            align-items: center;
        }
        .info-box-icon {
            border-radius: .25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }
        .info-box-content {
            flex: 1;
            padding: 0 10px;
        }
        .info-box-text {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .info-box-number {
            display: block;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .filter-card {
            margin-bottom: 20px;
        }
        .post-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .post-meta {
            font-size: 12px;
            color: #6c757d;
        }
        .post-meta .badge {
            font-size: 10px;
            padding: 3px 6px;
        }
        .post-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-blog mr-2"></i>Blog Posts</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Blog Posts</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')

<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-newspaper"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Posts</span>
                <span class="info-box-number">{{ number_format($totalPosts) }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Published</span>
                <span class="info-box-number">{{ number_format($publishedPosts) }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="fas fa-file-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Drafts</span>
                <span class="info-box-number">{{ number_format($draftPosts) }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="info-box bg-danger">
            <span class="info-box-icon"><i class="fas fa-eye"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Views</span>
                <span class="info-box-number">{{ number_format($totalViews) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Layout -->
<div class="row">
    <!-- Left Sidebar - Filters -->
    <div class="col-md-3">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filter Posts</h3>
            </div>
            <div class="card-body">
                <form id="filterForm">
                    <!-- Category Filter -->
                    <div class="form-group">
                        <label for="filter_category"><i class="fas fa-folder mr-1"></i> Category</label>
                        <select class="form-control" id="filter_category" name="category_id">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="form-group">
                        <label for="filter_status"><i class="fas fa-flag mr-1"></i> Status</label>
                        <select class="form-control" id="filter_status" name="status">
                            <option value="">All Status</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    
                    <!-- Date From -->
                    <div class="form-group">
                        <label for="date_from"><i class="fas fa-calendar-alt mr-1"></i> Date From</label>
                        <div class="input-group date" id="datetimepicker_from" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" 
                                   name="date_from" id="date_from" 
                                   data-target="#datetimepicker_from" 
                                   placeholder="Start date" />
                            <div class="input-group-append" data-target="#datetimepicker_from" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Date To -->
                    <div class="form-group">
                        <label for="date_to"><i class="fas fa-calendar-check mr-1"></i> Date To</label>
                        <div class="input-group date" id="datetimepicker_to" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" 
                                   name="date_to" id="date_to" 
                                   data-target="#datetimepicker_to" 
                                   placeholder="End date" />
                            <div class="input-group-append" data-target="#datetimepicker_to" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter Buttons -->
                    <div class="form-group">
                        <button type="button" class="btn btn-primary btn-block" id="applyFilter">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <button type="button" class="btn btn-secondary btn-block" id="resetFilter">
                            <i class="fas fa-redo"></i> Reset Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Content - Posts List -->
    <div class="col-md-9">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-newspaper mr-2"></i>Blog Posts</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.blog.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Create New Post
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover data-table">
                        <thead>
                            <tr>
                                <th width="50px">No</th>
                                <th width="70px">Image</th>
                                <th>Title</th>
                                <th width="100px">Status</th>
                                <th width="150px">Published</th>
                                <th width="100px">Action</th>
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
    <!-- DataTables -->
    <script src="{{asset('backend')}}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{asset('backend')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
    
<script type="text/javascript">
    $(document).ready(function () {
        // Initialize date pickers for filter
        $('#datetimepicker_from').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false,
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'fas fa-trash',
                close: 'fas fa-times'
            }
        });

        $('#datetimepicker_to').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false,
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'fas fa-trash',
                close: 'fas fa-times'
            }
        });

        // Toast helper
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        // DataTable
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.blog.index') }}",
                data: function (d) {
                    d.category_id = $('#filter_category').val();
                    d.status = $('#filter_status').val();
                    d.date_from = $('#date_from').val();
                    d.date_to = $('#date_to').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'image', name: 'image', orderable: false, searchable: false},
                {data: 'title', name: 'title'},
                {data: 'status', name: 'is_published'},
                {data: 'published_at', name: 'published_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order: [[4, 'desc']]
        });

        // Apply filters
        $('#applyFilter').click(function() {
            table.draw();
        });

        // Reset filters
        $('#resetFilter').click(function() {
            $('#filterForm')[0].reset();
            $('#datetimepicker_from').datetimepicker('clear');
            $('#datetimepicker_to').datetimepicker('clear');
            table.draw();
        });

        // Delete post
        $('body').on('click', '.deletePost', function () {
            var url = $(this).data("url");
            
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
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            table.draw();
                            Toast.fire({
                                icon: 'success',
                                title: data.success
                            });
                        },
                        error: function (data) {
                            Toast.fire({
                                icon: 'error',
                                title: 'Error deleting post!'
                            });
                        }
                    });
                }
            });
        });

        // Show success message if exists
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif
    });
</script>
@endpush
