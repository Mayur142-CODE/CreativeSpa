@extends('layouts.admin')

@section('content')

<div class="card">

    @if(auth()->user()->role_id != 0)
    <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addPackageModal">
        Add Package
    </button>
    @else

    <h5 class="card-header">
        Package Management
        <!-- Button for adding new package -->
        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addPackageModal">
            Add Package
        </button>
    </h5>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Table -->
    <div class="card-datatable text-nowrap table-responsive">
        <table class="dt-column-search table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Validity</th>
                    <th>Total Price</th>
                    <th>Therapies</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($packages as $package)
                    <tr>
                        <td>{{ $package->name }}</td>
                        <td class="text-truncate" style="max-width: 250px;">
                            {{ $package->detail }}
                        </td>
                        <td>{{ $package->validity_count. ' '. $package->validity_unit }} </td> <!-- Validity -->
                        <td>₹{{ number_format($package->price, 2) }}</td> <!-- Total Price -->
                        <td>
                            @foreach ($package->therapies as $therapy)
                                <div class="mb-1">
                                    <span class="badge bg-info">{{ $therapy->name }}</span>
                                    <small>
                                        (Qty: {{ $therapy->pivot->qty }},
                                        Price: ₹{{ number_format($therapy->pivot->total, 2) }})
                                    </small>
                                </div>
                            @endforeach
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning editPackageBtn" data-id="{{ $package->id }}" data-bs-toggle="modal" data-bs-target="#editPackageModal">
                                Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger deletePackageBtn" data-id="{{ $package->id }}" data-bs-toggle="modal" data-bs-target="#deletePackageModal">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    @endif
    
</div>

<!-- Modals -->
@include("admin.packages.modals.create")
@include("admin.packages.modals.edit")
@include("admin.packages.modals.delete")

@endsection
