@extends('admin.layouts.master')

@section('title', 'Add Payment Method')

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
                <h1><i class="fas fa-plus-circle mr-2"></i> Add Payment Method</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.payment-methods.index', ['campus_id' => $selectedCampusId]) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="campus_id" id="campus_id_input" value="{{ old('campus_id', $selectedCampusId) }}">
        <input type="hidden" name="type" id="type_input" value="{{ old('type', 'bank_transfer') }}">
        <input type="hidden" name="color" id="color_input" value="{{ old('color', '#007bff') }}">

        <div class="row">
            {{-- Left: Config --}}
            <div class="col-md-8">

                {{-- Campus  --}}
                <div class="card card-outline card-primary shadow-sm mb-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-university mr-2 text-primary"></i> Campus & Type</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="campus_sel">Campus <span class="text-danger">*</span></label>
                            <select name="campus_id" id="campus_sel" class="form-control select2" onchange="document.getElementById('campus_id_input').value=this.value;" required>
                                <option value="">-- Select Campus --</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}" {{ old('campus_id', $selectedCampusId) == $campus->id ? 'selected' : '' }}>
                                        {{ $campus->name_en }} ({{ $campus->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <label class="font-weight-bold">Payment Method Type <span class="text-danger">*</span></label>
                        <div class="row" id="type-grid">
                            @foreach($types as $key => $info)
                            <div class="col-4 col-md-3 col-lg-2 mb-2">
                                <div class="type-option {{ old('type','bank_transfer') === $key ? 'selected' : '' }}"
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

                {{-- Basic Details --}}
                <div class="card card-outline card-primary shadow-sm mb-3">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-info-circle mr-2"></i> Details</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Display Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" required placeholder="e.g. ABA Bank Transfer">
                                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Provider / Bank Name</label>
                                    <input type="text" name="provider_name" class="form-control" value="{{ old('provider_name') }}" placeholder="e.g. Advanced Bank of Asia">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Number / ID</label>
                                    <input type="text" name="account_number" class="form-control" value="{{ old('account_number') }}" placeholder="e.g. 000 123 456">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Holder Name</label>
                                    <input type="text" name="account_name" class="form-control" value="{{ old('account_name') }}" placeholder="e.g. SOURNG Academy Co., Ltd.">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Currency</label>
                                    <select name="currency" class="form-control">
                                        @foreach(['USD'=>'USD ($)', 'KHR'=>'KHR (៛)', 'EUR'=>'EUR (€)', 'GBP'=>'GBP (£)', 'CNY'=>'CNY (¥)', 'THB'=>'THB (฿)'] as $code => $label)
                                            <option value="{{ $code }}" {{ old('currency', 'USD') === $code ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end pb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Gateway / Payment Link <small class="text-muted">(PayPal email, external URL)</small></label>
                                    <input type="text" name="gateway_link" class="form-control" value="{{ old('gateway_link') }}" placeholder="https://paypal.me/yourhandle or mailto:payment@email.com">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Instructions for Student</label>
                                    <textarea name="instructions" class="form-control" rows="3" placeholder="e.g. Please include your invoice number in the transfer memo.">{{ old('instructions') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Appearance --}}
                <div class="card card-outline card-secondary shadow-sm mb-3">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-palette mr-2"></i> Appearance</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Icon Class <small class="text-muted">(Font Awesome)</small></label>
                                    <input type="text" name="icon" id="icon_input" class="form-control" value="{{ old('icon') }}" placeholder="fas fa-university">
                                    <small class="text-muted">Leave blank to use type default. <a href="https://fontawesome.com/icons" target="_blank">Browse icons →</a></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Card Color</label>
                                    <div class="d-flex align-items-center flex-wrap gap-2 mt-1">
                                        @foreach(['#004fa3','#c0392b','#ff6600','#27ae60','#003087','#6772e5','#37517e','#f39c12','#6c3483','#2c3e50','#e63946','#1a6fc4'] as $c)
                                            <span class="color-swatch mr-1 mb-1" style="background:{{ $c }};" data-color="{{ $c }}" title="{{ $c }}"></span>
                                        @endforeach
                                        <input type="color" id="custom-color" value="#007bff" style="width:30px;height:30px;padding:2px;border:none;cursor:pointer;" title="Custom color">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Logo / Icon Image <small class="text-muted">(optional, replaces icon)</small></label>
                                    <input type="file" name="logo_image" id="logo_image" class="form-control-file" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>QR Code Image</label>
                                    <input type="file" name="qr_image" id="qr_image" class="form-control-file" accept="image/*">
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
                                <div class="preview-icon mr-3" id="prev-icon-wrap" style="background:#007bff;">
                                    <i id="prev-icon" class="fas fa-university"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold" id="prev-name">Method Name</h6>
                                    <small class="text-muted" id="prev-type">Type</small>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="small text-muted" id="prev-account">Account: —</div>
                            <div class="small text-muted" id="prev-holder">Holder: —</div>
                        </div>

                        <div class="mt-3 text-center">
                            <div id="qr-preview-wrap" style="display:none;">
                                <img id="qr-preview" src="" style="max-width:140px;border:2px solid #007bff;border-radius:8px;padding:4px;" alt="QR Preview">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="fas fa-save mr-1"></i> Save Payment Method
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
    // Initialize select2
    $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

    // Type selection
    $('#type-grid').on('click', '.type-option', function() {
        $('#type-grid .type-option').removeClass('selected');
        $(this).addClass('selected');
        const type  = $(this).data('type');
        const icon  = $(this).data('icon');
        const color = $(this).data('color');
        $('#type_input').val(type);
        $('#prev-type').text($(this).find('small').text());
        updateIconPreview(icon, color);
        // Also update color input
        $('#color_input').val(color);
        highlightSwatch(color);
    });

    // Name → preview
    $('#name').on('input', function() { $('#prev-name').text($(this).val() || 'Method Name'); });

    // Account
    $('[name="account_number"]').on('input', function() { $('#prev-account').text('Account: ' + ($(this).val() || '—')); });
    $('[name="account_name"]').on('input', function() { $('#prev-holder').text('Holder: ' + ($(this).val() || '—')); });

    // Icon input
    $('#icon_input').on('input', function() {
        const v = $(this).val().trim();
        if (v) { $('#prev-icon').attr('class', v); }
    });

    // Color swatches
    $('.color-swatch').on('click', function() {
        const color = $(this).data('color');
        $('#color_input').val(color);
        $('#custom-color').val(color);
        updateIconPreview(null, color);
        highlightSwatch(color);
    });
    $('#custom-color').on('input', function() {
        const color = $(this).val();
        $('#color_input').val(color);
        updateIconPreview(null, color);
        highlightSwatch(null);
    });

    // QR preview
    $('#qr_image').on('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                $('#qr-preview').attr('src', e.target.result);
                $('#qr-preview-wrap').show();
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    function updateIconPreview(icon, color) {
        if (color) {
            $('#prev-icon-wrap').css('background', color);
            $('#preview-card').css('border-color', color);
        }
        if (icon) { $('#prev-icon').attr('class', icon); }
    }

    function highlightSwatch(color) {
        $('.color-swatch').removeClass('active');
        if (color) {
            $(`.color-swatch[data-color="${color}"]`).addClass('active');
        }
    }

    // Init preview with default
    const defaultType = $('#type-grid .type-option.selected');
    if (defaultType.length) {
        $('#prev-type').text(defaultType.find('small').text());
        updateIconPreview(defaultType.data('icon'), defaultType.data('color'));
    }
});
</script>
@endpush
