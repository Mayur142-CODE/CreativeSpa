<!-- Delete Expense Modal -->
<div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this expense?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteExpenseForm" method="POST">
                    @csrf
                    @method('DELETE')  <!-- Simulates a DELETE request -->
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const deleteModal = document.getElementById('deleteExpenseModal');
deleteModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget; // Button that triggered the modal
    const expenseId = button.getAttribute('data-id'); // Extract expense ID from data attribute
    const deleteForm = document.getElementById('deleteExpenseForm');
    deleteForm.action = `/admin/expenses/${expenseId}/delete`; // Set the form action dynamically
});
</script>
