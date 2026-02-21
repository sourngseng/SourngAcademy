@extends('admin.layouts.master')

@section('title', 'Edit Campus - ' . $campus->name_en)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .gallery-item { position: relative; margin-bottom: 20px; }
    .gallery-item img { height: 120px; width: 100%; object-fit: cover; border-radius: 8px; transition: opacity 0.3s; }
    .gallery-item.to-delete img { opacity: 0.3; filter: grayscale(1); }
    .delete-overlay { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); display:none; color:#dc3545; font-weight:bold; }
    .gallery-item.to-delete .delete-overlay { display: block; }
    .btn-delete-img { position: absolute; top: 5px; right: 5px; z-index: 10; }
    #map { height: 400px; width: 100%; border-radius: 8px; z-index: 1; }
    .lang-tab-nav .nav-link.active { background: #007bff; color: #fff; border-color: #007bff; }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-edit mr-2"></i> Edit Campus</h1>
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
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif
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
                    <h3 class="card-title">
                        <i class="fas fa-university mr-2 text-primary"></i>
                        Campus Details: <strong>{{ $campus->name_en }}</strong>
                    </h3>
                </div>
                <form action="{{ route('admin.campuses.update', $campus->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        {{-- ── Row 1: Feature Image + Code / Short Name ──────────────────── --}}
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <label class="d-block font-weight-bold mb-2">Feature Image</label>
                                <div class="position-relative mb-2" style="cursor:pointer;" onclick="document.getElementById('featureImage').click();">
                                    @if($campus->feature_image)
                                        <img id="featurePreview" src="{{ asset($campus->feature_image) }}" class="img-fluid rounded shadow-sm border w-100" style="height:200px; object-fit:cover;">
                                    @else
                                        <div id="featurePreviewContainer" class="border rounded shadow-sm bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                                            <div class="text-center text-muted">
                                                <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                                                <p class="mb-0">Browse Feature Image</p>
                                                <small>JPG, PNG, GIF (Max 2MB)</small>
                                            </div>
                                        </div>
                                        <img id="featurePreview" src="" class="img-fluid rounded shadow-sm border w-100" style="height:200px; object-fit:cover; display:none;">
                                    @endif
                                </div>
                                <input type="file" name="feature_image" class="d-none" id="featureImage" accept="image/*">
                                <small class="text-muted">Click image to change</small>
                            </div>

                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="code">Campus Code <span class="text-danger">*</span></label>
                                            <input type="text" name="code" id="code"
                                                   class="form-control @error('code') is-invalid @enderror"
                                                   value="{{ old('code', $campus->code) }}" required>
                                            @error('code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="short_name">Short Name</label>
                                            <input type="text" name="short_name" id="short_name" class="form-control"
                                                   value="{{ old('short_name', $campus->short_name) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug">Slug / URL</label>
                                            <input type="text" name="slug" id="slug" class="form-control"
                                                   value="{{ old('slug', $campus->slug) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="email">Campus Email</label>
                                            <input type="email" name="email" id="email" class="form-control"
                                                   value="{{ old('email', $campus->email) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" id="phone" class="form-control"
                                                   value="{{ old('phone', $campus->phone) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mt-1">
                                                <input type="checkbox" name="is_active" class="custom-control-input"
                                                       id="isActiveSwitch" value="1" {{ $campus->is_active ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="isActiveSwitch">Campus Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- ── Multilingual Tabs ──────────────────────────────────────────── --}}
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
                                        @php
                                            $required = ($code === config('app.fallback_locale', 'en'));
                                            $trans     = $campus->translations[$code] ?? [];
                                            // Fallback: use name_en / name_km for legacy data
                                            $currentName = old("name_{$code}", $trans['name'] ?? ($code === 'en' ? $campus->name_en : ($code === 'km' ? $campus->name_km : '')));
                                            $currentDesc = old("description_{$code}", $trans['description'] ?? ($code === 'en' || $code === 'km' ? $campus->description : ''));
                                            $currentAddr = old("address_{$code}", $trans['address'] ?? ($code === 'en' || $code === 'km' ? $campus->address : ''));
                                        @endphp
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
                                                               value="{{ $currentName }}"
                                                               @if($required) required @endif>
                                                        @error('name_'.$code)<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="description_{{ $code }}">Description ({{ strtoupper($code) }})</label>
                                                        <textarea name="description_{{ $code }}"
                                                                  id="description_{{ $code }}"
                                                                  class="form-control"
                                                                  rows="3">{{ $currentDesc }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="address_{{ $code }}">Address ({{ strtoupper($code) }})</label>
                                                        <textarea name="address_{{ $code }}"
                                                                  id="address_{{ $code }}"
                                                                  class="form-control"
                                                                  rows="2">{{ $currentAddr }}</textarea>
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
                                <p class="text-muted small mb-3">Upload the QR code image for this campus. Appears on student invoices.</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="border rounded p-3 mb-2" style="min-height:160px; display:flex; align-items:center; justify-content:center; background:#f8f9fa; cursor:pointer;" onclick="document.getElementById('qrImageInput').click();">
                                    @if($campus->payment_qr_image)
                                        <img id="qrPreview" src="{{ asset($campus->payment_qr_image) }}" style="max-height:150px; max-width:100%; object-fit:contain;" alt="QR Code">
                                    @else
                                        <div id="qrPlaceholder" class="text-center text-muted">
                                            <i class="fas fa-qrcode fa-4x mb-2"></i>
                                            <p class="mb-0 small">Click to upload QR</p>
                                        </div>
                                        <img id="qrPreview" src="" style="max-height:150px; max-width:100%; object-fit:contain; display:none;" alt="QR Code">
                                    @endif
                                </div>
                                <input type="file" name="payment_qr_image" id="qrImageInput" class="d-none" accept="image/*">
                                <small class="text-muted">PNG, JPG. Click to change.</small>
                            </div>
                            <div class="col-md-9 d-flex align-items-center">
                                <div>
                                    <ul class="text-muted small pl-3">
                                        <li>QR code appears on invoice Payment Methods section</li>
                                        <li>Students scan to pay via ABA, ACLEDA, Wing etc.</li>
                                        <li>Each campus can have its own QR/account</li>
                                    </ul>
                                    @if($campus->payment_qr_image)
                                        <div class="mt-2">
                                            <span class="badge badge-success"><i class="fas fa-check mr-1"></i> QR code set</span>
                                            <label class="ml-2 mb-0">
                                                <input type="checkbox" name="remove_payment_qr" value="1"> Remove QR code
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- ── Map Section ─────────────────────────────────────────────────── --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><i class="fas fa-map-marker-alt text-danger mr-2"></i> Campus Location (Pin on Map)</label>
                                    <p class="text-muted small">Click on the map to set or update the campus location.</p>
                                    <div id="map" class="shadow-sm border"></div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <input type="text" name="latitude" id="latitude" class="form-control bg-light" placeholder="Latitude" readonly value="{{ old('latitude', $campus->latitude) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="longitude" id="longitude" class="form-control bg-light" placeholder="Longitude" readonly value="{{ old('longitude', $campus->longitude) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- ── Existing Gallery ─────────────────────────────────────────────── --}}
                        <div class="form-group">
                            <label><i class="fas fa-th mr-2 text-info"></i> Current Gallery</label>
                            @if($campus->images->count() > 0)
                                <p class="small text-muted mb-3">Click the red <i class="fas fa-times"></i> to mark an image for deletion upon saving.</p>
                                <div class="row">
                                    @foreach($campus->images as $image)
                                        <div class="col-md-2 gallery-item" id="gallery-img-{{ $image->id }}">
                                            <button type="button" class="btn btn-danger btn-xs btn-delete-img" onclick="toggleImageDelete({{ $image->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <img src="{{ asset($image->image_path) }}" class="shadow-sm border">
                                            <div class="delete-overlay"><i class="fas fa-trash-alt fa-2x"></i></div>
                                            <input type="hidden" name="delete_images[]" id="del-input-{{ $image->id }}" value="" disabled>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted italic">No images in gallery yet.</p>
                            @endif
                        </div>

                        {{-- ── Add to Gallery ────────────────────────────────────────────── --}}
                        <div class="form-group">
                            <label><i class="fas fa-plus-circle mr-2 text-success"></i> Add New Images to Gallery</label>
                            <div class="custom-file">
                                <input type="file" name="gallery_images[]" class="custom-file-input" id="galleryImages" multiple accept="image/*">
                                <label class="custom-file-label" for="galleryImages">Select Images</label>
                            </div>
                            <div id="galleryPreview" class="row mt-4"></div>
                        </div>

                    </div>{{-- /card-body --}}

                    <div class="card-footer bg-light text-right">
                        <a href="{{ route('admin.campuses.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                            <i class="fas fa-save mr-1"></i> Update Campus
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
    function toggleImageDelete(id) {
        const item  = $('#gallery-img-' + id);
        const input = $('#del-input-' + id);
        if (item.hasClass('to-delete')) {
            item.removeClass('to-delete');
            input.attr('disabled', true).val('');
        } else {
            item.addClass('to-delete');
            input.attr('disabled', false).val(id);
        }
    }

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

        // ── Map
        var map = L.map('map');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker;
        var lat = "{{ old('latitude', $campus->latitude) }}";
        var lng = "{{ old('longitude', $campus->longitude) }}";

        if (lat && lng) {
            map.setView([lat, lng], 15);
            marker = L.marker([lat, lng]).addTo(map);
        } else {
            map.setView([11.91, 105.65], 13);
        }

        map.on('click', function(e) {
            if (marker) { marker.setLatLng(e.latlng); } else { marker = L.marker(e.latlng).addTo(map); }
            $('#latitude').val(e.latlng.lat);
            $('#longitude').val(e.latlng.lng);
        });
    });
</script>
@endpush
