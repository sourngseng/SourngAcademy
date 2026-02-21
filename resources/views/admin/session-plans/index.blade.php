@extends('admin.layouts.master')

@section('title', 'Session Roadmap')

@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    :root {
        --primary-accent: #4f46e5;
        --secondary-accent: #0ea5e9;
        --spine-color: #e2e8f0;
        --dot-color: #cbd5e1;
    }

    .roadmap-container {
        padding: 0 1.5rem 1.5rem 1.5rem;
    }
    .filter-card {
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        background: #fff;
        position: sticky;
        top: 20px;
    }
    .filter-header {
        background: #f8fafc;
        border-radius: 20px 20px 0 0;
        padding: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .filter-label {
        font-size: 0.7rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
        display: block;
    }

    /* Roadmap List Styling */
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
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        border: 1px solid #f1f5f9;
        position: relative;
        transition: transform 0.2s, box-shadow 0.2s;
        z-index: 2;
    }
    .roadmap-node:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
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
        transition: background 0.3s, border-color 0.3s;
    }
    .roadmap-node:hover .node-anchor {
        background: var(--primary-accent);
        border-color: #fff;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .time-badge {
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
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

    /* Skeleton Loading Effect */
    .skeleton {
        background: #eee;
        background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
        border-radius: 5px;
        background-size: 200% 100%;
        animation: 1.5s shine linear infinite;
    }
    @keyframes shine {
        to { background-position-x: -200%; }
    }

    .pagination .page-item.active .page-link { background-color: var(--primary-accent); border-color: var(--primary-accent); }
    .pagination .page-link { color: var(--primary-accent); border-radius: 8px; margin: 0 3px; }
</style>
@endpush

@section('page-title')
<section class="content-header p-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="font-weight-bold text-dark m-0 d-flex align-items-center">
                    <span class="mr-3 text-primary"><i class="fas fa-route"></i></span>
                    Learning Roadmaps
                </h1>
                <p class="text-muted mt-2 mb-0 font-weight-medium">Navigate through structured academic paths.</p>
            </div>
            <div class="col-md-6 text-md-right mt-3 mt-md-0">
                <a href="{{ route('admin.session-plans.create') }}" class="btn btn-primary btn-lg shadow-sm border-0" style="border-radius: 12px; background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                    <i class="fas fa-plus-circle mr-2"></i> New Curriculum Plan
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="roadmap-container">
    <div class="row">
        <!-- Explorer Sidebar -->
        <div class="col-xl-3 col-lg-4">
            <div class="card filter-card">
                <div class="filter-header d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-search mr-2 text-primary"></i> Path Finder</h6>
                    <button id="reset_filters" class="btn btn-link btn-sm p-0 text-muted" title="Clear Path">
                        <i class="fas fa-eraser"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="filter-label">Search Plan</label>
                        <div class="input-group">
                            <input type="text" id="filter_search" class="form-control" placeholder="Plan title...">
                            <div class="input-group-append">
                                <span class="input-group-text bg-white border-left-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="filter-label">Campus Branch</label>
                        <select id="filter_campus" class="form-control select2-filter">
                            <option value="">Exploring All Locations</option>
                            @foreach($campuses as $campus)
                                <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="filter-label">Academic Program</label>
                        <select id="filter_program" class="form-control select2-filter" disabled>
                            <option value="">Locked - Select Campus First</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="filter-label">Academic Year</label>
                        <select id="filter_academic_year" class="form-control select2-filter" disabled>
                            <option value="">Locked - Select Campus First</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="filter-label">Semester</label>
                        <select id="filter_semester" class="form-control select2-filter" disabled>
                            <option value="">Locked - Select Year First</option>
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="filter-label">Subject Domain</label>
                        <select id="filter_subject" class="form-control select2-filter" disabled>
                            <option value="">Locked - Select Program First</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roadmap Content -->
        <div class="col-xl-9 col-lg-8">
            <div id="roadmap_list_container">
                @include('admin.session-plans.partials._list')
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
    $('.select2-filter').select2({ theme: 'bootstrap4', width: '100%' });

    // AJAX Filter Execution
    function refreshList(page = 1) {
        var container = $('#roadmap_list_container');
        container.css('opacity', '0.5');
        
        var filters = {
            page: page,
            campus_id: $('#filter_campus').val(),
            program_id: $('#filter_program').val(),
            subject_id: $('#filter_subject').val(),
            academic_year_id: $('#filter_academic_year').val(),
            semester_id: $('#filter_semester').val(),
            search: $('#filter_search').val()
        };

        $.get("{{ route('admin.session-plans.index') }}", filters, function(html) {
            container.html(html);
            container.css('opacity', '1');
            // Re-bind pagination clicks
            bindPagination();
        });
    }

    function bindPagination() {
        $('.pagination a').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.split('page=')[1];
            refreshList(page);
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        });
    }

    // Trigger filters
    $('#filter_campus, #filter_program, #filter_subject, #filter_academic_year, #filter_semester').change(function() {
        refreshList();
    });

    var searchTimer;
    $('#filter_search').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(refreshList, 500);
    });

    // Dependent Dropdowns Logic
    $('#filter_campus').change(function() {
        var campusId = $(this).val();
        $('#filter_program, #filter_academic_year, #filter_semester, #filter_subject').empty().prop('disabled', true);
        
        if(campusId) {
            $.get("{{ url('admin/session-plans/get-programs') }}/" + campusId, function(data) {
                $('#filter_program').empty().append('<option value="">All Programs</option>').prop('disabled', false);
                $.each(data, function(key, value){ $('#filter_program').append('<option value="'+value.id+'">'+value.name+'</option>'); });
            });
            $.get("{{ url('admin/session-plans/get-academic-years') }}/" + campusId, function(data) {
                $('#filter_academic_year').empty().append('<option value="">All Years</option>').prop('disabled', false);
                $.each(data, function(key, value){ $('#filter_academic_year').append('<option value="'+value.id+'">'+value.name+'</option>'); });
            });
        }
    });

    $('#filter_academic_year').change(function() {
        var yearId = $(this).val();
        $('#filter_semester').empty().prop('disabled', true);
        if(yearId) {
            $.get("{{ url('admin/session-plans/get-semesters') }}/" + yearId, function(data) {
                $('#filter_semester').empty().append('<option value="">All Semesters</option>').prop('disabled', false);
                $.each(data, function(key, value){ $('#filter_semester').append('<option value="'+value.id+'">'+value.name+'</option>'); });
            });
        }
    });

    $('#filter_program, #filter_semester').change(function() {
        var programId = $('#filter_program').val();
        var semesterId = $('#filter_semester').val();
        if(programId) {
            var url = "{{ url('admin/session-plans/get-subjects') }}/" + programId;
            if(semesterId) url += "?semester_id=" + semesterId;
            $.get(url, function(data) {
                $('#filter_subject').empty().append('<option value="">All Subjects</option>').prop('disabled', false);
                $.each(data, function(key, value){ $('#filter_subject').append('<option value="'+value.id+'">'+value.name+'</option>'); });
            });
        }
    });

    $('#reset_filters').click(function() {
        $('#filter_search').val('');
        $('.select2-filter').val('').trigger('change');
        refreshList();
    });

    // Initial binding
    bindPagination();

    // Delete Action
    $(document).on('click', '.delete-btn', function() {
        let url = $(this).data('url');
        Swal.fire({
            title: 'Delete this Plan?',
            text: 'Associated modules will also be lost.',
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

    // Duplicate Action
    $(document).on('click', '.duplicate-btn', function() {
        let url = $(this).data('url');
        $.post(url, { _token: '{{ csrf_token() }}' }, function(response) {
            refreshList();
            Swal.fire('Cloned!', 'Plan duplicated successfully.', 'success');
        });
    });

    // Handle collapse icon change
    $(document).on('show.bs.collapse', '.collapse', function() {
        var target = $(this).attr('id');
        $('[data-target="#' + target + '"]').removeClass('fa-plus-circle').addClass('fa-minus-circle').addClass('text-danger').removeClass('text-primary');
    });
    $(document).on('hide.bs.collapse', '.collapse', function() {
        var target = $(this).attr('id');
        $('[data-target="#' + target + '"]').addClass('fa-plus-circle').removeClass('fa-minus-circle').removeClass('text-danger').addClass('text-primary');
    });
});
</script>
@endpush
