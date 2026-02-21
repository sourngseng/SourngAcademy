@extends('admin.layouts.master')

@section('title', __('Manage Languages'))

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('Manage Languages') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Languages') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('All Languages') }}</h3>
                    <a href="{{ route('admin.languages.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> {{ __('Add New Language') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="8%">{{ __('Flag') }}</th>
                                <th width="10%">{{ __('Code') }}</th>
                                <th width="18%">{{ __('Name') }}</th>
                                <th width="12%">{{ __('Status') }}</th>
                                <th width="12%">{{ __('Translation Keys') }}</th>
                                <th width="12%">{{ __('File Size') }}</th>
                                <th width="23%">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($languages as $index => $language)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-center" style="font-size: 32px;">{{ $language['flag'] }}</td>
                                    <td><span class="badge badge-info">{{ strtoupper($language['locale']) }}</span></td>
                                    <td><strong>{{ $language['name'] }}</strong></td>
                                    <td>
                                        @if(config('app.locale') === $language['locale'])
                                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> {{ __('Default') }}</span>
                                        @else
                                            <span class="badge badge-secondary">-</span>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-success">{{ $language['keys_count'] }} keys</span></td>
                                    <td>{{ number_format($language['file_size'] / 1024, 2) }} KB</td>
                                    <td>
                                        <a href="{{ route('admin.languages.edit', $language['locale']) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="{{ __('Edit Translations') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if(config('app.locale') !== $language['locale'])
                                            <form action="{{ route('admin.languages.set-default', $language['locale']) }}" 
                                                  method="POST" 
                                                  style="display: inline;">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-success" 
                                                        title="{{ __('Set as Default') }}">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($language['locale'] !== 'en')
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="deleteLanguage('{{ $language['locale'] }}')"
                                                    title="{{ __('Delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            
                                            <form id="delete-form-{{ $language['locale'] }}" 
                                                  action="{{ route('admin.languages.destroy', $language['locale']) }}" 
                                                  method="POST" 
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('No languages found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add New Translation Key Section -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ __('Add New Translation Key') }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.languages.add-key') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="key">{{ __('Translation Key') }}</label>
                                <input type="text" 
                                       class="form-control @error('key') is-invalid @enderror" 
                                       id="key" 
                                       name="key" 
                                       placeholder="e.g., New Feature"
                                       required>
                                @error('key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="value">{{ __('Default Value (English)') }}</label>
                                <input type="text" 
                                       class="form-control @error('value') is-invalid @enderror" 
                                       id="value" 
                                       name="value" 
                                       placeholder="e.g., New Feature"
                                       required>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-plus"></i> {{ __('Add to All') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> {{ __('This will add the translation key to all language files with the default English value.') }}
                    </small>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function deleteLanguage(locale) {
    Swal.fire({
        title: '{{ __("Are you sure?") }}',
        text: '{{ __("You won\'t be able to revert this!") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __("Yes, delete it!") }}',
        cancelButtonText: '{{ __("Cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + locale).submit();
        }
    });
}
</script>
@endpush
