<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Therapy;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('therapies')->get();
        $therapies = Therapy::all(); // Fetch all services
        return view('admin.packages.index', compact('packages', 'therapies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'detail' => 'required|string',
            'validity_count' => 'required|integer|min:1',
            'validity_unit' => 'required|string|in:day,week,month,year',
            'therapies_data' => 'required|array|min:1', // Ensure at least one therapy is selected
            'therapies_data.*.id' => 'required|exists:therapies,id', // Each therapy ID must exist
            'therapies_data.*.qty' => 'required|integer|min:1', // Ensure valid quantity
        ]);

        // Extract therapy IDs from `therapies_data`
        $therapyIds = array_column($request->therapies_data, 'id');

        // Fetch therapy details from DB
        $therapies = Therapy::whereIn('id', $therapyIds)->get()->keyBy('id');

        $totalBasePrice = 0;
        $therapiesData = [];

        foreach ($request->therapies_data as $index => $therapyData) {
            $therapyId = $therapyData['id'];
            if (!isset($therapies[$therapyId])) {
                continue; // Skip if therapy doesn't exist
            }

            $qty = (int) $therapyData['qty'];
            $price = $therapies[$therapyId]->price;
            $totalPrice = $price * $qty;

            $totalBasePrice += $totalPrice;

            // Prepare data for pivot table
            $therapiesData[$therapyId] = [
                'qty' => $qty,
                'total' => $totalPrice,
            ];
        }

        // Create the package
        $package = Package::create([
            'name' => $request->name,
            'detail' => $request->detail,
            'validity_count' => $request->validity_count,
            'validity_unit' => $request->validity_unit,
            'price' => $totalBasePrice
        ]);

        // Attach therapies to the package
        $package->therapies()->attach($therapiesData);

        return redirect()->back()->with('success', 'Package added successfully!');
    }

    public function edit($id)
    {
        // Fetch the package with its related therapies and pivot data
        $package = Package::with('therapies')->findOrFail($id);

        return response()->json([
            'id' => $package->id,
            'name' => $package->name,
            'detail' => $package->detail,
            'validity_count' => $package->validity_count,
            'validity_unit' => $package->validity_unit,
            'price' => $package->price, // Total price before discount
            'therapies' => $package->therapies->mapWithKeys(function ($therapy) {
                return [
                    $therapy->id => [
                        'id' => $therapy->id,
                        'name' => $therapy->name,
                        'qty' => $therapy->pivot->qty,
                        'total' => $therapy->pivot->total,
                    ]
                ];
            })->all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'detail' => 'required|string',
            'validity_count' => 'required|integer|min:1',
            'validity_unit' => 'required|string|in:day,week,month,year',
            'therapies_data' => 'required|array|min:1', // Ensure at least one therapy is selected
            'therapies_data.*.id' => 'required|exists:therapies,id', // Each therapy ID must exist
            'therapies_data.*.qty' => 'required|integer|min:1', // Ensure valid quantity
        ]);

        // Find the package to update
        $package = Package::findOrFail($id);

        // Extract therapy IDs from `therapies_data`
        $therapyIds = array_column($request->therapies_data, 'id');

        // Fetch therapy details from DB
        $therapies = Therapy::whereIn('id', $therapyIds)->get()->keyBy('id');

        $totalBasePrice = 0;
        $therapiesData = [];

        foreach ($request->therapies_data as $index => $therapyData) {
            $therapyId = $therapyData['id'];
            if (!isset($therapies[$therapyId])) {
                continue; // Skip if therapy doesn't exist
            }

            $qty = (int) $therapyData['qty'];
            $price = $therapies[$therapyId]->price;
            $totalPrice = $price * $qty;

            $totalBasePrice += $totalPrice;

            // Prepare data for pivot table
            $therapiesData[$therapyId] = [
                'qty' => $qty,
                'total' => $totalPrice,
            ];
        }

        // Update the package
        $package->update([
            'name' => $request->name,
            'detail' => $request->detail,
            'validity_count' => $request->validity_count,
            'validity_unit' => $request->validity_unit,
            'price' => $totalBasePrice
        ]);

        // Sync therapies for the package (this will detach any not in the array)
        $package->therapies()->sync($therapiesData);

        return redirect()->back()->with('success', 'Package updated successfully!');
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->therapies()->detach();
        $package->delete();

        return redirect()->back()->with('success', 'Package deleted successfully!');
    }

    // api


    public function index2()
{
    $packages = Package::with('therapies')->get();
    $therapies = Therapy::all();

    return response()->json([
        'success' => true,
        'data' => [
            'packages' => $packages,
            'therapies' => $therapies
        ]
    ]);
}


