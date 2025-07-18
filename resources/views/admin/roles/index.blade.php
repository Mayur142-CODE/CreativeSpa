@extends('layouts.admin')

@section('content')

<div class="card">
    <h5 class="card-header">
        Roles Management
        <!-- Button for adding new role -->
        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addRoleModal">
            Add New Role
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
                    <th>Role Name</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        
                            <td>
                                @if($role->permissions->isEmpty())
                                    <span class="badge bg-secondary">Not assigned any permission</span>
                                @else
                                    @foreach($role->permissions as $permission)
                                        <span class="badge bg-primary">{{ $permission->name }}</span>
                                    @endforeach
                                @endif
                            </td>
                        
                        <td>
                            <button type="button" class="btn btn-sm btn-warning editRoleBtn" data-id="{{ $role->id }}" data-name="{{ $role->name }}" data-bs-toggle="modal" data-bs-target="#editRoleModal">
                                Edit
                            </button>
                            <button class="btn btn-sm btn-danger deleteRoleBtn" data-id="{{ $role->id }}" data-bs-toggle="modal" data-bs-target="#deleteRoleModal">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Role Modal -->
@include("admin.roles.modals.create")
<!-- Edit Role Modal -->
@include("admin.roles.modals.edit")
<!-- Delete Role Modal -->
@include("admin.roles.modals.delete")

@endsection
