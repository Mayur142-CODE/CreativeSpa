@extends('layouts.admin')

@section('content')

<div class="card">
    <h5 class="card-header">
        Telecaller Management
            @if(Auth::user()->role_id == 0)
                <!-- Button for adding new customer -->
            <button type="button" class="btn btn-primary btn-sm float-end ms-2" data-bs-toggle="modal" data-bs-target="#addTelecallerModal">
                Add Telecaller
            </button>

            <button type="button" class="btn btn-success btn-sm float-end ms-2" data-bs-toggle="modal" data-bs-target="#importTelecallerModal">
                Import to Excel
            </button>
            @endif



    </h5>

    <!-- Alert for any errors -->
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
                    @if(Auth::user()->role_id == 0)
                    <th>Phone</th>
                    <th>Branch</th>
                    @endif
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($telecallers as $telecaller)
                    <tr>
                        <td>{{ $telecaller->name }}</td>
                        @if(Auth::user()->role_id == 0)
                        <td>
                            {{ $telecaller->phone_number }}
                        </td>
                        <td>
                            {{ $telecaller->branch->name ?? NA }}
                        </td>
                         @endif
                        <td>
                            <a href="tel:+91{{ $telecaller->phone_number }}" class="btn btn-sm btn-primary">
                                Call
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Telecaller Modal -->
<div class="modal fade" id="addTelecallerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Telecaller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin/telecaller/store') }}" method="POST">
                    @csrf

                    <!-- Customer Name -->
                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" name="customer_name" value="{{ old('customer_name') }}" required>
                        @error('customer_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number') }}"
                            onkeypress="return validatePhoneNumber(event)" maxlength="10" required>
                        @error('phone_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <script>
                        function validatePhoneNumber(event) {
                            // Allow only digits and prevent further input if length reaches 10
                            var key = event.keyCode || event.charCode;
                            if ((key >= 48 && key <= 57) || key === 8 || key === 9) {
                                // Key 8 is backspace, key 9 is tab (you can remove tab key if not needed)
                                return true;
                            } else {
                                event.preventDefault();
                                return false;
                            }
                        }
                    </script>
                    <!-- Branch Selection -->
                    <div class="mb-3">
                        <label class="form-label">Select Branch</label>
                        <select class="form-control" name="branch_id" required>
                            <option value="">-- Select Branch --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- Import Telecaller Modal -->
<div class="modal fade" id="importTelecallerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Telecallers from Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin/telecaller/import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Excel File Input -->
                    <div class="mb-3">
                        <label class="form-label">Choose Excel File</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Branch</label>
                        <select class="form-control" name="branch_id" required>
                            <option value="">-- Select Branch --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Import</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection
