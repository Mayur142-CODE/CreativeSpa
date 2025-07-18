<!-- Edit Expense Modal -->
<div class="modal fade" id="editExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editExpenseForm" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Expense Name -->
                    <div class="mb-3">
                        <label class="form-label">Expense Name</label>
                        <input type="text" class="form-control" name="expense_name" id="edit_expense_name" required>
                    </div>

                    <!-- Who Made -->
                    <div class="mb-3">
                        <label class="form-label">Who Made This Expense</label>
                        <input type="text" class="form-control" name="who_made" id="edit_who_made" required>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" class="form-control" name="amount" id="edit_amount" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea class="form-control" name="description" id="edit_description"></textarea>
                    </div>

                    <!-- Date -->
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" id="edit_date" required>
                    </div>

                    <!-- Branch Dropdown -->
                    @if(auth()->user()->role_id == 0)
                        <div class="mb-3">
                            <label class="form-label">Branch</label>
                            <select class="form-select" name="branch_id" id="edit_branch_id" required>
                                <!-- Options will be populated via JS -->
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                    @endif


                    <!-- Submit Button -->
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
        $('.editExpenseBtn').click(function () {
            var expenseId = $(this).data('id');

            $.ajax({
                url: "{{ url('admin/expenses/edit') }}/" + expenseId,
                type: "GET",
                success: function (response) {
                    $('#editExpenseForm').attr('action', "{{ url('admin/expenses/update') }}/" + expenseId);
                    $('#edit_expense_name').val(response.expense.expense_name);
                    $('#edit_who_made').val(response.expense.who_made);
                    $('#edit_amount').val(response.expense.amount);
                    $('#edit_description').val(response.expense.description);
                    $('#edit_date').val(response.expense.date);
                    @if(auth()->user()->role_id == 0)
                        $('#edit_branch_id').empty();  // Clear existing options

                        // Populate branch dropdown
                        $.each(response.branches, function(index, branch) {
                            var selected = (branch.id == response.expense.branch_id) ? 'selected' : '';
                            $('#edit_branch_id').append('<option value="' + branch.id + '" ' + selected + '>' + branch.name + '</option>');
                        });
                    @endif

                    $('#editExpenseModal').modal('show');
                }
            });
        });
    });
</script>
