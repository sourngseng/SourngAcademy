@extends('admin.layouts.master')

@section('title', 'Rooms Management')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    
    <style>
        /* Dropzone Style Image Uploader */
        .image-dropzone {
            border: 2px dashed #007bff;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .image-dropzone:hover {
            border-color: #0056b3;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
        }
        
        .image-dropzone.dragover {
            border-color: #28a745;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            transform: scale(1.02);
        }
        
        .image-dropzone-icon {
            font-size: 48px;
            color: #007bff;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }
        
        .image-dropzone:hover .image-dropzone-icon {
            transform: scale(1.1);
        }
        
        .image-dropzone-text {
            font-size: 16px;
            color: #495057;
            margin-bottom: 5px;
        }
        
        .image-dropzone-subtext {
            font-size: 13px;
            color: #6c757d;
        }
        
        .image-dropzone-subtext a {
            text-decoration: underline;
            cursor: pointer;
        }
        
        .image-dropzone-subtext a:hover {
            text-decoration: none;
            color: #0056b3;
        }
        
        /* Image Preview Grid */
        .image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .image-preview-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: #fff;
        }
        
        .image-preview-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .image-preview-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }
        
        .image-preview-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .image-preview-item:hover .image-preview-overlay {
            opacity: 1;
        }
        
        .image-preview-actions {
            display: flex;
            gap: 8px;
        }
        
        .image-preview-actions .btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
        }
        
        .image-preview-actions .btn:hover {
            transform: scale(1.15);
        }
        
        .image-preview-info {
            padding: 10px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }
        
        .image-preview-name {
            font-size: 11px;
            color: #495057;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Featured Badge */
        .featured-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 10px rgba(255, 193, 7, 0.4);
            z-index: 10;
        }
        
        .featured-badge i {
            margin-right: 4px;
        }
        
        /* New Image Badge */
        .new-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Existing Image Border */
        .image-preview-item.existing {
            border: 2px solid #17a2b8;
        }
        
        .image-preview-item.new-upload {
            border: 2px solid #28a745;
        }
        
        /* Upload Progress */
        .upload-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #e9ecef;
        }
        
        .upload-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #007bff, #00bcd4);
            transition: width 0.3s ease;
        }
        
        /* Animation for new items */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .image-preview-item {
            animation: fadeInUp 0.3s ease forwards;
        }
        
        /* Empty State */
        .images-empty-state {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }
        
        .images-empty-state i {
            font-size: 40px;
            margin-bottom: 10px;
            opacity: 0.5;
        }
    </style>
@endpush

