<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Branch;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->get();
        $branches = Branch::all();
        return view('admin.expenses.index', compact('expenses','branches'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'expense_name' => 'required|string|max:255',
            'who_made' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'branch_id' => 'required|exists:branches,id', // Validate that branch_id exists in the branches table
        ]);

        $branchId = auth()->user()->role_id != 0 ? auth()->user()->branch_id : $request->branch_id;


        // Create the expense record and associate the selected branch
        Expense::create([
            'expense_name' => $request->expense_name,
            'who_made' => $request->who_made,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
            'branch_id' => $branchId, // Store the branch_id selected by the user
        ]);

        return redirect()->back()->with('success', 'Expense added successfully.');
    }

        public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $branches = Branch::all();  // Get all branches for the dropdown

        return response()->json([
            'expense' => $expense,
            'branches' => $branches // Include branches data for dropdown
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'expense_name' => 'required|string|max:255',
            'who_made' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'branch_id' => 'required|exists:branches,id', // Validate that branch_id exists in the branches table
        ]);

        $expense = Expense::findOrFail($id);
        $branchId = auth()->user()->role_id != 0 ? auth()->user()->branch_id : $request->branch_id;

        $expense->update([
            'expense_name' => $request->expense_name,
            'who_made' => $request->who_made,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
            'branch_id' => $branchId, // Update the branch_id as well
        ]);

        return redirect()->back()->with('success', 'Expense updated successfully.');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->back()->with('success', 'Expense deleted successfully.');
    }

    // api

    public function index2()
{
    $expenses = Expense::latest()->get();
    $branches = Branch::all();

    return response()->json([
        'success' => true,
        'data' => [
            'expenses' => $expenses,
            'branches' => $branches,
        ]
    ]);
}

public function store2(Request $request)
{
    $request->validate([
        'expense_name' => 'required|string|max:255',
        'who_made' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'branch_id' => 'required|exists:branches,id',
    ]);

    try {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 401);
        }

        $branchId = $user->role_id != 0 ? $user->branch_id : $request->branch_id;

        $expense = Expense::create([
            'expense_name' => $request->expense_name,
            'who_made' => $request->who_made,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
            'branch_id' => $branchId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Expense added successfully.',
            'data' => $expense
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to add expense.',
            'error' => $e->getMessage()
        ], 500);
    }
}



public function edit2($id)
{
    $expense = Expense::findOrFail($id);
    $branches = Branch::all();

    return response()->json([
        'success' => true,
        'data' => [
            'expense' => $expense,
            'branches' => $branches,
        ]
    ]);
}


public function update2(Request $request, $id)
{
    $request->validate([
        'expense_name' => 'required|string|max:255',
        'who_made' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'branch_id' => 'required|exists:branches,id',
    ]);

    try {
        $expense = Expense::findOrFail($id);

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 401);
        }

        $branchId = $user->role_id != 0 ? $user->branch_id : $request->branch_id;

        $expense->update([
            'expense_name' => $request->expense_name,
            'who_made' => $request->who_made,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
            'branch_id' => $branchId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Expense updated successfully.',
            'data' => $expense
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update expense.',
            'error' => $e->getMessage()
        ], 500);
    }
}



public function destroy2($id)
{
    try {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expense deleted successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete expense.',
            'error' => $e->getMessage()
        ], 500);
    }
}



}
