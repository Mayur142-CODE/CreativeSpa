@extends('layouts.admin')

@section('content')

<!-- Branch Management -->
<div class="card">
    <h5 class="card-header">
        Branch Management
        <!-- Add Branch Button -->
        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addBranchModal">
            Add Branch
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
                    <th>Branch Name</th>
                    <th>Code</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($branches as $branch)
                    <tr>
                        <td>{{ $branch->name }}</td>
                        <td>{{ $branch->code }}</td>
                        <td>{{ $branch->phone }}</td>
                        <td>{{ Str::limit($branch->address, 30, '...') }}</td>
                        <td>
                            <span class="badge {{ $branch->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $branch->status }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm editBranchBtn" data-id="{{ $branch->id }}"
                                data-bs-toggle="modal" data-bs-target="#editBranchModal">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm deleteBranchBtn"
                                    data-id="{{ $branch->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteBranchModal">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@include("admin.branch.modals.create")
@include("admin.branch.modals.edit")
@include("admin.branch.modals.delete")



@endsection
