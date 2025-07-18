<!-- Delete Therapy Modal -->
<div class="modal fade" id="deleteTherapyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <p>Are you sure you want to delete this therapy? This action cannot be undone.</p>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <form id="deleteTherapyForm" method="POST" action="">
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
    document.addEventListener("DOMContentLoaded", function () {
        const deleteTherapyModal = document.getElementById('deleteTherapyModal');

        deleteTherapyModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const therapyId = button.getAttribute('data-id'); // Extract therapy ID
            const deleteForm = document.getElementById('deleteTherapyForm');

            // Set the form action dynamically
            deleteForm.action = `/admin/therapies/${therapyId}/delete`;
        });
    });
</script>
