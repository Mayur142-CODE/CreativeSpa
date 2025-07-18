<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Therapist;
use App\Models\Receipt;
use App\Models\Therapy;
use App\Models\Package;
use App\Models\Expense;
use App\Models\Saving;


class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();


        $todayAppointments = Receipt::with(['customer', 'receiptTherapies.therapist', 'receiptPackageTherapies.therapist'])
        ->whereDate('date', $today)
        ->get();

        // Get upcoming appointments (future dates)
        $upcomingAppointments = Receipt::with(['customer', 'receiptTherapies.therapist', 'receiptPackageTherapies.therapist'])
            ->whereDate('date', '>', $today)
            ->orderBy('date')
            ->limit(10) // limit to 10 upcoming appointments
            ->get();

        return view('admin.dashboard', [

            'totalUsers' => User::count(),
            'totalBranches' => Branch::count(),
            'totalCustomers' => Customer::count(),
            'totalTherapists' => Therapist::count(),
            'totalReceipts' => Receipt::count(),
            'totalTherapies' => Therapy::count(),
            'totalPackages' => Package::count(),

            // Today stats
            'todaySales' => Receipt::whereDate('date', $today)->sum('total_amount'),
            'todayExpenses' => Expense::whereDate('date', $today)->sum('amount'),
            'todayRevenue' => Receipt::whereDate('date', $today)->sum('total_amount') -
                            Expense::whereDate('date', $today)->sum('amount'),

            // Month stats
            'monthSales' => Receipt::where('date', '>=', $monthStart)->sum('total_amount'),
            'monthExpenses' => Expense::where('date', '>=', $monthStart)->sum('amount'),
            'monthRevenue' => Receipt::where('date', '>=', $monthStart)->sum('total_amount') -
                            Expense::where('date', '>=', $monthStart)->sum('amount'),

            // Total stats
            'totalSales' => Receipt::sum('total_amount'),
            'totalExpenses' => Expense::sum('amount'),
            'totalRevenue' => Receipt::sum('total_amount') - Expense::sum('amount'),
            'totalSavings' => Saving::sum('amount'),

            'totalCashPayments' => Receipt::where('payment_method', 'cash')->sum('total_amount'),
            'totalOnlinePayments' => Receipt::where('payment_method', 'online')->sum('total_amount'),

            'todayAppointments' => $todayAppointments,
            'upcomingAppointments' => $upcomingAppointments,
        ]);
    }


    // api

    public function index2()
    {
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        $todayAppointments = Receipt::with(['customer', 'receiptTherapies.therapist', 'receiptPackageTherapies.therapist'])
            ->whereDate('date', $today)
            ->get();

        $upcomingAppointments = Receipt::with(['customer', 'receiptTherapies.therapist', 'receiptPackageTherapies.therapist'])
            ->whereDate('date', '>', $today)
            ->orderBy('date')
            ->limit(10)
            ->get();

        $data = [
            'totalUsers' => User::count(),
            'totalBranches' => Branch::count(),
            'totalCustomers' => Customer::count(),
            'totalTherapists' => Therapist::count(),
            'totalReceipts' => Receipt::count(),
            'totalTherapies' => Therapy::count(),
            'totalPackages' => Package::count(),

            // Today stats
            'todaySales' => Receipt::whereDate('date', $today)->sum('total_amount'),
            'todayExpenses' => Expense::whereDate('date', $today)->sum('amount'),
            'todayRevenue' => Receipt::whereDate('date', $today)->sum('total_amount') -
                            Expense::whereDate('date', $today)->sum('amount'),

            // Month stats
            'monthSales' => Receipt::where('date', '>=', $monthStart)->sum('total_amount'),
            'monthExpenses' => Expense::where('date', '>=', $monthStart)->sum('amount'),
            'monthRevenue' => Receipt::where('date', '>=', $monthStart)->sum('total_amount') -
                            Expense::where('date', '>=', $monthStart)->sum('amount'),

            // Total stats
            'totalSales' => Receipt::sum('total_amount'),
            'totalExpenses' => Expense::sum('amount'),
            'totalRevenue' => Receipt::sum('total_amount') - Expense::sum('amount'),
            'totalSavings' => Saving::sum('amount'),

            'totalCashPayments' => Receipt::where('payment_method', 'cash')->sum('total_amount'),
            'totalOnlinePayments' => Receipt::where('payment_method', 'online')->sum('total_amount'),

            'todayAppointments' => $todayAppointments,
            'upcomingAppointments' => $upcomingAppointments,
        ];

        return response()->json($data);
    }

}
