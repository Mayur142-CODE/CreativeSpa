<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Telecaller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TelecallerImport;
use App\Models\Branch;
class TelecallerController extends Controller
{

    public function index()
    {
        $user = auth()->user(); // Get the authenticated user
        $branches = collect(); // Initialize branches collection
        $telecallers = collect(); // Initialize telecallers collection

        if ($user && $user->branch_id) {
            $branches = Branch::where('id', $user->branch_id)->get();
            $telecallers = Telecaller::where('branch_id', $user->branch_id)->get(); // Fetch telecallers for the user's branch
        } else {
            // User is not associated with a branch (e.g., admin), fetch all
            $branches = Branch::all();
            $telecallers = Telecaller::all();
        }

        return view('admin.telecaller.index', compact('telecallers', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|unique:telecaller,phone_number',
            'branch_id' => 'required|exists:branches,id',
        ]);

        Telecaller::create([
            'name' => $request->customer_name,
            'phone_number' => $request->phone_number,
            'branch_id' => $request->branch_id, // Save branch_id
        ]);

        return redirect()->back()->with('success', 'Telecaller created successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'branch_id' => 'required|exists:branches,id',
        ]);

        // Pass branch_id to import class
        Excel::import(new TelecallerImport($request->branch_id), $request->file('file'));

        return redirect()->back()->with('success', 'Telecallers imported successfully!');
    }



    // api

    public function index2(Request $request)
    {
        // Get the authenticated user using $request->user() for API authentication with Sanctum
        $user = $request->user();
        $branches = collect();
        $telecallers = collect();

        // Check if user is authenticated and has a branch_id
        if ($user && $user->branch_id) {
            // If the user has a branch_id, filter the branches and telecallers for that branch
            $branches = Branch::where('id', $user->branch_id)->get();
            $telecallers = Telecaller::where('branch_id', $user->branch_id)->get();
        } else {
            // If the user does not have a branch_id, return all branches and telecallers
            $branches = Branch::all();
            $telecallers = Telecaller::all();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'telecallers' => $telecallers,
                'branches' => $branches
            ]
        ]);
    }


    public function store2(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|unique:telecaller,phone_number',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $telecaller = Telecaller::create([
            'name' => $validated['customer_name'],
            'phone_number' => $validated['phone_number'],
            'branch_id' => $validated['branch_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Telecaller created successfully.',
            'data' => $telecaller
        ], 201);
    }

    public function import2(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'branch_id' => 'required|exists:branches,id',
        ]);

        try {
            Excel::import(new TelecallerImport($validated['branch_id']), $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Telecallers imported successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to import telecallers: ' . $e->getMessage()
            ], 500);
        }
    }

}
