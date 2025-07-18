@extends('layouts.admin')

@section('content')

<!-- Expense Management -->
<div class="card">
    @if(auth()->user()->role_id != 0)
    <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
        Add Expense
    </button>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @else
    <h5 class="card-header">
        Expense Management
        <!-- Add Expense Button -->
        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
            Add Expense
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

    <div class="card-datatable text-nowrap">
        <table class="dt-column-search table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Expense Name</th>
                    <th>Who Made</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Branch</th> <!-- New column for Branch -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $expense->expense_name }}</td>
                        <td>{{ $expense->who_made }}</td>
                        <td>{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ $expense->description ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
                        <td>{{ $expense->branch ? $expense->branch->name : 'N/A' }}</td> <!-- Display the branch name -->
                        <td>
                            <button class="btn btn-warning btn-sm editExpenseBtn" data-id="{{ $expense->id }}"
                                data-bs-toggle="modal" data-bs-target="#editExpenseModal">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm deleteExpenseBtn"
                                    data-id="{{ $expense->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteExpenseModal">
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

@include("admin.expenses.modals.create")
@include("admin.expenses.modals.edit")
@include("admin.expenses.modals.delete")

@endsection
