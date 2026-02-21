<div class="roadmap-list-wrapper">
    @forelse($sessions as $session)
        @php
            // Calculate Total Duration for Parent
            $parentMinutes = ($session->duration_unit == 'hours') ? ($session->duration * 60) : $session->duration;
            $childrenMinutes = 0;
            foreach ($session->children as $child) {
                $childrenMinutes += ($child->duration_unit == 'hours') ? ($child->duration * 60) : $child->duration;
            }
            $totalMinutes = $parentMinutes + $childrenMinutes;
            
            if ($totalMinutes >= 60) {
                $h = floor($totalMinutes / 60);
                $m = $totalMinutes % 60;
                $displayDuration = $h . 'h' . ($m > 0 ? ' ' . $m . 'm' : '');
            } else {
                $displayDuration = $totalMinutes . 'm';
            }
        @endphp

        <div class="roadmap-node-container mb-4">
            <div class="roadmap-node p-4">
                <div class="node-anchor"></div>
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="d-flex align-items-start">
                            @if($session->children_count > 0)
                                <div class="mr-3 mt-1">
                                    <i class="fas fa-plus-circle expand-trigger text-primary" 
                                       style="cursor: pointer; font-size: 1.2rem;" 
                                       data-toggle="collapse" 
                                       data-target="#sub-roadmap-{{ $session->id }}"></i>
                                </div>
                            @else
                                <div class="mr-3 mt-1">
                                    <i class="fas fa-circle text-muted opacity-25" style="font-size: 0.8rem;"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="font-weight-bold text-dark mb-1">{{ $session->title }}</h5>
                                <div class="d-flex align-items-center mt-2 flex-wrap">
                                    <span class="text-muted small mr-3 mb-1">
                                        <i class="fas fa-book-open mr-1"></i> 
                                        {{ $session->subject->code ?? 'N/A' }} - {{ $session->subject ? ($session->subject->getTranslatedName() ?? $session->subject->name) : 'N/A' }}
                                    </span>
                                    @if($session->children_count > 0)
                                        <span class="badge badge-primary-light py-1 px-2 small mb-1" style="background:#eef2ff; color:#6366f1; border-radius:8px;">
                                            {{ $session->children_count }} Modules
                                        </span>
                                    @endif
                                    <span class="text-muted small ml-3 mb-1">
                                        <i class="fas fa-user-edit mr-1"></i> {{ $session->author->name ?? 'System' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="time-badge">
                            <i class="far fa-clock mr-2"></i> {{ $displayDuration }}
                        </div>
                        @if($childrenMinutes > 0)
                            <div class="small text-muted mt-1" style="font-size: 0.7rem;">(Incl. sub-sessions)</div>
                        @endif
                    </div>
                    <div class="col-md-2 text-right">
                        <div class="action-group d-flex justify-content-end">
                            <a href="{{ route('teacher.session-plans.show', $session->id) }}" class="btn btn-view" title="Details"><i class="fas fa-eye"></i></a>
                            @if($session->created_by == Auth::id())
                                <a href="{{ route('teacher.session-plans.edit', $session->id) }}" class="btn btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-delete delete-btn" data-id="{{ $session->id }}" data-url="{{ route('teacher.session-plans.destroy', $session->id) }}" title="Delete"><i class="fas fa-trash"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Collapsible Sub-Roadmap --}}
            @if($session->children_count > 0)
                <div class="collapse sub-roadmap-container mt-2" id="sub-roadmap-{{ $session->id }}">
                    <div class="roadmap-list pl-4 py-2" style="border-left: 2px dashed #e2e8f0; margin-left: 19px;">
                        @foreach($session->children as $child)
                            <div class="sub-roadmap-item mb-2 p-3 bg-light border rounded">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-caret-right text-primary mr-2"></i>
                                        <span class="font-weight-bold text-dark">{{ $child->title }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-white border mr-3 py-1 px-2" style="font-size: 0.75rem;">
                                            <i class="far fa-clock mr-1"></i> {{ $child->duration }} {{ $child->duration_unit }}
                                        </span>
                                        <div class="action-group">
                                            <a href="{{ route('teacher.session-plans.show', $child->id) }}" class="btn btn-xs btn-view border" title="Details"><i class="fas fa-eye text-primary"></i></a>
                                            @if($child->created_by == Auth::id())
                                                <a href="{{ route('teacher.session-plans.edit', $child->id) }}" class="btn btn-xs btn-edit border" title="Edit"><i class="fas fa-edit text-warning"></i></a>
                                                <button type="button" class="btn btn-xs btn-delete border delete-btn" data-id="{{ $child->id }}" data-url="{{ route('teacher.session-plans.destroy', $child->id) }}" title="Delete"><i class="fas fa-trash text-danger"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div class="text-center py-5 bg-white rounded shadow-sm border">
            <i class="fas fa-folder-open text-muted fa-3x mb-3"></i>
            <h5 class="text-muted">No session plans found matching your criteria.</h5>
            <p class="text-muted small">Only plans you created or subjects you teach are visible here.</p>
        </div>
    @endforelse

    <div class="d-flex justify-content-center mt-4">
        {{ $sessions->links() }}
    </div>
</div>
