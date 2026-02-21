@extends('admin.layouts.master')

@section('title', 'Manage Payment Methods')

@push('styles')
<style>
    .method-card {
        border-radius: 12px;
        border: 2px solid #e9ecef;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .method-card:hover {
        border-color: #007bff;
        box-shadow: 0 6px 20px rgba(0,123,255,0.12);
        transform: translateY(-2px);
    }
    .method-card .type-badge {
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 20px;
    }
    .method-card .method-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #fff;
        flex-shrink: 0;
    }
    .method-card.inactive {
        opacity: 0.55;
        border-style: dashed;
    }
    .drag-handle { cursor: grab; color: #adb5bd; }
    .drag-handle:active { cursor: grabbing; }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-credit-card mr-2"></i> Payment Methods</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.payment-methods.create', ['campus_id' => $selectedCampus?->id]) }}"
                   class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle mr-1"></i> Add Payment Method
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
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('error') }}
        </div>
    @endif

    {{-- Campus Filter --}}
    <div class="card card-outline card-primary shadow-sm mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.payment-methods.index') }}" class="form-inline">
                <label class="mr-2 font-weight-bold"><i class="fas fa-university mr-1"></i> Campus:</label>
                <select name="campus_id" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">-- Select Campus --</option>
                    @foreach($campuses as $campus)
                        <option value="{{ $campus->id }}" {{ $selectedCampus?->id == $campus->id ? 'selected' : '' }}>
                            {{ $campus->name_en }} ({{ $campus->code }})
                        </option>
                    @endforeach
                </select>
                <a href="{{ route('admin.payment-methods.create', ['campus_id' => $selectedCampus?->id]) }}"
                   class="btn btn-success ml-2">
                    <i class="fas fa-plus mr-1"></i> Add Method
                </a>
            </form>
        </div>
    </div>

    @if($selectedCampus)
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="card-title">
                    <i class="fas fa-university mr-2 text-primary"></i>
                    <strong>{{ $selectedCampus->name_en }}</strong> — Payment Methods
                    <span class="badge badge-primary ml-2">{{ $methods->count() }} total</span>
                </h3>
                <div class="card-tools">
                    <small class="text-muted"><i class="fas fa-arrows-alt mr-1"></i> Drag rows to reorder</small>
                </div>
            </div>
            <div class="card-body">

                @if($methods->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-credit-card fa-4x text-muted mb-3 d-block"></i>
                        <h5 class="text-muted">No payment methods configured yet</h5>
                        <p class="text-muted">Add payment methods (banks, PayPal, QR) so students know how to pay their invoices.</p>
                        <a href="{{ route('admin.payment-methods.create', ['campus_id' => $selectedCampus->id]) }}"
                           class="btn btn-primary px-4">
                            <i class="fas fa-plus mr-1"></i> Add First Method
                        </a>
                    </div>
                @else
                    <div class="row" id="methods-sortable">
                        @foreach($methods as $method)
                        <div class="col-md-6 col-lg-4 mb-3" data-id="{{ $method->id }}">
                            <div class="method-card card mb-0 {{ !$method->is_active ? 'inactive' : '' }}">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-grip-vertical drag-handle mr-2 mt-1"></i>
                                        <div class="method-icon-wrap mr-3" style="background: {{ $method->getEffectiveColor() }};">
                                            @if($method->logo_image)
                                                <img src="{{ asset($method->logo_image) }}" style="width:36px;height:36px;object-fit:contain;" alt="">
                                            @else
                                                <i class="{{ $method->getEffectiveIcon() }}"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 min-width-0">
                                            <div class="d-flex align-items-center mb-1">
                                                <h6 class="mb-0 mr-2 font-weight-bold text-truncate">{{ $method->name }}</h6>
                                                @if(!$method->is_active)
                                                    <span class="badge badge-secondary type-badge">Inactive</span>
                                                @endif
                                            </div>
                                            <span class="badge type-badge" style="background: {{ $method->getEffectiveColor() }}; color: #fff;">
                                                {{ $method->getTypeLabel() }}
                                            </span>
                                            @if($method->account_number)
                                                <div class="text-muted small mt-1">
                                                    <i class="fas fa-hashtag mr-1"></i> {{ $method->account_number }}
                                                </div>
                                            @endif
                                            @if($method->account_name)
                                                <div class="text-muted small">
                                                    <i class="fas fa-user mr-1"></i> {{ $method->account_name }}
                                                </div>
                                            @endif
                                            <div class="text-muted small">
                                                <i class="fas fa-dollar-sign mr-1"></i> {{ $method->currency }}
                                            </div>
                                        </div>
                                        @if($method->qr_image)
                                            <div class="ml-2">
                                                <img src="{{ asset($method->qr_image) }}" style="width:50px;height:50px;object-fit:contain;border:1px solid #dee2e6;border-radius:6px;" alt="QR">
                                            </div>
                                        @endif
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-active"
                                                   id="toggle-{{ $method->id }}"
                                                   data-id="{{ $method->id }}"
                                                   {{ $method->is_active ? 'checked' : '' }}>
                                            <label class="custom-control-label text-muted small" for="toggle-{{ $method->id }}">
                                                {{ $method->is_active ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.payment-methods.edit', $method->id) }}"
                                               class="btn btn-xs btn-outline-primary mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.payment-methods.destroy', $method->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-outline-danger"
                                                        onclick="return confirm('Delete this payment method?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-university fa-4x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Select a campus above to manage its payment methods.</h5>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function () {
    // Toggle active
    $(document).on('change', '.toggle-active', function () {
        const id    = $(this).data('id');
        const label = $(this).closest('.custom-control').find('label');
        $.post(`{{ url('admin/payment-methods') }}/${id}/toggle`, {_token: '{{ csrf_token() }}'}, function (res) {
            label.text(res.is_active ? 'Active' : 'Inactive');
        });
    });

    // Sortable
    const el = document.getElementById('methods-sortable');
    if (el) {
        Sortable.create(el, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function () {
                const order = [...el.querySelectorAll('[data-id]')].map(el => el.dataset.id);
                $.post('{{ route("admin.payment-methods.reorder") }}', {_token: '{{ csrf_token() }}', order}, () => {});
            }
        });
    }
});
</script>
@endpush
