<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="title" content="Timetable - {{ $class->name }}">
    <title>Timetable - {{ $class->name }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Kantumruy+Pro:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&family=Source+Sans+Pro:wght@300;400;600;700&family=Hanuman:wght@400;700&family=Noto+Sans+Khmer:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        @php
            $primaryFont = \App\Models\Setting::get('primary_font', "'Source Sans Pro', sans-serif");
            $khmerFont = \App\Models\Setting::get('khmer_font', "'Kantumruy Pro', sans-serif");
        @endphp
        body { 
            font-family: {!! $primaryFont !!}, {!! $khmerFont !!}, sans-serif; 
            font-size: 12pt; margin: 0; padding: 20px; position: relative; 
        }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24pt; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0; font-size: 14pt; font-weight: bold; }
        
        .info-section { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info-box { width: 48%; }
        .info-row { margin-bottom: 5px; }
        .info-label { font-weight: bold; display: inline-block; width: 100px; }

        .timetable { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .timetable th, .timetable td { border: 1px solid #000; padding: 12px; text-align: left; }
        .timetable th { background-color: #f0f0f0; text-transform: uppercase; font-size: 10pt; text-align: center; }
        .timetable td { font-size: 11pt; }
        .text-center { text-align: center; }
        
        .footer { margin-top: 50px; text-align: center; font-size: 10pt; color: #555; border-top: 1px solid #ccc; padding-top: 10px; }
        
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 2cm; }
            .header { border-bottom: 2px solid #000; }
            .draggable-signature { border: none !important; box-shadow: none !important; cursor: default !important; }
        }

        /* Draggable style */
        .draggable-item {
            position: absolute;
            text-align: center;
            cursor: move;
            z-index: 1000;
            background: rgba(255,255,255,0.7);
            border: 1px dashed transparent;
            padding: 8px 15px;
            border-radius: 4px;
            user-select: none;
            transition: background 0.2s, border 0.2s;
            min-width: 100px;
            min-height: 20px;
            overflow: hidden;
            resize: both; /* Enable resizing */
        }
        .draggable-item:hover {
            border: 1px dashed #007bff;
            background: rgba(255,255,255,0.95);
            box-shadow: 0 0 10px rgba(0,123,255,0.1);
        }
        .draggable-item.dragging {
            border-color: #28a745;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 1001;
            opacity: 0.8;
        }
        .btn-delete {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #ff4d4d;
            color: white;
            border: none;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 12px;
            line-height: 18px;
            text-align: center;
            cursor: pointer;
            display: none;
            z-index: 1002;
            padding: 0;
        }
        .draggable-item:hover .btn-delete {
            display: block;
        }
        @media print {
            .btn-delete, .resizer { display: none !important; }
            .draggable-item { border: none !important; background: transparent !important; resize: none !important; }
        }
        /* Header drag area - fits to content */
        .header-drag {
            width: 100%;
            background: transparent;
            position: relative;
            margin-bottom: 20px;
            min-height: 120px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        /* Make header draggable fit to text, not full width */
        .header-drag .draggable-item {
            width: auto !important;
            display: inline-block;
            white-space: nowrap;
        }
    </style>
</head>
<body>

    <div id="controls-panel" class="no-print" style="position: fixed; top: 20px; right: 20px; z-index: 2000; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); border: 1px solid #ddd; width: 220px; transition: all 0.3s ease;">
        <div style="padding: 10px; cursor: pointer; background: #f8f9fa; border-bottom: 1px solid #eee; border-radius: 8px 8px 0 0; display: flex; justify-content: space-between; align-items: center;" onclick="toggleControls()">
            <h5 style="margin: 0; font-size: 10pt; color: #333; font-weight: bold;"><i class="fas fa-tools mr-1"></i> Designer Tools</h5>
            <i id="toggle-icon" class="fas fa-chevron-up" style="font-size: 10px; color: #666;"></i>
        </div>
        
        <div id="controls-body" style="padding: 15px; overflow: hidden; transition: max-height 0.3s ease; max-height: 600px;">
            <button onclick="window.print()" style="width: 100%; padding: 10px; cursor: pointer; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold; margin-bottom: 8px;">
                🖨️ Print Timetable
            </button>
            <button onclick="addNewLabel()" style="width: 100%; padding: 8px; cursor: pointer; background: #007bff; color: white; border: none; border-radius: 4px; font-weight: bold; margin-bottom: 8px;">
                ＋ Add New Label
            </button>

            <!-- Font Selector -->
            <div style="margin-bottom: 8px;">
                <label style="font-size: 8pt; color: #555; display: block; margin-bottom: 4px; font-weight: bold;">🔤 Font Family</label>
                <select id="font-selector" onchange="changeFont(this.value)" style="width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; font-size: 9pt;">
                    <option value="'Source Sans Pro', sans-serif">Source Sans Pro</option>
                    <option value="'Inter', sans-serif">Inter</option>
                    <option value="'Roboto', sans-serif">Roboto</option>
                    <option value="'Outfit', sans-serif">Outfit</option>
                    <option value="'Kantumruy Pro', sans-serif">Kantumruy Pro (KH)</option>
                    <option value="'Hanuman', serif">Hanuman (KH)</option>
                    <option value="'Noto Sans Khmer', sans-serif">Noto Sans Khmer (KH)</option>
                    <option value="'Times New Roman', serif">Times New Roman</option>
                    <option value="'Georgia', serif">Georgia</option>
                </select>
            </div>

            <!-- Font Size Selector -->
            <div style="margin-bottom: 8px;">
                <label style="font-size: 8pt; color: #555; display: block; margin-bottom: 4px; font-weight: bold;">📏 Font Size</label>
                <select id="fontsize-selector" onchange="changeFontSize(this.value)" style="width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; font-size: 9pt;">
                    <option value="10pt">10pt (Small)</option>
                    <option value="11pt">11pt</option>
                    <option value="12pt" selected>12pt (Default)</option>
                    <option value="13pt">13pt</option>
                    <option value="14pt">14pt (Large)</option>
                </select>
            </div>

            <button onclick="window.close()" style="width: 100%; padding: 8px; cursor: pointer; background: #6c757d; color: white; border: none; border-radius: 4px; font-weight: bold; margin-bottom: 8px;">
                ✕ Close
            </button>
            <div style="font-size: 8pt; color: #666; text-align: center; border-top: 1px solid #eee; padding-top: 8px;">
                <b>Tips:</b><br>
                Drag items to move.<br>
                Resize bottom-right corner.<br>
                Click text to edit.<br>
                (×) to remove item.
            </div>
        </div>
    </div>

    <div class="header-drag">
        <!-- University Name - centered, fits text -->
        <div class="draggable-item" style="top: 5px; left: 50%; transform: translateX(-50%);">
            <button class="btn-delete" title="Delete" onclick="this.parentElement.remove()">×</button>
            <h1 contenteditable="true" style="margin: 0; font-size: 24pt; text-transform: uppercase; letter-spacing: 2px; white-space: nowrap;">
                {{ \App\Models\Setting::get('university_name', config('app.name')) }}
            </h1>
        </div>
        <!-- Timetable subtitle - centered, fits text -->
        <div class="draggable-item" style="top: 60px; left: 50%; transform: translateX(-50%);">
            <button class="btn-delete" title="Delete" onclick="this.parentElement.remove()">×</button>
            <p contenteditable="true" style="margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; white-space: nowrap;">
                {{ __('CLASS TIMETABLE') }}
            </p>
        </div>
    </div>

    <div class="info-section">
        <div class="info-box">
            <div class="info-row"><span class="info-label">{{ __('Class') }}:</span> {{ $class->name }}</div>
            <div class="info-row"><span class="info-label">{{ __('Code') }}:</span> {{ $class->code }}</div>
            <div class="info-row"><span class="info-label">{{ __('Program') }}:</span> {{ $class->program ? $class->program->getTranslatedName() : 'N/A' }}</div>
        </div>
        <div class="info-box" style="text-align: right;">
            <div class="info-row"><span class="info-label">{{ __('Campus') }}:</span> {{ $class->campus ? ($class->campus->name_en ?? $class->campus->name) : 'N/A' }}</div>
            <div class="info-row"><span class="info-label">{{ __('Semester') }}:</span> {{ $class->semester ? $class->semester->name : 'N/A' }}</div>
            <div class="info-row"><span class="info-label">{{ __('Academic Year') }}:</span> {{ $class->semester && $class->semester->academicYear ? $class->semester->academicYear->name : date('Y') }}</div>
        </div>
    </div>

    @php
        $daysMap = [
            'mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 
            'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'
        ];
        
        // 1. Define Rows based on Shift Sessions
        $shiftSessions = $class->shift ? $class->shift->sessions : [];
        $rows = [];
        
        if (!empty($shiftSessions) && is_array($shiftSessions)) {
            foreach($shiftSessions as $session) {
                // Create a key for the row, e.g., "08:00 - 10:00"
                $timeKey = $session['start'] . ' - ' . $session['end'];
                $rows[$timeKey] = [
                    'name' => $session['name'] ?? '', // e.g., "Session 1"
                    'start' => $session['start'],
                    'end' => $session['end'],
                    'is_break' => stripos($session['name'] ?? '', 'break') !== false
                ];
            }
        }
        
        // Data Collection
        $matrix = []; // [TimeKey][Day] = [Subject Info]
        $activeDays = []; // We might still want to show all days or just active ones? Usually M-F or M-S.
        
        // If we have shift sessions, we want to match subjects to them.
        foreach($class->classSubjects as $subject) {
            $rawSchedule = $subject->schedule_json;
            
            // Handle double-encoded JSON string if present
            if (is_string($rawSchedule)) {
                $decoded = json_decode($rawSchedule, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $rawSchedule = $decoded;
                }
            }

            $schedulesToParse = [];
            
            if (is_array($rawSchedule)) {
                 foreach($rawSchedule as $k => $v) {
                    // Key is likely Day (monday), Value is time string or array of strings
                    if (is_string($k) && !is_numeric($k)) {
                        if (is_array($v)) {
                            foreach($v as $timeStr) {
                                $schedulesToParse[] = $k . ' ' . $timeStr;
                            }
                        } else {
                            $schedulesToParse[] = $k . ' ' . $v;
                        }
                    } else {
                        // Numeric index, just value
                        if (is_array($v)) {
                             foreach($v as $subV) $schedulesToParse[] = $subV;
                        } else {
                             $schedulesToParse[] = $v;
                        }
                    }
                }
            } elseif (is_string($rawSchedule)) {
                $schedulesToParse[] = $rawSchedule;
            }

            foreach($schedulesToParse as $scStr) {
                if(!is_string($scStr)) continue;
                $lowerSc = strtolower($scStr);
                
                foreach($daysMap as $short => $fullLabel) {
                    if (strpos($lowerSc, $short) !== false) {
                        if(!in_array($fullLabel, $activeDays)) $activeDays[] = $fullLabel;

                        // Extract Time
                        $timePart = trim(preg_replace('/'. $short .'[a-z]*\.?/i', '', $scStr));
                        $timePart = trim($timePart, " ,-:");
                        
                        // Parse this timePart to match against Shift Rows
                        // We need a fuzzy match or exact match
                        // $timePart might be "08:00 - 10:00" or "8:00-10:00"
                        
                        // Normalize time part
                        $subjectTime = $timePart ? $timePart : "TBA";
                        
                        // Find matching row
                        $matchedRowKey = null;
                        
                        // Parse Subject Time Range
                        $parts = explode('-', $subjectTime);
                        $sStartRaw = trim($parts[0]);
                        $sEndRaw = isset($parts[1]) ? trim($parts[1]) : '';
                        
                        $sStart = $sStartRaw ? date('H:i', strtotime($sStartRaw)) : null;
                        $sEnd = $sEndRaw ? date('H:i', strtotime($sEndRaw)) : null;

                        if (!empty($rows)) {
                            foreach($rows as $rKey => $rData) {
                                // Normalize Row Time
                                $rStart = date('H:i', strtotime($rData['start']));
                                $rEnd = date('H:i', strtotime($rData['end']));
                                
                                $isOverlap = false;

                                if ($sStart && $sEnd) {
                                    // Check overlap: Start < End AND End > Start
                                    // String comparison of H:i works for same day times
                                    if ($sStart < $rEnd && $sEnd > $rStart) {
                                        $isOverlap = true;
                                    }
                                } elseif ($sStart) {
                                    // Only start time known, strict match on start
                                    if ($rStart == $sStart) {
                                        $isOverlap = true;
                                    }
                                }

                                if ($isOverlap) {
                                    $matchedRowKey = $rKey; 

                                    if(!isset($matrix[$rKey])) $matrix[$rKey] = [];
                                    if(!isset($matrix[$rKey][$fullLabel])) $matrix[$rKey][$fullLabel] = [];

                                    $matrix[$rKey][$fullLabel][] = [
                                         'subject' => $subject->subject->getTranslatedName(),
                                         'subject_code' => $subject->subject->code,
                                         'teacher' => $subject->teacher ? $subject->teacher->name : 'N/A',
                                         'room' => $subject->classroom ? $subject->classroom->room_number : '-',
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        // Sort Active Days
        $orderedDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $finalDays = array_intersect($orderedDays, $activeDays);
        if(empty($finalDays)) $finalDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']; // Default if empty

        // Sort Rows by Start Time
        uasort($rows, function($a, $b) {
            return strtotime($a['start']) - strtotime($b['start']);
        });
    @endphp

    <table class="timetable">
        <thead>
            <tr>
                <th style="width: 15%; background-color: #e9ecef;">{{ __('Time / Session') }}</th>
                @foreach($finalDays as $dayLabel)
                    <th style="width: {{ 85 / count($finalDays) }}%;">{{ __($dayLabel) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if(count($rows) > 0)
                @foreach($rows as $timeKey => $rowInfo)
                    @if($rowInfo['is_break'])
                        <tr>
                             <td colspan="{{ count($finalDays) + 1 }}" style="background-color: #f0f0f0; text-align: center; font-weight: bold; padding: 8px;">
                                 {{ $rowInfo['name'] }} ({{ \Carbon\Carbon::parse($rowInfo['start'])->format('H:i') }} - {{ \Carbon\Carbon::parse($rowInfo['end'])->format('H:i') }})
                             </td>
                        </tr>
                    @else
                        <tr>
                            <td style="font-weight: bold; background-color: #f8f9fa; vertical-align: middle; text-align: center;">
                                <div style="font-size: 10pt;">{{ $rowInfo['name'] }}</div>
                                <div style="font-size: 9pt; color: #555;">{{ \Carbon\Carbon::parse($rowInfo['start'])->format('H:i') }} - {{ \Carbon\Carbon::parse($rowInfo['end'])->format('H:i') }}</div>
                            </td>
                            @foreach($finalDays as $dayLabel)
                                <td style="vertical-align: top;">
                                    @if(isset($matrix[$timeKey][$dayLabel]))
                                        @foreach($matrix[$timeKey][$dayLabel] as $item)
                                            <div class="subject-block" style="margin-bottom: 8px; border-bottom: 1px dashed #eee; padding-bottom: 5px;">
                                                <div style="font-weight: bold; color: #000; font-size: 11pt;">{{ $item['subject_code'] }} - {{ $item['subject'] }}</div>
                                                <div style="font-size: 10pt; color: #333;">{{ __('Teacher') }}: {{ $item['teacher'] }}</div>
                                                @if($item['room'] != '-')
                                                    <div style="font-size: 9pt; font-style: italic;">{{ __('Room') }}: {{ $item['room'] }}</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div style="text-align: center; color: #eee;">-</div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="{{ count($finalDays) + 1 }}" class="text-center" style="padding: 30px;">
                        <em>{{ __('No schedule information available.') }}</em>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Spacer below table (replaces signature wrapper spacing) -->
    <div style="margin-top: 60px;"></div>

    <!-- sig-block: freely draggable anywhere on the page -->
    <div id="sig-block" style="position: absolute; min-width: 260px; min-height: 200px;
        padding: 10px; border: 1px dashed transparent; border-radius: 6px; user-select: none;">

        <!-- Drag handle bar -->
        <div class="sig-handle no-print" title="Drag block" style="cursor: grab; background: #f0f4ff; border: 1px dashed #aac; border-radius: 4px;
            text-align: center; font-size: 8pt; color: #667; padding: 3px 0; margin-bottom: 6px; letter-spacing: 1px;">&#8597; Move Block</div>
        <button class="btn-delete" title="Remove" onclick="this.parentElement.remove()">&#215;</button>

        <!-- Issued Date (draggable inside block) -->
        <div class="sig-item" style="position: absolute; top: 30px; left: 50%; transform: translateX(-50%); white-space: nowrap; padding: 4px 8px;
            border: 1px dashed transparent; border-radius: 3px; cursor: move;">
            <span contenteditable="true" style="border-bottom: 1px dashed #aaa; padding: 0 6px;"
                onfocus="this.parentElement.style.cursor='text'" onblur="this.parentElement.style.cursor='move'"
            >{{ \App\Models\Setting::get('timetable_issued_date', date('d-m-Y')) }}</span>
        </div>

        <!-- Director Title (draggable inside block) -->
        <div class="sig-item" style="position: absolute; top: 80px; left: 50%; transform: translateX(-50%); white-space: nowrap; padding: 4px 8px;
            border: 1px dashed transparent; border-radius: 3px; cursor: move;">
            <h4 contenteditable="true" style="margin: 0; text-transform: uppercase; letter-spacing: 1px;"
                onfocus="this.parentElement.style.cursor='text'" onblur="this.parentElement.style.cursor='move'"
            >{{ __('Director') }}</h4>
        </div>

        <!-- Name + Signature Line (draggable inside block) -->
        <div class="sig-item" style="position: absolute; top: 140px; left: 50%; transform: translateX(-50%); white-space: nowrap; text-align: center; padding: 4px 8px;
            border: 1px dashed transparent; border-radius: 3px; cursor: move;">
            <div style="border-top: 2px solid #000; padding-top: 6px; min-width: 200px; display: inline-block; text-align: center;">
                <span contenteditable="true" style="font-weight: bold; font-size: 13pt; display: block;"
                    onfocus="this.parentElement.parentElement.style.cursor='text'" onblur="this.parentElement.parentElement.style.cursor='move'"
                >{{ \App\Models\Setting::get('director_name', 'University Director') }}</span>
                <small style="color: #666; font-style: italic;">({{ __('Signature of Director') }})</small>
            </div>
        </div>

    </div>

    <script>
        /**
         * makeDraggable(el, container, handleEl)
         * Makes `el` draggable.
         * If `container` is provided, `el` is constrained within it.
         * `handleEl` is the element the user grabs; defaults to el itself.
         * Uses a delta-based approach for smooth dragging.
         */
        function makeDraggable(el, container = null, handleEl = null) {
            const grip = handleEl || el;
            let isDragging = false;
            let initialX, initialY; // Mouse position when drag starts
            let offsetX, offsetY;   // Offset from element's top-left to mouse position

            // Ensure element is positioned absolutely for top/left manipulation
            if (getComputedStyle(el).position === 'static') {
                el.style.position = 'absolute';
            }

            grip.addEventListener('mousedown', (e) => {
                // Prevent dragging if clicking on editable content or delete button
                if (e.target.contentEditable === 'true' || e.target.classList.contains('btn-delete')) {
                    return;
                }

                isDragging = true;
                e.preventDefault(); // Prevent default browser drag behavior

                // Get current position of the element relative to its offset parent
                const rect = el.getBoundingClientRect();
                const parentRect = (el.offsetParent || document.body).getBoundingClientRect();
                const currentLeft = rect.left - parentRect.left;
                const currentTop = rect.top - parentRect.top;

                // Calculate offset from element's top-left to mouse click
                offsetX = e.clientX - rect.left;
                offsetY = e.clientY - rect.top;

                initialX = e.clientX;
                initialY = e.clientY;

                // Set initial top/left based on current visual position
                el.style.left = currentLeft + 'px';
                el.style.top = currentTop + 'px';
                el.style.right = 'auto';
                el.style.bottom = 'auto';
                el.classList.add('dragging');

                // Add event listeners to document to handle dragging across the entire window
                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });

            function onMouseMove(e) {
                if (!isDragging) return;

                let newX = e.clientX - offsetX;
                let newY = e.clientY - offsetY;

                // Get container boundaries if applicable
                if (container) {
                    const containerRect = container.getBoundingClientRect();
                    const elRect = el.getBoundingClientRect();

                    // Adjust newX and newY relative to the container's top-left
                    newX = e.clientX - offsetX - containerRect.left;
                    newY = e.clientY - offsetY - containerRect.top;

                    // Constrain within container
                    newX = Math.max(0, Math.min(newX, containerRect.width - elRect.width));
                    newY = Math.max(0, Math.min(newY, containerRect.height - elRect.height));

                    // Auto-expand container height if needed (only for vertical overflow)
                    const neededHeight = newY + elRect.height + 10; // 10px padding
                    if (neededHeight > containerRect.height) {
                        container.style.minHeight = neededHeight + 'px';
                    }
                } else {
                    // For free dragging, adjust relative to document body (or offsetParent)
                    const parentRect = (el.offsetParent || document.body).getBoundingClientRect();
                    newX = e.clientX - offsetX - parentRect.left;
                    newY = e.clientY - offsetY - parentRect.top;

                    // Ensure it doesn't go off-screen to the left/top
                    newX = Math.max(0, newX);
                    newY = Math.max(0, newY);
                }

                el.style.left = newX + 'px';
                el.style.top = newY + 'px';
            }

            function onMouseUp() {
                if (isDragging) {
                    isDragging = false;
                    el.classList.remove('dragging');
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                }
            }

            // Visual hover cue
            el.addEventListener('mouseenter', () => {
                if (!isDragging) el.style.outline = '1px dashed #007bff';
            });
            el.addEventListener('mouseleave', () => {
                el.style.outline = '';
            });
        }

        /**
         * normalizePos(el)
         * Converts an element's CSS transform (like translateX(-50%)) into
         * equivalent top/left pixel values, making it easier to drag.
         */
        function normalizePos(el) {
            const style = getComputedStyle(el);
            const transform = style.transform;

            if (transform && transform !== 'none') {
                const rect = el.getBoundingClientRect();
                const parentRect = (el.offsetParent || document.body).getBoundingClientRect();

                el.style.position = 'absolute';
                el.style.left = (rect.left - parentRect.left) + 'px';
                el.style.top = (rect.top - parentRect.top) + 'px';
                el.style.transform = 'none'; // Remove transform after setting top/left
            }
        }

        // ── sig-block setup ─────────────────────────────────────────────
        const sigBlock = document.getElementById('sig-block');
        const sigHandle = sigBlock ? sigBlock.querySelector('.sig-handle') : null;

        if (sigBlock) {
            // Append to body if not already a direct child, to ensure correct positioning context
            if (sigBlock.parentNode !== document.body) {
                document.body.appendChild(sigBlock);
            }

            sigBlock.style.position = 'absolute';
            sigBlock.style.right = 'auto';
            sigBlock.style.bottom = 'auto';
            sigBlock.style.transform = 'none';

            // Place at bottom-right of document (initial placement)
            // Calculate based on document dimensions, not just clientWidth/Height
            const bodyRect = document.body.getBoundingClientRect();
            const docScrollX = window.scrollX || document.documentElement.scrollLeft;
            const docScrollY = window.scrollY || document.documentElement.scrollTop;

            // Get actual content width/height, considering scrollable area
            const docWidth = Math.max(
                document.body.scrollWidth, document.documentElement.scrollWidth,
                document.body.offsetWidth, document.documentElement.offsetWidth,
                document.body.clientWidth, document.documentElement.clientWidth
            );
            const docHeight = Math.max(
                document.body.scrollHeight, document.documentElement.scrollHeight,
                document.body.offsetHeight, document.documentElement.offsetHeight,
                document.body.clientHeight, document.documentElement.clientHeight
            );

            // Position relative to the document's content area
            sigBlock.style.left = Math.max(0, docWidth - sigBlock.offsetWidth - 20) + 'px';
            sigBlock.style.top = Math.max(0, docHeight - sigBlock.offsetHeight - 20) + 'px';

            // Normalize sig-items (they start with left:50% transform) BEFORE drag init
            sigBlock.querySelectorAll('.sig-item').forEach(item => normalizePos(item));

            // Wire drags
            makeDraggable(sigBlock, null, sigHandle || sigBlock); // sigBlock is freely draggable
            sigBlock.querySelectorAll('.sig-item').forEach(item => makeDraggable(item, sigBlock)); // sig-items are draggable within sigBlock
        }

        // ── Header labels ────────────────────────────────────────────────
        document.querySelectorAll('.header-drag .draggable-item').forEach(item => {
            normalizePos(item);
            makeDraggable(item, item.closest('.header-drag'));
        });

        // ── Custom labels ────────────────────────────────────────────────
        function initDraggable(item) {
            makeDraggable(item, null); // Custom labels are freely draggable
        }

        function addNewLabel() {
            const newItem = document.createElement('div');
            newItem.className = 'draggable-item';
            newItem.style.cssText = 'position:absolute; top:' + (window.scrollY + 200) + 'px; left:50px; width:auto;';
            newItem.innerHTML = `
                <button class="btn-delete" title="Remove" onclick="this.parentElement.remove()">×</button>
                <div contenteditable="true" style="padding:5px; outline:none; min-height:20px; white-space:nowrap;">New Custom Label</div>
            `;
            document.body.appendChild(newItem);
            initDraggable(newItem);
            setTimeout(() => newItem.querySelector('[contenteditable]').focus(), 100);
        }

        function changeFont(f) {
            document.body.style.fontFamily = f;
        }

        function changeFontSize(s) {
            document.body.style.fontSize = s;
        }

        function toggleControls() {
            const body = document.getElementById('controls-body');
            const icon = document.getElementById('toggle-icon');
            const panel = document.getElementById('controls-panel');
            if (body.style.maxHeight === '0px' || body.style.maxHeight === '') { // Check for empty string too
                body.style.maxHeight = '600px'; // Or a value large enough to contain content
                body.style.padding = '15px';
                icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                panel.style.opacity = '1';
            } else {
                body.style.maxHeight = '0px';
                body.style.padding = '0px';
                icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                panel.style.opacity = '0.9';
            }
        }

        window.addEventListener('load', () => {
            const sel = document.getElementById('font-selector');
            const f = document.body.style.fontFamily;
            if (sel && f) {
                for (let opt of sel.options) {
                    // Compare font family, handling quotes
                    const optValue = opt.value.replace(/['"]/g, '');
                    if (f.includes(optValue)) {
                        opt.selected = true;
                        break;
                    }
                }
            }
        });
    </script>
</body>

</html>
