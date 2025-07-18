@extends('layouts.admin')

@section('content')

<div class="card">
    <h5 class="card-header">Sales Summary Report</h5>

    <!-- Sales Summary Cards -->
    <div class="row m-3">
        <div class="col-md-6 col-xl-4">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Sales</h5>
                    <p class="card-text text-white">Total number of receipts</p>
                    <h4 class="mb-0 text-white">{{ $totalReceipts }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Amount</h5>
                    <p class="card-text text-white">Sum of all sales</p>
                    <h4 class="mb-0 text-white">₹{{ number_format($totalAmount, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter Form -->
    <form method="GET" action="{{ url('admin/reports/sales-summary') }}" class="mb-3 card-header">
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


    <div class="card-datatable text-nowrap">
        <table class="dt-column-search table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Receipt ID</th>
                    <th>Customer</th>
                    <th>Service Type</th>
                    <th>Therapies Allocated</th>
                    <th>Package Allocated</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipts as $receipt)
                <tr>
                    <td>{{ $receipt->id }}</td>
                    <td>{{ $receipt->customer->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($receipt->service_type) }}</td>
                    <td>
                        @if($receipt->receiptTherapies->count() > 0)
                            {{ $receipt->receiptTherapies->pluck('therapy.name')->implode(', ') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($receipt->package)
                            {{ $receipt->package->name }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($receipt->date)->format('d-M-Y') }}</td>
                    <td>₹{{ number_format($receipt->total_amount, 2) }}</td>
                    <td>{{ ucfirst($receipt->payment_method) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
