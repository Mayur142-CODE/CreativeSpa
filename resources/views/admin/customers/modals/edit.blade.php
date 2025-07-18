<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCustomerForm" action="{{ url('admin/customers/update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" id="edit_phone" name="phone" class="form-control"
                               onkeypress="validatePhone(event)"
                               oninput="enforcePhoneLength(this)"
                               maxlength="10" required>
                        <small id="editPhoneError" class="text-danger d-none">Phone number must be exactly 10 digits.</small>
                    </div>

                    <div class="mb-3">
                        <label>Branch</label>
                        <select name="branch_id" id="edit_branch" class="form-control" required>
                            <option value="" disabled>Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="address" id="edit_address" class="form-control"></textarea>
                    </div>

                    <!-- Add Date Input -->
                    <div class="mb-3">
                        <label>Date</label>
                        <input type="date" name="date" id="edit_date" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>

$(document).ready(function () {

function validatePhone(event) {
    // Allow only digits (0-9)
    var char = String.fromCharCode(event.which);
    if (!char.match(/[0-9]/)) {
        event.preventDefault();
    }
}

function enforcePhoneLength(input) {
    let phoneError = document.getElementById("editPhoneError");

    // Remove non-numeric characters
    input.value = input.value.replace(/\D/g, '');

    // Ensure length is exactly 10 digits
    if (input.value.length < 10) {
        phoneError.classList.remove("d-none");
    } else {
        phoneError.classList.add("d-none");
    }
}

// Open Edit Modal and Load Customer Data
$('.editCustomerBtn').on('click', function () {
    let id = $(this).data('id');
    console.log('Customer ID:', id);

    $('#editCustomerForm').attr('data-id', id); // Store ID in form attribute

    $.ajax({
        url: '/admin/customers/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#editCustomerForm').attr('action', '/admin/customers/update/' + id);
            $('#editCustomerForm').attr('data-id', id);
            $('#edit_name').val(data.name);
            $('#edit_phone').val(data.phone);
            $('#edit_address').val(data.address);
            $('#edit_branch').val(data.branch_id); // Pre-select branch
            $('#edit_date').val(data.date);  // Pre-fill the date field with the customer's date
            $('#editCustomerModal').modal('show'); // Show modal
        },
        error: function () {
            alert('Failed to fetch customer details.');
        }
    });
});
});


</script>