public function store2(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'detail' => 'required|string',
        'validity_count' => 'required|integer|min:1',
        'validity_unit' => 'required|string|in:day,week,month,year',
        'therapies_data' => 'required|array|min:1',
        'therapies_data.*.id' => 'required|exists:therapies,id',
        'therapies_data.*.qty' => 'required|integer|min:1',
    ]);

    try {
        $therapyIds = array_column($request->therapies_data, 'id');
        $therapies = Therapy::whereIn('id', $therapyIds)->get()->keyBy('id');

        $totalBasePrice = 0;
        $therapiesData = [];

        foreach ($request->therapies_data as $therapyData) {
            $therapyId = $therapyData['id'];
            if (!isset($therapies[$therapyId])) continue;

            $qty = (int) $therapyData['qty'];
            $price = $therapies[$therapyId]->price;
            $totalPrice = $price * $qty;
            $totalBasePrice += $totalPrice;

            $therapiesData[$therapyId] = [
                'qty' => $qty,
                'total' => $totalPrice,
            ];
        }

        $package = Package::create([
            'name' => $request->name,
            'detail' => $request->detail,
            'validity_count' => $request->validity_count,
            'validity_unit' => $request->validity_unit,
            'price' => $totalBasePrice
        ]);

        $package->therapies()->attach($therapiesData);

        return response()->json([
            'success' => true,
            'message' => 'Package added successfully!',
            'data' => $package
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to add package.',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function edit2($id)
{
    $package = Package::with('therapies')->findOrFail($id);

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $package->id,
            'name' => $package->name,
            'detail' => $package->detail,
            'validity_count' => $package->validity_count,
            'validity_unit' => $package->validity_unit,
            'price' => $package->price,
            'therapies' => $package->therapies->mapWithKeys(function ($therapy) {
                return [
                    $therapy->id => [
                        'id' => $therapy->id,
                        'name' => $therapy->name,
                        'qty' => $therapy->pivot->qty,
                        'total' => $therapy->pivot->total,
                    ]
                ];
            }),
        ]
    ]);
}

public function update2(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'detail' => 'required|string',
        'validity_count' => 'required|integer|min:1',
        'validity_unit' => 'required|string|in:day,week,month,year',
        'therapies_data' => 'required|array|min:1',
        'therapies_data.*.id' => 'required|exists:therapies,id',
        'therapies_data.*.qty' => 'required|integer|min:1',
    ]);

    try {
        $package = Package::findOrFail($id);

        $therapyIds = array_column($request->therapies_data, 'id');
        $therapies = Therapy::whereIn('id', $therapyIds)->get()->keyBy('id');

        $totalBasePrice = 0;
        $therapiesData = [];

        foreach ($request->therapies_data as $therapyData) {
            $therapyId = $therapyData['id'];
            if (!isset($therapies[$therapyId])) continue;

            $qty = (int) $therapyData['qty'];
            $price = $therapies[$therapyId]->price;
            $totalPrice = $price * $qty;
            $totalBasePrice += $totalPrice;

            $therapiesData[$therapyId] = [
                'qty' => $qty,
                'total' => $totalPrice,
            ];
        }

        $package->update([
            'name' => $request->name,
            'detail' => $request->detail,
            'validity_count' => $request->validity_count,
            'validity_unit' => $request->validity_unit,
            'price' => $totalBasePrice
        ]);

        $package->therapies()->sync($therapiesData);

        return response()->json([
            'success' => true,
            'message' => 'Package updated successfully!',
            'data' => $package
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update package.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function destroy2($id)
{
    try {
        $package = Package::findOrFail($id);
        $package->therapies()->detach();
        $package->delete();

        return response()->json([
            'success' => true,
            'message' => 'Package deleted successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete package.',
            'error' => $e->getMessage()
        ], 500);
    }
}




}
