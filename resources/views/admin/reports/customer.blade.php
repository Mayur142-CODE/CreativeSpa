@extends('layouts.admin')

@section('content')

<div class="card">
    <h5 class="card-header">
        Customer Report
    </h5>

    <!-- Customer Summary Cards -->
    <div class="row m-3">
        <div class="col-md-6 col-xl-4">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Customers</h5>
                    <p class="card-text text-white">Overall registered customers</p>
                    <h4 class="mb-0 text-white">{{ $totalCustomers }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h5 class="card-title text-white">Active Customers (Last 30 Days)</h5>
                    <p class="card-text text-white">Customers who made a purchase</p>
                    <h4 class="mb-0 text-white">{{ $activeCustomers }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter Form -->
    <form method="GET" action="{{ url('admin/reports/customer') }}" class="mb-3 card-header">
    <div class="row">
        <div class="col-md-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" name="start_date" value="{{ request()->start_date }}">
        </div>
        <div class="col-md-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" name="end_date" value="{{ request()->end_date }}">
        </div>
        <div class="col-md-3">
            <label for="branch_id" class="form-label">Branch</label>
            <select name="branch_id" class="form-control">
                <option value="all">All Branches</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>


    <!-- Customer Table -->
    <div class="card-datatable text-nowrap">
        <table class="dt-column-search table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Branch</th>
                    <th>Joined Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->branch->name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($customer->date)->format('d-M-Y') }}</td>
                    <td>
                        @if($customer->receipts()->whereDate('date', '>=', now()->subDays(30))->exists())
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
