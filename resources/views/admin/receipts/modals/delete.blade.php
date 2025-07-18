<div class="modal fade" id="deleteReceiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <p>Are you sure you want to delete this receipt? This action cannot be undone.</p>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <form id="deleteReceiptForm" method="POST" action="">
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
        const deleteReceiptModal = document.getElementById("deleteReceiptModal");

        deleteReceiptModal.addEventListener("show.bs.modal", function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const receiptId = button.getAttribute("data-id"); // Extract receipt ID
            const deleteForm = document.getElementById("deleteReceiptForm");

            // Set the form action dynamically
            deleteForm.action = `/admin/receipts/${receiptId}/delete`;
        });
    });
</script>