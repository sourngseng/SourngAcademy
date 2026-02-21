@extends('admin.layouts.master')

@section('title', 'Edit Degree Level')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-edit mr-2"></i> Edit Degree Level</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.degree-levels.index') }}" class="btn btn-secondary shadow-sm">
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
            <div class="card card-warning card-outline shadow">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">Degree Level Details: {{ $degreeLevel->name }}</h3>
                </div>
                <form action="{{ route('admin.degree-levels.update', $degreeLevel->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Degree Level Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $degreeLevel->name) }}" required>
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $degreeLevel->description) }}</textarea>
                                    @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="short_name">Short Name</label>
                                    <input type="text" name="short_name" id="short_name" class="form-control @error('short_name') is-invalid @enderror" value="{{ old('short_name', $degreeLevel->short_name) }}">
                                    @error('short_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sort_order">Sort Order</label>
                                    <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $degreeLevel->sort_order) }}">
                                    @error('sort_order') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mt-4">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="status" class="custom-control-input" id="statusSwitch" {{ $degreeLevel->status ? 'checked' : '' }} value="1">
                                        <label class="custom-control-label" for="statusSwitch">Status Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-right">
                        <button type="submit" class="btn btn-warning shadow-sm px-4">
                            <i class="fas fa-save mr-1"></i> Update Degree Level
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
