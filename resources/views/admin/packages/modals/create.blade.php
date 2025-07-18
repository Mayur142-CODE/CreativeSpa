<div class="modal fade" id="addPackageModal" tabindex="-1" aria-labelledby="addPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPackageModalLabel">Add New Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('admin/packages/store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Package Name -->
                    <div class="mb-3">
                        <label for="package_name" class="form-label">Package Name</label>
                        <input type="text" class="form-control" id="package_name" name="name" required>
                    </div>

                    <!-- Package Details -->
                    <div class="mb-3">
                        <label for="package_detail" class="form-label">Details</label>
                        <textarea class="form-control" id="package_detail" name="detail" rows="3" required></textarea>
                    </div>

                    <!-- Package Validity -->
                    <div class="mb-3">
                        <label for="package_validity_unit" class="form-label">Validity</label>
                        <div class="d-flex gap-2">
                            <select class="form-control" id="package_validity_unit" name="validity_unit" required>
                                <option value="day">Day(s)</option>
                                <option value="week">Week(s)</option>
                                <option value="month">Month(s)</option>
                                <option value="year">Year(s)</option>
                            </select>
                            <select class="form-control" id="package_validity_count" name="validity_count" required>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                    </div>

                    <!-- Therapies Selection with Repeater -->
                    <div class="mb-3 therapy-repeater">
                        <label class="form-label">Select Therapies</label>
                        <div class="therapy-container">
                            <div class="therapy-row row mb-2 align-items-end">
                                <div class="col-md-8">
                                    <select class="form-control therapy-select" name="therapies_data[0][id]" required>
                                        <option value="">Select Therapy</option>
                                        @foreach ($therapies as $therapy)
                                            <option value="{{ $therapy->id }}" data-price="{{ $therapy->price }}">
                                                {{ $therapy->name }} (₹{{ number_format($therapy->price, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control therapy-qty" name="therapies_data[0][qty]" min="1" value="1" required>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger remove-therapy d-none">×</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success mt-2 add-therapy d-none">Add Another Therapy</button>
                    </div>

                    <!-- Total Price Display -->
                    <div class="mb-3">
                        <h5>Total Price: ₹<span id="totalPrice">0.00</span></h5>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Package</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const totalPriceElement = document.getElementById("totalPrice");
    const addTherapyBtn = document.querySelector('.add-therapy');
    const therapyContainer = document.querySelector('.therapy-container');
    let therapyCounter = 1;
    const maxTherapies = @json(count($therapies)); // Get total number of available therapies

    function updateTotalPrice() {
        let total = 0;
        document.querySelectorAll('.therapy-row').forEach(row => {
            const select = row.querySelector('.therapy-select');
            const qtyInput = row.querySelector('.therapy-qty');
            if (select.value) {
                const price = parseFloat(select.selectedOptions[0].dataset.price);
                const qty = parseInt(qtyInput.value) || 1;
                total += price * qty;
            }
        });
        totalPriceElement.textContent = total.toFixed(2);
    }

    function updateAddButtonVisibility() {
        const therapyCount = document.querySelectorAll('.therapy-row').length;
        addTherapyBtn.classList.toggle('d-none', therapyCount < 1);
        addTherapyBtn.disabled = therapyCount >= maxTherapies;
    }

    function updateTherapyOptions() {
        const selectedValues = new Set();
        document.querySelectorAll('.therapy-select').forEach(select => {
            if (select.value) selectedValues.add(select.value);
        });

        document.querySelectorAll('.therapy-select').forEach(select => {
            const currentValue = select.value;
            Array.from(select.options).forEach(option => {
                if (option.value && option.value !== currentValue) {
                    option.disabled = selectedValues.has(option.value);
                }
            });
        });
    }

    // Add new therapy row
    addTherapyBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'therapy-row row mb-2 align-items-end';
        newRow.innerHTML = `
            <div class="col-md-8">
                <select class="form-control therapy-select" name="therapies_data[${therapyCounter}][id]" required>
                    ${Array.from(document.querySelector('.therapy-select').options)
                        .map(option => `<option value="${option.value}" data-price="${option.dataset.price}">${option.text}</option>`)
                        .join('')}
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control therapy-qty" name="therapies_data[${therapyCounter}][qty]" min="1" value="1" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-therapy">×</button>
            </div>
        `;
        therapyContainer.appendChild(newRow);
        therapyCounter++;

        // Add event listeners to new elements
        const newSelect = newRow.querySelector('.therapy-select');
        const newQty = newRow.querySelector('.therapy-qty');
        newSelect.addEventListener('change', function() {
            updateTherapyOptions();
            updateTotalPrice();
            updateAddButtonVisibility();
        });
        newQty.addEventListener('input', updateTotalPrice);
        newRow.querySelector('.remove-therapy').addEventListener('click', function() {
            newRow.remove();
            updateTherapyOptions();
            updateTotalPrice();
            updateAddButtonVisibility();
        });

        updateTherapyOptions();
        updateTotalPrice();
        updateAddButtonVisibility();
    });

    // Event listeners for initial row
    const initialSelect = document.querySelector('.therapy-select');
    const initialQty = document.querySelector('.therapy-qty');
    initialSelect.addEventListener('change', function() {
        updateTherapyOptions();
        updateTotalPrice();
        updateAddButtonVisibility();
    });
    initialQty.addEventListener('input', updateTotalPrice);

    // Validity options
    const unitSelect = document.querySelector("#package_validity_unit");
    const countSelect = document.querySelector("#package_validity_count");
    const validityOptions = {
        "day": 6,
        "week": 3,
        "month": 11,
        "year": 10
    };

    function updateValidityOptions() {
        let unit = unitSelect.value;
        let maxCount = validityOptions[unit];
        countSelect.innerHTML = "";
        for (let i = 1; i <= maxCount; i++) {
            let option = document.createElement("option");
            option.value = i;
            option.textContent = i;
            countSelect.appendChild(option);
        }
    }

    updateValidityOptions();
    unitSelect.addEventListener("change", updateValidityOptions);
});
</script>
