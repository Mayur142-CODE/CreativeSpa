<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin/users/update') }}" method="POST" enctype="multipart/form-data" id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editUserId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editUserName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editUserName" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editUserEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editUserEmail" name="email" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editUserPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="editUserPhone" name="phone" onkeypress="return isNumberKey(event)" maxlength="10" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editUserBranch" class="form-label">Branch</label>
                            <select class="form-control" id="editUserBranch" name="branch_id" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Role Dropdown -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editUserRole" class="form-label">Role</label>
                            <select class="form-control" id="editUserRole" name="role_id" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editUserStatus" class="form-label">Status</label>
                            <select class="form-control" id="editUserStatus" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editUserProfilePicture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="editUserProfilePicture" name="profile_picture" accept="image/*">
                        </div>
                    </div>

                    <div id="editUserProfilePicturePreview" style="display: none;">
                        <div class="mb-2">
                            <img id="editUserProfilePicturePreviewImage" alt="Profile Picture" width="100" height="100" style="object-fit: cover;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editUserPassword" class="form-label">Password (Optional)</label>
                            <input type="password" class="form-control" id="editUserPassword" name="password" placeholder="Enter New Password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editUserPasswordConfirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="editUserPasswordConfirmation" name="password_confirmation" placeholder="Confirm Password">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editUserAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="editUserAddress" name="address"></textarea>
                        </div>
                    </div>

                    <div class="text-center">
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

                            // Check if the key pressed is not a number (0-9) or if the length exceeds 10
                            if ((charCode < 48 || charCode > 57) || input.length >= 10) {
                                return false; // prevent the input if it's not a number or if the length is already 10
                            }
                            return true; // allow the input if it's a number and the length is less than 10
                        }

$(document).ready(function () {
    $('.editUserBtn').click(function () {
        var userId = $(this).data('id');

        $.ajax({
            url: "{{ url('admin/users/edit') }}/" + userId,
            type: "GET",
            success: function (response) {
                $('#editUserForm').attr('action', "{{ url('admin/users/update') }}/" + userId);
                $('#editUserId').val(response.id);
                $('#editUserName').val(response.name);
                $('#editUserEmail').val(response.email);
                $('#editUserPhone').val(response.phone);
                $('#editUserAddress').val(response.address);
                $('#editUserBranch').val(response.branch_id);
                $('#editUserStatus').val(response.status);
                $('#editUserRole').val(response.role_id); // Set Role ID

                if (response.profile_picture) {
                    var profilePicUrl = "{{ asset('') }}/" + response.profile_picture;
                    $('#editUserProfilePicturePreview').show();
                    $('#editUserProfilePicturePreview img').attr('src', profilePicUrl);
                } else {
                    $('#editUserProfilePicturePreview').hide();
                }

                $('#editUserModal').modal('show');
            },
            error: function (xhr, status, error) {
                alert("An error occurred: " + error);
            }
        });
    });
});

</script>
