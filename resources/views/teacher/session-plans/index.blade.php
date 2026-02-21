@extends('teacher.layouts.master')

@section('title', 'My Lesson Plans')

@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    :root {
        --primary-accent: #6366f1;
        --secondary-accent: #06b6d4;
        --spine-color: #f1f5f9;
        --dot-color: #e2e8f0;
    }

    .roadmap-list-wrapper {
        position: relative;
        padding-left: 40px;
        min-height: 400px;
    }
    .roadmap-list-wrapper::before {
        content: '';
        position: absolute;
        left: 19px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--spine-color);
        border-radius: 3px;
        z-index: 1;
    }

    .roadmap-node {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        position: relative;
        transition: transform 0.2s, box-shadow 0.2s;
        z-index: 2;
    }
    .roadmap-node:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
        border-color: var(--primary-accent);
    }

    .node-anchor {
        position: absolute;
        left: -44.5px;
        top: 50%;
        transform: translateY(-50%);
        width: 14px;
        height: 14px;
        background: #fff;
        border: 3px solid var(--dot-color);
        border-radius: 50%;
        z-index: 3;
    }
    .roadmap-node:hover .node-anchor {
        background: var(--primary-accent);
        border-color: #fff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .time-badge {
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
    }

    .action-group .btn {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        margin-left: 5px;
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #64748b;
        transition: all 0.2s;
    }
    .action-group .btn:hover { background: #f8fafc; border-color: var(--primary-accent); color: var(--primary-accent); }
    .action-group .btn-xs { width: 28px; height: 28px; font-size: 0.75rem; }
    
    .filter-card { border-radius: 20px; border: none; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
</style>
@endpush

@section('content')
<div class="content-header px-4 pt-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="font-weight-bold text-dark m-0">
                    <span class="text-primary mr-2"><i class="fas fa-layer-group"></i></span>
                    My Lesson Plans
                </h1>
                <p class="text-muted">Manage the curriculum roadmap for your assigned subjects.</p>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('teacher.session-plans.create') }}" class="btn btn-primary shadow-sm px-4 py-2" style="border-radius: 12px;">
                    <i class="fas fa-plus mr-2"></i> Create New Plan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 mt-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card filter-card mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-filter mr-2 text-primary"></i> Filter Plans</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="small font-weight-bold text-uppercase text-muted">Search Title</label>
                        <input type="text" id="filter_search" class="form-control" placeholder="Plan name...">
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-uppercase text-muted">By Subject</label>
                        <select id="filter_subject" class="form-control select2">
                            <option value="">All My Subjects</option>
                            @foreach($assignedSubjects as $subject)
                                <option value="{{ $subject->id }}" @selected(request('subject_id') == $subject->id)>{{ $subject->getTranslatedName() ?? $subject->name }} ({{ $subject->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button id="reset_filters" class="btn btn-outline-secondary btn-block mt-4">
                        <i class="fas fa-undo mr-1"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div id="roadmap_list_container">
                @include('teacher.session-plans.partials._list')
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

    function refreshList(page = 1) {
        var container = $('#roadmap_list_container');
        container.css('opacity', '0.5');
        
        var filters = {
            page: page,
            subject_id: $('#filter_subject').val(),
            search: $('#filter_search').val()
        };

        $.get("{{ route('teacher.session-plans.index') }}", filters, function(html) {
            container.html(html);
            container.css('opacity', '1');
            bindPagination();
        });
    }

    function bindPagination() {
        $('.pagination a').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.split('page=')[1];
            refreshList(page);
        });
    }

    $('#filter_subject').change(function() {
        refreshList();
    });
    
    var searchTimer;
    $('#filter_search').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(refreshList, 500);
    });

    $('#reset_filters').click(function() {
        $('#filter_search').val('');
        $('#filter_subject').val('').trigger('change');
    });

    bindPagination();

    // Delete
    $(document).on('click', '.delete-btn', function() {
        let url = $(this).data('url');
        Swal.fire({
            title: 'Delete this lesson plan?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        refreshList();
                        Swal.fire('Deleted!', 'Plan has been removed.', 'success');
                    }
                });
            }
        });
    });
});
</script>
@endpush
