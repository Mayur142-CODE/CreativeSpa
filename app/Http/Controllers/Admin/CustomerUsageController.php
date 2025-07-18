<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReceiptTherapy;
use App\Models\ReceiptPackageTherapy;
use App\Models\Receipt;
use Illuminate\Support\Facades\Validator;

class CustomerUsageController extends Controller
{

    public function packagesUsage()
    {
        $user = auth()->user(); // Get the authenticated user

        $query = Receipt::whereNotNull('package_id')
            ->with([
                'customer' => function ($query) {
                    $query->select('id', 'name', 'phone', 'branch_id'); // Optimize by selecting only needed fields
                },
                'package' => function ($query) {
                    $query->select('id', 'name', 'price'); // Optimize package data
                },
                'receiptPackageTherapies.therapy' => function ($query) {
                    $query->select('id', 'name', 'price'); // Optimize therapy data
                },
                'receiptPackageTherapies.therapist' => function ($query) {
                    $query->select('id', 'name'); // Optimize therapist data
                },
            ]);

        // Apply branch filter if user has a branch_id
        if ($user && $user->branch_id) {
            $query->whereHas('customer', function ($query) use ($user) {
                $query->where('branch_id', $user->branch_id);
            });
        }

        $receipts = $query->get();

        return view('admin.usage.packages', compact('receipts'));
    }
        public function getReceiptDetails($id)
    {
        $receipt = Receipt::with([
            'customer',
            'package',
            'receiptPackageTherapies.therapy',
            'receiptPackageTherapies.therapist'
        ])->findOrFail($id);

        return response()->json($receipt);
    }


