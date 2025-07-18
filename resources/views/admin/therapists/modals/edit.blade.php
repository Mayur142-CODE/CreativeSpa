<!-- Edit Therapist Modal -->
<div class="modal fade" id="editTherapistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Therapist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin/therapists/update') }}" method="POST" enctype="multipart/form-data" id="editTherapistForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editTherapistId">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="editTherapistName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editTherapistName" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editTherapistEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editTherapistEmail" name="email" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="editTherapistDOB" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="editTherapistDOB" name="dob">
                        </div>
                        <div class="col-md-6">
                            <label for="editTherapistContact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="editTherapistContact" name="contact" maxlength="10" required onkeypress="return isNumberKey(event)">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="editTherapistGender" class="form-label">Gender</label>
                            <select class="form-control" id="editTherapistGender" name="gender">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editTherapistBranch" class="form-label">Branch</label>
                            <select class="form-control" id="editTherapistBranch" name="branch_id">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="editTherapistDesignation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="editTherapistDesignation" name="designation" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editTherapistFixedSalary" class="form-label">Fixed Salary</label>
                            <input type="number" step="0.01" class="form-control" id="editTherapistFixedSalary" name="fixed_salary">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="editTherapistHourlyRate" class="form-label">Hourly Rate</label>
                            <input type="number" step="0.01" class="form-control" id="editTherapistHourlyRate" name="hourly_rate">
                        </div>
                        <div class="col-md-6">
                            <label for="editTherapistWorkingHoursOrDays" class="form-label">Working Hours/Days</label>
                            <input type="number" step="0.01" class="form-control" id="editTherapistWorkingHoursOrDays" name="working_hours_or_days">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="editTherapistWorkingHoursType" class="form-label">Working Type</label>
                            <select class="form-control" id="editTherapistWorkingHoursType" name="working_hours_type">
                                <option value="Hours">Hours</option>
                                <option value="Days">Days</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editTherapistHolidays" class="form-label">Holidays</label>
                            <input type="number" class="form-control" id="editTherapistHolidays" name="holidays">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="editTherapistPayrollCalculation" class="form-label">Payroll Calculation</label>
                            <select class="form-control" id="editTherapistPayrollCalculation" name="payroll_calculation">
                                <option value="Fixed">Fixed</option>
                                <option value="Hourly">Hourly</option>
                                <option value="Commission">Commission</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div id="editTherapistProfilePicturePreview" class="text-center mt-3" style="display: none;">
                                <img src="" alt="Profile Picture" class="img-thumbnail" width="150">
                            </div>
                            <label for="editTherapistProfilePicture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="editTherapistProfilePicture" name="profile_picture" accept="image/*">
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        var input = evt.target.value;

        if ((charCode < 48 || charCode > 57) || input.length >= 10) {
            return false;
        }
        return true;
    }

    $(document).ready(function () {
        $('.editTherapistBtn').click(function () {
            var employeeId = $(this).data('id');

            $.ajax({
                url: "{{ url('admin/therapists/edit') }}/" + employeeId,
                type: "GET",
                success: function (response) {
                    $('#editTherapistForm').attr('action', "{{ url('admin/therapists/update') }}/" + employeeId);
                    $('#editTherapistId').val(response.id);
                    $('#editTherapistName').val(response.name);
                    $('#editTherapistEmail').val(response.email);
                    $('#editTherapistDOB').val(response.dob);
                    $('#editTherapistContact').val(response.contact);
                    $('#editTherapistGender').val(response.gender);
                    $('#editTherapistBranch').val(response.branch_id);
                    $('#editTherapistDesignation').val(response.designation);
                    $('#editTherapistFixedSalary').val(response.fixed_salary);
                    $('#editTherapistHourlyRate').val(response.hourly_rate);
                    $('#editTherapistWorkingHoursOrDays').val(response.working_hours_or_days);
                    $('#editTherapistWorkingHoursType').val(response.working_hours_type);
                    $('#editTherapistHolidays').val(response.holidays);
                    $('#editTherapistPayrollCalculation').val(response.payroll_calculation);

                    if (response.profile_picture) {
                        var profilePicUrl = "{{ asset('') }}/" + response.profile_picture;
                        $('#editTherapistProfilePicturePreview img').attr('src', profilePicUrl);
                        $('#editTherapistProfilePicturePreview').show();
                    } else {
                        $('#editTherapistProfilePicturePreview').hide();
                    }

                    $('#editTherapistModal').modal('show');
                },
                error: function (xhr, status, error) {
                    alert("An error occurred: " + error);
                }
            });
        });
    });
</script>
