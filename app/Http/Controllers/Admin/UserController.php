<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Branch;
use App\Models\User;
use App\Models\Role;


class UserController extends Controller
{
    public function index()
    {
        $branches = Branch::where('status', 'Active')->get();
        $users = User::where('role_id','!=',0)->get();
        $roles = Role::where('id','!=',0)->get();
        return view('admin.users.index', compact('branches', 'users', 'roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|numeric|digits:10',
            'address' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
            'role_id' => 'required|exists:roles,id', // Validate role
            'status' => 'required|in:Active,Inactive',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/uploads/pfp'), $fileName);
            $profilePicturePath = 'images/uploads/pfp/' . $fileName;
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->branch_id = $request->input('branch_id');
        $user->role_id = $request->input('role_id');
        $user->status = $request->input('status');
        $user->profile_picture = $profilePicturePath;

        $user->save();

        return redirect()->back()->with('success', 'User created successfully!');
    }

    public function edit($id)
    {

        $user = User::findOrFail($id);
        return response()->json($user);

    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role_id == '0') {
            return redirect()->back()->with('error', 'Super Admin cannot be Updated.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|numeric|digits:10',
            'address' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:Active,Inactive',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',

        ]);

        // If validation fails, return back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle profile picture update if provided
        $profilePicturePath = $user->profile_picture; // Keep existing profile picture if no new one is uploaded
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                unlink(public_path($user->profile_picture));
            }

            $file = $request->file('profile_picture');
            $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/uploads/pfp'), $fileName);

            $profilePicturePath = 'images/uploads/pfp/' . $fileName;
        }

        // Update the user data
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Only update password if it's provided
        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->branch_id = $request->input('branch_id');
        $user->role_id = $request->input('role_id');
        $user->status = $request->input('status');
        $user->profile_picture = $profilePicturePath;
        $user->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        // Find the user and delete
        $user = User::findOrFail($id);

        // Prevent deleting Super Admin
        if ($user->role_id == '0') {
            return redirect()->back()->with('error', 'Super Admin cannot be deleted.');
        }

        // Delete profile picture if it exists
        if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
            unlink(public_path($user->profile_picture));
        }

        // Delete user
        $user->delete();

        // Return a success response or redirect as needed
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    // api

    public function index2()
    {
        $branches = Branch::where('status', 'Active')->get();
        $users = User::where('role_id', '!=', 0)->get();
        $roles = Role::where('id', '!=', 0)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'branches' => $branches,
                'users' => $users,
                'roles' => $roles
            ]
        ]);
    }

    public function store2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|numeric|digits:10',
            'address' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:Active,Inactive',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/uploads/pfp'), $fileName);
            $profilePicturePath = 'images/uploads/pfp/' . $fileName;
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->branch_id = $request->input('branch_id');
        $user->role_id = $request->input('role_id');
        $user->status = $request->input('status');
        $user->profile_picture = $profilePicturePath;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
            'data' => $user
        ], 201);
    }

    public function edit2($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function update2(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role_id == '0') {
            return response()->json([
                'success' => false,
                'message' => 'Super Admin cannot be updated.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|numeric|digits:10',
            'address' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:Active,Inactive',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $profilePicturePath = $user->profile_picture;
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                unlink(public_path($user->profile_picture));
            }

            $file = $request->file('profile_picture');
            $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/uploads/pfp'), $fileName);
            $profilePicturePath = 'images/uploads/pfp/' . $fileName;
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->branch_id = $request->input('branch_id');
        $user->role_id = $request->input('role_id');
        $user->status = $request->input('status');
        $user->profile_picture = $profilePicturePath;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
            'data' => $user
        ]);
    }

    public function destroy2($id)
    {
        $user = User::findOrFail($id);

        if ($user->role_id == '0') {
            return response()->json([
                'success' => false,
                'message' => 'Super Admin cannot be deleted.'
            ], 403);
        }

        if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
            unlink(public_path($user->profile_picture));
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }


}
