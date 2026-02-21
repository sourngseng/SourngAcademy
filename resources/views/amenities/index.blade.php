@extends('admin.layouts.master')

@section('title', 'Amenities')

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
                    <h1>Amenities</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Amenities</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Amenities List</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" id="createNewAmenity">
                    <i class="fas fa-plus"></i> Add New Amenity
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
                    <form id="amenityForm" name="amenityForm" class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="amenity_id">
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
                                    <input type="text" id="icon" name="icon" class="form-control" placeholder="e.g., fa-wifi, fa-swimming-pool" readonly>
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
                        <input type="text" id="iconSearch" class="form-control" placeholder="Search icons... (e.g., wifi, pool, gym)">
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
                ajax: "{{ route('admin.amenities.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'icon', name: 'icon', orderable: false, searchable: false},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'description', name: 'description'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // Popular Font Awesome icons for amenities
            const popularIcons = [
                'fa-wifi', 'fa-swimming-pool', 'fa-dumbbell', 'fa-parking', 'fa-utensils',
                'fa-spa', 'fa-coffee', 'fa-tv', 'fa-snowflake', 'fa-wind',
                'fa-concierge-bell', 'fa-bed', 'fa-bath', 'fa-shower', 'fa-hot-tub',
                'fa-smoking', 'fa-smoking-ban', 'fa-glass-martini-alt', 'fa-cocktail', 'fa-beer',
                'fa-wine-glass', 'fa-baby', 'fa-wheelchair', 'fa-blind', 'fa-dog',
                'fa-cat', 'fa-paw', 'fa-sun', 'fa-umbrella-beach', 'fa-mountain',
                'fa-tree', 'fa-water', 'fa-fire', 'fa-lock', 'fa-key',
                'fa-shield-alt', 'fa-first-aid', 'fa-heartbeat', 'fa-phone', 'fa-envelope',
                'fa-desktop', 'fa-laptop', 'fa-tablet-alt', 'fa-mobile-alt', 'fa-print',
                'fa-fax', 'fa-building', 'fa-home', 'fa-car', 'fa-bicycle',
                'fa-bus', 'fa-taxi', 'fa-plane', 'fa-train', 'fa-subway',
                'fa-ship', 'fa-anchor', 'fa-map-marked-alt', 'fa-map-marker-alt', 'fa-route',
                'fa-credit-card', 'fa-money-bill-wave', 'fa-coins', 'fa-piggy-bank', 'fa-wallet',
                'fa-calendar', 'fa-clock', 'fa-stopwatch', 'fa-hourglass', 'fa-users',
                'fa-user-tie', 'fa-user-friends', 'fa-user-check', 'fa-id-card', 'fa-passport',
                'fa-ticket-alt', 'fa-tag', 'fa-tags', 'fa-gift', 'fa-birthday-cake',
                'fa-music', 'fa-film', 'fa-video', 'fa-camera', 'fa-microphone',
                'fa-headphones', 'fa-gamepad', 'fa-dice', 'fa-chess', 'fa-puzzle-piece',
                'fa-book', 'fa-newspaper', 'fa-graduation-cap', 'fa-language', 'fa-globe',
                'fa-flag', 'fa-star', 'fa-heart', 'fa-thumbs-up', 'fa-trophy'
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

            $('#createNewAmenity').click(function () {
                $('#saveBtn').val("create-amenity");
                $('#amenity_id').val('');
                $('#amenityForm').trigger("reset");
                $('#modelHeading').html("Create New Amenity");
                $('#ajaxModelexa').modal('show');
                $('#image-preview').html('');
                $('#icon-preview').html('');
            });

            $('body').on('click', '.editAmenity', function () {
                var amenity_id = $(this).data('id');
                $.get("{{ route('admin.amenities.index') }}" +'/' + amenity_id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Amenity");
                    $('#saveBtn').val("edit-amenity");
                    $('#ajaxModelexa').modal('show');
                    $('#amenity_id').val(data.id);
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

            $('#amenityForm').submit(function (e) {
                e.preventDefault();
                $('#saveBtn').html('Sending..');

                var formData = new FormData(this);
                var id = $('#amenity_id').val();
                
                // Add CSRF token to FormData
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                
                // If ID exists, we are UPDATING.
                // However, directly using PUT with FormData often fails in PHP/Laravel.
                // Best practice is to use POST and spoof PUT with _method.
                var url = id ? "{{ route('admin.amenities.index') }}" + '/' + id : "{{ route('admin.amenities.store') }}";
                
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
                        $('#amenityForm').trigger("reset");
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

            $('body').on('click', '.deleteAmenity', function () {
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
