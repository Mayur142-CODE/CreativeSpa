<div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPackageModalLabel">Edit Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPackageForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_package_id" name="id">

                    <div class="mb-3">
                        <label for="edit_package_name" class="form-label">Package Name</label>
                        <input type="text" class="form-control" id="edit_package_name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_package_detail" class="form-label">Details</label>
                        <textarea class="form-control" id="edit_package_detail" name="detail" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_package_validity_unit" class="form-label">Validity</label>
                        <div class="d-flex gap-2">
                            <select class="form-control" id="edit_package_validity_unit" name="validity_unit" required>
                                <option value="day">Day(s)</option>
                                <option value="week">Week(s)</option>
                                <option value="month">Month(s)</option>
                                <option value="year">Year(s)</option>
                            </select>

                            <select class="form-control" id="edit_package_validity_count" name="validity_count" required>
                                <!-- Options will be dynamically populated -->
                            </select>
                        </div>
                    </div>

                    <!-- Therapies Selection with Repeater -->
                    <div class="mb-3 therapy-repeater">
                        <label class="form-label">Select Therapies</label>
                        <div class="therapy-container" id="edit_therapy_container">
                            <!-- Therapy rows will be added dynamically -->
                        </div>
                        <button type="button" class="btn btn-success mt-2" id="edit_add_therapy">Add Another Therapy</button>
                    </div>

                    <!-- Total Price Display -->
                    <div class="mb-3">
                        <h5>Total Price: ₹<span id="edit_totalPrice">0.00</span></h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Package</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Function to update validity count options based on the selected unit
        function populateValidityCount(unit, selectedCount = null) {
            let countDropdown = $('#edit_package_validity_count');
            countDropdown.empty(); // Clear existing options

            let maxCount = 11; // Default max count
            if (unit === 'week') maxCount = 3;
            if (unit === 'year') maxCount = 10;
            if (unit === 'day') maxCount = 6;

            for (let i = 1; i <= maxCount; i++) {
                let isSelected = (selectedCount === i) ? 'selected' : '';
                countDropdown.append(`<option value="${i}" ${isSelected}>${i}</option>`);
            }
        }

        // Therapy counter for dynamic rows
        let editTherapyCounter = 0;
        const maxTherapies = @json(count($therapies));

        // Function to update total price
        function updateEditTotalPrice() {
            let total = 0;
            $('.edit-therapy-row').each(function() {
                const select = $(this).find('.edit-therapy-select');
                const qtyInput = $(this).find('.edit-therapy-qty');
                if (select.val()) {
                    const price = parseFloat(select.find('option:selected').data('price'));
                    const qty = parseInt(qtyInput.val()) || 1;
                    total += price * qty;
                }
            });
            $('#edit_totalPrice').text(total.toFixed(2));
        }

        // Function to update add button visibility and state
        function updateAddButtonVisibility() {
            const therapyCount = $('.edit-therapy-row').length;
            $('#edit_add_therapy').toggle(therapyCount < maxTherapies);
            $('#edit_add_therapy').prop('disabled', therapyCount >= maxTherapies);
        }

        // Function to update therapy options
        function updateEditTherapyOptions() {
            const selectedValues = [];
            $('.edit-therapy-select').each(function() {
                if ($(this).val()) {
                    selectedValues.push($(this).val());
                }
            });

            $('.edit-therapy-select').each(function() {
                const currentValue = $(this).val();
                $(this).find('option').each(function() {
                    if ($(this).val() && $(this).val() !== currentValue) {
                        $(this).prop('disabled', selectedValues.includes($(this).val()));
                    }
                });
            });
        }

        // Function to add a new therapy row
        function addEditTherapyRow(therapyId = '', qty = 1) {
            const rowId = editTherapyCounter++;
            const rowHtml = `
                <div class="edit-therapy-row row mb-2 align-items-end" data-row="${rowId}">
                    <div class="col-md-8">
                        <select class="form-control edit-therapy-select" name="therapies_data[${rowId}][id]" required>
                            <option value="">Select Therapy</option>
                            @foreach ($therapies as $therapy)
                                <option value="{{ $therapy->id }}" data-price="{{ $therapy->price }}"
                                    ${therapyId == {{ $therapy->id }} ? 'selected' : ''}>
                                    {{ $therapy->name }} (₹{{ number_format($therapy->price, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control edit-therapy-qty" name="therapies_data[${rowId}][qty]" min="1" value="${qty}" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger edit-remove-therapy">×</button>
                    </div>
                </div>
            `;
            $('#edit_therapy_container').append(rowHtml);

            // Set up event handlers for the new row
            const rowElement = $(`[data-row="${rowId}"]`);
            rowElement.find('.edit-therapy-select').change(function() {
                updateEditTherapyOptions();
                updateEditTotalPrice();
            });
            rowElement.find('.edit-therapy-qty').on('input', updateEditTotalPrice);
            rowElement.find('.edit-remove-therapy').click(function() {
                rowElement.remove();
                updateEditTherapyOptions();
                updateEditTotalPrice();
                updateAddButtonVisibility();
            });

            updateEditTherapyOptions();
            updateEditTotalPrice();
            updateAddButtonVisibility();
        }

        // Add new therapy row button
        $('#edit_add_therapy').click(function() {
            addEditTherapyRow();
        });

        // Handle Edit Package Button Click
        $(document).on('click', '.editPackageBtn', function() {
            var packageId = $(this).data('id');
            editTherapyCounter = 0; // Reset counter
            $('#edit_therapy_container').empty(); // Clear existing rows

            // AJAX request to fetch package details
            $.ajax({
                url: '/admin/packages/' + packageId + '/edit',
                type: 'GET',
                success: function(response) {
                    // Populate package details
                    $('#edit_package_id').val(response.id);
                    $('#edit_package_name').val(response.name);
                    $('#edit_package_detail').val(response.detail);
                    $('#edit_package_validity_unit').val(response.validity_unit);

                    // Pre-populate the validity count dropdown
                    populateValidityCount(response.validity_unit, response.validity_count);

                    // Add therapy rows for each selected therapy
                    $.each(response.therapies, function(index, therapy) {
                        addEditTherapyRow(therapy.id, therapy.qty);
                    });

                    // Set form action dynamically
                    $('#editPackageForm').attr('action', '/admin/packages/update/' + response.id);

                    // Show modal
                    $('#editPackageModal').modal('show');
                }
            });
        });

        // Ensure the validity count dropdown is properly populated when the modal opens
        $('#editPackageModal').on('shown.bs.modal', function() {
            let unit = $('#edit_package_validity_unit').val();
            let selectedCount = parseInt($('#edit_package_validity_count').val()) || null;
            populateValidityCount(unit, selectedCount);
        });

        // Handle change in validity unit
        $('#edit_package_validity_unit').on('change', function() {
            let unit = $(this).val();
            let selectedCount = null; // Reset selected count when changing unit
            populateValidityCount(unit, selectedCount);
        });
    });
    </script>
