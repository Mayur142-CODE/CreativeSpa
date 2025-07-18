@extends('layouts.admin')

@section('content')

<!-- Today Row -->
<div class="row g-6 mb-6">
    <div class="col-md-6 col-xl-4">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h5 class="card-title text-white">Today Sales</h5>
                <p class="card-text text-white">Daily Total Sales</p>
                <h4 class="mb-0 text-white">₹{{ number_format($todaySales, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card text-bg-warning">
            <div class="card-body">
                <h5 class="card-title text-white">Today Expenses</h5>
                <p class="card-text text-white">Daily Expenses</p>
                <h4 class="mb-0 text-white">₹{{ number_format($todayExpenses, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card text-bg-success">
            <div class="card-body">
                <h5 class="card-title text-white">Today Revenue</h5>
                <p class="card-text text-white">Daily Revenue</p>
                <h4 class="mb-0 text-white">₹{{ number_format($todayRevenue, 2) }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Month Row -->
<div class="row g-6 mb-6">
    <div class="col-md-6 col-xl-4">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h5 class="card-title text-white">Month Sales</h5>
                <p class="card-text text-white">Monthly Total Sales</p>
                <h4 class="mb-0 text-white">₹{{ number_format($monthSales, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card text-bg-warning">
            <div class="card-body">
                <h5 class="card-title text-white">Month Expenses</h5>
                <p class="card-text text-white">Monthly Expenses</p>
                <h4 class="mb-0 text-white">₹{{ number_format($monthExpenses, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card text-bg-success">
            <div class="card-body">
                <h5 class="card-title text-white">Month Revenue</h5>
                <p class="card-text text-white">Monthly Revenue</p>
                <h4 class="mb-0 text-white">₹{{ number_format($monthRevenue, 2) }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Total Row -->
<div class="row g-6 mb-6">
    <div class="col-md-6 col-xl-4">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h5 class="card-title text-white">Total Sales</h5>
                <p class="card-text text-white">All Time Sales</p>
                <h4 class="mb-0 text-white">₹{{ number_format($totalSales, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card text-bg-warning">
            <div class="card-body">
                <h5 class="card-title text-white">Total Expenses</h5>
                <p class="card-text text-white">All Time Expenses</p>
                <h4 class="mb-0 text-white">₹{{ number_format($totalExpenses, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card text-bg-success">
            <div class="card-body">
                <h5 class="card-title text-white">Total Revenue</h5>
                <p class="card-text text-white">All Time Revenue</p>
                <h4 class="mb-0 text-white">₹{{ number_format($totalRevenue, 2) }}</h4>
            </div>
        </div>
    </div>
</div>
<hr class="my-5">


<!-- Appointments Section -->
<!-- Appointments Section -->
{{-- <div class="row g-6 mb-6">
    <!-- Today's Appointments -->
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Today's Appointments</h5>
            <div class="card-datatable text-nowrap">
                @if($todayAppointments->count() > 0)
                    <table class="dt-column-search table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Customer Name</th>
                                <th>Customer Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todayAppointments as $appointment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                    <td>{{ $appointment->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $appointment->customer->phone ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info m-3">No appointments for today.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="col-12 mt-4">
        <div class="card">
            <h5 class="card-header">Upcoming Appointments</h5>
            <div class="card-datatable text-nowrap">
                @if($upcomingAppointments->count() > 0)
                    <table class="dt-column-search table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Customer Name</th>
                                <th>Customer Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingAppointments as $appointment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                    <td>{{ $appointment->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $appointment->customer->phone ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info m-3">No upcoming appointments.</div>
                @endif
            </div>
        </div>
    </div>
</div>
<hr class="my-5"> --}}
<!-- Additional Stats Row -->
<div class="row g-6">
    <div class="col-md-6 col-xl-4">
        <a href="{{ url('admin/users/index') }}" class="text-decoration-none">
            <div class="card shadow-none bg-label-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Users</h5>
                    <p class="card-text">All Registered Users</p>
                    <h4 class="mb-0 text-primary">{{ $totalUsers }}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-4">
        <a href="{{ url('admin/branches/index') }}" class="text-decoration-none">
            <div class="card shadow-none bg-label-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Branches</h5>
                    <p class="card-text">All Branches</p>
                    <h4 class="mb-0 text-primary">{{ $totalBranches }}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-4">
        <a href="{{ url('admin/customers/index') }}" class="text-decoration-none">
            <div class="card shadow-none bg-label-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Customers</h5>
                    <p class="card-text">All Customers</p>
                    <h4 class="mb-0 text-primary">{{ $totalCustomers }}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-4">
        <a href="{{ url('admin/therapists/index') }}" class="text-decoration-none">
            <div class="card shadow-none bg-label-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Therapists</h5>
                    <p class="card-text">All Therapists</p>
                    <h4 class="mb-0 text-primary">{{ $totalTherapists }}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-4">
        <a href="{{ url('admin/receipts/index') }}" class="text-decoration-none">
            <div class="card shadow-none bg-label-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Receipts</h5>
                    <p class="card-text">All Receipts</p>
                    <h4 class="mb-0 text-primary">{{ $totalReceipts }}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-4">
        <a href="{{ url('admin/therapies/index') }}" class="text-decoration-none">
            <div class="card shadow-none bg-label-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Therapies</h5>
                    <p class="card-text">All Therapies</p>
                    <h4 class="mb-0 text-primary">{{ $totalTherapies }}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-4">
        <a href="{{ url('admin/packages/index') }}" class="text-decoration-none">
            <div class="card shadow-none bg-label-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Packages</h5>
                    <p class="card-text">All Packages</p>
                    <h4 class="mb-0 text-primary">{{ $totalPackages }}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card hadow-none bg-label-primary">
            <div class="card-body">
                <h5 class="card-title text-primary">Total Cash Payments</h5>
                <p class="card-text">All Time Cash Payments</p>
                <h4 class="mb-0 text-primary">₹{{ number_format($totalCashPayments, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card hadow-none bg-label-primary">
            <div class="card-body">
                <h5 class="card-title text-primary">Total Online Payments</h5>
                <p class="card-text">All Time Online Payments</p>
                <h4 class="mb-0 text-primary">₹{{ number_format($totalOnlinePayments, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
    <div class="card shadow-none bg-label-primary">
        <div class="card-body">
            <h5 class="card-title text-primary">Total Savings</h5>
            <p class="card-text">All Time Saved Amount</p>
            <h4 class="mb-0 text-primary">₹{{ number_format($totalSavings, 2) }}</h4>
        </div>
    </div>
</div>

</div>

@endsection
