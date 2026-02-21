@extends('admin.layouts.master')

@section('title', 'Edit Payment Method - ' . $method->name)

@push('styles')
<style>
    .type-option { cursor: pointer; border: 2px solid #dee2e6; border-radius: 10px; padding: 12px 10px; text-align: center; transition: all 0.2s; }
    .type-option:hover, .type-option.selected { border-color: #007bff; background: #f0f7ff; }
    .type-option i { font-size: 1.6rem; margin-bottom: 5px; display: block; }
    .color-swatch { width: 30px; height: 30px; border-radius: 50%; border: 2px solid #dee2e6; cursor: pointer; display: inline-block; vertical-align: middle; }
    .color-swatch.active { border-color: #343a40; border-width: 3px; }
    #preview-card { border-radius: 12px; border: 2px solid #007bff; max-width: 320px; }
    .preview-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #fff; }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-edit mr-2"></i> Edit Payment Method</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.payment-methods.index', ['campus_id' => $method->campus_id]) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back
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

    <form action="{{ route('admin.payment-methods.update', $method->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <input type="hidden" name="type" id="type_input" value="{{ old('type', $method->type) }}">
        <input type="hidden" name="color" id="color_input" value="{{ old('color', $method->color) }}">

        <div class="row">
            {{-- Left --}}
            <div class="col-md-8">
                <div class="card card-outline card-primary shadow-sm mb-3">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-university mr-2 text-primary"></i> Campus & Type</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Campus</label>
                            <select name="campus_id" class="form-control select2" required>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}" {{ $method->campus_id == $campus->id ? 'selected' : '' }}>
                                        {{ $campus->name_en }} ({{ $campus->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <label class="font-weight-bold">Payment Method Type</label>
                        <div class="row" id="type-grid">
                            @foreach($types as $key => $info)
                            <div class="col-4 col-md-3 col-lg-2 mb-2">
                                <div class="type-option {{ old('type', $method->type) === $key ? 'selected' : '' }}"
                                     data-type="{{ $key }}"
                                     data-icon="{{ $info['icon'] }}"
                                     data-color="{{ $info['color'] }}"
                                     title="{{ $info['label'] }}">
                                    <i class="{{ $info['icon'] }}" style="color: {{ $info['color'] }};"></i>
                                    <small>{{ $info['label'] }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-primary shadow-sm mb-3">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-info-circle mr-2"></i> Details</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Display Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $method->name) }}" required>
                                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Provider / Bank Name</label>
                                    <input type="text" name="provider_name" class="form-control" value="{{ old('provider_name', $method->provider_name) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Number / ID</label>
                                    <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $method->account_number) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Holder Name</label>
                                    <input type="text" name="account_name" class="form-control" value="{{ old('account_name', $method->account_name) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Currency</label>
                                    <select name="currency" class="form-control">
                                        @foreach(['USD'=>'USD ($)', 'KHR'=>'KHR (៛)', 'EUR'=>'EUR (€)', 'GBP'=>'GBP (£)', 'CNY'=>'CNY (¥)', 'THB'=>'THB (฿)'] as $code => $label)
                                            <option value="{{ $code }}" {{ old('currency', $method->currency) === $code ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $method->sort_order) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end pb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1"
                                           {{ old('is_active', $method->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Gateway / Payment Link</label>
                                    <input type="text" name="gateway_link" class="form-control" value="{{ old('gateway_link', $method->gateway_link) }}" placeholder="https://paypal.me/...">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Instructions for Student</label>
                                    <textarea name="instructions" class="form-control" rows="3">{{ old('instructions', $method->instructions) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-secondary shadow-sm mb-3">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-palette mr-2"></i> Appearance</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Icon Class (Font Awesome)</label>
                                    <input type="text" name="icon" id="icon_input" class="form-control" value="{{ old('icon', $method->icon) }}" placeholder="fas fa-university">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Card Color</label>
                                    <div class="d-flex align-items-center flex-wrap mt-1">
                                        @foreach(['#004fa3','#c0392b','#ff6600','#27ae60','#003087','#6772e5','#37517e','#f39c12','#6c3483','#2c3e50','#e63946','#1a6fc4'] as $c)
                                            <span class="color-swatch mr-1 mb-1 {{ $method->color === $c ? 'active' : '' }}" style="background:{{ $c }};" data-color="{{ $c }}" title="{{ $c }}"></span>
                                        @endforeach
                                        <input type="color" id="custom-color" value="{{ old('color', $method->color ?? '#007bff') }}" style="width:30px;height:30px;padding:2px;border:none;cursor:pointer;">
                                    </div>
                                </div>
                            </div>
                            {{-- Logo --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Logo Image</label>
                                    @if($method->logo_image)
                                        <div class="mb-2">
                                            <img src="{{ asset($method->logo_image) }}" style="height:40px;border:1px solid #dee2e6;border-radius:6px;padding:2px;" alt="Logo">
                                            <label class="ml-2 mb-0"><input type="checkbox" name="remove_logo" value="1"> Remove</label>
                                        </div>
                                    @endif
                                    <input type="file" name="logo_image" id="logo_image" class="form-control-file" accept="image/*">
                                </div>
                            </div>
                            {{-- QR --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>QR Code Image</label>
                                    @if($method->qr_image)
                                        <div class="mb-2">
                                            <img src="{{ asset($method->qr_image) }}" style="height:60px;border:1px solid #dee2e6;border-radius:6px;padding:2px;" alt="QR">
                                            <label class="ml-2 mb-0"><input type="checkbox" name="remove_qr" value="1"> Remove</label>
                                        </div>
                                    @endif
                                    <input type="file" name="qr_image" id="qr_image" class="form-control-file" accept="image/*">
                                    <div id="qr-preview-wrap" style="display:none; margin-top:8px;">
                                        <img id="qr-preview" src="" style="max-width:120px;border:2px solid #007bff;border-radius:8px;padding:3px;" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Preview --}}
            <div class="col-md-4">
                <div class="card card-outline card-info mb-3 sticky-top" style="top: 80px;">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-eye mr-2"></i> Live Preview</h3></div>
                    <div class="card-body">
                        <div id="preview-card" class="card p-3">
                            <div class="d-flex align-items-center">
                                <div class="preview-icon mr-3" id="prev-icon-wrap" style="background: {{ $method->getEffectiveColor() }};">
                                    <i id="prev-icon" class="{{ $method->getEffectiveIcon() }}"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold" id="prev-name">{{ $method->name }}</h6>
                                    <small class="text-muted" id="prev-type">{{ $method->getTypeLabel() }}</small>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="small text-muted" id="prev-account">Account: {{ $method->account_number ?: '—' }}</div>
                            <div class="small text-muted" id="prev-holder">Holder: {{ $method->account_name ?: '—' }}</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning btn-block btn-lg">
                            <i class="fas fa-save mr-1"></i> Update Method
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

    $('#type-grid').on('click', '.type-option', function() {
        $('#type-grid .type-option').removeClass('selected');
        $(this).addClass('selected');
        $('#type_input').val($(this).data('type'));
        $('#prev-type').text($(this).find('small').text());
        updateIconPreview($(this).data('icon'), $(this).data('color'));
        $('#color_input').val($(this).data('color'));
    });

    $('#name').on('input', function() { $('#prev-name').text($(this).val() || 'Method Name'); });
    $('[name="account_number"]').on('input', function() { $('#prev-account').text('Account: ' + ($(this).val() || '—')); });
    $('[name="account_name"]').on('input', function() { $('#prev-holder').text('Holder: ' + ($(this).val() || '—')); });
    $('#icon_input').on('input', function() { if ($(this).val().trim()) $('#prev-icon').attr('class', $(this).val().trim()); });

    $('.color-swatch').on('click', function() {
        const c = $(this).data('color');
        $('#color_input').val(c);
        $('#custom-color').val(c);
        updateIconPreview(null, c);
        $('.color-swatch').removeClass('active');
        $(this).addClass('active');
    });
    $('#custom-color').on('input', function() {
        $('#color_input').val($(this).val());
        updateIconPreview(null, $(this).val());
        $('.color-swatch').removeClass('active');
    });

    $('#qr_image').on('change', function() {
        if (this.files && this.files[0]) {
            const r = new FileReader();
            r.onload = e => { $('#qr-preview').attr('src', e.target.result); $('#qr-preview-wrap').show(); };
            r.readAsDataURL(this.files[0]);
        }
    });

    function updateIconPreview(icon, color) {
        if (color) { $('#prev-icon-wrap').css('background', color); $('#preview-card').css('border-color', color); }
        if (icon)  { $('#prev-icon').attr('class', icon); }
    }
});
</script>
@endpush
