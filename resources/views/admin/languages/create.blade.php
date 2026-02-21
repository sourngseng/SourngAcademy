@extends('admin.layouts.master')

@section('title', __('Add New Language'))

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('Add New Language') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.languages.index') }}">{{ __('Languages') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Add New') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.languages.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Language Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="locale">{{ __('Language Code') }} <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('locale') is-invalid @enderror" 
                                       id="locale" 
                                       name="locale" 
                                       value="{{ old('locale') }}"
                                       placeholder="e.g., es, de, ja, zh"
                                       required>
                                <small class="text-muted">{{ __('ISO 639-1 language code (2 letters)') }}</small>
                                @error('locale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name">{{ __('Language Name') }} <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       placeholder="e.g., Spanish, German, Japanese"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="flag">{{ __('Flag Emoji') }}</label>
                                <input type="text" 
                                       class="form-control @error('flag') is-invalid @enderror" 
                                       id="flag" 
                                       name="flag" 
                                       value="{{ old('flag') }}"
                                       placeholder="e.g., 🇪🇸 🇩🇪 🇯🇵">
                                <small class="text-muted">{{ __('Optional: Add a flag emoji for visual identification') }}</small>
                                @error('flag')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('Create Language') }}
                            </button>
                            <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h5><i class="icon fas fa-info-circle"></i> {{ __('How it works') }}</h5>
                                <p>{{ __('The new language will be created based on the English template.') }}</p>
                                <p>{{ __('All translation keys will be copied with English values, which you can then edit.') }}</p>
                            </div>

                            <h5>{{ __('Template Preview') }}</h5>
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('Total Translation Keys') }}</span>
                                    <span class="info-box-number">{{ count($template) }}</span>
                                </div>
                            </div>

                            <h6>{{ __('Some Example Keys:') }}</h6>
                            <ul class="list-unstyled">
                                @foreach(array_slice($template, 0, 5, true) as $key => $value)
                                    <li><code>{{ $key }}</code></li>
                                @endforeach
                                <li class="text-muted"><em>{{ __('... and') }} {{ count($template) - 5 }} {{ __('more') }}</em></li>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Common Language Codes') }}</h3>
                        </div>
                        <div class="card-body p-2">
                            <table class="table table-sm">
                                <tbody>
                                    <tr><td>🇪🇸 Spanish</td><td><code>es</code></td></tr>
                                    <tr><td>🇩🇪 German</td><td><code>de</code></td></tr>
                                    <tr><td>🇯🇵 Japanese</td><td><code>ja</code></td></tr>
                                    <tr><td>🇨🇳 Chinese</td><td><code>zh</code></td></tr>
                                    <tr><td>🇰🇷 Korean</td><td><code>ko</code></td></tr>
                                    <tr><td>🇮🇹 Italian</td><td><code>it</code></td></tr>
                                    <tr><td>🇵🇹 Portuguese</td><td><code>pt</code></td></tr>
                                    <tr><td>🇷🇺 Russian</td><td><code>ru</code></td></tr>
                                    <tr><td>🇦🇪 Arabic</td><td><code>ar</code></td></tr>
                                    <tr><td>🇹🇭 Thai</td><td><code>th</code></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
