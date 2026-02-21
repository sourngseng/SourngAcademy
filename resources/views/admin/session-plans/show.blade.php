@php
    $layout = 'admin.layouts.master';
    if(Auth::user()->hasRole('teacher')) {
        $layout = 'teacher.layouts.master';
    } elseif(Auth::user()->hasRole('student')) {
        $layout = 'student.layouts.master';
    } elseif(Auth::user()->hasRole('staff')) {
        $layout = 'staff.layouts.master';
    }
@endphp

@extends($layout)

@section('title', __('Session Reader') . ': ' . $sessionPlan->title)

@push('styles')
<style>
    .reader-container {
        /* max-width: 1200px; */
        margin: 0 auto;
    }
    .reader-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        padding: 40px 0;
        border-radius: 0 0 20px 20px;
        margin-bottom: 30px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .info-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }
    .info-card:hover {
        transform: translateY(-2px);
    }
    .objective-section {
        background: #f8fafc;
        border-left: 5px solid #3b82f6;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
    }
    .book-frame {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
    }
    .book-frame:fullscreen {
        width: 100vw !important;
        height: 100vh !important;
        border-radius: 0;
        margin: 0;
        padding: 0;
        overflow-y: auto;
    }
    .book-frame:-webkit-full-screen {
        width: 100% !important;
        height: 100% !important;
    }
    .book-frame:fullscreen iframe, 
    .book-frame:fullscreen #docx-preview-container {
        height: 100vh !important;
    }
    .status-badge {
        padding: 6px 15px;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
    }
    #docx-preview-container {
        padding: 40px !important;
        background-color: #f1f5f9 !important;
    }
    .docx-wrapper {
        background-color: transparent !important;
        padding: 0 !important;
    }
    .docx {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        margin: 0 auto !important;
    }
    .sticky-top {
        top: 20px;
    }
    .fullscreen-btn {
        cursor: pointer;
        transition: all 0.2s;
        padding: 8px 15px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #1e293b;
        font-weight: 600;
        border: 1px solid #e2e8f0;
    }
    .fullscreen-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .collapse-icon {
        transition: transform 0.2s ease, color 0.2s ease;
        font-size: 1.1rem;
    }

    /* Dark Mode Transitions */
    body, .info-card, .objective-section, .nav-roadmap, .nav-sub-link, .nav-roadmap-link {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }

    /* Dark Mode Specific Styles */
    body.dark-mode .info-card, 
    body.dark-mode .objective-section {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #f1f5f9;
    }
    
    body.dark-mode .card-header {
        background-color: #0f172a !important;
        color: #f1f5f9 !important;
        border-bottom-color: #334155 !important;
    }

    body.dark-mode .nav-roadmap-item {
        border-bottom-color: #334155;
    }

    body.dark-mode .nav-roadmap-link {
        color: #cbd5e1;
    }

    body.dark-mode .nav-roadmap-link:hover {
        background: #334155;
        color: #60a5fa;
    }

    body.dark-mode .nav-roadmap-link.active {
        background: #1e3a8a;
        color: #bfdbfe;
    }

    body.dark-mode .nav-sub-roadmap {
        background: #0f172a;
        border-left-color: #334155;
    }

    body.dark-mode .nav-sub-link {
        color: #94a3b8;
    }

    body.dark-mode .nav-sub-link:hover {
        background: #334155;
        color: #f1f5f9;
    }

    body.dark-mode .nav-sub-link.active {
        background: #2563eb;
        color: #fff;
    }

    body.dark-mode .table td {
        color: #f1f5f9;
    }

    body.dark-mode .text-muted {
        color: #94a3b8 !important;
    }

    body.dark-mode .book-frame {
        background: #0f172a;
        border-color: #334155;
    }

    body.dark-mode .fullscreen-btn {
        background: #334155;
        color: #f1f5f9;
        border-color: #475569;
    }

    body.dark-mode .fullscreen-btn:hover {
        background: #475569;
    }

    body.dark-mode #docx-preview-container {
        background-color: #0f172a !important;
    }
    /* Nav Roadmap Styles */
    .nav-roadmap {
        border-radius: 15px;
        overflow: hidden;
    }
    .nav-roadmap-item {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s;
    }
    .nav-roadmap-link {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: #475569;
        font-weight: 500;
        text-decoration: none !important;
        position: relative;
    }
    .nav-roadmap-link:hover {
        background: #f8fafc;
        color: #2563eb;
    }
    .nav-roadmap-link.active {
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
    }
    .nav-roadmap-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #3b82f6;
    }
    .nav-roadmap-collapse-btn {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #94a3b8;
        transition: transform 0.2s;
    }
    .nav-roadmap-item.active .nav-roadmap-collapse-btn {
        transform: rotate(90deg);
        color: #3b82f6;
    }
    .nav-sub-roadmap {
        background: #fcfcfd;
        padding-left: 15px;
        border-left: 2px solid #f1f5f9;
        margin-left: 25px;
        margin-bottom: 10px;
    }
    .nav-sub-link {
        display: block;
        padding: 8px 15px;
        font-size: 0.85rem;
        color: #64748b;
        text-decoration: none !important;
        border-radius: 6px;
        margin-right: 10px;
        transition: all 0.2s;
    }
    .nav-sub-link:hover {
        background: #f1f5f9;
        color: #1e293b;
    }
    .nav-sub-link.active {
        background: #3b82f6;
        color: #fff;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="reader-container">
    <!-- Immersive Header -->
    <div class="reader-header shadow">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('admin.session-plans.index') }}" class="text-white-50">{{ __('Session Plans') }}</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">{{ $sessionPlan->subject->code ?? 'N/A' }}</li>
                        </ol>
                    </nav>
                    <h1 class="display-5 font-weight-bold mb-1">{{ $sessionPlan->title }}</h1>
                    <p class="lead text-white-50 mb-0">
                        <i class="fas fa-university mr-2"></i> {{ $sessionPlan->campus->name ?? 'N/A' }} 
                        <span class="mx-2">|</span> 
                        <i class="fas fa-graduation-cap mr-2"></i> {{ method_exists($sessionPlan->program, 'getTranslatedName') ? $sessionPlan->program->getTranslatedName() : $sessionPlan->program->name }}
                    </p>
                </div>
                <div class="col-md-4 text-md-right mt-4 mt-md-0">
                    <div class="d-flex justify-content-md-end align-items-center">
                        <div class="text-right mr-3 border-right pr-3 d-none d-lg-block">
                            <small class="text-white-50 d-block text-uppercase letter-spacing-1">{{ __('Duration') }}</small>
                            <span class="h4 mb-0 font-weight-bold">{{ $sessionPlan->duration ?? '-' }} {{ __(ucfirst($sessionPlan->duration_unit)) }}</span>
                        </div>
                        <a href="{{ route('admin.session-plans.edit', $sessionPlan->id) }}" class="btn btn-light btn-lg px-4 shadow-sm border-0 transition-3d-hover">
                            <i class="fas fa-edit mr-2 text-primary"></i> {{ __('Edit Plan') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <div class="sticky-top">
                    <!-- Session Details Card -->
                    <div class="card info-card mb-4">
                        <div class="d-flex align-items-center justify-content-between py-3 pl-3 pr-2 collapsed" 
                             style="cursor: pointer;" data-toggle="collapse" data-target="#quick-info-collapse">
                            <span><i class="fas fa-info-circle mr-2 text-primary"></i> {{ __('Quick Info') }}</span>
                            <i class="fas fa-plus-circle text-primary collapse-icon"></i>
                        </div>
                        <div class="collapse" id="quick-info-collapse">
                            <div class="card-body p-0">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted pb-1">{{ __('Academic Year') }}</td>
                                            <td class="font-weight-bold pb-1 text-right">{{ $sessionPlan->academicYear->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-1">{{ __('Semester') }}</td>
                                            <td class="font-weight-bold py-1 text-right">{{ $sessionPlan->semester->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-1">{{ __('Created By') }}</td>
                                            <td class="font-weight-bold py-1 text-right">{{ $sessionPlan->author->name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted pt-1">{{ __('Status') }}</td>
                                            <td class="pt-1 text-right">
                                                <span class="badge {{ $sessionPlan->status ? 'badge-success' : 'badge-danger' }} status-badge">
                                                    {{ $sessionPlan->status ? __('Active') : __('Inactive') }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- Hierarchy Navigation -->
                    <div class="card info-card nav-roadmap mb-4">
                        <div class="card-header bg-white font-weight-bold py-3 d-flex align-items-center justify-content-between">
                            <span><i class="fas fa-route mr-2 text-primary"></i> {{ __('Subject Roadmap') }}</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="nav-roadmap-list">
                                @forelse($roadmapSessions as $roadmap)
                                    @php
                                        $isCurrentParent = $sessionPlan->id == $roadmap->id;
                                        $hasCurrentChild = $roadmap->children->contains('id', $sessionPlan->id);
                                        $isExpanded = $isCurrentParent || $hasCurrentChild;
                                    @endphp
                                    <div class="nav-roadmap-item {{ $isExpanded ? 'active' : '' }}">
                                        <div class="d-flex align-items-center">
                                            @if($roadmap->children->count() > 0)
                                                <div class="nav-roadmap-collapse-btn" data-toggle="collapse" data-target="#nav-sub-{{ $roadmap->id }}">
                                                    <i class="fas fa-plus-circle text-primary collapse-icon"></i>
                                                </div>
                                            @else
                                                <div class="px-3"></div>
                                            @endif
                                            <a href="{{ route('admin.session-plans.show', $roadmap->id) }}" class="nav-roadmap-link flex-grow-1 {{ $isCurrentParent ? 'active' : '' }}">
                                                {{ $roadmap->title }}
                                            </a>
                                        </div>

                                        @if($roadmap->children->count() > 0)
                                            <div class="collapse {{ $isExpanded ? 'show' : '' }}" id="nav-sub-{{ $roadmap->id }}">
                                                <div class="nav-sub-roadmap">
                                                    @foreach($roadmap->children as $child)
                                                        <a href="{{ route('admin.session-plans.show', $child->id) }}" class="nav-sub-link {{ $sessionPlan->id == $child->id ? 'active' : '' }}">
                                                            <i class="fas fa-angle-right mr-1 opacity-50"></i> {{ $child->title }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="p-3 text-center text-muted small">{{ __('No other sessions found.') }}</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- File Download Card -->
                    @if($sessionPlan->attachment)
                    <div class="card info-card mb-4 bg-primary text-white">
                        <div class="card-body text-center py-4">
                            @php
                                $ext = pathinfo($sessionPlan->attachment, PATHINFO_EXTENSION);
                                $icon = 'fa-file-alt';
                                switch(strtolower($ext)) {
                                    case 'pdf': $icon = 'fa-file-pdf'; break;
                                    case 'doc': case 'docx': $icon = 'fa-file-word'; break;
                                    case 'xls': case 'xlsx': $icon = 'fa-file-excel'; break;
                                    case 'ppt': case 'pptx': $icon = 'fa-file-powerpoint'; break;
                                }
                            @endphp
                            <i class="fas {{ $icon }} fa-3x mb-3 opacity-8"></i>
                            <h5 class="font-weight-bold mb-1">{{ __('Source Material') }}</h5>
                            <p class="small text-white-50 mb-3 text-uppercase font-weight-bold">{{ strtoupper($ext) }} {{ __('Document') }}</p>
                            <a href="{{ asset($sessionPlan->attachment) }}" target="_blank" class="btn btn-light btn-block font-weight-bold">
                                <i class="fas fa-download mr-2"></i> {{ __('Download File') }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($sessionPlan->video_url)
                    <div class="card info-card mb-4 bg-danger text-white border-0">
                        <div class="card-body text-center py-4">
                            <i class="fab fa-youtube fa-3x mb-3 opacity-8"></i>
                            <h5 class="font-weight-bold mb-1">{{ __('Video Lecture') }}</h5>
                            <p class="small text-white-50 mb-3 text-uppercase font-weight-bold">{{ __('Extra Material') }}</p>
                            <a href="{{ $sessionPlan->video_url }}" target="_blank" class="btn btn-light btn-block font-weight-bold">
                                <i class="fas fa-external-link-alt mr-2"></i> {{ __('Watch on YouTube') }}
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Main Reader View -->
            <div class="col-lg-8">
                <!-- Objective Section -->
                <div class="objective-section shadow-sm mb-4 bg-white border p-0 overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between py-3 pl-3 pr-2 collapsed" 
                         style="cursor: pointer;" data-toggle="collapse" data-target="#objectives-collapse">
                        <h5 class="font-weight-bold text-primary mb-0">
                            <i class="fas fa-bullseye mr-2"></i> {{ __('Learning Objectives') }}
                        </h5>
                        <i class="fas fa-plus-circle text-primary collapse-icon"></i>
                    </div>
                    <div class="collapse" id="objectives-collapse">
                        <div class="p-4 pt-0">
                            <div class="text-dark lead" style="font-size: 1.1rem; line-height: 1.6;">
                                @if($sessionPlan->description)
                                    {!! $sessionPlan->description !!}
                                @else
                                    <span class="text-muted font-italic">{{ __('No detailed objectives established for this session.') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($videoEmbedUrl))
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="fab fa-youtube mr-2 text-danger"></i> {{ __('Video Lecture') }}
                        </h5>
                    </div>
                    <div class="embed-responsive embed-responsive-16by9 bg-dark shadow-sm rounded overflow-hidden">
                        <iframe class="embed-responsive-item" src="{{ $videoEmbedUrl }}" allowfullscreen></iframe>
                    </div>
                </div>
                @endif

                @if($sessionPlan->attachment)
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-book-open mr-2 text-primary"></i> {{ __('Interactive Reader') }}
                        </h5>
                        <div class="ml-auto">
                            <button onclick="toggleFullscreen()" class="fullscreen-btn shadow-sm">
                                <i class="fas fa-expand mr-2"></i> {{ __('Read Full Screen') }}
                            </button>
                        </div>
                    </div>

                    <div id="reader-frame" class="book-frame">
                        @php
                            $ext = pathinfo($sessionPlan->attachment, PATHINFO_EXTENSION);
                            $fileUrl = asset($sessionPlan->attachment);
                        @endphp

                        @if(strtolower($ext) == 'pdf')
                            <iframe src="{{ $fileUrl }}" width="100%" height="900px" style="border: none;">
                                {{ __('This browser does not support PDFs.') }} <a href="{{ $fileUrl }}">{{ __('Download PDF') }}</a>
                            </iframe>
                        @elseif(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            <div class="p-4 text-center bg-light">
                                <img src="{{ $fileUrl }}" alt="{{ __('Session Content') }}" class="img-fluid shadow-lg rounded" style="max-height: 1200px;">
                            </div>
                        @elseif(in_array(strtolower($ext), ['mp4', 'webm', 'ogg']))
                            <div class="embed-responsive embed-responsive-16by9 bg-dark">
                                <video class="embed-responsive-item" controls>
                                    <source src="{{ $fileUrl }}" type="video/{{ $ext }}">
                                </video>
                            </div>
                        @elseif(strtolower($ext) == 'docx')
                            <div id="docx-preview-container" style="min-height: 800px; display: flex; flex-direction: column;">
                                <div class="text-center p-5 my-auto">
                                    <div class="spinner-grow text-primary" role="status" style="width: 3rem; height: 3rem;">
                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                    </div>
                                    <h5 class="mt-4 font-weight-bold text-muted">{{ __('Preparing your document...') }}</h5>
                                </div>
                            </div>
                        @elseif(in_array(strtolower($ext), ['doc', 'ppt', 'pptx', 'xls', 'xlsx']))
                            <div class="alert alert-info border-0 rounded-0 mb-0 py-4 px-5">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <i class="fas fa-cloud-sun fa-3x opacity-5"></i>
                                    </div>
                                    <div class="col-md-8">
                                        <h5 class="font-weight-bold text-primary">{{ __('Local Preview Capability') }}</h5>
                                        <p class="mb-0">{{ __('This document format') }} (<strong>.{{ $ext }}</strong>) {{ __('requires a public connection for live preview via Google Viewer. For optimal local reading, please download the file directly.') }}</p>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <a href="{{ $fileUrl }}" class="btn btn-primary btn-block shadow-sm">
                                            <i class="fas fa-download mr-1"></i> {{ __('Download Now') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Attempting Google Viewer anyway for semi-public environments -->
                            <iframe src="https://docs.google.com/gview?url={{ urlencode($fileUrl) }}&embedded=true" width="100%" height="800px" frameborder="0"></iframe>
                        @else
                            <div class="p-5 text-center bg-light border-top">
                                <i class="fas fa-file-download fa-5x text-muted mb-4"></i>
                                <h3 class="font-weight-bold">{{ __('File Ready for Download') }}</h3>
                                <p class="text-muted text-lg mb-4">{{ __('This browser cannot natively preview') }} <strong>.{{ strtoupper($ext) }}</strong> {{ __('files.') }}</p>
                                <a href="{{ $fileUrl }}" class="btn btn-primary btn-lg px-5 shadow">
                                    <i class="fas fa-download mr-2"></i> {{ __('Save File to Computer') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                @if(!$sessionPlan->attachment && !$sessionPlan->video_url)
                <div class="card info-card bg-light border-dashed">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-file-signature text-muted fa-4x mb-4 opacity-5"></i>
                        <h4 class="font-weight-bold">{{ __('No Content Attached') }}</h4>
                        <p class="text-muted">{{ __('This session has been defined but no external files or book materials have been uploaded yet.') }}</p>
                        <a href="{{ route('admin.session-plans.edit', $sessionPlan->id) }}" class="btn btn-outline-primary mt-2">
                             {{ __('Update Content') }}
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/jszip/dist/jszip.min.js"></script>
<script src="https://unpkg.com/docx-preview/dist/docx-preview.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(isset($sessionPlan->attachment) && pathinfo($sessionPlan->attachment, PATHINFO_EXTENSION) == 'docx')
            var fileUrl = "{{ asset($sessionPlan->attachment) }}";
            
            fetch(fileUrl)
                .then(response => response.blob())
                .then(blob => {
                    var container = document.getElementById("docx-preview-container");
                    container.innerHTML = ""; // Clear loading
                    
                    var docxOptions = { 
                        className: "docx",
                        inWrapper: true,
                        ignoreWidth: false,
                        ignoreHeight: false,
                        breakPages: true,
                        experimental: true,
                    };
                    
                    docx.renderAsync(blob, container, null, docxOptions)
                        .catch(e => {
                            console.error("docx error:", e);
                            container.innerHTML = '<div class="alert alert-danger m-5">Error rendering document preview. Please use the download link in the sidebar.</div>';
                        });
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    document.getElementById("docx-preview-container").innerHTML = '<div class="alert alert-danger m-5">Failed to fetch document material.</div>';
                });
        @endif
    });

    function toggleFullscreen() {
        var elem = document.getElementById("reader-frame");
        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }
    }

    // Generic Collapse Icon Toggle (Plus/Minus)
    $(document).on('show.bs.collapse', '.collapse', function () {
        var targetId = $(this).attr('id');
        var icon = $('[data-target="#' + targetId + '"]').find('.collapse-icon');
        icon.removeClass('fa-plus-circle').addClass('fa-minus-circle').addClass('text-danger').removeClass('text-primary');
    });

    $(document).on('hide.bs.collapse', '.collapse', function () {
        var targetId = $(this).attr('id');
        var icon = $('[data-target="#' + targetId + '"]').find('.collapse-icon');
        icon.addClass('fa-plus-circle').removeClass('fa-minus-circle').removeClass('text-danger').addClass('text-primary');
    });
</script>
@endpush
