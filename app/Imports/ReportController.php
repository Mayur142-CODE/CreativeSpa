<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Therapy;
use App\Models\Expense;
use App\Models\Branch;
use Carbon\Carbon;
use App\Models\Saving;


class ReportController extends Controller
{
    public function sales_summaryReport(Request $request)
    {
        $query = Receipt::with(['customer', 'package', 'receiptTherapies', 'receiptPackageTherapies']);

        // Apply date filter if provided
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $receipts = $query->get();

        // Calculate total number of receipts and total amount
        $totalReceipts = $receipts->count();
        $totalAmount = $receipts->sum('total_amount');

        return view('admin.reports.sales-summary', compact('receipts', 'totalReceipts', 'totalAmount'));
    }

    public function customerReport(Request $request)
    {
        // Fetch filtered customers based on date range (if provided)
        $query = Customer::query();

        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Get customers
        $customers = $query->with('branch')->get();

        // Get total customer count
        $totalCustomers = Customer::count();

        // Get active customers who made a purchase in the last 30 days using 'date' column
        $activeCustomers = Customer::whereHas('receipts', function ($query) {
            $query->whereDate('date', '>=', now()->subDays(30));
        })->count();

        return view('admin.reports.customer', compact('customers', 'totalCustomers', 'activeCustomers'));
    }

    public function appointmentsReport(Request $request)
    {
        // Fetch appointments with related models
        $query = Receipt::with(['customer', 'package.therapies', 'receiptTherapies.therapy', 'receiptPackageTherapies.therapy']);

        // Apply date filter if provided
        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Get all appointments
        $appointments = $query->orderBy('date', 'asc')->get();

        // Get appointment counts
        $totalAppointments = Receipt::count();
        $todayAppointments = Receipt::whereDate('date', now()->format('Y-m-d'))->count();
        $upcomingAppointments = Receipt::whereDate('date', '>', now()->format('Y-m-d'))->count();
        $completedAppointments = Receipt::whereDate('date', '<', now()->format('Y-m-d'))->count();

        return view('admin.reports.appointments', compact(
            'appointments', 'totalAppointments', 'todayAppointments', 'upcomingAppointments', 'completedAppointments'
        ));
    }

    public function packagesReport(Request $request)
    {
        $query = Receipt::whereHas('package');

        // Apply date filters if provided
        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $receipts = $query->get();

        // Summary Data
        $totalPackages = $receipts->count();
        $usedPackages = 0;
        $pendingPackages = 0;
        $expiredPackages = 0;
        $expiringSoonPackages = 0;

        foreach ($receipts as $receipt) {
            $validityCount = $receipt->package->validity_count ?? 0;
            $validityUnit = $receipt->package->validity_unit ?? 'day';
            $purchaseDate = \Carbon\Carbon::parse($receipt->date);
            $expiryDate = $purchaseDate->copy()->add($validityCount, $validityUnit);
            $daysToExpire = now()->diffInDays($expiryDate, false);

            // Fetch used therapies (those that have a recorded usage)
            $usedTherapies = $receipt->receiptPackageTherapies()
                ->whereNotNull('time_in')
                ->whereNotNull('time_out')
                ->whereNotNull('date')
                ->with('therapy')
                ->get();

            // Get all therapies in the package
            $allTherapies = $receipt->package->therapies;

            // Identify not used therapies
            $usedTherapyIds = $usedTherapies->pluck('therapy_id')->toArray();
            $notUsedTherapies = $allTherapies->whereNotIn('id', $usedTherapyIds);

            $usedTherapyCount = $usedTherapies->count();
            $totalTherapies = $allTherapies->count();
            $remainingTherapies = $totalTherapies - $usedTherapyCount;

            // Determine status
            if ($daysToExpire < 0) {
                $receipt->status = 'Expired';
                $expiredPackages++;
            } elseif ($daysToExpire <= 3) {
                $receipt->status = 'Expiring Soon';
                $expiringSoonPackages++;
            } elseif ($remainingTherapies == 0) {
                $receipt->status = 'Used';
                $usedPackages++;
            } else {
                $receipt->status = 'Pending';
                $pendingPackages++;
            }

            // Add therapy details to the receipt
            $receipt->usedTherapies = $usedTherapies;
            $receipt->notUsedTherapies = $notUsedTherapies;
            $receipt->remainingTherapies = $remainingTherapies;
        }

        return view('admin.reports.packages', compact(
            'receipts', 'totalPackages', 'usedPackages', 'pendingPackages', 'expiredPackages', 'expiringSoonPackages'
        ));
    }

