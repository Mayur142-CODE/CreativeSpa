<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Therapy;

class TherapyController extends Controller
{
    public function index()
    {
        $therapies = Therapy::all();
        return view('admin.therapies.index', compact('therapies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:therapies,name',
            'detail' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer'
        ]);

        Therapy::create([
            'name' => $request->name,
            'detail' => $request->detail,
            'price' => $request->price,
            'duration' => $request->duration
        ]);

        return redirect()->back()->with('success', 'Therapy added successfully!');
    }

    public function edit($id)
    {
        $service = Therapy::findOrFail($id);
        return response()->json($service);
    }

    public function update(Request $request, $id)
    {
        $service = Therapy::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:therapies,name,' . $service->id, // Assuming you have a $service model object
            'detail' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer',
        ]);

        $service->update([
            'name' => $request->name,
            'detail' => $request->detail,
            'price' => $request->price,
            'duration' => $request->duration
        ]);

        return redirect()->back()->with('success', 'Therapy updated successfully!');
    }

    public function destroy($id)
    {
        $service = Therapy::findOrFail($id);
        $service->delete();

        return redirect()->back()->with('success', 'Therapy deleted successfully!');
    }

    // api

    public function index2()
    {
        $therapies = Therapy::all();

        return response()->json([
            'success' => true,
            'data' => [
                'therapies' => $therapies
            ]
        ]);
    }

    public function store2(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:therapies,name',
            'detail' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer'
        ]);

        $therapy = Therapy::create([
            'name' => $validated['name'],
            'detail' => $validated['detail'],
            'price' => $validated['price'],
            'duration' => $validated['duration']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Therapy added successfully!',
            'data' => $therapy
        ], 201);
    }

    public function edit2($id)
    {
        $therapy = Therapy::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $therapy
        ]);
    }

    public function update2(Request $request, $id)
    {
        $therapy = Therapy::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:therapies,name,' . $id,
            'detail' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer',
        ]);

        $therapy->update([
            'name' => $validated['name'],
            'detail' => $validated['detail'],
            'price' => $validated['price'],
            'duration' => $validated['duration']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Therapy updated successfully!',
            'data' => $therapy
        ]);
    }

    public function destroy2($id)
    {
        $therapy = Therapy::findOrFail($id);
        $therapy->delete();

        return response()->json([
            'success' => true,
            'message' => 'Therapy deleted successfully!'
        ]);
    }

}
