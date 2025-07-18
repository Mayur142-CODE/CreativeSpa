<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('admin/permission/update')}}" id="editPermissionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editPermissionId">
                    <div class="mb-3">
                        <label class="form-label">Permission Name</label>
                        <input type="text" class="form-control" name="name" id="editPermissionName">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control" name="description" id="editPermissionDescription">
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
    $(document).on('click', '.editPermissionBtn', function () {
        let id = $(this).data('id');
        console.log('Permission ID:', id);

        $('#editPermissionForm').attr('data-id', id); // Store ID in form attribute

        $.ajax({
            url: '/admin/permissions/' + id,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#editPermissionForm').attr('action', '/admin/permissions/update/' + id);
                $('#editPermissionForm').attr('data-id', id);
                $('#editPermissionId').val(data.id);
                $('#editPermissionName').val(data.name);
                $('#editPermissionDescription').val(data.description);
                $('#editPermissionModal').modal('show'); // Show modal
            },
            error: function () {
                alert('Failed to fetch permission details.');
            }
        });
    });
});


</script>
