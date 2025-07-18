<!-- Edit Saving Modal -->
<div class="modal fade" id="editSavingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Saving</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSavingForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="edit_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="edit_date" name="date" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="edit_amount" class="form-label">Amount</label>
                            <input type="number" step="10.00" class="form-control" id="edit_amount" name="amount" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="edit_branch_id" class="form-label">Branch</label>
                            <select class="form-select" id="edit_branch_id" name="branch_id" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- New Field: Who Made This Expense -->
                    <div class="row">
                        <div class="col mb-3">
                            <label for="edit_who_made" class="form-label">Who Made This Expense</label>
                            <input type="text" class="form-control" id="edit_who_made" name="who_made" required>
                            <span class="text-danger" id="edit_who_made_error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.editSavingBtn').on('click', function () {
            const id = $(this).data('id');

            // AJAX request to get the saving details
            $.ajax({
                url: `/admin/savings/${id}/edit`,
                method: 'GET',
                success: function (data) {
                    // Populate the modal fields
                    $('#edit_date').val(data.date);
                    $('#edit_amount').val(data.amount);
                    $('#edit_branch_id').val(data.branch_id);
                    $('#edit_who_made').val(data.who_made); // Populate new field

                    // Set the form action for updating
                    $('#editSavingForm').attr('action', `/admin/savings/update/${id}`);
                },
                error: function () {
                    alert('Failed to load saving data.');
                }
            });
        });
    });
</script>
