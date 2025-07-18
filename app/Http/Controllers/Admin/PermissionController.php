<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index() {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'description' => 'nullable|string',
        ]);

        Permission::create($request->all());

        return redirect()->back()->with('success', 'Permission added successfully.');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json($permission);
    }


    public function update(Request $request, Permission $permission) {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string',
        ]);

        $permission->update($request->all());

        return redirect()->back()->with('success', 'Permission updated successfully.');
    }


    public function destroy(Permission $permission) {
        $permission->delete();
        return redirect()->back()->with('success', 'Permission deleted successfully.');
    }

    // api

    public function index2()
    {
        $permissions = Permission::all();

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    public function store2(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'description' => 'nullable|string',
        ]);

        $permission = Permission::create($request->only(['name', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Permission added successfully.',
            'data' => $permission
        ], 201);
    }

    public function edit2($id)
    {
        $permission = Permission::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $permission
        ]);
    }


    public function update2(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string',
        ]);

        $permission->update($request->only(['name', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully.',
            'data' => $permission
        ]);
    }


    public function destroy2(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully.'
        ]);
    }


}
