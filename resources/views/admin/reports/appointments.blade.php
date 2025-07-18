@extends('layouts.admin')

@section('content')

<div class="card">
    <h5 class="card-header">
        Appointment Report
    </h5>

    <!-- Appointment Summary Cards -->
    <div class="row m-3">
        <div class="col-md-3">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Appointments</h5>
                    <h4 class="mb-0 text-white">{{ $totalAppointments }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <h5 class="card-title text-white">Upcoming Appointments</h5>
                    <h4 class="mb-0 text-white">{{ $upcomingAppointments }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-info">
                <div class="card-body">
                    <h5 class="card-title text-white">Today's Appointments</h5>
                    <h4 class="mb-0 text-white">{{ $todayAppointments }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h5 class="card-title text-white">Completed Appointments</h5>
                    <h4 class="mb-0 text-white">{{ $completedAppointments }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Date & Branch Filter Form -->
    <form method="GET" action="{{ url('admin/reports/appointments') }}" class="mb-3 card-header">
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
                <select name="branch_id" class="form-select">
                    <option value="all">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request()->branch_id == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>


    <!-- Appointment DataTable -->
    <div class="card-datatable text-nowrap">
        <table class="dt-column-search table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Customer</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                    <th>Service</th>
                    <th>Therapies</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->id }}</td>
                    <td>{{ $appointment->customer->name ?? 'Unknown' }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d-M-Y') }}</td>
                    <td>
                        @if(\Carbon\Carbon::parse($appointment->date)->isToday())
                            <span class="badge bg-info">Today</span>
                        @elseif(\Carbon\Carbon::parse($appointment->date)->isFuture())
                            <span class="badge bg-warning">Upcoming</span>
                        @else
                            <span class="badge bg-success">Completed</span>
                        @endif
                    </td>
                    <td>{{ ucfirst($appointment->service_type) }}</td>
                    <td>
                        @if($appointment->service_type == 'package')
                            @if($appointment->package && $appointment->package->therapies->count() > 0)
                                {{ $appointment->package->therapies->pluck('name')->implode(', ') }}
                            @else
                                <span class="text-muted">No therapies assigned</span>
                            @endif
                        @else
                            @if($appointment->receiptTherapies->count() > 0)
                                {{ $appointment->receiptTherapies->pluck('therapy.name')->implode(', ') }}
                            @else
                                <span class="text-muted">No Therapy</span>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection
