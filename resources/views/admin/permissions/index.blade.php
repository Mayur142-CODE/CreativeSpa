@extends('layouts.admin')

@section('content')

<!-- Permission Management Card -->
<div class="card">
    <h5 class="card-header">
        Permission Management
        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
            Add Permission
        </button>
    </h5>



    <!-- Table -->
    <div class="card-datatable text-nowrap table-responsive">
        <table class="dt-column-search table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Permission Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->description }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm editPermissionBtn" data-id="{{ $permission->id }}" data-bs-toggle="modal" data-bs-target="#editPermissionModal">Edit</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deletePermissionModal" data-id="{{ $permission->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>


@include('admin.permissions.modals.create')
<!-- Include Edit Employee Modal -->
@include('admin.permissions.modals.edit')
<!-- Include Delete Employee Modal -->
@include('admin.permissions.modals.delete')

@endsection
