<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;



class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('id','!=','0')->with('permissions')->get();
        $permissions = Permission::all();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array', // Ensure it's an array
            'permissions.*' => 'exists:permissions,id', // Ensure each permission exists
        ]);

        // Create new role
        $role = Role::create([
            'name' => $request->name
        ]);

        // Attach selected permissions to the role
        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        // Redirect with success message
        return redirect()->back()->with('success', 'Role added successfully!');
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        if($role->id == '0'){
            return redirect()->back()->with('error', 'You cannot update this role.');
        }

        $role->update(['name' => $request->name]);
        $role->permissions()->sync($request->permissions);

        return redirect()->back()->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if($role->id == '0'){
            return redirect()->back()->with('error', 'You cannot update this role.');
        }
        // Detach permissions
        $role->permissions()->detach();

        // Delete role
        $role->delete();

        return redirect()->back()->with('success', 'Role deleted successfully.');
    }


    // api


    public function index2()
    {
        $roles = Role::where('id', '!=', '0')->with('permissions')->get();
        $permissions = Permission::all();

        return response()->json([
            'success' => true,
            'data' => [
                'roles' => $roles,
                'permissions' => $permissions
            ]
        ]);
    }

    public function store2(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name']
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->attach($validated['permissions']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role added successfully!',
            'data' => $role->load('permissions')
        ], 201);
    }

    public function edit2($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function update2(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($role->id == '0') {
            return response()->json([
                'success' => false,
                'message' => 'You cannot update this role.'
            ], 403);
        }

        $role->update(['name' => $validated['name']]);
        $role->permissions()->sync($validated['permissions'] ?? []);

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully!',
            'data' => $role->load('permissions')
        ]);
    }

    public function destroy2($id)
    {
        $role = Role::findOrFail($id);

        if ($role->id == '0') {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete this role.'
            ], 403);
        }

        $role->permissions()->detach();
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully.'
        ]);
    }

}
