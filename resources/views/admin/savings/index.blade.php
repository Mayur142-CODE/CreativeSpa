@extends('layouts.admin')

@section('content')
<div class="card">
    @if(auth()->user()->role_id != 0)

        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addSavingModal">
            Add New Saving
        </button>

    @else

        <h5 class="card-header">
            Savings Management
            <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addSavingModal">
                Add New Saving
            </button>
        </h5>

        <!-- Display Errors -->
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
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Who Made</th>
                        <th>Branch</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($savings as $saving)
                        <tr>
                            <td>{{ $saving->date }}</td>
                            <td>₹{{ number_format($saving->amount, 2) }}</td>
                            <td>{{ $saving->who_made }}</td>
                            <td>{{ $saving->branch->name }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning editSavingBtn"
                                    data-id="{{ $saving->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editSavingModal">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger deleteSavingBtn"
                                    data-id="{{ $saving->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteSavingModal">
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
@include("admin.savings.modals.create")
@include("admin.savings.modals.edit")
@include("admin.savings.modals.delete")

@endsection
