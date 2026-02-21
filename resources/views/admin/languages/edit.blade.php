@extends('admin.layouts.master')

@section('title', __('Edit Translations') . ' - ' . $languageName)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('Edit Translations') }} - {{ $languageName }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.languages.index') }}">{{ __('Languages') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('admin.languages.update', $locale) }}" method="POST" id="translationForm">
            @csrf
            @method('PUT')
            
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ __('Translation Keys & Values') }}</h3>
                        <div>
                            <input type="text" id="searchKeys" class="form-control form-control-sm" placeholder="{{ __('Search keys...') }}" style="width: 300px;">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-bordered table-striped table-hover" id="translationsTable">
                            <thead style="position: sticky; top: 0; background: white; z-index: 10;">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="35%">{{ __('Translation Key') }}</th>
                                    <th width="60%">{{ __('Value') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($translations as $key => $value)
                                    <tr class="translation-row" data-key="{{ strtolower($key) }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <code>{{ $key }}</code>
                                            <input type="hidden" name="translations[{{ $key }}]_key" value="{{ $key }}">
                                        </td>
                                        <td>
                                            @if(strlen($value) > 100)
                                                <textarea class="form-control" 
                                                          name="translations[{{ $key }}]" 
                                                          rows="3">{{ $value }}</textarea>
                                            @else
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="translations[{{ $key }}]" 
                                                       value="{{ $value }}">
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('Save Translations') }}
                    </button>
                    <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Languages') }}
                    </a>
                    <span class="float-right text-muted">
                        <strong>{{ count($translations) }}</strong> {{ __('translation keys') }}
                    </span>
                </div>
            </div>
        </form>

        <!-- Statistics Card -->
        <div class="row">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-key"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('Total Keys') }}</span>
                        <span class="info-box-number">{{ count($translations) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-language"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('Language') }}</span>
                        <span class="info-box-number">{{ strtoupper($locale) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-file-code"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('File') }}</span>
                        <span class="info-box-number" style="font-size: 14px;">{{ $locale }}.json</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .translation-row.hidden {
        display: none;
    }
    
    #translationsTable tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    #translationsTable code {
        color: #e83e8c;
        font-weight: 600;
    }
    
    #translationsTable textarea,
    #translationsTable input[type="text"] {
        font-size: 14px;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchKeys').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        
        $('.translation-row').each(function() {
            var keyText = $(this).data('key');
            var valueText = $(this).find('input[type="text"], textarea').val().toLowerCase();
            
            if (keyText.includes(searchTerm) || valueText.includes(searchTerm)) {
                $(this).removeClass('hidden');
            } else {
                $(this).addClass('hidden');
            }
        });
    });
    
    // Auto-save notification
    let hasChanges = false;
    
    $('input[type="text"], textarea').on('change', function() {
        hasChanges = true;
    });
    
    $(window).on('beforeunload', function() {
        if (hasChanges) {
            return '{{ __("You have unsaved changes. Are you sure you want to leave?") }}';
        }
    });
    
    $('#translationForm').on('submit', function() {
        hasChanges = false;
    });
});
</script>
@endpush
