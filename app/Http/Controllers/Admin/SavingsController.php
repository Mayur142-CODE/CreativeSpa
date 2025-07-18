<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Saving;
use App\Models\Branch;

class SavingsController extends Controller
{


    public function index()
    {
        $user = auth()->user();
        $savings =  Saving::all();
        $branches = $user->branch_id ? Branch::where('id', $user->branch_id)->get() :Branch::all();
        return view('admin.savings.index', compact('savings', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
            'who_made' => 'required|string|max:255',
        ]);

        $branchId = auth()->user()->role_id != 0 ? auth()->user()->branch_id : $request->branch_id;

        Saving::create([
            'amount' => $request->amount,
            'date' => $request->date,
            'branch_id' => $branchId,
            'who_made' => $request->who_made,
        ]);

        return redirect()->back()->with('success', 'Saving added successfully');
    }


    public function edit($id)
    {
        $saving = Saving::findOrFail($id);

        return response()->json([
            'id' => $saving->id,
            'amount' => $saving->amount,
            'date' => $saving->date,
            'branch_id' => $saving->branch_id,
            'who_made' => $saving->who_made,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
            'who_made' => 'required|string|max:255',
        ]);

        $saving = Saving::findOrFail($id);
        $branchId = auth()->user()->role_id != 0 ? auth()->user()->branch_id : $request->branch_id;

        $saving->update([
            'amount' => $request->amount,
            'date' => $request->date,
            'branch_id' => $branchId,
            'who_made' => $request->who_made,
        ]);

        return redirect()->back()->with('success', 'Saving updated successfully');
    }


    public function destroy($id)
    {
        $saving = Saving::findOrFail($id);
        $saving->delete();
        return redirect()->back()->with('success', 'Saving deleted successfully');
    }

    // api

    public function index2(Request $request)
{
    $user = $request->user(); // Retrieves the authenticated user via Sanctum

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized access'
        ], 401);
    }

    $savings = Saving::all();
    $branches = $user->branch_id ? Branch::where('id', $user->branch_id)->get() : Branch::all();

    return response()->json([
        'success' => true,
        'data' => [
            'savings' => $savings,
            'branches' => $branches
        ]
    ]);
}

public function store2(Request $request)
{
    $validated = $request->validate([
        'amount' => 'required|numeric',
        'date' => 'required|date',
        'branch_id' => 'required|exists:branches,id',
        'who_made' => 'required|string|max:255',
    ]);

    $user = $request->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    $branchId = $user->role_id != 0 ? $user->branch_id : $validated['branch_id'];

    $saving = Saving::create([
        'amount' => $validated['amount'],
        'date' => $validated['date'],
        'branch_id' => $branchId,
        'who_made' => $validated['who_made'],
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Saving added successfully',
        'data' => $saving
    ], 201);
}

    public function edit2($id)
    {
        $saving = Saving::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $saving->id,
                'amount' => $saving->amount,
                'date' => $saving->date,
                'branch_id' => $saving->branch_id,
                'who_made' => $saving->who_made,
            ]
        ]);
    }

    public function update2(Request $request, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
            'who_made' => 'required|string|max:255',
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $saving = Saving::findOrFail($id);

        $branchId = $user->role_id != 0 ? $user->branch_id : $validated['branch_id'];

        $saving->update([
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'branch_id' => $branchId,
            'who_made' => $validated['who_made'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Saving updated successfully',
            'data' => $saving
        ]);
    }


    public function destroy2($id)
    {
        $saving = Saving::findOrFail($id);
        $saving->delete();

        return response()->json([
            'success' => true,
            'message' => 'Saving deleted successfully'
        ]);
    }
}
