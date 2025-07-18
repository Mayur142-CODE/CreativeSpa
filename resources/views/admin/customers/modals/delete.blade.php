<!-- Delete Customer Modal -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <p>Are you sure you want to delete this customer?</p>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <form id="deleteCustomerForm" method="POST" action="">
                    @csrf
                    @method('DELETE') <!-- Simulate DELETE request -->
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteCustomerModal = document.getElementById('deleteCustomerModal');

        deleteCustomerModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const customerId = button.getAttribute('data-id'); // Get customer ID

            const deleteForm = document.getElementById('deleteCustomerForm');
            deleteForm.action = `/admin/customers/${customerId}/destroy`; // Set the correct delete URL
        });
    });
</script>
