<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_role_id" name="id">

                    <div class="mb-3">
                        <label for="edit_role_name" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="edit_role_name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="row">
                            @foreach ($permissions as $permission)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input edit-permission-checkbox"
                                               type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->id }}"
                                               id="edit_permission_{{ $permission->id }}">
                                        <label class="form-check-label" for="edit_permission_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
$(document).ready(function () {
    $(document).on('click', '.editRoleBtn', function () {
        var roleId = $(this).data('id');

        $.ajax({
            url: '/admin/roles/' + roleId + '/edit',
            type: 'GET',
            success: function (response) {
                $('#edit_role_id').val(response.id);
                $('#edit_role_name').val(response.name);

                // Uncheck all permissions first
                $('.edit-permission-checkbox').prop('checked', false);

                // Loop through the role's permissions and check the boxes
                response.permissions.forEach(function(permission) {
                    $('#edit_permission_' + permission.id).prop('checked', true);
                });

                // Set form action dynamically
                $('#editRoleForm').attr('action', '/admin/roles/update/' + response.id);

                // Show modal
                $('#editRoleModal').modal('show');
            }
        });
    });
});
</script>