@section('page-title')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Rooms Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Rooms</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
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
                        <label>Room Type</label>
                        <select class="form-control" id="filter_room_type">
                            <option value="">All Room Types</option>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->room_type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" id="filter_status">
                            <option value="">All Status</option>
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="reserved">Reserved</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Floor</label>
                        <select class="form-control" id="filter_floor">
                            <option value="">All Floors</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">Floor {{ $i }}</option>
                            @endfor
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
            </div>
        </div>
    </div>

    <!-- Room List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Rooms List</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" id="createNewRoom">
                    <i class="fas fa-plus"></i> Add New Room
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Image</th>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Floor</th>
                        <th>Status</th>
                        <th>Price/Night</th>
                        <th width="100px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Create/Edit -->
    <div class="modal fade" id="roomModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalHeading"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="roomForm" name="roomForm" class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="room_id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="room_number">Room Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="room_number" name="room_number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="room_type_id">Room Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="room_type_id" name="room_type_id" required>
                                        <option value="">Select Room Type</option>
                                        @foreach($roomTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->room_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="floor">Floor <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="floor" name="floor" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="available">Available</option>
                                        <option value="occupied">Occupied</option>
                                        <option value="maintenance">Maintenance</option>
                                        <option value="reserved">Reserved</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price_per_night">Price Per Night ($) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="price_per_night" name="price_per_night" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="amenities">Amenities</label>
                            <select class="form-control select2" id="amenities" name="amenities[]" multiple="multiple" style="width: 100%;">
                                @foreach($amenities as $amenity)
                                    <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Room Images</label>
                            
                            <!-- Hidden file input (outside dropzone for proper click handling) -->
                            <input type="file" class="d-none" id="images" name="images[]" multiple accept="image/*">
                            
                            <!-- Dropzone Area -->
                            <div class="image-dropzone" id="imageDropzone">
                                <div class="image-dropzone-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="image-dropzone-text">
                                    <strong>Drag & drop images here</strong>
                                </div>
                                <div class="image-dropzone-subtext">
                                    or <a href="javascript:void(0)" class="text-primary font-weight-bold" id="browseImages">click to browse</a> • Max 2MB per image • JPG, PNG, GIF
                                </div>
                            </div>
                            
                            <!-- Hidden input to track existing images to keep -->
                            <input type="hidden" id="existingImages" name="existing_images" value="">
                            <!-- Hidden input to track featured image -->
                            <input type="hidden" id="featuredImageId" name="featured_image_id" value="">
                            
                            <!-- Image Preview Grid -->
                            <div id="images-preview" class="image-preview-grid"></div>
                            
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle"></i> Click the <span class="text-warning"><i class="fas fa-star"></i></span> icon to set as featured image. Featured image will be displayed as the primary room photo.
                            </small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveBtn">Save Room</button>
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
    <!-- Select2 -->
    <script src="{{asset('backend')}}/plugins/select2/js/select2.full.min.js"></script>

    <script type="text/javascript">
        $(function () {
            // Image Upload Manager
            var ImageManager = {
                existingImages: [],
                newFiles: [],
                featuredImageId: null,
                featuredNewIndex: 0,
                
                init: function() {
                    this.bindEvents();
                },
                
                reset: function() {
                    this.existingImages = [];
                    this.newFiles = [];
                    this.featuredImageId = null;
                    this.featuredNewIndex = 0;
                    $('#images-preview').html('');
                    $('#existingImages').val('');
                    $('#featuredImageId').val('');
                    $('#images').val('');
                },
                
                bindEvents: function() {
                    var self = this;
                    
                    // Browse button click
                    $(document).on('click', '#browseImages', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $('#images').trigger('click');
                    });
                    
                    // Dropzone click (entire area)
                    $(document).on('click', '#imageDropzone', function(e) {
                        // Don't trigger if clicking on the browse link (it has its own handler)
                        if ($(e.target).attr('id') === 'browseImages') {
                            return;
                        }
                        e.preventDefault();
                        $('#images').trigger('click');
                    });
                    
                    // File input change
                    $(document).on('change', '#images', function(e) {
                        self.handleFiles(e.target.files);
                    });
                    
                    // Drag & Drop events
                    $(document).on('dragover', '#imageDropzone', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $(this).addClass('dragover');
                    });
                    
                    $(document).on('dragleave', '#imageDropzone', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $(this).removeClass('dragover');
                    });
                    
                    $(document).on('drop', '#imageDropzone', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $(this).removeClass('dragover');
                        var files = e.originalEvent.dataTransfer.files;
                        self.handleFiles(files);
                    });
                    
                    // Delete image
                    $(document).on('click', '.delete-image', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var type = $(this).data('type');
                        var id = $(this).data('id');
                        self.deleteImage(type, id);
                    });
                    
                    // Set featured image
                    $(document).on('click', '.set-featured', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var type = $(this).data('type');
                        var id = $(this).data('id');
                        self.setFeatured(type, id);
                    });
                },
                
                handleFiles: function(files) {
                    var self = this;
                    var validFiles = [];
                    
                    Array.from(files).forEach(function(file) {
                        // Validate file type
                        if (!file.type.match('image.*')) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Invalid File',
                                text: file.name + ' is not an image file.',
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            return;
                        }
                        
                        // Validate file size (2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'File Too Large',
                                text: file.name + ' exceeds 2MB limit.',
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            return;
                        }
                        
                        validFiles.push(file);
                    });
                    
                    if (validFiles.length > 0) {
                        // If this is the first image and no featured is set
                        if (self.existingImages.length === 0 && self.newFiles.length === 0) {
                            self.featuredNewIndex = 0;
                        }
                        
                        validFiles.forEach(function(file) {
                            self.newFiles.push(file);
                        });
                        
                        self.updatePreview();
                        self.updateFileInput();
                    }
                },
                
                loadExistingImages: function(images) {
                    var self = this;
                    self.existingImages = [];
                    self.featuredImageId = null;
                    
                    if (images && images.length > 0) {
                        images.forEach(function(img) {
                            self.existingImages.push({
                                id: img.id,
                                path: img.image_path,
                                isPrimary: img.is_primary
                            });
                            if (img.is_primary) {
                                self.featuredImageId = img.id;
                            }
                        });
                    }
                    
                    self.updatePreview();
                    self.updateHiddenInputs();
                },
                
                deleteImage: function(type, id) {
                    var self = this;
                    
                    Swal.fire({
                        title: 'Remove Image?',
                        text: 'This image will be removed.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, remove it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (type === 'existing') {
                                self.existingImages = self.existingImages.filter(img => img.id != id);
                                if (self.featuredImageId == id) {
                                    // Set first remaining image as featured
                                    if (self.existingImages.length > 0) {
                                        self.featuredImageId = self.existingImages[0].id;
                                    } else if (self.newFiles.length > 0) {
                                        self.featuredImageId = null;
                                        self.featuredNewIndex = 0;
                                    } else {
                                        self.featuredImageId = null;
                                    }
                                }
                            } else {
                                self.newFiles.splice(id, 1);
                                if (self.featuredImageId === null && self.featuredNewIndex == id) {
                                    self.featuredNewIndex = 0;
                                } else if (self.featuredNewIndex > id) {
                                    self.featuredNewIndex--;
                                }
                            }
                            
                            self.updatePreview();
                            self.updateHiddenInputs();
                            self.updateFileInput();
                        }
                    });
                },
                
                setFeatured: function(type, id) {
                    var self = this;
                    
                    if (type === 'existing') {
                        self.featuredImageId = id;
                        self.featuredNewIndex = -1;
                    } else {
                        self.featuredImageId = null;
                        self.featuredNewIndex = id;
                    }
                    
                    self.updatePreview();
                    self.updateHiddenInputs();
                },
                
                updatePreview: function() {
                    var self = this;
                    var html = '';
                    
                    // Existing images
                    self.existingImages.forEach(function(img, index) {
                        var isFeatured = self.featuredImageId == img.id;
                        html += self.createImageCard(img.path, 'existing', img.id, isFeatured);
                    });
                    
                    // New files
                    self.newFiles.forEach(function(file, index) {
                        var isFeatured = self.featuredImageId === null && self.featuredNewIndex === index;
                        html += self.createImageCard(URL.createObjectURL(file), 'new', index, isFeatured, file.name);
                    });
                    
                    if (html === '') {
                        html = '<div class="images-empty-state"><i class="fas fa-images"></i><p>No images uploaded yet</p></div>';
                    }
                    
                    $('#images-preview').html(html);
                },
                
                createImageCard: function(src, type, id, isFeatured, fileName) {
                    var isExisting = type === 'existing';
                    var assetUrl = isExisting ? '{{ asset("") }}' : '';
                    
                    return `
                        <div class="image-preview-item ${isExisting ? 'existing' : 'new-upload'}">
                            ${isFeatured ? '<span class="featured-badge"><i class="fas fa-star"></i> Featured</span>' : ''}
                            ${!isExisting ? '<span class="new-badge"><i class="fas fa-plus"></i> New</span>' : ''}
                            <img src="${assetUrl}${src}" alt="Room Image">
                            <div class="image-preview-overlay">
                                <div class="image-preview-actions">
                                    <button type="button" class="btn btn-warning set-featured" data-type="${type}" data-id="${id}" title="Set as Featured">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger delete-image" data-type="${type}" data-id="${id}" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="image-preview-info">
                                <div class="image-preview-name">${fileName || (isExisting ? 'Existing Image' : 'New Image')}</div>
                            </div>
                        </div>
                    `;
                },
                
                updateHiddenInputs: function() {
                    var self = this;
                    var existingIds = self.existingImages.map(img => img.id);
                    $('#existingImages').val(JSON.stringify(existingIds));
                    $('#featuredImageId').val(self.featuredImageId || '');
                },
                
                updateFileInput: function() {
                    var self = this;
                    var dataTransfer = new DataTransfer();
                    self.newFiles.forEach(function(file) {
                        dataTransfer.items.add(file);
                    });
                    $('#images')[0].files = dataTransfer.files;
                },
                
                getFeaturedNewIndex: function() {
                    return this.featuredNewIndex;
                }
            };
            
            // Initialize Image Manager
            ImageManager.init();
            
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Select Amenities',
                allowClear: true
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.rooms.index') }}",
                    data: function (d) {
                        d.room_type_id = $('#filter_room_type').val();
                        d.status = $('#filter_status').val();
                        d.floor = $('#filter_floor').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'room_number', name: 'room_number'},
                    {data: 'room_type', name: 'roomType.room_type_name'},
                    {data: 'floor', name: 'floor'},
                    {data: 'status', name: 'status'},
                    {data: 'price_per_night', name: 'price_per_night'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // Apply filters
            $('#applyFilters').click(function(){
                table.draw();
            });

            $('#createNewRoom').click(function () {
                $('#room_id').val('');
                $('#roomForm').trigger("reset");
                $('.select2').val(null).trigger('change');
                $('#modalHeading').html("Create New Room");
                $('#roomModal').modal('show');
                ImageManager.reset();
            });

            $('body').on('click', '.editRoom', function () {
                var room_id = $(this).data('id');
                ImageManager.reset();
                
                $.get("{{ route('admin.rooms.index') }}" +'/' + room_id +'/edit', function (data) {
                    $('#modalHeading').html("Edit Room");
                    $('#roomModal').modal('show');
                    $('#room_id').val(data.id);
                    $('#room_number').val(data.room_number);
                    $('#room_type_id').val(data.room_type_id);
                    $('#floor').val(data.floor);
                    $('#status').val(data.status);
                    $('#price_per_night').val(data.price_per_night);
                    $('#description').val(data.description);
                    
                    // Set amenities
                    if(data.amenity_ids) {
                        $('#amenities').val(data.amenity_ids).trigger('change');
                    }

                    // Load existing images into ImageManager
                    ImageManager.loadExistingImages(data.images);
                });
            });

            $('#roomForm').submit(function (e) {
                e.preventDefault();
                $('#saveBtn').html('<i class="fas fa-spinner fa-spin"></i> Saving...');

                var formData = new FormData(this);
                var id = $('#room_id').val();
                
                // Add featured new index if applicable
                if (ImageManager.featuredImageId === null && ImageManager.newFiles.length > 0) {
                    formData.append('featured_new_index', ImageManager.getFeaturedNewIndex());
                }
                
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                
                var url = id ? "{{ route('admin.rooms.index') }}" + '/' + id : "{{ route('admin.rooms.store') }}";
                
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
                        $('#roomForm').trigger("reset");
                        $('#roomModal').modal('hide');
                        ImageManager.reset();
                        table.draw();
                        $('#saveBtn').html('Save Room');
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
                        $('#saveBtn').html('Save Room');
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

            $('body').on('click', '.deleteRoom', function () {
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
