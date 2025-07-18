<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\User;

class BranchController extends Controller
{
    public function index(){

        $branches = Branch::all();
        return view('admin.branch.index',compact('branches'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code',
            'phone' => 'required|digits_between:7,15|numeric',
            'address' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $branch = new Branch();

        // Assign the input values to the model's attributes
        $branch->name = $request->name;
        $branch->code = $request->code;
        $branch->phone = (int) $request->phone; // Ensure phone is an integer
        $branch->address = $request->address; // Assuming you have manager_id in the request
        $branch->status = $request->status;

        // Save the branch instance to the database
        $branch->save();

        return redirect()->back()->with('success', 'Branch created successfully.');
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return response()->json(['branch' => $branch]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code,' . $id,
            'phone' => 'required|digits_between:7,15|numeric',
            'address' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $branch = Branch::findOrFail($id);
        $branch->update($request->all());

        return redirect()->back()->with('success', 'Branch updated successfully.');
    }

    public function destroy($id)
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return redirect()->back()->with('error', 'Branch not found.');
        }

        $branch->delete();

        return redirect()->back()->with('success', 'Branch deleted successfully.');
    }

    // api

    public function index2()
{
    $branches = Branch::all();
    return response()->json([
        'status' => 'success',
        'branches' => $branches
    ]);
}

public function store2(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:branches,code',
        'phone' => 'required|digits_between:7,15|numeric',
        'address' => 'required|string|max:255',
        'status' => 'required|in:Active,Inactive',
    ]);

    $branch = new Branch();
    $branch->name = $request->name;
    $branch->code = $request->code;
    $branch->phone = (int) $request->phone;
    $branch->address = $request->address;
    $branch->status = $request->status;
    $branch->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Branch created successfully.',
        'branch' => $branch
    ]);
}

public function edit2($id)
{
    $branch = Branch::find($id);

    if (!$branch) {
        return response()->json([
            'status' => 'error',
            'message' => 'Branch not found.'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'branch' => $branch
    ]);
}

public function update2(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:branches,code,' . $id,
        'phone' => 'required|digits_between:7,15|numeric',
        'address' => 'required|string|max:255',
        'status' => 'required|in:Active,Inactive',
    ]);

    $branch = Branch::find($id);

    if (!$branch) {
        return response()->json([
            'status' => 'error',
            'message' => 'Branch not found.'
        ], 404);
    }

    $branch->update($request->all());

    return response()->json([
        'status' => 'success',
        'message' => 'Branch updated successfully.',
        'branch' => $branch
    ]);
}
    public function destroy2($id)
{
    $branch = Branch::find($id);

    if (!$branch) {
        return response()->json([
            'status' => 'error',
            'message' => 'Branch not found.'
        ], 404);
    }

    $branch->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Branch deleted successfully.'
    ]);


}
}