    public function branchReport(Request $request)
    {
        $duration = $request->input('duration', 'all');
        $branchId = $request->input('branch_id', 'all');


        $queryDate = match ($duration) {
            'today' => [Carbon::today(), Carbon::tomorrow()],
            'yesterday' => [Carbon::yesterday(), Carbon::today()],
            'this_week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'this_month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'last_3_months' => [Carbon::now()->subMonths(3), Carbon::now()],
            'this_year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            'last_3_years' => [Carbon::now()->subYears(3), Carbon::now()],
            default => [null, null],
        };

        [$start, $end] = $queryDate;

        $receiptQuery = Receipt::with(['customer', 'receiptTherapies', 'receiptPackageTherapies']);
        $expenseQuery = Expense::query();
        $savingsQuery = Saving::query();

        if ($start && $end) {
            $receiptQuery->whereBetween('date', [$start, $end]);
            $expenseQuery->whereBetween('date', [$start, $end]);
            $savingsQuery->whereBetween('date', [$start, $end]);

        }

        if ($branchId !== 'all') {
            $receiptQuery->whereHas('customer', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });

            $expenseQuery->where('branch_id', $branchId);
            $savingsQuery->where('branch_id', $branchId);
        }

        $receipts = $receiptQuery->get();

        // Unique customer count from receipt data
        $totalCustomers = $receipts->pluck('customer_id')->unique()->count();
        $totalTherapies = $receipts->sum(fn($r) => $r->receiptTherapies->count());
        $totalPackages = $receipts->sum(fn($r) => $r->receiptPackageTherapies->count());
        $codIncome = $receipts->where('payment_method', 'cash')->sum('total_amount');
        $onlineIncome = $receipts->where('payment_method', 'online')->sum('total_amount');
        $totalIncome = $codIncome + $onlineIncome;
        $revenue = $totalIncome - $expenseQuery->sum('amount');
        $totalSavings = $savingsQuery->sum('amount');
        $data = [
            'branches' => Branch::all(),
            'selectedDuration' => $duration,
            'selectedBranch' => $branchId,
            'totalCustomers' => $totalCustomers,
            'totalTherapies' => $totalTherapies,
            'totalPackages' => $totalPackages,
            'codIncome' => $codIncome,
            'onlineIncome' => $onlineIncome,
            'totalExpenses' => $expenseQuery->sum('amount'),
            'totalExpenses' => $expenseQuery->sum('amount'),
            'revenue' => $revenue,
            'totalSavings' => $totalSavings,

        ];

