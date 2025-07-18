@extends('layouts.admin')

@section('content')

<div class="card">
    <h5 class="card-header">
        Therapist Management
        <!-- Button for adding new therapist -->
        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addTherapistModal">
            Add Therapist
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
                    <th>Name</th>
                    <th>PFP</th>
                    <th>Email</th>
                    <th>Branch</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Designation</th>
                    <th>Salary Type</th>
                    <th>Working Hours</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($therapists as $therapist)
                    <tr>
                        <td>{{ $therapist->name }}</td>
                        <td>
                            @if ($therapist->profile_picture && file_exists(public_path($therapist->profile_picture)))
                                <img src="{{ asset($therapist->profile_picture) }}" alt="Profile Picture" width="50" height="50" class="rounded-circle">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>{{ $therapist->email}}</td>
                        <td>
                            @if($therapist->branch)
                                @if($therapist->branch->status == 'Active')
                                    {{ $therapist->branch->name }}
                                @else
                                <span class="text-danger">{{ $therapist->branch->name }} Branch Not Active</span>
                                @endif
                            @else
                                Not Assigned
                            @endif
                        </td>
                        <td>{{ $therapist->contact ?? 'N/A' }}</td>
                        <td>{{ $therapist->address ?? 'N/A' }}</td>
                        <td>{{ $therapist->designation ?? 'N/A' }}</td>
                        <td>{{ $therapist->payroll_calculation ?? 'N/A' }}</td>
                        <td>{{ $therapist->working_hours_or_days ?? 'N/A' }} {{ $therapist->working_hours_type }}</td>

                        <td>
                            <button type="button" class="btn btn-sm btn-warning editTherapistBtn" data-id="{{ $therapist->id }}" data-bs-toggle="modal" data-bs-target="#editTherapistModal">
                                Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger deleteTherapistBtn" data-id="{{ $therapist->id }}" data-bs-toggle="modal" data-bs-target="#deleteTherapistModal">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Include Create Therapist Modal -->
@include('admin.therapists.modals.create')
<!-- Include Edit Therapist Modal -->
@include('admin.therapists.modals.edit')
<!-- Include Delete Therapist Modal -->
@include('admin.therapists.modals.delete')



@endsection