    public function updatePackageUsage(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'receipt_id' => 'required|exists:receipts,id',
            'therapy_id' => 'required|array',
            'redeem_qty' => 'required|array',
            'time_in' => 'nullable|array',
            'time_out' => 'nullable|array',
            'date' => 'nullable|array',
        ]);

        try {
            // Get the receipt ID
            $receiptId = $request->receipt_id;

            // Loop through therapies to update usage details
            foreach ($request->therapy_id as $therapyId => $id) {
                // Find the existing package therapy usage record
                $usage = ReceiptPackageTherapy::where('receipt_id', $receiptId)
                    ->where('id', $id)
                    ->first();

                if ($usage) {
                    // Update the existing record
                    $usage->redeem_qty = $request->redeem_qty[$id] ?? $usage->redeem_qty;
                    $usage->time_in = $request->time_in[$id] ?? $usage->time_in;
                    $usage->time_out = $request->time_out[$id] ?? $usage->time_out;
                    $usage->date = $request->date[$id] ?? $usage->date;
                    $usage->save();
                }
                else{
                    dd($request->therapy_id);
                }
            }

            return redirect()->back()->with('success', 'Package usage updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update package usage: ' . $e->getMessage());
        }
    }

    public function therapiesUsage()
{
    $user = auth()->user(); // Get the authenticated user

    $query = Receipt::where('service_type', 'therapy')
        ->whereNull('package_id')
        ->with([
            'customer' => function ($query) {
                $query->select('id', 'name', 'phone', 'branch_id'); // Optimize by selecting only needed fields
            },
            'receiptTherapies.therapy' => function ($query) {
                $query->select('id', 'name', 'price'); // Optimize therapy data
            },
            'receiptTherapies.therapist' => function ($query) {
                $query->select('id', 'name'); // Optimize therapist data
            },
        ]);

    // Apply branch filter if user has a branch_id
    if ($user && $user->branch_id) {
        $query->whereHas('customer', function ($query) use ($user) {
            $query->where('branch_id', $user->branch_id);
        });
    }

    $receipts = $query->get();

    return view('admin.usage.therapies', compact('receipts'));
}

    public function getReceiptTherapies($receiptId)
{
    $receipt = Receipt::with(['receiptTherapies.therapy', 'receiptTherapies.therapist', 'customer.branch'])
                ->findOrFail($receiptId);

    return response()->json([
        'success' => true,
        'data' => $receipt,
        'therapies' => $receipt->receiptTherapies->map(function($therapy) {
            return [
                'id' => $therapy->id,
                'name' => $therapy->therapy->name,
                'therapist' => $therapy->therapist->name,
                'quantity' => $therapy->original_qty,
                'time_in' => $therapy->time_in,
                'time_out' => $therapy->time_out,
                'status' => ($therapy->time_in && $therapy->time_out) ? 'used' : 'pending'
            ];
        })
    ]);
}

    public function updateTherapyUsage(Request $request)
    {
        $request->validate([
            'receipt_id' => 'required|exists:receipts,id',
            'usage_date' => 'required|date',
            'therapies' => 'required|array',
            'therapies.*.time_in' => 'required|date_format:H:i',
            'therapies.*.time_out' => 'required|date_format:H:i',
        ]);

        foreach ($request->therapies as $therapyId => $therapyData) {
            $receiptTherapy = ReceiptTherapy::find($therapyId);

            if ($receiptTherapy) {
                // Check if time_out is after time_in manually
                if (strtotime($therapyData['time_out']) <= strtotime($therapyData['time_in'])) {
                    return back()->withErrors(['therapies.' . $therapyId . '.time_out' => 'Time Out must be after Time In.']);
                }

                // Update therapy usage details
                $receiptTherapy->update([
                    'time_in' => $therapyData['time_in'],
                    'time_out' => $therapyData['time_out'],
                    'usage_date' => $request->usage_date,
                ]);
            }
        }

        return back()->with('success', 'Therapy usage updated successfully.');
    }


    // api

    public function packagesUsage2(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $query = Receipt::whereNotNull('package_id')
            ->with([
                'customer:id,name,phone,branch_id',
                'package:id,name,price',
                'receiptPackageTherapies.therapy:id,name,price',
                'receiptPackageTherapies.therapist:id,name',
            ]);

        if ($user->branch_id) {
            $query->whereHas('customer', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }

        $receipts = $query->get();

        return response()->json([
            'success' => true,
            'data' => $receipts
        ]);
    }


    public function getReceiptDetails2($id)
    {
        $receipt = Receipt::with([
            'customer',
            'package',
            'receiptPackageTherapies.therapy',
            'receiptPackageTherapies.therapist'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $receipt
        ]);
    }


    public function updatePackageUsage2(Request $request)
    {
        $request->validate([
            'receipt_id' => 'required|exists:receipts,id',
            'therapy_id' => 'required|array',
            'redeem_qty' => 'required|array',
            'time_in' => 'nullable|array',
            'time_out' => 'nullable|array',
            'date' => 'nullable|array',
        ]);

        try {
            foreach ($request->therapy_id as $therapyId => $id) {
                $usage = ReceiptPackageTherapy::where('receipt_id', $request->receipt_id)
                    ->where('id', $id)
                    ->first();

                if ($usage) {
                    $usage->update([
                        'redeem_qty' => $request->redeem_qty[$id] ?? $usage->redeem_qty,
                        'time_in' => $request->time_in[$id] ?? $usage->time_in,
                        'time_out' => $request->time_out[$id] ?? $usage->time_out,
                        'date' => $request->date[$id] ?? $usage->date,
                    ]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Package usage updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update package usage.', 'error' => $e->getMessage()], 500);
        }
    }


    public function therapiesUsage2(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    $query = Receipt::where('service_type', 'therapy')
        ->whereNull('package_id')
        ->with([
            'customer:id,name,phone,branch_id',
            'receiptTherapies.therapy:id,name,price',
            'receiptTherapies.therapist:id,name',
        ]);

    if ($user->branch_id) {
        $query->whereHas('customer', function ($q) use ($user) {
            $q->where('branch_id', $user->branch_id);
        });
    }

    $receipts = $query->get();

    return response()->json([
        'success' => true,
        'data' => $receipts
    ]);
}



    public function getReceiptTherapies2($receiptId)
    {
        $receipt = Receipt::with(['receiptTherapies.therapy', 'receiptTherapies.therapist', 'customer.branch'])
            ->findOrFail($receiptId);

        return response()->json([
            'success' => true,
            'data' => $receipt,
            'therapies' => $receipt->receiptTherapies->map(function ($therapy) {
                return [
                    'id' => $therapy->id,
                    'name' => $therapy->therapy->name,
                    'therapist' => $therapy->therapist->name,
                    'quantity' => $therapy->original_qty,
                    'time_in' => $therapy->time_in,
                    'time_out' => $therapy->time_out,
                    'status' => ($therapy->time_in && $therapy->time_out) ? 'used' : 'pending'
                ];
            })
        ]);
    }


    public function updateTherapyUsage2(Request $request)
    {
        $request->validate([
            'receipt_id' => 'required|exists:receipts,id',
            'usage_date' => 'required|date',
            'therapies' => 'required|array',
            'therapies.*.time_in' => 'required|date_format:H:i',
            'therapies.*.time_out' => 'required|date_format:H:i',
        ]);

        foreach ($request->therapies as $therapyId => $therapyData) {
            $receiptTherapy = ReceiptTherapy::find($therapyId);

            if ($receiptTherapy) {
                // Check if time_out is after time_in manually
                if (strtotime($therapyData['time_out']) <= strtotime($therapyData['time_in'])) {
                    return response()->json(['error' => 'Time Out must be after Time In.'], 400);
                }

                // Update therapy usage details
                $receiptTherapy->update([
                    'time_in' => $therapyData['time_in'],
                    'time_out' => $therapyData['time_out'],
                    'usage_date' => $request->usage_date,
                ]);
            }
        }

        return response()->json(['message' => 'Therapy usage updated successfully.']);
    }
    // public function updateTherapyUsage2(Request $request)
    // {
    //     $request->validate([
    //         'receipt_id' => 'required|exists:receipts,id',
    //         'usage_date' => 'required|date',
    //         'therapies' => 'required|array',
    //         'therapies.*.time_in' => 'required|date_format:H:i',
    //         'therapies.*.time_out' => 'required|date_format:H:i',
    //     ]);

    //     try {
    //         foreach ($request->therapies as $therapyId => $therapyData) {
    //             $receiptTherapy = ReceiptTherapy::find($therapyId);

    //             if ($receiptTherapy) {
    //                 if (strtotime($therapyData['time_out']) <= strtotime($therapyData['time_in'])) {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => "Time Out must be after Time In for therapy ID $therapyId"
    //                     ], 422);
    //                 }

    //                 $receiptTherapy->update([
    //                     'time_in' => $therapyData['time_in'],
    //                     'time_out' => $therapyData['time_out'],
    //                     'usage_date' => $request->usage_date,
    //                 ]);
    //             }
    //         }

    //         return response()->json(['success' => true, 'message' => 'Therapy usage updated successfully.']);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Failed to update therapy usage.', 'error' => $e->getMessage()], 500);
    //     }
    // }

}