        return view('admin.reports.branch', $data);
    }



    // api


    public function sales_summaryReport2(Request $request)
    {
        $query = Receipt::with(['customer', 'package', 'receiptTherapies', 'receiptPackageTherapies']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $receipts = $query->get();
        $totalReceipts = $receipts->count();
        $totalAmount = $receipts->sum('total_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'receipts' => $receipts,
                'totalReceipts' => $totalReceipts,
                'totalAmount' => $totalAmount
            ]
        ]);
    }

    public function customerReport2(Request $request)
    {
        $query = Customer::query();

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date); // Changed 'date' to 'created_at' assuming customer creation date
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $customers = $query->with('branch')->get();
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::whereHas('receipts', function ($query) {
            $query->whereDate('date', '>=', now()->subDays(30));
        })->count();

        return response()->json([
            'success' => true,
            'data' => [
                'customers' => $customers,
                'totalCustomers' => $totalCustomers,
                'activeCustomers' => $activeCustomers
            ]
        ]);
    }

    public function appointmentsReport2(Request $request)
    {
        $query = Receipt::with(['customer', 'package.therapies', 'receiptTherapies.therapy', 'receiptPackageTherapies.therapy']);

        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $appointments = $query->orderBy('date', 'asc')->get();
        $totalAppointments = Receipt::count();
        $todayAppointments = Receipt::whereDate('date', now()->format('Y-m-d'))->count();
        $upcomingAppointments = Receipt::whereDate('date', '>', now()->format('Y-m-d'))->count();
        $completedAppointments = Receipt::whereDate('date', '<', now()->format('Y-m-d'))->count();

        return response()->json([
            'success' => true,
            'data' => [
                'appointments' => $appointments,
                'totalAppointments' => $totalAppointments,
                'todayAppointments' => $todayAppointments,
                'upcomingAppointments' => $upcomingAppointments,
                'completedAppointments' => $completedAppointments
            ]
        ]);
    }

    public function packagesReport2(Request $request)
    {
        $query = Receipt::whereHas('package');

        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $receipts = $query->get();

        $totalPackages = $receipts->count();
        $usedPackages = 0;
        $pendingPackages = 0;
        $expiredPackages = 0;
        $expiringSoonPackages = 0;

        $processedReceipts = $receipts->map(function ($receipt) {
            $validityCount = $receipt->package->validity_count ?? 0;
            $validityUnit = $receipt->package->validity_unit ?? 'day';
            $purchaseDate = Carbon::parse($receipt->date);
            $expiryDate = $purchaseDate->copy()->add($validityCount, $validityUnit);
            $daysToExpire = now()->diffInDays($expiryDate, false);

            $usedTherapies = $receipt->receiptPackageTherapies()
                ->whereNotNull('time_in')
                ->whereNotNull('time_out')
                ->whereNotNull('date')
                ->with('therapy')
                ->get();

            $allTherapies = $receipt->package->therapies;
            $usedTherapyIds = $usedTherapies->pluck('therapy_id')->toArray();
            $notUsedTherapies = $allTherapies->whereNotIn('id', $usedTherapyIds);

            $usedTherapyCount = $usedTherapies->count();
            $totalTherapies = $allTherapies->count();
            $remainingTherapies = $totalTherapies - $usedTherapyCount;

            if ($daysToExpire < 0) {
                $status = 'Expired';
                $expiredPackages++;
            } elseif ($daysToExpire <= 3) {
                $status = 'Expiring Soon';
                $expiringSoonPackages++;
            } elseif ($remainingTherapies == 0) {
                $status = 'Used';
                $usedPackages++;
            } else {
                $status = 'Pending';
                $pendingPackages++;
            }

            return [
                'receipt' => $receipt,
                'status' => $status,
                'usedTherapies' => $usedTherapies,
                'notUsedTherapies' => $notUsedTherapies,
                'remainingTherapies' => $remainingTherapies
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'receipts' => $processedReceipts,
                'totalPackages' => $totalPackages,
                'usedPackages' => $usedPackages,
                'pendingPackages' => $pendingPackages,
                'expiredPackages' => $expiredPackages,
                'expiringSoonPackages' => $expiringSoonPackages
            ]
        ]);
    }

    public function branchReport2(Request $request)
    {
        $duration = $request->input('duration', 'all');
        $branchId = $request->input('branch_id', 'all');

        $queryDate = match ($duration) {
            'today' => [Carbon::today(), Carbon::tomorrow()],
            'yesterday' => [Carbon::yesterday(), Carbon::today()],
            'this_week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'this_month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'last_3_months' => [Carbon::now()->subMonths(3), Carbon::now()],
            'this_year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            'last_3_years' => [Carbon::now()->subYears(3), Carbon::now()],
            default => [null, null],
        };

        [$start, $end] = $queryDate;

        $receiptQuery = Receipt::with(['customer', 'receiptTherapies', 'receiptPackageTherapies']);
        $expenseQuery = Expense::query();
        $savingsQuery = Saving::query();

        if ($start && $end) {
            $receiptQuery->whereBetween('date', [$start, $end]);
            $expenseQuery->whereBetween('date', [$start, $end]);
            $savingsQuery->whereBetween('date', [$start, $end]);
        }

        if ($branchId !== 'all') {
            $receiptQuery->whereHas('customer', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });

            $expenseQuery->where('branch_id', $branchId);
            $savingsQuery->where('branch_id', $branchId);
        }

        $receipts = $receiptQuery->get();

        $totalCustomers = $receipts->pluck('customer_id')->unique()->count();
        $totalTherapies = $receipts->sum(fn($r) => $r->receiptTherapies->count());
        $totalPackages = $receipts->sum(fn($r) => $r->receiptPackageTherapies->count());
        $codIncome = $receipts->where('payment_method', 'cash')->sum('total_amount');
        $onlineIncome = $receipts->where('payment_method', 'online')->sum('total_amount');
        $totalIncome = $codIncome + $onlineIncome;
        $totalExpenses = $expenseQuery->sum('amount');
        $totalSavings = $savingsQuery->sum('amount');
        $revenue = $totalIncome - $totalExpenses;

        return response()->json([
            'status' => true,
            'message' => 'Branch report generated successfully.',
            'data' => [
                'duration' => $duration,
                'branch_id' => $branchId,
                'total_customers' => $totalCustomers,
                'total_therapies' => $totalTherapies,
                'total_packages' => $totalPackages,
                'cod_income' => $codIncome,
                'online_income' => $onlineIncome,
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'total_savings' => $totalSavings,
                'revenue' => $revenue,
            ]
        ]);
    }






}
