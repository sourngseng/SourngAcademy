@extends('admin.layouts.master')

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
<style>
    .nav-tabs .nav-link.active {
        font-weight: bold;
        border-top: 3px solid #007bff;
    }
</style>
@endpush

@section('title', 'Add New Department')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-plus-circle mr-2"></i> Add New Department</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary shadow-sm">
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
                    <h3 class="card-title">Department Details</h3>
                </div>
                <form action="{{ route('admin.departments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Side: media -->
                            <div class="col-md-4 text-center">
                                <label>Department Thumbnail (Feature Image)</label>
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
                                @error('feature_image') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Right Side: info -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="faculty_id">Faculty <span class="text-danger">*</span></label>
                                    <select name="faculty_id" id="faculty_id" class="form-control select2 @error('faculty_id') is-invalid @enderror" required>
                                        <option value="">Select Faculty</option>
                                        @foreach($faculties as $faculty)
                                            <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                                {{ $faculty->name }} ({{ $faculty->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('faculty_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <!-- Language Tabs -->
                                <div class="card card-primary card-outline card-outline-tabs">
                                    <div class="card-header p-0 border-bottom-0">
                                        <ul class="nav nav-tabs" id="languageTabs" role="tablist">
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
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="languageTabsContent">
                                            @foreach(config('app.available_locales', ['en' => 'English', 'km' => 'Khmer']) as $locale => $localeConfig)
                                                @php
                                                    $localeName = is_array($localeConfig) ? $localeConfig['name'] : $localeConfig;
                                                @endphp
                                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $locale }}" role="tabpanel" aria-labelledby="{{ $locale }}-tab">
                                                    
                                                    <div class="form-group">
                                                        <label>Name ({{ $localeName }}) <span class="text-danger">*</span></label>
                                                        <input type="text" name="translations[{{ $locale }}][name]" class="form-control" placeholder="Enter name in {{ $localeName }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Description ({{ $localeName }})</label>
                                                        <textarea name="translations[{{ $locale }}][description]" class="form-control summernote" placeholder="Enter description in {{ $localeName }}"></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Mission ({{ $localeName }})</label>
                                                        <textarea name="translations[{{ $locale }}][mission]" class="form-control summernote"></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Vision ({{ $localeName }})</label>
                                                        <textarea name="translations[{{ $locale }}][vision]" class="form-control summernote"></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Goal ({{ $localeName }})</label>
                                                        <textarea name="translations[{{ $locale }}][goal]" class="form-control summernote"></textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->

                                <div class="form-group mt-3">
                                    <label for="slug">Slug <span class="text-muted text-xs">(URL Friendly Name)</span></label>
                                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="e.g. computer-science">
                                    <small class="text-muted">Leave empty to auto-generate from name</small>
                                    @error('slug') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="code">Department Code</label>
                                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}">
                                            @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="head_of_department_id">Head of Department</label>
                                            <select name="head_of_department_id" id="head_of_department_id" class="form-control select2 @error('head_of_department_id') is-invalid @enderror">
                                                <option value="">Select User</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ old('head_of_department_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('head_of_department_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Department Email</label>
                                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="e.g. cs@academy.edu">
                                            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="phone">Department Phone</label>
                                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="e.g. +855 12 345 678">
                                            @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="working_hours">Working Hours</label>
                                            <input type="text" name="working_hours" id="working_hours" class="form-control @error('working_hours') is-invalid @enderror" value="{{ old('working_hours', 'Mon - Fri: 8AM-5PM') }}" placeholder="e.g. Mon - Fri: 8AM-5PM">
                                            @error('working_hours') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-right">
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="fas fa-save mr-1"></i> Save Department
                        </button>
                    </div>
                </form>
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
            height: 150,
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
    });
</script>
@endpush
