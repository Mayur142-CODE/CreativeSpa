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

        // Apply date filter
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        // Apply branch filter
        if ($request->branch_id && $request->branch_id !== 'all') {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        $receipts = $query->get();

        // Totals
        $totalReceipts = $receipts->count();
        $totalAmount = $receipts->sum('total_amount');

        $branches = Branch::all();

        return view('admin.reports.sales-summary', compact('receipts', 'totalReceipts', 'totalAmount', 'branches'));
    }

    public function customerReport(Request $request)
    {
        $query = Customer::query()->with('branch');

        // Filter by date range
        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Filter by branch
        if ($request->branch_id && $request->branch_id !== 'all') {
            $query->where('branch_id', $request->branch_id);
        }

        $customers = $query->get();

        $totalCustomers = Customer::count();

        $activeCustomers = Customer::whereHas('receipts', function ($q) {
            $q->whereDate('date', '>=', now()->subDays(30));
        })->count();

        $branches = Branch::all();

        return view('admin.reports.customer', compact('customers', 'totalCustomers', 'activeCustomers', 'branches'));
    }

    public function appointmentsReport(Request $request)
    {
        $query = Receipt::with(['customer', 'package.therapies', 'receiptTherapies.therapy', 'receiptPackageTherapies.therapy']);

        // Apply date filter if provided
        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Apply branch filter if provided
        if ($request->branch_id && $request->branch_id !== 'all') {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        // Get filtered appointments
        $appointments = $query->orderBy('date', 'asc')->get();

        // Get counts without filters (or you can apply same filters if needed)
        $totalAppointments = Receipt::count();
        $todayAppointments = Receipt::whereDate('date', now()->format('Y-m-d'))->count();
        $upcomingAppointments = Receipt::whereDate('date', '>', now()->format('Y-m-d'))->count();
        $completedAppointments = Receipt::whereDate('date', '<', now()->format('Y-m-d'))->count();

        $branches = Branch::all();

        return view('admin.reports.appointments', compact(
            'appointments', 'totalAppointments', 'todayAppointments',
            'upcomingAppointments', 'completedAppointments', 'branches'
        ));
    }

    public function packagesReport(Request $request)
    {
        $query = Receipt::whereHas('package');

        // Filter by branch if selected
        if ($request->branch_id) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        // Apply date filters
        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $receipts = $query->get();

        // Summary Counters
        $totalPackages = $receipts->count();
        $usedPackages = $pendingPackages = $expiredPackages = $expiringSoonPackages = 0;

        foreach ($receipts as $receipt) {
            $validityCount = $receipt->package->validity_count ?? 0;
            $validityUnit = $receipt->package->validity_unit ?? 'day';
            $purchaseDate = \Carbon\Carbon::parse($receipt->date);
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

            $receipt->usedTherapies = $usedTherapies;
            $receipt->notUsedTherapies = $notUsedTherapies;
            $receipt->remainingTherapies = $remainingTherapies;
        }

        // Get all branches for filter dropdown
        $branches = \App\Models\Branch::all();

        return view('admin.reports.packages', compact(
            'receipts', 'totalPackages', 'usedPackages', 'pendingPackages', 'expiredPackages', 'expiringSoonPackages', 'branches'
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

        // Apply date filter
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        // Apply branch filter
        if ($request->branch_id && $request->branch_id !== 'all') {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
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
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Filter by branch
        if ($request->branch_id && $request->branch_id !== 'all') {
            $query->where('branch_id', $request->branch_id);
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

        // Apply branch filter if provided
        if ($request->branch_id && $request->branch_id !== 'all') {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
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

        // Filter by branch if selected
        if ($request->branch_id) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

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

            // Fetch used therapies
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
            $receipt->notUsedTherapies = $notUsedTherapies->values(); // Ensure it's a clean collection
            $receipt->remainingTherapies = $remainingTherapies;
        }

        return response()->json([
            'receipts' => $receipts,
            'totalPackages' => $totalPackages,
            'usedPackages' => $usedPackages,
            'pendingPackages' => $pendingPackages,
            'expiredPackages' => $expiredPackages,
            'expiringSoonPackages' => $expiringSoonPackages,
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
