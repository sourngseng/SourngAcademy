@extends('admin.layouts.master')

@section('title', 'Blog Categories')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <style>
        .icon-item {
            text-align: center;
            padding: 15px;
            margin: 5px;
            border: 2px solid #dee2e6;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .icon-item:hover {
            border-color: #007bff;
            background-color: #f8f9fa;
            transform: scale(1.05);
        }
        .icon-item i {
            font-size: 24px;
            display: block;
            margin-bottom: 5px;
        }
        .icon-item.selected {
            border-color: #28a745;
            background-color: #d4edda;
        }
        #iconGrid {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Blog Categories</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Blog Categories</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">All Categories</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" id="createNewCategory">
                <i class="fas fa-plus"></i> Add New Category
            </button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Icon</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th width="100px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Create/Edit -->
<div class="modal fade" id="ajaxModelexa" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="categoryForm" name="categoryForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="category_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-12">
                            <textarea id="description" name="description" placeholder="Enter Description" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Font Awesome Icon</label>
                        <div class="col-sm-12">
                            <div class="input-group">
                                <input type="text" id="icon" name="icon" class="form-control" placeholder="e.g., fa-tag, fa-folder, fa-book" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="selectIconBtn">
                                        <i class="fas fa-icons"></i> Select Icon
                                    </button>
                                </div>
                            </div>
                            <div id="icon-preview" class="mt-2"></div>
                            <small class="form-text text-muted">Click "Select Icon" to choose from Font Awesome icons</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Image</label>
                        <div class="col-sm-12">
                            <input type="file" id="image" name="image" class="form-control">
                            <div id="image-preview"></div>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Font Awesome Icon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" id="iconSearch" class="form-control" placeholder="Search icons... (e.g., tag, folder, book)">
                </div>
                <div id="iconGrid" class="row">
                    <!-- Icons will be populated here -->
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
    <!-- SweetAlert2 -->
    <script src="{{asset('backend')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

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
                ajax: "{{ route('admin.blog-categories.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'icon', name: 'icon', orderable: false, searchable: false},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'description', name: 'description'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // Popular Font Awesome icons for blog categories
            const popularIcons = [
                'fa-tag', 'fa-tags', 'fa-folder', 'fa-folder-open', 'fa-book',
                'fa-newspaper', 'fa-file-alt', 'fa-bookmark', 'fa-pen', 'fa-pencil-alt',
                'fa-edit', 'fa-blog', 'fa-rss', 'fa-bullhorn', 'fa-comment',
                'fa-comments', 'fa-envelope', 'fa-inbox', 'fa-archive', 'fa-star',
                'fa-heart', 'fa-thumbs-up', 'fa-fire', 'fa-bolt', 'fa-graduation-cap',
                'fa-lightbulb', 'fa-camera', 'fa-image', 'fa-video', 'fa-music',
                'fa-film', 'fa-gamepad', 'fa-trophy', 'fa-gift', 'fa-certificate',
                'fa-code', 'fa-laptop', 'fa-mobile-alt', 'fa-tablet-alt', 'fa-desktop',
                'fa-globe', 'fa-map-marker-alt', 'fa-plane', 'fa-car', 'fa-utensils',
                'fa-coffee', 'fa-beer', 'fa-wine-glass', 'fa-pizza-slice', 'fa-hamburger',
                'fa-shopping-cart', 'fa-shopping-bag', 'fa-credit-card', 'fa-money-bill-wave', 'fa-wallet',
                'fa-briefcase', 'fa-building', 'fa-home', 'fa-users', 'fa-user-tie',
                'fa-chart-line', 'fa-chart-bar', 'fa-chart-pie', 'fa-calendar', 'fa-clock',
                'fa-flag', 'fa-bell', 'fa-cog', 'fa-wrench', 'fa-tools',
                'fa-palette', 'fa-paint-brush', 'fa-theater-masks', 'fa-cocktail', 'fa-bicycle',
                'fa-tree', 'fa-leaf', 'fa-seedling', 'fa-sun', 'fa-moon',
                'fa-cloud', 'fa-umbrella', 'fa-snowflake', 'fa-mountain', 'fa-hiking'
            ];

            // Populate icon grid
            function populateIconGrid(icons) {
                const iconGrid = $('#iconGrid');
                iconGrid.empty();
                icons.forEach(function(iconClass) {
                    const iconHtml = `
                        <div class="col-md-2 col-sm-3 col-4">
                            <div class="icon-item" data-icon="${iconClass}">
                                <i class="fas ${iconClass}"></i>
                                <small>${iconClass.replace('fa-', '')}</small>
                            </div>
                        </div>
                    `;
                    iconGrid.append(iconHtml);
                });
            }

            // Initialize icon grid
            populateIconGrid(popularIcons);

            // Open icon picker modal
            $('#selectIconBtn').click(function() {
                $('#iconPickerModal').modal('show');
                populateIconGrid(popularIcons);
            });

            // Search icons
            $('#iconSearch').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                if (searchTerm === '') {
                    populateIconGrid(popularIcons);
                } else {
                    const filtered = popularIcons.filter(icon => icon.toLowerCase().includes(searchTerm));
                    populateIconGrid(filtered);
                }
            });

            // Select icon from picker
            $(document).on('click', '.icon-item', function() {
                const iconClass = $(this).data('icon');
                $('#icon').val(iconClass);
                $('#icon-preview').html(`<i class="fas ${iconClass}" style="font-size: 32px;"></i> <span class="ml-2">${iconClass}</span>`);
                $('.icon-item').removeClass('selected');
                $(this).addClass('selected');
                $('#iconPickerModal').modal('hide');
            });

            $('#createNewCategory').click(function () {
                $('#saveBtn').val("create-category");
                $('#category_id').val('');
                $('#categoryForm').trigger("reset");
                $('#modelHeading').html("Create New Category");
                $('#ajaxModelexa').modal('show');
                $('#image-preview').html('');
                $('#icon-preview').html('');
            });

            $('body').on('click', '.editCategory', function () {
                var category_id = $(this).data('id');
                $.get("{{ route('admin.blog-categories.index') }}" +'/' + category_id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Category");
                    $('#saveBtn').val("edit-category");
                    $('#ajaxModelexa').modal('show');
                    $('#category_id').val(data.id);
                    $('#name').val(data.name);
                    $('#description').val(data.description);
                    
                    // Display icon - check if it's a Font Awesome class or image path
                    if(data.icon){
                        if(data.icon.includes('uploads')){
                            $('#icon-preview').html('<img src="{{ asset('') }}'+data.icon+'" width="100" class="img-thumbnail mt-2" />');
                            $('#icon').val(''); // Clear icon field if it's an uploaded image
                        } else {
                            // It's a Font Awesome class
                            $('#icon').val(data.icon);
                            $('#icon-preview').html(`<i class="fas ${data.icon}" style="font-size: 32px;"></i> <span class="ml-2">${data.icon}</span>`);
                        }
                    } else {
                        $('#icon').val('');
                        $('#icon-preview').html('');
                    }

                    if(data.image && data.image.includes('uploads')){
                         $('#image-preview').html('<img src="{{ asset('') }}'+data.image+'" width="100" class="img-thumbnail mt-2" />');
                    }else{
                        $('#image-preview').html('');
                    }
                })
            });

            $('#categoryForm').submit(function (e) {
                e.preventDefault();
                $('#saveBtn').html('Sending..');

                var formData = new FormData(this);
                var id = $('#category_id').val();
                
                // Add CSRF token to FormData
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                
                // If ID exists, we are UPDATING.
                // However, directly using PUT with FormData often fails in PHP/Laravel.
                // Best practice is to use POST and spoof PUT with _method.
                var url = id ? "{{ route('admin.blog-categories.index') }}" + '/' + id : "{{ route('admin.blog-categories.store') }}";
                
                if(id){
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    data: formData,
                    url: url,
                    type: 'POST', // Always POST for FormData, use _method to spoof PUT
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#categoryForm').trigger("reset");
                        $('#ajaxModelexa').modal('hide');
                        table.draw();
                        $('#saveBtn').html('Save changes');
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
                        $('#saveBtn').html('Save changes');
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

            $('body').on('click', '.deleteCategory', function () {
                var deleteUrl = $(this).data("url");

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
