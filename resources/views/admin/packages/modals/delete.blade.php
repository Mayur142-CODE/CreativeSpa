<!-- Delete Package Modal -->
<div class="modal fade" id="deletePackageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <p>Are you sure you want to delete this package? This action cannot be undone.</p>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <form id="deletePackageForm" method="POST" action="">
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
    const deletePackageModal = document.getElementById('deletePackageModal');

    deletePackageModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const packageId = button.getAttribute('data-id');
        const deleteForm = document.getElementById('deletePackageForm');

        deleteForm.action = `/admin/packages/${packageId}/delete`;
    });
</script>
