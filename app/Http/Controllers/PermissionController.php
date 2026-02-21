<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    public function index()
    {
        if (!Gate::allows('manage_users')) {
            abort(403);
        }
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        if (!Gate::allows('manage_users')) {
            abort(403);
        }
        
        // Get all defined route names
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route->getName();
        })->filter()->unique()->values();

        return view('admin.permissions.create', compact('routes'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('manage_users')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|unique:permissions,name',
            'description' => 'nullable|string',
        ]);

        Permission::create($request->all());

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function destroy(Permission $permission)
    {
        if (!Gate::allows('manage_users')) {
            abort(403);
        }
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
