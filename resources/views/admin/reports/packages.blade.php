@extends('layouts.admin')

@section('content')

<div class="card">
    <h5 class="card-header">
        Package Report
    </h5>

    <!-- Package Summary Cards -->
    <div class="row m-3">
        <div class="col-md-4 col-xl-2">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Packages</h5>
                    <h4 class="mb-0 text-white">{{ $totalPackages }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h5 class="card-title text-white">Used Packages</h5>
                    <h4 class="mb-0 text-white">{{ $usedPackages }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card text-bg-info">
                <div class="card-body">
                    <h5 class="card-title text-white">Pending Packages</h5>
                    <h4 class="mb-0 text-white">{{ $pendingPackages }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card text-bg-danger">
                <div class="card-body">
                    <h5 class="card-title text-white">Expired Packages</h5>
                    <h4 class="mb-0 text-white">{{ $expiredPackages }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <h5 class="card-title text-white">Expiring Soon</h5>
                    <h4 class="mb-0 text-white">{{ $expiringSoonPackages }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter Form -->
    <!-- Date & Branch Filter Form -->
<form method="GET" action="{{ url('admin/reports/packages') }}" class="mb-3 card-header">
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
                <option value="">All Branches</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request()->branch_id == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary mt-4">Filter</button>
        </div>
    </div>
</form>


    <!-- Package Table -->
    <div class="card-datatable text-nowrap">
        @if($receipts->isEmpty())
            <div class="text-center p-4">
                <h4 class="text-muted">No Package Reports available</h4>
            </div>
        @else
        <table class="dt-column-search table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Package Name</th>
                    <th>Receipt Date</th>
                    <th>Status</th>
                    <th>Used Therapies</th>
                    <th>Not Used Therapies</th>
                    <th>Remaining Therapies</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipts as $receipt)
                <tr>
                    <td>{{ $receipt->customer->name }}</td>
                    <td>{{ $receipt->customer->phone }}</td>
                    <td>{{ $receipt->package->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($receipt->date)->format('d-M-Y') }}</td>
                    <td>
                        @if($receipt->status == 'Expired')
                            <span class="badge bg-danger">{{ $receipt->status }}</span>
                        @elseif($receipt->status == 'Expiring Soon')
                            <span class="badge bg-warning">{{ $receipt->status }}</span>
                        @elseif($receipt->status == 'Used')
                            <span class="badge bg-success">{{ $receipt->status }}</span>
                        @else
                            <span class="badge bg-info">{{ $receipt->status }}</span>
                        @endif
                    </td>
                    <td>
                        @if($receipt->usedTherapies->isNotEmpty())
                            <ul>
                                @foreach($receipt->usedTherapies as $therapyUsage)
                                    <li>{{ $therapyUsage->therapy->name }} - {{ $therapyUsage->redeem_qty }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">No Used Therapies</span>
                        @endif
                    </td>
                    <td>
                        @if($receipt->notUsedTherapies->isNotEmpty())
                            <ul>
                                @foreach($receipt->notUsedTherapies as $notUsedTherapy)
                                    <li>{{ $notUsedTherapy->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">All Therapies Used</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $receipt->remainingTherapies }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>


        @endif
    </div>

</div>

@endsection
