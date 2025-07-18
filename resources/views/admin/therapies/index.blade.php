@extends('layouts.admin')

@section('content')

<div class="card">
    <h5 class="card-header">
        Therapy Management
        <!-- Button for adding new service -->
        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addTherapyModal">
            Add Therapy
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
                    <th>Detail</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($therapies as $therapy)
                    <tr>
                        <td>{{ $therapy->name }}</td>
                        <td class="text-truncate" style="max-width: 250px;">{{ $therapy->detail }}</td>
                        <td>{{ $therapy->formatted_price }}</td>
                        <td>{{ $therapy->duration }} minutes</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning editTherapyBtn" data-id="{{ $therapy->id }}" data-bs-toggle="modal" data-bs-target="#editTherapyModal">
                                Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger deleteTherapyBtn" data-id="{{ $therapy->id }}" data-bs-toggle="modal" data-bs-target="#deleteTherapyModal">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



<!-- Modals -->
@include("admin.therapies.modals.create")
@include("admin.therapies.modals.edit")
@include("admin.therapies.modals.delete")

@endsection
