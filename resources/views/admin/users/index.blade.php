@extends('layouts.admin')

@section('content')

<div class="card">
    <h5 class="card-header">
        User Management
        <!-- Button for adding new user -->
        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Add User
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
                    <th>PFP</th>
                    <th>Email</th>
                    <th>Branch</th>
                    <th>Role</th> <!-- Added Role Column -->
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>
                            @if ($user->profile_picture)
                                <img src="{{ asset($user->profile_picture) }}" alt="Profile Picture" width="50" height="50" style="object-fit: cover;">
                            @else
                                No PFP
                            @endif
                        </td>
                        <td>{{$user->email}}</td>
                        <td>
                            @if($user->branch)
                                @if($user->branch->status == 'Active')
                                    {{ $user->branch->name }}
                                @else
                                    <span class="text-danger">  {{ $user->branch->name }} Branch Not Active</span>
                                @endif
                            @else
                                Branch Not Assigned
                            @endif
                        </td>
                        <td>
                            @if ($user->role)
                                {{ $user->role->name }} <!-- Display role name -->
                            @else
                                <span class="text-danger">No Role Assigned</span>
                            @endif
                        </td>
                        <td>{{$user->phone}}</td>
                        <td>{{$user->address}}</td>

                        <td>
                            <span class="badge {{ $user->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->status }}
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning editUserBtn" data-id="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#editUserModal">
                                Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-id="{{ $user->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

<!-- Add User Modal -->
@include("admin.users.modals.create")
@include("admin.users.modals.edit")
@include("admin.users.modals.delete")

@endsection
