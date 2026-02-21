@extends('admin.layouts.master')

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
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

@section('title', 'Add New Faculty')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-plus-circle mr-2"></i> Add New Faculty</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.faculties.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card card-primary card-outline shadow">
                <div class="card-header bg-white">
                    <h3 class="card-title">Faculty Details</h3>
                </div>
                <form action="{{ route('admin.faculties.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Side: media -->
                            <div class="col-md-4 text-center">
                                <label>Faculty Thumbnail (Feature Image)</label>
                                <div class="position-relative mb-3" style="cursor: pointer;" onclick="document.getElementById('featureImage').click();">
                                    <div id="featurePreviewContainer" class="border rounded shadow-sm bg-light d-flex align-items-center justify-content-center" style="height: 180px; width: 100%;">
                                        <div class="text-center">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Browse Image</p>
                                        </div>
                                    </div>
                                    <img id="featurePreview" src="" class="img-fluid rounded shadow-sm border w-100" style="height: 180px; object-fit: cover; display: none;">
                                </div>
                                <input type="file" name="feature_image" class="d-none" id="featureImage" accept="image/*">
                                
                                <div class="form-group mt-3">
                                    <label for="icon">Or FontAwesome Icon</label>
                                    <div class="input-group">
                                        <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon') }}" placeholder="Select an icon" readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="selectIconBtn">
                                                <i class="fas fa-icons"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="icon-preview" class="mt-2 text-primary"></div>
                                    <small class="text-muted">Used if no image is uploaded</small>
                                    @error('icon') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Right Side: info -->
                            <div class="col-md-8">
                                <!-- Language Tabs -->
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    @foreach(config('app.available_locales', ['en' => 'English', 'km' => 'Khmer']) as $locale => $localeConfig)
                                        @php
                                            $localeName = is_array($localeConfig) ? $localeConfig['name'] : $localeConfig;
                                        @endphp
                                        <li class="nav-item">
                                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $locale }}-tab" data-toggle="pill" href="#tab-{{ $locale }}" role="tab" aria-controls="tab-{{ $locale }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                <i class="fas fa-globe mr-1"></i> {{ strtoupper($locale) }} - {{ $localeName }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content border rounded p-3 bg-light mb-3" id="pills-tabContent">
                                    @foreach(config('app.available_locales', ['en' => 'English', 'km' => 'Khmer']) as $locale => $localeConfig)
                                        @php
                                            $localeName = is_array($localeConfig) ? $localeConfig['name'] : $localeConfig;
                                        @endphp
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $locale }}" role="tabpanel" aria-labelledby="{{ $locale }}-tab">
                                            <div class="form-group">
                                                <label>Faculty Name ({{ $localeName }}) <span class="text-danger">*</span></label>
                                                <input type="text" name="translations[{{ $locale }}][name]" class="form-control @error('translations.'.$locale.'.name') is-invalid @enderror" value="{{ old('translations.'.$locale.'.name') }}" placeholder="Enter faculty name in {{ $localeName }}" {{ $loop->first ? 'required' : '' }}>
                                                @error('translations.'.$locale.'.name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Faculty Description ({{ $localeName }})</label>
                                                <textarea name="translations[{{ $locale }}][description]" class="form-control summernote @error('translations.'.$locale.'.description') is-invalid @enderror" placeholder="Enter description in {{ $localeName }}">{{ old('translations.'.$locale.'.description') }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-group">
                                    <label for="campus_id">Campus <span class="text-danger">*</span></label>
                                    <select name="campus_id" id="campus_id" class="form-control select2 @error('campus_id') is-invalid @enderror" required>
                                        <option value="">Select Campus</option>
                                        @foreach($campuses as $campus)
                                            <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>
                                                {{ $campus->name_en }} ({{ $campus->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('campus_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="slug">Slug <span class="text-muted text-xs">(URL Friendly Name)</span></label>
                                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="Auto-generated if empty">
                                            <small class="text-muted">Leaving empty will generate from the default language name.</small>
                                            @error('slug') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="code">Faculty Code</label>
                                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}">
                                            @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dean_name">Dean Name</label>
                                            <select name="dean_name" id="dean_name" class="form-control select2 @error('dean_name') is-invalid @enderror">
                                                <option value="">Select Teacher</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->name }}" {{ old('dean_name') == $teacher->name ? 'selected' : '' }}>
                                                        {{ $teacher->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('dean_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-right">
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="fas fa-save mr-1"></i> Save Faculty
                        </button>
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
                    <input type="text" id="iconSearch" class="form-control" placeholder="Search icons...">
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
<script src="{{ asset('backend/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
    $(document).ready(function () {
        // Initialize Summernote
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Feature Image Preview
        $("#featureImage").change(function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#featurePreview').attr('src', e.target.result).css('display', 'block');
                    $('#featurePreviewContainer').removeClass('d-flex').hide();
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Icon Picker Logic
        const popularIcons = [
            'fa-university', 'fa-graduation-cap', 'fa-book', 'fa-flask', 'fa-microscope',
            'fa-atom', 'fa-biohazard', 'fa-brain', 'fa-calculator', 'fa-chart-line',
            'fa-code', 'fa-dna', 'fa-globe', 'fa-history', 'fa-language',
            'fa-balance-scale', 'fa-briefcase', 'fa-gavel', 'fa-heartbeat', 'fa-landmark',
            'fa-palette', 'fa-pencil-ruler', 'fa- stethoscope', 'fa-user-md', 'fa-laptop-code',
            'fa-music', 'fa-film', 'fa-theater-masks', 'fa-vial', 'fa-vials'
        ];

        function populateIconGrid(icons) {
            const iconGrid = $('#iconGrid');
            iconGrid.empty();
            icons.forEach(function(iconClass) {
                const iconHtml = `
                    <div class="col-md-2 col-sm-3 col-4 text-center">
                        <div class="icon-item" data-icon="fas ${iconClass}">
                            <i class="fas ${iconClass}"></i>
                            <small class="d-block">${iconClass.replace('fa-', '')}</small>
                        </div>
                    </div>
                `;
                iconGrid.append(iconHtml);
            });
        }

        $('#selectIconBtn').click(function() {
            $('#iconPickerModal').modal('show');
            populateIconGrid(popularIcons);
        });

        $('#iconSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            const filtered = popularIcons.filter(icon => icon.toLowerCase().includes(searchTerm));
            populateIconGrid(filtered);
        });

        $(document).on('click', '.icon-item', function() {
            const iconClass = $(this).data('icon');
            $('#icon').val(iconClass);
            $('#icon-preview').html(`<i class="${iconClass} fa-3x"></i>`);
            $('#iconPickerModal').modal('hide');
        });
    });
</script>
@endpush
