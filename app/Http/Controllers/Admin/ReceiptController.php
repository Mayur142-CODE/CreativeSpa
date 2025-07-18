<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Therapist;
use App\Models\Customer;
use App\Models\Therapy;
use App\Models\ReceiptPackageTherapy;
use App\Models\ReceiptTherapy;
use App\Models\Package;
use App\Models\Branch;
use Barryvdh\DomPDF\Facade\Pdf;


class ReceiptController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Initialize query with proper joins to avoid N+1 problems
        $query = Receipt::with([
                'customer' => function ($query) {
                    $query->select('id', 'name', 'phone', 'branch_id');
                },
                'customer.branch' => function ($query) {
                    $query->select('id', 'name');
                },
                'receiptTherapies.therapist' => function ($query) {
                    $query->select('id', 'name');
                },
                'receiptPackageTherapies.therapist' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->select('receipts.*') // Explicitly select receipts columns
            ->join('customers', 'receipts.customer_id', '=', 'customers.id');

        // Filter by branch if user has one
        if ($user && $user->branch_id) {
            $query->where('customers.branch_id', $user->branch_id);
        }

        // Order by date descending (this should work globally)
        $receipts = $query->orderBy('receipts.date', 'desc')
                         ->orderBy('receipts.id', 'desc') // Secondary sort for consistent ordering
                         ->get();

        // Other data fetches remain the same
        $therapies = Therapy::all();
        $packages = Package::all();
        $branches = Branch::select('id', 'name')->get();

        $therapists = $user && $user->branch_id
            ? Therapist::where('branch_id', $user->branch_id)->get()
            : Therapist::all();

        return view('admin.receipts.index', compact('receipts', 'therapies', 'packages', 'therapists', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|regex:/^[0-9]{10}$/',
            'service_type' => 'required|in:therapy,package',
            'payment_method' => 'required|in:cash,online',
            'date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'therapies' => 'required|array|min:1',
            'therapies.*.therapy_id' => 'required|exists:therapies,id',
            'therapies.*.therapist_id' => 'required|exists:therapists,id',
            'therapies.*.price' => 'required|numeric|min:0',
            'therapies.*.qty' => 'required|integer|min:1',
            'therapies.*.time_in' => 'nullable|date_format:H:i',
            'therapies.*.time_out' => 'nullable|date_format:H:i|after:therapies.*.time_in',
            'therapies.*.total' => 'required|numeric|min:0',
            'package_id' => 'nullable|required_if:service_type,package|exists:packages,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        // Create or find customer
        $customer = Customer::firstOrCreate(
            ['phone' => $validated['customer_phone']],
            [
                'name' => $validated['customer_name'],
                'branch_id' => $validated['branch_id'],
            ]
        );

        // Update branch_id if customer already exists and branch_id is different
        if ($customer->wasRecentlyCreated === false && $customer->branch_id != $validated['branch_id']) {
            $customer->update(['branch_id' => $validated['branch_id']]);
        }

        // Create receipt
        $receipt = Receipt::create([
            'customer_id' => $customer->id,
            'service_type' => $validated['service_type'],
            'date' => $validated['date'],
            'total_amount' => $validated['total_amount'],
            'payment_method' => $validated['payment_method'],
            'package_id' => $validated['service_type'] === 'package' ? $validated['package_id'] : null,
        ]);

        // Create therapy records
        foreach ($validated['therapies'] as $therapy) {
            if ($validated['service_type'] === 'therapy') {
                ReceiptTherapy::create([
                    'receipt_id' => $receipt->id,
                    'therapy_id' => $therapy['therapy_id'],
                    'therapist_id' => $therapy['therapist_id'],
                    'price' => $therapy['price'],
                    'original_qty' => $therapy['qty'],
                    'total' => $therapy['total'],
                    'time_in' => $therapy['time_in'],
                    'time_out' => $therapy['time_out'],
                    'date' => now()->toDateString(), // Set date to today
                ]);
            } else {
                ReceiptPackageTherapy::create([
                    'receipt_id' => $receipt->id,
                    'package_id' => $validated['package_id'],
                    'therapy_id' => $therapy['therapy_id'],
                    'therapist_id' => $therapy['therapist_id'],
                    'price' => $therapy['price'],
                    'original_qty' => $therapy['qty'],
                    'total' => $therapy['total'] // Set date to today
                ]);
            }
        }

        return redirect()->back()->with('success', 'Receipt created successfully!');
    }

    public function edit($id)
    {
        $receipt = Receipt::with([
            'customer',
            'receiptTherapies.therapy',
            'receiptTherapies.therapist',
            'receiptPackageTherapies.therapy',
            'receiptPackageTherapies.therapist'
        ])->findOrFail($id);

        return response()->json($receipt);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|regex:/^[0-9]{10}$/',
            'service_type' => 'required|in:therapy,package',
            'payment_method' => 'required|in:cash,online',
            'date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'therapies' => 'required|array|min:1',
            'therapies.*.therapy_id' => 'required|exists:therapies,id',
            'therapies.*.therapist_id' => 'required|exists:therapists,id',
            'therapies.*.price' => 'required|numeric|min:0',
            'therapies.*.qty' => 'required|min:1',
            'therapies.*.time_in' => 'nullable|required_if:service_type,therapy',
            'therapies.*.time_out' => 'nullable|required_if:service_type,therapy|after:therapies.*.time_in',
            'therapies.*.total' => 'required|numeric|min:0',
            'package_id' => 'nullable|required_if:service_type,package|exists:packages,id',
            'package_price' => 'nullable|required_if:service_type,package|numeric|min:0',
            'branch_id' => 'required|exists:branches,id',
        ]);

        // Find the receipt
        $receipt = Receipt::findOrFail($id);

        // Update or create customer
        $customer = Customer::updateOrCreate(
            ['phone' => $validated['customer_phone']],
            [
                'name' => $validated['customer_name'],
                'branch_id' => $validated['branch_id'],
            ]
        );

        // Update receipt
        $receipt->update([
            'customer_id' => $customer->id,
            'service_type' => $validated['service_type'],
            'date' => $validated['date'],
            'total_amount' => $validated['total_amount'],
            'payment_method' => $validated['payment_method'],
            'package_id' => $validated['service_type'] === 'package' ? $validated['package_id'] : null,
        ]);

        // Handle therapies based on service type
        if ($validated['service_type'] === 'therapy') {
            // Get existing therapy IDs from the request
            $requestTherapyIds = collect($validated['therapies'])->pluck('therapy_id')->toArray();

            // Delete therapies that are not in the request
            $receipt->receiptTherapies()
                ->whereNotIn('therapy_id', $requestTherapyIds)
                ->delete();

            // Update or create therapies
            foreach ($validated['therapies'] as $therapy) {
                $receiptTherapy = $receipt->receiptTherapies()
                    ->where('therapy_id', $therapy['therapy_id'])
                    ->first();

                if ($receiptTherapy) {
                    // Update existing therapy (including time_in, time_out, and date)
                    $receiptTherapy->update([
                        'therapist_id' => $therapy['therapist_id'],
                        'price' => $therapy['price'],
                        'original_qty' => $therapy['qty'],
                        'total' => $therapy['total'],
                        'time_in' => $therapy['time_in'],
                        'time_out' => $therapy['time_out'],
                        'date' => now()->toDateString(), // Set date to today
                    ]);
                } else {
                    // Create new therapy
                    ReceiptTherapy::create([
                        'receipt_id' => $receipt->id,
                        'therapy_id' => $therapy['therapy_id'],
                        'therapist_id' => $therapy['therapist_id'],
                        'price' => $therapy['price'],
                        'original_qty' => $therapy['qty'],
                        'total' => $therapy['total'],
                        'time_in' => $therapy['time_in'],
                        'time_out' => $therapy['time_out'],
                        'date' => now()->toDateString(), // Set date to today
                    ]);
                }
            }
        } else {
            // For packages
            $requestTherapyIds = collect($validated['therapies'])->pluck('therapy_id')->toArray();

            // Delete package therapies that are not in the request
            $receipt->receiptPackageTherapies()
                ->whereNotIn('therapy_id', $requestTherapyIds)
                ->delete();

            foreach ($validated['therapies'] as $therapy) {
                $receiptPackageTherapy = $receipt->receiptPackageTherapies()
                    ->where('therapy_id', $therapy['therapy_id'])
                    ->first();

                if ($receiptPackageTherapy) {
                    // Update existing package therapy (basic info only)
                    $receiptPackageTherapy->update([
                        'therapist_id' => $therapy['therapist_id'],
                        'price' => $therapy['price'],
                        'original_qty' => $therapy['qty'],
                        'total' => $therapy['total'],
                    ]);
                } else {
                    // Create new package therapy
                    ReceiptPackageTherapy::create([
                        'receipt_id' => $receipt->id,
                        'package_id' => $validated['package_id'],
                        'therapy_id' => $therapy['therapy_id'],
                        'therapist_id' => $therapy['therapist_id'],
                        'price' => $therapy['price'],
                        'original_qty' => $therapy['qty'],
                        'total' => $therapy['total'],
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Receipt updated successfully!');
    }


    public function destroy($id)
    {
        $receipt = Receipt::with(['receiptTherapies', 'receiptPackageTherapies'])->find($id);

        if (!$receipt) {
            return redirect()->back()->with('error', 'Receipt not found.');
        }

        // Delete related records based on service type
        if ($receipt->service_type === 'therapy') {
            $receipt->receiptTherapies()->delete();
        } else {
            $receipt->receiptPackageTherapies()->delete();
        }

        $receipt->delete();

        return redirect()->back()->with('success', 'Receipt deleted successfully.');
    }

    public function searchCustomers(Request $request)
    {
        $search = $request->input('query', ''); // Default to empty string if no query
        $user = auth()->user(); // Get the authenticated user

        $query = Customer::query();

        // Apply branch filter if user has a branch_id
        if ($user && $user->branch_id) {
            $query->where('branch_id', $user->branch_id);
        }

        // Apply search filters
        $customers = $query->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
            })
            ->select('id', 'name', 'phone', 'branch_id')
            ->with(['branch' => function ($query) {
                $query->select('id', 'name'); // Assuming 'name' is the branch name column
            }])
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'branch_id' => $customer->branch_id,
                    'branch_name' => $customer->branch ? $customer->branch->name : null,
                ];
            });

        return response()->json($customers);
    }

    public function getPhone(Request $request)
    {
        $customer = Customer::where('name', $request->name)->first();

        if ($customer) {
            return response()->json(['phone' => $customer->phone]);
        } else {
            return response()->json(['phone' => null]);
        }
    }

    public function show($id)
    {
        $receipt = Receipt::findOrFail($id);
        return view('admin.receipts.show', compact('receipt'));
    }

    public function downloadPdf($id)
    {
        $receipt = Receipt::find($id);

        if (!$receipt) {
            return redirect()->back()->with('error', 'Receipt not found.');
        }

        $customer = $receipt->customer;
        $customerName = $customer->name ?? 'unknown_customer';
        $customerId = $customer->id ?? 'unknown_customer_id';

        $pdf = Pdf::loadView('admin.receipts.show', compact('receipt'));

        $fileName =  $receipt->id . '_' . $customerId . '_' .'receipt_' . str_replace(' ', '_', $customerName) . '.pdf';

        return $pdf->download($fileName);
    }

    public function getPackageTherapies(Request $request, $packageId)
    {
        $package = Package::with('therapies')->findOrFail($packageId);
        $therapies = $package->therapies->map(function ($therapy) {
            return [
                'id' => $therapy->id,
                'name' => $therapy->name,
                'price' => $therapy->price,
                'quantity' => $therapy->pivot->qty ?? 1,// Default to 1 if quantity isn't specified
            ];
        });

        return response()->json($therapies);
    }


    // api


    public function index2(Request $request)
    {
        $user = $request->user(); // Using $request->user() for API authentication

        // Check if the user is authenticated
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized access.',
                'status' => 'error'
            ], 401);
        }

        $query = Receipt::with([
                'customer:id,name,phone,branch_id',
                'customer.branch:id,name',
                'receiptTherapies.therapist:id,name',
                'receiptPackageTherapies.therapist:id,name',
            ])
            ->select('receipts.*')
            ->join('customers', 'receipts.customer_id', '=', 'customers.id');

        // Filtering based on the authenticated user's branch
        if ($user->branch_id) {
            $query->where('customers.branch_id', $user->branch_id);
        }

        $receipts = $query->orderBy('receipts.date', 'desc')
                        ->orderBy('receipts.id', 'desc')
                        ->get();

        return response()->json($receipts);
    }



    public function store2(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|regex:/^[0-9]{10}$/',
            'service_type' => 'required|in:therapy,package',
            'payment_method' => 'required|in:cash,online',
            'date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'therapies' => 'required|array|min:1',
            'therapies.*.therapy_id' => 'required|exists:therapies,id',
            'therapies.*.therapist_id' => 'required|exists:therapists,id',
            'therapies.*.price' => 'required|numeric|min:0',
            'therapies.*.qty' => 'required|integer|min:1',
            'therapies.*.time_in' => 'nullable|date_format:H:i',
            'therapies.*.time_out' => 'nullable|date_format:H:i|after:therapies.*.time_in',
            'therapies.*.total' => 'required|numeric|min:0',
            'package_id' => 'nullable|required_if:service_type,package|exists:packages,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $customer = Customer::firstOrCreate(
            ['phone' => $validated['customer_phone']],
            [
                'name' => $validated['customer_name'],
                'branch_id' => $validated['branch_id'],
            ]
        );

        if (!$customer->wasRecentlyCreated && $customer->branch_id != $validated['branch_id']) {
            $customer->update(['branch_id' => $validated['branch_id']]);
        }

        $receipt = Receipt::create([
            'customer_id' => $customer->id,
            'service_type' => $validated['service_type'],
            'date' => $validated['date'],
            'total_amount' => $validated['total_amount'],
            'payment_method' => $validated['payment_method'],
            'package_id' => $validated['service_type'] === 'package' ? $validated['package_id'] : null,
        ]);

        foreach ($validated['therapies'] as $therapy) {
            $data = [
                'receipt_id' => $receipt->id,
                'therapy_id' => $therapy['therapy_id'],
                'therapist_id' => $therapy['therapist_id'],
                'price' => $therapy['price'],
                'original_qty' => $therapy['qty'],
                'total' => $therapy['total'],
            ];

            if ($validated['service_type'] === 'therapy') {
                $data['time_in'] = $therapy['time_in'];
                $data['time_out'] = $therapy['time_out'];
                $data['date'] = now()->toDateString();
                ReceiptTherapy::create($data);
            } else {
                $data['package_id'] = $validated['package_id'];
                ReceiptPackageTherapy::create($data);
            }
        }

        return response()->json(['message' => 'Receipt created successfully!', 'receipt_id' => $receipt->id]);
    }


    public function edit2($id)
    {
        $receipt = Receipt::with([
            'customer',
            'receiptTherapies.therapy',
            'receiptTherapies.therapist',
            'receiptPackageTherapies.therapy',
            'receiptPackageTherapies.therapist'
        ])->findOrFail($id);

        return response()->json($receipt);
    }


    public function update2(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|regex:/^[0-9]{10}$/',
            'service_type' => 'required|in:therapy,package',
            'payment_method' => 'required|in:cash,online',
            'date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'therapies' => 'required|array|min:1',
            'therapies.*.therapy_id' => 'required|exists:therapies,id',
            'therapies.*.therapist_id' => 'required|exists:therapists,id',
            'therapies.*.price' => 'required|numeric|min:0',
            'therapies.*.qty' => 'required|min:1',
            'therapies.*.time_in' => 'nullable|required_if:service_type,therapy',
            'therapies.*.time_out' => 'nullable|required_if:service_type,therapy|after:therapies.*.time_in',
            'therapies.*.total' => 'required|numeric|min:0',
            'package_id' => 'nullable|required_if:service_type,package|exists:packages,id',
            'package_price' => 'nullable|required_if:service_type,package|numeric|min:0',
            'branch_id' => 'required|exists:branches,id',
        ]);


        $receipt = Receipt::findOrFail($id);
        $customer = Customer::updateOrCreate(
            ['phone' => $validated['customer_phone']],
            [
                'name' => $validated['customer_name'],
                'branch_id' => $validated['branch_id'],
            ]
        );

        $receipt->update([
            'customer_id' => $customer->id,
            'service_type' => $validated['service_type'],
            'date' => $validated['date'],
            'total_amount' => $validated['total_amount'],
            'payment_method' => $validated['payment_method'],
            'package_id' => $validated['service_type'] === 'package' ? $validated['package_id'] : null,
        ]);

        $therapyModel = $validated['service_type'] === 'therapy' ? ReceiptTherapy::class : ReceiptPackageTherapy::class;
        $relation = $validated['service_type'] === 'therapy' ? 'receiptTherapies' : 'receiptPackageTherapies';

        $existingIds = collect($validated['therapies'])->pluck('therapy_id')->toArray();
        $receipt->$relation()->whereNotIn('therapy_id', $existingIds)->delete();

        foreach ($validated['therapies'] as $therapy) {
            $model = $receipt->$relation()->where('therapy_id', $therapy['therapy_id'])->first();

            $updateData = [
                'therapist_id' => $therapy['therapist_id'],
                'price' => $therapy['price'],
                'original_qty' => $therapy['qty'],
                'total' => $therapy['total'],
            ];

            if ($validated['service_type'] === 'therapy') {
                $updateData['time_in'] = $therapy['time_in'];
                $updateData['time_out'] = $therapy['time_out'];
                $updateData['date'] = now()->toDateString();
            } else {
                $updateData['package_id'] = $validated['package_id'];
            }

            if ($model) {
                $model->update($updateData);
            } else {
                $therapyModel::create(array_merge(['receipt_id' => $receipt->id, 'therapy_id' => $therapy['therapy_id']], $updateData));
            }
        }

        return response()->json(['message' => 'Receipt updated successfully!']);
    }


    public function destroy2($id)
    {
        $receipt = Receipt::with(['receiptTherapies', 'receiptPackageTherapies'])->find($id);

        if (!$receipt) {
            return response()->json(['error' => 'Receipt not found.'], 404);
        }

        $receipt->service_type === 'therapy'
            ? $receipt->receiptTherapies()->delete()
            : $receipt->receiptPackageTherapies()->delete();

        $receipt->delete();

        return response()->json(['message' => 'Receipt deleted successfully.']);
    }

    public function searchCustomers2(Request $request)
{
    $search = $request->input('query', '');
    $user = $request->user(); // Use $request->user() for API authentication with Sanctum

    // Check if the user is authenticated
    if (!$user) {
        return response()->json([
            'message' => 'Unauthorized access.',
            'status' => 'error'
        ], 401);
    }

    $query = Customer::query();

    // Apply branch filtering if the user has a branch_id
    if ($user->branch_id) {
        $query->where('branch_id', $user->branch_id);
    }

    $customers = $query->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
        })
        ->select('id', 'name', 'phone', 'branch_id')
        ->with('branch:id,name')
        ->limit(10)
        ->get()
        ->map(function ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'branch_id' => $customer->branch_id,
                'branch_name' => $customer->branch?->name,
            ];
        });

    return response()->json($customers);
}


    public function getPhone2(Request $request)
    {
        $customer = Customer::where('name', $request->name)->first();
        return response()->json(['phone' => $customer->phone ?? null]);
    }


    public function show2($id)
    {
        $receipt = Receipt::with([
            'customer',
            'receiptTherapies.therapy',
            'receiptTherapies.therapist',
            'receiptPackageTherapies.therapy',
            'receiptPackageTherapies.therapist'
        ])->findOrFail($id);

        return response()->json($receipt);
    }

    public function downloadPdf2($id)
    {
        $receipt = Receipt::find($id);

        if (!$receipt) {
            return response()->json([
                'success' => false,
                'message' => 'Receipt not found.'
            ], 404);
        }

        $customer = $receipt->customer;
        $customerName = $customer->name ?? 'unknown_customer';
        $customerId = $customer->id ?? 'unknown_customer_id';

        $pdf = Pdf::loadView('admin.receipts.show', compact('receipt'));
        $fileName = $receipt->id . '_' . $customerId . '_' .'receipt_' . str_replace(' ', '_', $customerName) . '.pdf';

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
    }

    public function getPackageTherapies2(Request $request, $packageId)
    {
        $package = Package::with('therapies')->findOrFail($packageId);
        $therapies = $package->therapies->map(function ($therapy) {
            return [
                'id' => $therapy->id,
                'name' => $therapy->name,
                'price' => $therapy->price,
                'quantity' => $therapy->pivot->qty ?? 1,
            ];
        });

        return response()->json($therapies);
    }




}
