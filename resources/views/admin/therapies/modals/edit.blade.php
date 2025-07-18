<div class="modal fade" id="editTherapyModal" tabindex="-1" aria-labelledby="editTherapyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTherapyModalLabel">Edit Therapy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTherapyForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_therapy_id" name="id">

                    <div class="mb-3">
                        <label for="edit_therapy_name" class="form-label">Therapy Name</label>
                        <input type="text" class="form-control" id="edit_therapy_name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_therapy_detail" class="form-label">Detail</label>
                        <textarea class="form-control" id="edit_therapy_detail" name="detail" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_therapy_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="edit_therapy_price" name="price" step="0.01" required>
                    </div>

                    <!-- Duration Dropdown -->
                    <div class="mb-3">
                        <label for="edit_therapy_duration" class="form-label">Duration (in minutes)</label>
                        <select class="form-select" id="edit_therapy_duration" name="duration" required>
                            <option value="">Select Duration</option>
                            <option value="30">30 minutes</option>
                            <option value="45">45 minutes</option>
                            <option value="60">60 minutes</option>
                            <option value="90">90 minutes</option>
                            <option value="120">120 minutes</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Therapy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(document).on('click', '.editTherapyBtn', function () {
        var therapyId = $(this).data('id');

        $.ajax({
            url: '/admin/therapies/' + therapyId + '/edit',
            type: 'GET',
            success: function (response) {
                $('#edit_therapy_id').val(response.id);
                $('#edit_therapy_name').val(response.name);
                $('#edit_therapy_detail').val(response.detail);
                $('#edit_therapy_price').val(response.price);
                $('#edit_therapy_duration').val(response.duration); // Set the duration from the response

                // Set form action dynamically
                $('#editTherapyForm').attr('action', '/admin/therapies/update/' + response.id);

                // Show modal
                $('#editTherapyModal').modal('show');
            }
        });
    });
});
</script>
