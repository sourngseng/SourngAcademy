<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Modules\Core\Models\Campus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Shift::with('campus')->select('shifts.*');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('campus_id', function($row) {
                    return $row->campus ? $row->campus->name : 'All Campuses';
                })
                ->editColumn('sessions', function($row) {
                    if(!empty($row->sessions) && is_array($row->sessions)) {
                         $html = '';
                         foreach($row->sessions as $s) {
                             $start = isset($s['start']) ? \Carbon\Carbon::parse($s['start'])->format('g:i A') : '';
                             $end = isset($s['end']) ? \Carbon\Carbon::parse($s['end'])->format('g:i A') : '';
                             $html .= '<span class="badge badge-info mr-1 mb-1">'. ($s['name'] ?? 'S') . ': ' . $start . ' - ' . $end . '</span>';
                         }
                         return $html;
                    }
                    return '<span class="text-muted">No sessions</span>';
                })
                ->editColumn('is_active', function($row) {
                    return $row->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.shifts.edit', $row->id);
                    $duplicateUrl = route('admin.shifts.duplicate', $row->id);
                    $deleteUrl = route('admin.shifts.destroy', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');
                    return '
                        <div class="d-flex justify-content-start">
                            <a href="'.$editUrl.'" class="btn btn-outline-warning btn-sm mr-2" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="'.$duplicateUrl.'" method="POST" class="d-inline duplicate-form mr-2">
                                '.$csrf.'
                                <button type="button" class="btn btn-outline-info btn-sm" title="Duplicate" onclick="confirmDuplicate(this)">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </form>
                            <form action="'.$deleteUrl.'" method="POST" class="d-inline delete-form">
                                '.$csrf.'
                                '.$method.'
                                <button type="button" class="btn btn-outline-danger btn-sm" title="Delete" onclick="confirmDelete(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['sessions', 'is_active', 'action'])
                ->make(true);
        }
        return view('admin.shifts.index');
    }

    public function create()
    {
        $campuses = Campus::all();
        $languages = config('app.available_locales');
        return view('admin.shifts.create', compact('campuses', 'languages'));
    }

    public function store(Request $request)
    {
        $languages = config('app.available_locales');
        $rules = [
            'campus_id' => 'nullable|exists:campuses,id',
            'sessions' => 'required|array',
            'sessions.*.name' => 'required|string',
            'sessions.*.start' => 'required',
            'sessions.*.end' => 'required',
        ];

        foreach ($languages as $code => $lang) {
            $rules["name_$code"] = ($code == config('app.fallback_locale', 'en')) ? 'required|string|max:255' : 'nullable|string|max:255';
        }

        $request->validate($rules);

        $translations = [];
        foreach ($languages as $code => $lang) {
             $translations[$code] = ['name' => $request->input("name_$code")];
        }

        $fallbackLocale = config('app.fallback_locale', 'en');
        $primaryName = $request->input("name_$fallbackLocale");

        Shift::create([
            'campus_id' => $request->campus_id,
            'name' => $primaryName,
            'translations' => $translations,
            'sessions' => $request->sessions,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift created successfully.');
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        $campuses = Campus::all();
        $languages = config('app.available_locales');
        return view('admin.shifts.edit', compact('shift', 'campuses', 'languages'));
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);
        
        $languages = config('app.available_locales');
        $rules = [
            'campus_id' => 'nullable|exists:campuses,id',
            'sessions' => 'required|array',
            'sessions.*.name' => 'required|string',
            'sessions.*.start' => 'required',
            'sessions.*.end' => 'required',
        ];

        foreach ($languages as $code => $lang) {
            $rules["name_$code"] = ($code == config('app.fallback_locale', 'en')) ? 'required|string|max:255' : 'nullable|string|max:255';
        }

        $request->validate($rules);

        $translations = [];
        foreach ($languages as $code => $lang) {
             $translations[$code] = ['name' => $request->input("name_$code")];
        }

        $fallbackLocale = config('app.fallback_locale', 'en');
        $primaryName = $request->input("name_$fallbackLocale");

        $shift->update([
            'campus_id' => $request->campus_id,
            'name' => $primaryName,
            'translations' => $translations,
            'sessions' => $request->sessions,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift updated successfully.');
    }

    public function duplicate($id)
    {
        $shift = Shift::findOrFail($id);
        
        $newShift = $shift->replicate();
        $newShift->name = $newShift->name . ' (Copy)';
        
        $translations = $shift->translations ?? [];
        $languages = config('app.available_locales');
        
        foreach($languages as $code => $lang) {
            if(isset($translations[$code]['name'])) {
                $translations[$code]['name'] .= ' (Copy)';
            }
        }
        
        $newShift->translations = $translations;
        $newShift->save();

        return redirect()->route('admin.shifts.index')->with('success', 'Shift duplicated successfully.');
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();
        return redirect()->route('admin.shifts.index')->with('success', 'Shift deleted successfully.');
    }
}
