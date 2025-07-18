<!-- Add Saving Modal -->
<div class="modal fade" id="addSavingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Saving</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('admin/savings/store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" step="10.00" class="form-control" id="amount" name="amount" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select class="form-select" id="branch_id" name="branch_id" required>
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
                            <label class="form-label">Who Made This Expense</label>
                            <input type="text" class="form-control" name="who_made" value="{{ old('who_made') }}" required>
                            @error('who_made')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
