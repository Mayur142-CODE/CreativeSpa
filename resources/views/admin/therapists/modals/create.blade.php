<div class="modal fade" id="addTherapistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Therapist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin/therapists/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <label for="therapistName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="therapistName" name="name" placeholder="Enter Therapist Name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="therapistEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="therapistEmail" name="email" placeholder="Enter Email" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="therapistDOB" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="therapistDOB" name="dob">
                        </div>
                        <div class="col-md-6">
                            <label for="therapistContact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="therapistContact" name="contact" placeholder="Enter Contact Number"
                                onkeypress="return isNumberKey(event)" maxlength="10">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="therapistGender" class="form-label">Gender</label>
                            <select class="form-control" id="therapistGender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="therapistBranch" class="form-label">Branch</label>
                            <select class="form-control" id="therapistBranch" name="branch_id">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="therapistDesignation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="therapistDesignation" name="designation" placeholder="Enter Designation">
                        </div>
                        <div class="col-md-6">
                            <label for="therapistFixedSalary" class="form-label">Fixed Salary</label>
                            <input type="number" step="0.01" class="form-control" id="therapistFixedSalary" name="fixed_salary" placeholder="Enter Fixed Salary">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="therapistHourlyRate" class="form-label">Hourly Rate</label>
                            <input type="number" step="0.01" class="form-control" id="therapistHourlyRate" name="hourly_rate" placeholder="Enter Hourly Rate">
                        </div>
                        <div class="col-md-6">
                            <label for="therapistWorkingHoursOrDays" class="form-label">Working Hours/Days</label>
                            <input type="number" step="0.01" class="form-control" id="therapistWorkingHoursOrDays" name="working_hours_or_days" placeholder="Enter Working Hours/Days">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="therapistWorkingHoursType" class="form-label">Working Type</label>
                            <select class="form-control" id="therapistWorkingHoursType" name="working_hours_type">
                                <option value="Hours">Hours</option>
                                <option value="Days">Days</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="therapistHolidays" class="form-label">Holidays</label>
                            <input type="number" class="form-control" id="therapistHolidays" name="holidays" placeholder="Enter Number of Holidays">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="therapistPayrollCalculation" class="form-label">Payroll Calculation</label>
                            <select class="form-control" id="therapistPayrollCalculation" name="payroll_calculation">
                                <option value="Fixed">Fixed</option>
                                <option value="Hourly">Hourly</option>
                                <option value="Commission">Commission</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="therapistProfilePicture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="therapistProfilePicture" name="profile_picture" accept="image/*">
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for restricting number input -->
<script>
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        var input = evt.target.value;

        if ((charCode < 48 || charCode > 57) || input.length >= 10) {
            return false;
        }
        return true;
    }
</script>
