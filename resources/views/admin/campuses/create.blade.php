@extends('admin.layouts.master')

@section('title', 'Add New Campus')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 400px; width: 100%; border-radius: 8px; z-index: 1; }
    .lang-tab-nav .nav-link { border-radius: 6px 6px 0 0; }
    .lang-tab-nav .nav-link.active { background: #007bff; color: #fff; border-color: #007bff; }
    .lang-tab-nav .nav-link .fi { font-size: 1.1em; }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-plus-circle mr-2"></i> Add New Campus</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.campuses.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline shadow">
                <div class="card-header bg-white">
                    <h3 class="card-title"><i class="fas fa-university mr-2 text-primary"></i> Campus Details &amp; Media</h3>
                </div>
                <form action="{{ route('admin.campuses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        {{-- ── Row 1: Feature Image + Code / Short Name ──────────────────── --}}
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <label class="d-block font-weight-bold mb-2">Feature Image</label>
                                <div class="position-relative mb-2" style="cursor:pointer;" onclick="document.getElementById('featureImage').click();">
                                    <div id="featurePreviewContainer" class="border rounded shadow-sm bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                                            <p class="mb-0">Browse Feature Image</p>
                                            <small>JPG, PNG, GIF (Max 2MB)</small>
                                        </div>
                                    </div>
                                    <img id="featurePreview" src="" class="img-fluid rounded shadow-sm border w-100" style="height:200px; object-fit:cover; display:none;">
                                </div>
                                <input type="file" name="feature_image" class="d-none" id="featureImage" accept="image/*">
                                <small class="text-muted">Click box to upload image</small>
                            </div>

                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="code">Campus Code <span class="text-danger">*</span></label>
                                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                                                   value="{{ old('code') }}" required placeholder="e.g. SR01">
                                            @error('code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="short_name">Short Name</label>
                                            <input type="text" name="short_name" id="short_name" class="form-control"
                                                   value="{{ old('short_name') }}" placeholder="e.g. Siem Reap">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug">Slug / URL</label>
                                            <input type="text" name="slug" id="slug" class="form-control"
                                                   value="{{ old('slug') }}" placeholder="auto-generated if blank">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="email">Campus Email</label>
                                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mt-1">
                                                <input type="checkbox" name="is_active" class="custom-control-input" id="isActiveSwitch" value="1" checked>
                                                <label class="custom-control-label" for="isActiveSwitch">Campus Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- ── Multilingual Tabs (Name, Description, Address) ─────────────── --}}
                        <div class="card card-outline card-primary mb-3">
                            <div class="card-header p-0 border-bottom-0">
                                <h3 class="card-title p-3"><i class="fas fa-language mr-2 text-primary"></i> Multilingual Content</h3>
                                <ul class="nav nav-tabs lang-tab-nav ml-auto" id="langTabs" role="tablist">
                                    @foreach($languages as $code => $lang)
                                        <li class="nav-item">
                                            <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                               id="tab-{{ $code }}-tab"
                                               data-toggle="pill"
                                               href="#tab-{{ $code }}"
                                               role="tab">
                                                {!! $lang['flag'] ?? '' !!} {{ $lang['name'] }}
                                                @if($code === config('app.fallback_locale', 'en'))
                                                    <span class="badge badge-danger badge-sm ml-1">Required</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="langTabContent">
                                    @foreach($languages as $code => $lang)
                                        @php $required = ($code === config('app.fallback_locale', 'en')); @endphp
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                             id="tab-{{ $code }}"
                                             role="tabpanel">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="name_{{ $code }}">
                                                            Campus Name ({{ strtoupper($code) }})
                                                            @if($required) <span class="text-danger">*</span> @endif
                                                        </label>
                                                        <input type="text"
                                                               name="name_{{ $code }}"
                                                               id="name_{{ $code }}"
                                                               class="form-control @error('name_'.$code) is-invalid @enderror"
                                                               value="{{ old('name_'.$code) }}"
                                                               @if($required) required @endif
                                                               placeholder="{{ $lang['name'] }} name of campus">
                                                        @error('name_'.$code)<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="description_{{ $code }}">Description ({{ strtoupper($code) }})</label>
                                                        <textarea name="description_{{ $code }}"
                                                                  id="description_{{ $code }}"
                                                                  class="form-control"
                                                                  rows="3"
                                                                  placeholder="Campus description in {{ $lang['name'] }}">{{ old('description_'.$code) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="address_{{ $code }}">Address ({{ strtoupper($code) }})</label>
                                                        <textarea name="address_{{ $code }}"
                                                                  id="address_{{ $code }}"
                                                                  class="form-control"
                                                                  rows="2"
                                                                  placeholder="Full address in {{ $lang['name'] }}">{{ old('address_'.$code) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- ── Payment QR Section ─────────────────────────────────────────── --}}
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-2"><i class="fas fa-qrcode text-success mr-2"></i> Payment QR Code</h5>
                                <p class="text-muted small mb-3">Upload the QR code image for this campus (e.g. KHQR, ABA Pay, Wing). This will appear on invoices.</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="border rounded p-3 mb-2" style="min-height:160px; display:flex; align-items:center; justify-content:center; background:#f8f9fa; cursor:pointer;" onclick="document.getElementById('qrImageInput').click();">
                                    <div id="qrPlaceholder" class="text-center text-muted">
                                        <i class="fas fa-qrcode fa-4x mb-2"></i>
                                        <p class="mb-0 small">Click to upload QR</p>
                                    </div>
                                    <img id="qrPreview" src="" style="max-height:150px; max-width:100%; object-fit:contain; display:none;" alt="QR Code">
                                </div>
                                <input type="file" name="payment_qr_image" id="qrImageInput" class="d-none" accept="image/*">
                                <small class="text-muted">PNG recommended</small>
                            </div>
                            <div class="col-md-9 d-flex align-items-center">
                                <ul class="text-muted small pl-3">
                                    <li>QR code will appear on invoices for this campus</li>
                                    <li>Students scan to pay via ABA, ACLEDA, Wing etc.</li>
                                    <li>Each campus can have its own QR/account</li>
                                </ul>
                            </div>
                        </div>

                        <hr>

                        {{-- ── Map Section ─────────────────────────────────────────────────── --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><i class="fas fa-map-marker-alt text-danger mr-2"></i> Campus Location (Pin on Map)</label>
                                    <p class="text-muted small">Click on the map to set the campus location.</p>
                                    <div id="map" class="shadow-sm border"></div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <input type="text" name="latitude" id="latitude" class="form-control bg-light" placeholder="Latitude" readonly value="{{ old('latitude') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="longitude" id="longitude" class="form-control bg-light" placeholder="Longitude" readonly value="{{ old('longitude') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- ── Gallery Section ──────────────────────────────────────────────── --}}
                        <div class="form-group">
                            <label><i class="fas fa-images mr-2 text-info"></i> Campus Image Gallery</label>
                            <div class="custom-file">
                                <input type="file" name="gallery_images[]" class="custom-file-input" id="galleryImages" multiple accept="image/*">
                                <label class="custom-file-label" for="galleryImages">Select Multiple Images</label>
                            </div>
                            <div id="galleryPreview" class="row mt-4"></div>
                        </div>

                    </div>{{-- /card-body --}}

                    <div class="card-footer bg-light text-right">
                        <a href="{{ route('admin.campuses.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                            <i class="fas fa-save mr-1"></i> Save Campus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
$(document).ready(function () {
    // ── Feature Image Preview
    $("#featureImage").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#featurePreview').attr('src', e.target.result).show();
                $('#featurePreviewContainer').removeClass('d-flex').hide();
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // ── QR Image Preview
    $("#qrImageInput").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#qrPreview').attr('src', e.target.result).show();
                $('#qrPlaceholder').hide();
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // ── Gallery Preview
    $("#galleryImages").change(function () {
        $('#galleryPreview').html('');
        let files = this.files;
        for (let i = 0; i < files.length; i++) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#galleryPreview').append(`
                    <div class="col-md-2 mb-3">
                        <img src="${e.target.result}" class="img-fluid rounded border shadow-sm" style="height:100px; width:100%; object-fit:cover;">
                    </div>
                `);
            };
            reader.readAsDataURL(files[i]);
        }
        $(this).next('.custom-file-label').html(files.length + ' images selected');
    });

    // ── Auto-slug from English name
    $('#name_{{ config("app.fallback_locale","en") }}').on('input', function() {
        let slug = $(this).val().toLowerCase().replace(/[^a-z0-9\s-]/g,'').trim().replace(/\s+/g,'-');
        if (!$('#slug').data('manual')) {
            $('#slug').val(slug);
        }
    });
    $('#slug').on('input', function() { $(this).data('manual', true); });

    // ── Map Initialization
    var map = L.map('map').setView([11.91, 105.65], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker;
    var oldLat = "{{ old('latitude') }}";
    var oldLng = "{{ old('longitude') }}";
    if (oldLat && oldLng) {
        marker = L.marker([oldLat, oldLng]).addTo(map);
        map.setView([oldLat, oldLng], 15);
    }

    map.on('click', function(e) {
        if (marker) { marker.setLatLng(e.latlng); } else { marker = L.marker(e.latlng).addTo(map); }
        $('#latitude').val(e.latlng.lat);
        $('#longitude').val(e.latlng.lng);
    });
});
</script>
@endpush
