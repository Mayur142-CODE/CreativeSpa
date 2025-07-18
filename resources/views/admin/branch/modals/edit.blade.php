<!-- Edit Branch Modal -->
<div class="modal fade" id="editBranchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editBranchForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Branch Name</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Branch Code</label>
                        <input type="text" class="form-control" name="code" id="edit_code" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" id="edit_phone" required onkeypress="return isNumberKey(event)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" id="edit_address" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status" id="edit_status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {


        $('.editBranchBtn').click(function () {
            var branchId = $(this).data('id');

            $.ajax({
                url: "{{ url('admin/branches/edit') }}/" + branchId,
                type: "GET",
                success: function (response) {
                    $('#editBranchForm').attr('action', "{{ url('admin/branches/update') }}/" + branchId);
                    $('#edit_name').val(response.branch.name);
                    $('#edit_code').val(response.branch.code);
                    $('#edit_phone').val(response.branch.phone);
                    $('#edit_address').val(response.branch.address);
                    $('#edit_manager_id').val(response.branch.manager_id);
                    $('#edit_status').val(response.branch.status);
                    $('#editBranchModal').modal('show');
                }
            });
        });
    });
</script>
