<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <form id="deleteUserForm" method="POST" action="">
                    @csrf
                    @method('DELETE') <!-- This is the important part to simulate a DELETE request -->
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Button -->


<script>
    const deleteUserModal = document.getElementById('deleteUserModal');

    deleteUserModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Button that triggered the modal
        const userId = button.getAttribute('data-id'); // Extract user ID from data attribute

        // Update the action URL for the form dynamically
        const deleteForm = document.getElementById('deleteUserForm');
        deleteForm.action = `/admin/users/${userId}/delete`; // Set the form action to the correct delete URL
    });
</script>
