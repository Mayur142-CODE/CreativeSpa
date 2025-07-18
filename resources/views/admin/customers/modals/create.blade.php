
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('admin/customers/store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control"
                               onkeypress="validatePhone(event)"
                               oninput="enforcePhoneLength(this)"
                               maxlength="10" required>
                        <small id="phoneError" class="text-danger d-none">Phone number must be exactly 10 digits.</small>
                    </div>

                    <div class="mb-3">
                        <label>Branch</label>
                        <select name="branch_id" class="form-control" required>
                            <option value="" disabled selected>Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="address" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" required>
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

<script>
    function validatePhone(event) {
        // Allow only digits (0-9)
        var char = String.fromCharCode(event.which);
        if (!char.match(/[0-9]/)) {
            event.preventDefault();
        }
    }

    function enforcePhoneLength(input) {
        let phoneError = document.getElementById("phoneError");

        // Remove non-numeric characters
        input.value = input.value.replace(/\D/g, '');

        // Ensure length is exactly 10 digits
        if (input.value.length < 10) {
            phoneError.classList.remove("d-none");
        } else {
            phoneError.classList.add("d-none");
        }
    }
</script>
