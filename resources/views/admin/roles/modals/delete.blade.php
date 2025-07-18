<!-- Delete Role Modal -->
<div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <p>Are you sure you want to delete this role? This action cannot be undone.</p>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <form id="deleteRoleForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const deleteRoleModal = document.getElementById('deleteRoleModal');

    deleteRoleModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Button that triggered the modal
        const roleId = button.getAttribute('data-id'); // Extract role ID
        const deleteForm = document.getElementById('deleteRoleForm');

        // Set the form action dynamically
        deleteForm.action = `/admin/roles/${roleId}/delete`;
    });
</script>
