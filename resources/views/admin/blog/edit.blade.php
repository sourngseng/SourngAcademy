@extends('admin.layouts.master')

@section('title', 'Edit Blog Post')

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    #imagePreview {
        text-align: center;
    }
    .stat-box {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .stat-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
    }
    .stat-value {
        font-size: 24px;
        font-weight: bold;
        color: #007bff;
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Blog Post</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blog Posts</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.blog.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="blogPostForm">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Left Column - Form Fields -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Post Details</h3>
                        </div>
                        <div class="card-body">
                            <!-- Title -->
                            <div class="form-group">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" placeholder="Enter post title" 
                                       value="{{ old('title', $post->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select class="form-control @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Excerpt -->
                            <div class="form-group">
                                <label for="excerpt">Excerpt</label>
                                <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                          id="excerpt" name="excerpt" rows="3" 
                                          placeholder="Short summary of the post">{{ old('excerpt', $post->excerpt) }}</textarea>
                                @error('excerpt')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Maximum 500 characters</small>
                            </div>

                            <!-- Content -->
                            <div class="form-group">
                                <label for="content">Content <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          id="content" name="content" required>{{ old('content', $post->content) }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Published Date & Time -->
                            <div class="form-group">
                                <label for="published_at">Published Date & Time</label>
                                <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input @error('published_at') is-invalid @enderror" 
                                           name="published_at" id="published_at" 
                                           data-target="#datetimepicker" 
                                           placeholder="Select date and time"
                                           value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d H:i:s') : '') }}" />
                                    <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                @error('published_at')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Published Status -->
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" 
                                           {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_published">Publish Post</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reader Statistics -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Reader Statistics</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Total Views -->
                                <div class="col-md-3">
                                    <div class="stat-box text-center">
                                        <div class="stat-label">Total Views</div>
                                        <div class="stat-value">{{ number_format($post->views_count) }}</div>
                                    </div>
                                </div>
                                
                                <!-- Unique Locations -->
                                <div class="col-md-3">
                                    <div class="stat-box text-center">
                                        <div class="stat-label">Unique Locations</div>
                                        <div class="stat-value">{{ count($topLocations) }}</div>
                                    </div>
                                </div>

                                <!-- Device Types -->
                                <div class="col-md-3">
                                    <div class="stat-box text-center">
                                        <div class="stat-label">Device Types</div>
                                        <div class="stat-value">{{ count($deviceCounts) }}</div>
                                    </div>
                                </div>

                                <!-- Total Readers -->
                                <div class="col-md-3">
                                    <div class="stat-box text-center">
                                        <div class="stat-label">Total Readers</div>
                                        <div class="stat-value">{{ $post->views_count > 0 ? number_format($post->views_count) : '0' }}</div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Top Locations -->
                            @if(count($topLocations) > 0)
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5>Top Reader Locations</h5>
                                    <ul class="list-group">
                                        @foreach($topLocations as $location => $count)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <i class="fas fa-map-marker-alt text-danger mr-2"></i> {{ $location ?: 'Unknown' }}
                                            <span class="badge badge-primary badge-pill">{{ $count }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- Device Types Breakdown -->
                                <div class="col-md-6">
                                    <h5>Reader Device Types</h5>
                                    <ul class="list-group">
                                        @foreach($deviceCounts as $device => $count)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            @if($device == 'mobile')
                                                <i class="fas fa-mobile-alt text-success mr-2"></i>
                                            @elseif($device == 'tablet')
                                                <i class="fas fa-tablet-alt text-info mr-2"></i>
                                            @else
                                                <i class="fas fa-desktop text-primary mr-2"></i>
                                            @endif
                                            {{ ucfirst($device ?: 'Unknown') }}
                                            <span class="badge badge-success badge-pill">{{ $count }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No reader statistics available yet.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Image Upload -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Featured Image</h3>
                        </div>
                        <div class="card-body">
                            <!-- Current Image -->
                            @if($post->image_path)
                            <div class="mb-3 text-center" id="currentImage">
                                <img src="{{ asset($post->image_path) }}" alt="Current Image" class="img-fluid rounded" style="max-height: 200px; border: 2px solid #007bff;">
                                <p class="text-muted mt-2 mb-0"><small>Current Image</small></p>
                            </div>
                            @endif

                            <div class="form-group">
                                <label>Upload New Featured Image</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="imageInput" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    <label class="custom-file-label" for="imageInput">Choose file</label>
                                </div>
                                <small class="form-text text-muted">JPG, PNG, GIF, WEBP (Max 2MB)</small>
                            </div>
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px; border: 2px solid #28a745;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImage">
                                    <i class="fas fa-times"></i> Remove New Image
                                </button>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Update Post
                            </button>
                            <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Summernote
    $('#content').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Initialize Datetime Picker
    $('#datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
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
        },
        buttons: {
            showToday: true,
            showClear: true,
            showClose: true
        }
    });

    // File input handler with preview
    $('#imageInput').on('change', function(e) {
        var fileName = e.target.files[0].name;
        $(this).next('.custom-file-label').html(fileName);
        
        // Show preview
        if (e.target.files && e.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
                $('#imagePreview').show();
                $('#currentImage').hide(); // Hide current image when new one is selected
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
    
    // Remove image
    $('#removeImage').on('click', function() {
        $('#imageInput').val('');
        $('.custom-file-label').html('Choose file');
        $('#imagePreview').hide();
        $('#currentImage').show(); // Show current image again
    });
});
</script>
@endpush
