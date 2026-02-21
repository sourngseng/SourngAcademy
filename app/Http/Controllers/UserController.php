<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::query();
            return DataTables::of($data)
                ->addColumn('action', function($row){
                    $editBtn = '<button class="btn btn-primary btn-sm edit-btn" data-id="'.$row->id.'"><i class="fas fa-edit"></i></button>';
                    $deleteBtn = '<button class="btn btn-danger btn-sm delete-btn" data-url="'.route('admin.users.destroy', $row->id).'"><i class="fas fa-trash"></i></button>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->editColumn('role', function($row){
                    $badges = [
                        'admin' => 'badge-danger',
                        'staff' => 'badge-info',
                        'guest' => 'badge-secondary',
                    ];
                    $class = $badges[$row->role] ?? 'badge-primary';
                    return '<span class="badge '.$class.'">'.ucfirst($row->role).'</span>';
                })
                ->editColumn('created_at', function($row){
                    return $row->created_at->format('Y-m-d H:i');
                })
                ->rawColumns(['action', 'role'])
                ->make(true);
        }

        // Get all available roles for the form
        $roles = \App\Models\Role::orderBy('name')->get();
        return view('admin.users.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.users.index')->with('open_modal_create', true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $availableRoles = \App\Models\Role::pluck('name')->toArray();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in($availableRoles)],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), // Auto-verify created users
        ]);

        return response()->json(['success' => 'User created successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $availableRoles = \App\Models\Role::pluck('name')->toArray();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in($availableRoles)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['success' => 'User updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($id == auth()->id()) {
            return response()->json(['error' => 'You cannot delete yourself.'], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => 'User deleted successfully.']);
    }
}
