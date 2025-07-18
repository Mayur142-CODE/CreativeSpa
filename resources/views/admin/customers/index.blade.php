@extends('layouts.admin')

@section('content')

<div class="card">
    @if(auth()->user()->role_id != 0)
    <button type="button" class="btn btn-primary btn-sm float-end ms-2" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
        Add Customer
    </button>
    @else
    <h5 class="card-header">
        Customer Management
        <!-- Button for adding new customer -->
        <button type="button" class="btn btn-primary btn-sm float-end ms-2" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            Add Customer
        </button>
        <button type="button" class="btn btn-success btn-sm float-end" onclick="window.location='{{ url('admin/customer/export') }}'">
            Export to Excel
        </button>
        <button type="button" class="btn btn-info btn-sm float-end me-2" data-bs-toggle="modal" data-bs-target="#importCustomerModal">
            Import from Excel
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
        <table class="dt-column-search table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Branch</th>
                    <th>Address</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                        <td>{{ $customer->branch ? $customer->branch->name : 'N/A' }}</td>
                        <td>{{ $customer->address ?? 'N/A' }}</td>
                        <td>{{ $customer->date ? $customer->date : 'N/A' }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning editCustomerBtn" data-id="{{ $customer->id }}" data-bs-toggle="modal" data-bs-target="#editCustomerModal">
                                Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger deleteCustomerBtn" data-bs-toggle="modal" data-bs-target="#deleteCustomerModal" data-id="{{ $customer->id }}">
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

<!-- Include Create Customer Modal -->
@include('admin.customers.modals.create')
<!-- Include Edit Customer Modal -->
@include('admin.customers.modals.edit')
<!-- Include Delete Customer Modal -->
@include('admin.customers.modals.delete')
<!-- Include Import Customer Modal -->
@include('admin.customers.modals.import')
@endsection
