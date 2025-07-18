<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Branch;
use App\Exports\CustomersExport;
use App\Imports\CustomerImport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index()
    {
        if(auth()->user()->role_id == 0)
        {
        $customers = Customer::all();
        $branches = Branch::all();
        }
        else{
            $customers = Customer::where('branch_id',auth()->user()->branch_id)->get();
            $branches = Branch::where('id',auth()->user()->branch_id)->get();
        }
        return view('admin.customers.index',compact('customers','branches'));
    }



    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:customers,phone',
            'address' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
            'date' => 'required|date',
        ]);

        // Create a new customer with the validated data
        Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'branch_id' => $request->branch_id,
            'date' => $request->date,  // Store the date field
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Customer added successfully!');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    public function update(Request $request, $id)
{
    // Validate the incoming request, including the new 'date' field
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20|unique:customers,phone,' . $id,
        'address' => 'nullable|string|max:500',
        'branch_id' => 'required|exists:branches,id', // Validate branch_id
        'date' => 'required|date', // Validate the date field
    ]);

    // Find the customer by ID
    $customer = Customer::findOrFail($id);

    // Update the customer data, including the 'date' field
    $customer->update([
        'name' => $request->name,
        'phone' => $request->phone,
        'address' => $request->address,
        'branch_id' => $request->branch_id,
        'date' => $request->date, // Update the date field
    ]);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Customer updated successfully!');
}


    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->back()->with('success', 'Customer deleted successfully!');
    }


    public function export()
    {
        return Excel::download(new CustomersExport, 'customer_list.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'customer_file' => 'required|mimes:xlsx,csv,xls',
            'date' => 'required|date',
            'branch_id' => auth()->user()->role_id == 0 ? 'required|exists:branches,id' : '',
        ]);

        $branchId = auth()->user()->role_id == 0 ? $request->branch_id : auth()->user()->branch_id;
        $date = $request->date;
        Excel::import(new CustomerImport($branchId,$date), $request->file('customer_file'));

        return redirect()->back()->with('success', 'Customers imported successfully.');
    }

    // api




    public function index2(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 401);
    }

    if ($user->role_id == 0) {
        $customers = Customer::all();
        $branches = Branch::all();
    } else {
        $customers = Customer::where('branch_id', $user->branch_id)->get();
        $branches = Branch::where('id', $user->branch_id)->get();
    }

    return response()->json([
        'status' => 'success',
        'customers' => $customers,
        'branches' => $branches
    ]);
}

    public function store2(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:customers,phone',
            'address' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
            'date' => 'required|date',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'branch_id' => $request->branch_id,
            'date' => $request->date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer added successfully!',
            'customer' => $customer
        ]);
    }


    public function edit2($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['status' => 'error', 'message' => 'Customer not found.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'customer' => $customer
        ]);
    }


    public function update2(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:customers,phone,' . $id,
            'address' => 'nullable|string|max:500',
            'branch_id' => 'required|exists:branches,id',
            'date' => 'required|date',
        ]);

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['status' => 'error', 'message' => 'Customer not found.'], 404);
        }

        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'branch_id' => $request->branch_id,
            'date' => $request->date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer updated successfully!',
            'customer' => $customer
        ]);
    }


    public function destroy2($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['status' => 'error', 'message' => 'Customer not found.'], 404);
        }

        $customer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Customer deleted successfully!'
        ]);
    }


    public function export2()
    {
        try {
            return Excel::download(new CustomersExport, 'customer_list.xlsx');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Export failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function import2(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 401);
    }

    $request->validate([
        'customer_file' => 'required|mimes:xlsx,csv,xls',
        'date' => 'required|date',
        'branch_id' => $user->role_id == 0 ? 'required|exists:branches,id' : '',
    ]);

    $branchId = $user->role_id == 0 ? $request->branch_id : $user->branch_id;
    $date = $request->date;

    try {
        Excel::import(new CustomerImport($branchId, $date), $request->file('customer_file'));

        return response()->json([
            'status' => 'success',
            'message' => 'Customers imported successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Import failed.',
            'error' => $e->getMessage()
        ], 500);
    }
}



}
