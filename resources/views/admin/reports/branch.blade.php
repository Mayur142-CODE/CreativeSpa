@extends('layouts.admin')

@section('content')
<div class="card">
    <h5 class="card-header">Branch Report</h5>
    <div class="card-body">

        <!-- Filters -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Duration</label>
                <select name="duration" class="form-select">
                    <option value="all" {{ $selectedDuration == 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="today" {{ $selectedDuration == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="yesterday" {{ $selectedDuration == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                    <option value="this_week" {{ $selectedDuration == 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="this_month" {{ $selectedDuration == 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_3_months" {{ $selectedDuration == 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                    <option value="this_year" {{ $selectedDuration == 'this_year' ? 'selected' : '' }}>This Year</option>
                    <option value="last_3_years" {{ $selectedDuration == 'last_3_years' ? 'selected' : '' }}>Last 3 Years</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Branch</label>
                <select name="branch_id" class="form-select">
                    <option value="all" {{ $selectedBranch == 'all' ? 'selected' : '' }}>All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $selectedBranch == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- Report Summary -->
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <h6 class="card-title text-white">Total Customers Arrived</h6>
                        <h4 class="mb-0 text-white">{{ $totalCustomers }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-info">
                    <div class="card-body">
                        <h6 class="card-title text-white">Total Therapies Used</h6>
                        <h4 class="mb-0 text-white">{{ $totalTherapies }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-warning">
                    <div class="card-body">
                        <h6 class="card-title text-white">Total Packages Used</h6>
                        <h4 class="mb-0 text-white">{{ $totalPackages }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-danger">
                    <div class="card-body">
                        <h6 class="card-title text-white">Total Expenses</h6>
                        <h4 class="mb-0 text-white">₹{{ number_format($totalExpenses, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <h6 class="card-title text-white">Savings</h6>
                        <h4 class="mb-0 text-white">₹{{ number_format($totalSavings, 2) }}</h4>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <h6 class="card-title text-white">Income (Cash)</h6>
                        <h4 class="mb-0 text-white">₹{{ number_format($codIncome, 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-bg-info">
                    <div class="card-body">
                        <h6 class="card-title text-white">Income (Online)</h6>
                        <h4 class="mb-0 text-white">₹{{ number_format($onlineIncome, 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <h6 class="card-title text-white">Revenue</h6>
                        <h4 class="mb-0 text-white">₹{{ number_format($revenue, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
