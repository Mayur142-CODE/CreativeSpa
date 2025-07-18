<div class="modal fade" id="addTherapyModal" tabindex="-1" aria-labelledby="addTherapyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTherapyModalLabel">Add New Therapy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTherapyForm" action="{{ url('admin/therapies/store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="therapy_name" class="form-label">Therapy Name</label>
                        <input type="text" class="form-control" id="therapy_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="therapy_detail" class="form-label">Detail</label>
                        <textarea class="form-control" id="therapy_detail" name="detail" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="therapy_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="therapy_price" name="price" step="0.01" required>
                    </div>
                    <!-- Duration Dropdown -->
                    <div class="mb-3">
                        <label for="therapy_duration" class="form-label">Duration (in minutes)</label>
                        <select class="form-select" id="therapy_duration" name="duration" required>
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
                    <button type="submit" class="btn btn-primary">Save Therapy</button>
                </div>
            </form>
        </div>
    </div>
</div>
