<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Therapist;
use App\Models\Branch;
use Illuminate\Support\Str;

class TherapistController extends Controller
{
    public function index()
    {
        $therapists = Therapist::all();
        $branches = Branch::where('status', 'Active')->get();

        return view('admin.therapists.index', compact('therapists','branches'));

    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:therapists,email',
            'dob' => 'nullable|date',
            'contact' => 'nullable|string|max:10',
            'gender' => 'nullable|in:Male,Female,Other',
            'branch_id' => 'nullable|exists:branches,id',
            'designation' => 'nullable|string|max:255',
            'fixed_salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'working_hours_or_days' => 'nullable|numeric|min:0',
            'working_hours_type' => 'nullable|in:Hours,Days',
            'holidays' => 'nullable|integer|min:0',
            'payroll_calculation' => 'nullable|in:Fixed,Hourly,Commission',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $therapist = new Therapist();
        $therapist->name = $request->name;
        $therapist->email = $request->email;
        $therapist->dob = $request->dob;
        $therapist->contact = $request->contact;
        $therapist->gender = $request->gender;
        $therapist->branch_id = $request->branch_id;
        $therapist->designation = $request->designation;
        $therapist->fixed_salary = $request->fixed_salary;
        $therapist->hourly_rate = $request->hourly_rate;
        $therapist->working_hours_or_days = $request->working_hours_or_days;
        $therapist->working_hours_type = $request->working_hours_type;
        $therapist->holidays = $request->holidays;
        $therapist->payroll_calculation = $request->payroll_calculation;

        // Custom file upload handling
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/uploads/pfp'), $fileName);
            $profilePicturePath = 'images/uploads/pfp/' . $fileName;
        }

        $therapist->profile_picture = $profilePicturePath;
        $therapist->save();

        return redirect()->back()->with('success', 'Therapist added successfully!');
    }

    public function edit($id)
    {
        $therapist = Therapist::with('branch')->findOrFail($id);
        return response()->json($therapist);
    }
    public function update(Request $request, $id)
    {
        $therapist = Therapist::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:therapists,email,' . $id,
            'dob' => 'nullable|date',
            'contact' => 'nullable|string|max:10',
            'gender' => 'nullable|in:Male,Female,Other',
            'branch_id' => 'nullable|exists:branches,id',
            'designation' => 'nullable|string|max:255',
            'fixed_salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'working_hours_or_days' => 'nullable|numeric|min:0',
            'working_hours_type' => 'nullable|in:Hours,Days',
            'holidays' => 'nullable|integer|min:0',
            'payroll_calculation' => 'nullable|in:Fixed,Hourly,Commission',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $therapist->name = $request->name;
        $therapist->email = $request->email;
        $therapist->dob = $request->dob;
        $therapist->contact = $request->contact;
        $therapist->gender = $request->gender;
        $therapist->branch_id = $request->branch_id;
        $therapist->designation = $request->designation;
        $therapist->fixed_salary = $request->fixed_salary;
        $therapist->hourly_rate = $request->hourly_rate;
        $therapist->working_hours_or_days = $request->working_hours_or_days;
        $therapist->working_hours_type = $request->working_hours_type;
        $therapist->holidays = $request->holidays;
        $therapist->payroll_calculation = $request->payroll_calculation;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($therapist->profile_picture && file_exists(public_path($therapist->profile_picture))) {
                unlink(public_path($therapist->profile_picture));
            }

            $file = $request->file('profile_picture');
            $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/uploads/pfp'), $fileName);
            $therapist->profile_picture = 'images/uploads/pfp/' . $fileName;
        }

        $therapist->save();

        return redirect()->back()->with('success', 'Therapist updated successfully!');
    }

    public function destroy($id)
    {
        $therapist = Therapist::findOrFail($id);

        if ($therapist->profile_picture && file_exists(public_path($therapist->profile_picture))) {
            unlink(public_path($therapist->profile_picture));
        }

        $therapist->delete();

        return redirect()->back()->with('success', 'Therapist deleted successfully!');
    }


    // api

    public function index2()
    {
        $therapists = Therapist::all();
        $branches = Branch::where('status', 'Active')->get();

        return response()->json([
            'therapists' => $therapists,
            'branches' => $branches
        ]);
    }

    public function store2(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:therapists,email',
            'dob' => 'nullable|date',
            'contact' => 'nullable|string|max:10',
            'gender' => 'nullable|in:Male,Female,Other',
            'branch_id' => 'nullable|exists:branches,id',
            'designation' => 'nullable|string|max:255',
            'fixed_salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'working_hours_or_days' => 'nullable|numeric|min:0',
            'working_hours_type' => 'nullable|in:Hours,Days',
            'holidays' => 'nullable|integer|min:0',
            'payroll_calculation' => 'nullable|in:Fixed,Hourly,Commission',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $therapist = new Therapist();
        $therapist->fill($request->except('profile_picture'));

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/uploads/pfp'), $fileName);
            $therapist->profile_picture = 'images/uploads/pfp/' . $fileName;
        }

        $therapist->save();

        return response()->json(['message' => 'Therapist added successfully!', 'therapist' => $therapist], 201);
    }

    public function edit2($id)
    {
        $therapist = Therapist::with('branch')->findOrFail($id);
        return response()->json($therapist);
    }

    public function update2(Request $request, $id)
    {
        $therapist = Therapist::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:therapists,email,' . $id,
            'dob' => 'nullable|date',
            'contact' => 'nullable|string|max:10',
            'gender' => 'nullable|in:Male,Female,Other',
            'branch_id' => 'nullable|exists:branches,id',
            'designation' => 'nullable|string|max:255',
            'fixed_salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'working_hours_or_days' => 'nullable|numeric|min:0',
            'working_hours_type' => 'nullable|in:Hours,Days',
            'holidays' => 'nullable|integer|min:0',
            'payroll_calculation' => 'nullable|in:Fixed,Hourly,Commission',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $therapist->fill($request->except('profile_picture'));

        if ($request->hasFile('profile_picture')) {
            if ($therapist->profile_picture && file_exists(public_path($therapist->profile_picture))) {
                unlink(public_path($therapist->profile_picture));
            }

            $file = $request->file('profile_picture');
            $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/uploads/pfp'), $fileName);
            $therapist->profile_picture = 'images/uploads/pfp/' . $fileName;
        }

        $therapist->save();

        return response()->json(['message' => 'Therapist updated successfully!', 'therapist' => $therapist]);
    }

    public function destroy2($id)
    {
        $therapist = Therapist::findOrFail($id);

        if ($therapist->profile_picture && file_exists(public_path($therapist->profile_picture))) {
            unlink(public_path($therapist->profile_picture));
        }

        $therapist->delete();

        return response()->json(['message' => 'Therapist deleted successfully!']);
    }
}


