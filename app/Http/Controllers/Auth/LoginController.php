<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // dd('hellop');
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            // if (!$user->email_verified_at) {
            //     return redirect()->back()->with('error', 'Please verify your email before logging in.');
            // }

            Auth::login($user,true);

            // Check if user is a super admin (id == 0)
            if ($user->role_id == 0) {
                return redirect('admin/dashboard')->with('message', 'Welcome Super Admin '.$user->name . '!');
            } else {
                return redirect('admin/telecaller/index')->with('message', 'Welcome '.$user->name . '!');
            }
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

// api

public function index2()
{
    return response()->json([
        'message' => 'Login view endpoint - use POST /login for login',
        'status' => 'ok'
    ]);
}

public function authenticate2(Request $request)
{
    // Validate input data
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Find the user by email
    $user = User::where('email', $request->email)->first();

    // Check if the user exists and the password matches
    if ($user && Hash::check($request->password, $user->password)) {
        // Create an API token for the user
        $token = $user->createToken('creativeSpa')->plainTextToken;

        // Optionally, you can return the user details along with the token
        return response()->json([
            'message' => $user->role_id == 0
                ? 'Welcome Super Admin ' . $user->name . '!'
                : 'Welcome ' . $user->name . '!',
            'user' => $user,
            'token' => $token, // Provide the token
            'status' => 'success'
        ]);
    }

    // If user doesn't exist or password is incorrect
    return response()->json([
        'message' => 'Invalid credentials',
        'status' => 'error'
    ], 401);
}

public function logout2(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'message' => 'User not authenticated',
            'status' => 'error'
        ], 401);
    }

    // Revoke all tokens
    $user->tokens()->delete();

    return response()->json([
        'message' => 'Logged out successfully',
        'status' => 'success'
    ]);
}

}
