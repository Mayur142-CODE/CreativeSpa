<!-- Create Receipt Modal -->
<div class="modal fade" id="addReceiptModal" tabindex="-1" aria-labelledby="addReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReceiptModalLabel">Create New Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="createReceiptForm" action="{{ url('admin/receipts/store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Customer Name, Phone & Branch -->
                    <div class="row mb-1">
                        <div class="col-md-6 position-relative">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" autocomplete="off" required>
                            <div id="customer_suggestions" class="dropdown-menu w-100" style="display: none; z-index: 1000;"></div>
                        </div>
                        <div class="col-md-3 position-relative">
                            <label for="customer_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="customer_phone" name="customer_phone" required
                                onkeypress="return isNumberKey(event)"
                                oninput="validatePhoneLength(this)">
                            <div id="phone_suggestions" class="dropdown-menu w-100" style="display: none; z-index: 1000;"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select class="form-control" id="branch_id" name="branch_id" required>
                                @if (auth()->user()->role_id == 0)
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                @else
                                    <option value="{{auth()->user()->branch->id}}">{{auth()->user()->branch->name}}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Service Type & Date -->
                    <div class="row mb-1">
                        <div class="col-md-6">
                            <label for="service_type" class="form-label">Service Type</label>
                            <select class="form-control" id="service_type" name="service_type" required>
                                <option value="">Select Service Type</option>
                                <option value="therapy">Therapy</option>
                                <option value="package">Package</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                    </div>

                    <!-- Therapy Fields with Repeater -->
                    <div id="therapy_fields" style="display: none;">
                        <h6 class="mb-3">Therapy Services</h6>
                        <div id="therapy_repeater">
                            <!-- Initial Therapy Row -->
                            <div class="therapy-row mb-3" data-index="0">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <label class="form-label">Therapy</label>
                                        <select class="form-control therapy-select" name="therapies[0][therapy_id]" required>
                                            <option value="">Select Therapy</option>
                                            @foreach ($therapies as $therapy)
                                                <option value="{{ $therapy->id }}" data-price="{{ $therapy->price }}">{{ $therapy->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Therapist</label>
                                        <select class="form-control therapist-select" name="therapies[0][therapist_id]" required>
                                            <option value="">Select Therapist</option>
                                            @foreach ($therapists as $therapist)
                                                <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Time In</label>
                                        <input type="time" class="form-control time-in" name="therapies[0][time_in]" required>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Time Out</label>
                                        <input type="time" class="form-control time-out" name="therapies[0][time_out]" required>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Price</label>
                                        <input type="number" class="form-control price" name="therapies[0][price]" min="0" step="10.00" required>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Qty</label>
                                        <input type="number" class="form-control qty" name="therapies[0][qty]" min="1" value="1" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Total</label>
                                        <input type="number" class="form-control total" name="therapies[0][total]" readonly required>
                                    </div>
                                    {{-- <div class="col-md-1"> 
                                        <label class="form-label invisible">Action</label>
                                        <button type="button" class="btn btn-icon btn-danger remove-therapy" disabled>
                                            <i class="menu-icon icon-base ti tabler-trash"></i>
                                        </button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        {{-- <button type="button" class="btn btn-success mb-1" id="add_therapy">Add Therapy</button> --}}
                    </div>

                    <!-- Package Fields -->
                    <div id="package_fields" style="display: none;">
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <label for="package_id" class="form-label">Select Package</label>
                                <select class="form-control service-select" id="package_id" name="package_id">
                                    <option value="">Select Package</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}" data-price="{{ $package->price }}">{{ $package->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="package_price" class="form-label">Package Price</label>
                                <input type="number" class="form-control" id="package_price" name="package_price" min="0" step="0.01">
                            </div>
                        </div>
                        <h6 class="mb-3">Package Therapies</h6>
                        <div id="package_therapy_repeater"></div>
                    </div>

                    <!-- Payment Method -->
                    <div class="row mb-1">
                        <div class="col-md-6">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="cash">Cash</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="service_total_amount" class="form-label">Total Amount (₹)</label>
                            <input type="text" class="form-control" id="service_total_amount" name="total_amount" readonly required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Receipt</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let therapyIndex = 1;
    const maxTherapies = @json(count($therapies)); // Number of available therapy options

    // Initialize date field with today's date
    $('#date').val(new Date().toISOString().split('T')[0]);

    // Set default time values (current time rounded to nearest 15 minutes)
    const now = new Date();
    const minutes = Math.ceil(now.getMinutes() / 15) * 15;
    now.setMinutes(minutes);
    const timeString = now.toTimeString().slice(0, 5);
    $('.time-in').val(timeString);

    // Set default time out (1 hour after time in)
    const endTime = new Date(now);
    endTime.setHours(endTime.getHours() + 1);
    const endTimeString = endTime.toTimeString().slice(0, 5);
    $('.time-out').val(endTimeString);

    // Service type change handler
    $('#service_type').change(function() {
        const serviceType = $(this).val();
        $('#therapy_fields, #package_fields').hide();
        $('#therapy_repeater, #package_therapy_repeater').empty();
        $('#service_total_amount').val('');
        $('#package_id').val('');
        $('#package_price').val('').prop('required', false); // Clear and remove required

        if (serviceType === 'therapy') {
            $('#therapy_fields').show();
            addTherapyRow(0); // Add initial therapy row
            updateAddButtonState(); // Check button state after adding initial row
        } else if (serviceType === 'package') {
            $('#package_fields').show();
            $('#package_price').prop('required', true); // Add required for package
        }
    });

    // Package selection handler
    $('#package_id').change(function() {
        const packageId = $(this).val();
        const packagePrice = $(this).find('option:selected').data('price');
        $('#package_price').val(packagePrice || 0);

        if (!packageId) {
            $('#package_therapy_repeater').empty();
            $('#service_total_amount').val('');
            return;
        }

        // Fetch package therapies
        $.get(`/admin/receipts/package-therapies/${packageId}`, function(therapies) {
            $('#package_therapy_repeater').empty();
            therapies.forEach((therapy, index) => {
                addPackageTherapyRow(therapy, index);
            });
            $('#service_total_amount').val(packagePrice);
        }).fail(function() {
            alert('Failed to load package therapies');
        });
    });

    // Add therapy row
    // $('#add_therapy').click(function() {
    //     if ($('.therapy-row').length < maxTherapies) {
    //         addTherapyRow(therapyIndex++);
    //         updateAddButtonState();
    //         updateTherapyOptions(); // Update therapy options to disable selected therapies

    //         // Set default time values for new row
    //         const now = new Date();
    //         const minutes = Math.ceil(now.getMinutes() / 15) * 15;
    //         now.setMinutes(minutes);
    //         const timeString = now.toTimeString().slice(0, 5);

    //         const endTime = new Date(now);
    //         endTime.setHours(endTime.getHours() + 1);
    //         const endTimeString = endTime.toTimeString().slice(0, 5);

    //         $('.therapy-row').last().find('.time-in').val(timeString);
    //         $('.therapy-row').last().find('.time-out').val(endTimeString);
    //     }
    // });

    // Remove therapy row
    $(document).on('click', '.remove-therapy', function() {
        $(this).closest('.therapy-row').remove();
        updateTotalAmount();
        updateAddButtonState();
        updateTherapyOptions(); // Update therapy options after removal
    });

    // Calculate total when therapy, price, qty changes
    $(document).on('change', '.therapy-select, .price, .qty', function() {
        const row = $(this).closest('.therapy-row');
        calculateRowTotal(row);
        updateTotalAmount();
        if ($(this).hasClass('therapy-select')) {
            updateTherapyOptions(); // Update therapy options when therapy changes
        }
    });

    // Update price field and calculate total when therapy is selected
    $(document).on('change', '.therapy-select', function() {
        const row = $(this).closest('.therapy-row');
        const selectedOption = $(this).find('option:selected');
        const price = selectedOption.data('price') || 0;
        row.find('.price').val(price);
        calculateRowTotal(row); // Calculate row total immediately
        updateTotalAmount(); // Update overall total
    });

    // Validate time out is after time in
    $(document).on('change', '.time-in, .time-out', function() {
        const row = $(this).closest('.therapy-row');
        const timeIn = row.find('.time-in').val();
        const timeOut = row.find('.time-out').val();

        if (timeIn && timeOut && timeOut <= timeIn) {
            alert('Time Out must be after Time In');
            row.find('.time-out').val('');
        }
    });

    // Customer name and phone autocomplete
    let customerInput = document.getElementById('customer_name');
    let phoneInput = document.getElementById('customer_phone');
    let branchSelect = document.getElementById('branch_id');
    let customerSuggestionsBox = document.getElementById('customer_suggestions');
    let phoneSuggestionsBox = document.getElementById('phone_suggestions');
    let isMouseDownOnCustomerSuggestion = false;
    let isMouseDownOnPhoneSuggestion = false;

    // Hide both suggestion boxes when clicking outside
    document.addEventListener('click', function(e) {
        if (!customerInput.contains(e.target) && !customerSuggestionsBox.contains(e.target)) {
            customerSuggestionsBox.style.display = 'none';
        }
        if (!phoneInput.contains(e.target) && !phoneSuggestionsBox.contains(e.target)) {
            phoneSuggestionsBox.style.display = 'none';
        }
    });

    // Customer name autocomplete
    customerInput.addEventListener('focus', function() {
        fetchCustomers(this.value.trim(), 'name');
    });

    customerInput.addEventListener('input', function() {
        fetchCustomers(this.value.trim(), 'name');
    });

    // Phone number autocomplete
    phoneInput.addEventListener('focus', function() {
        fetchCustomers(this.value.trim(), 'phone');
    });

    phoneInput.addEventListener('input', function() {
        fetchCustomers(this.value.trim(), 'phone');
    });

    function fetchCustomers(query = '', type = 'name') {
        fetch(`/admin/receipts/search-customers?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                const suggestionsBox = type === 'name' ? customerSuggestionsBox : phoneSuggestionsBox;
                suggestionsBox.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(customer => {
                        let suggestionItem = document.createElement('a');
                        suggestionItem.href = '#';
                        suggestionItem.classList.add('dropdown-item');
                        suggestionItem.textContent = `${customer.name} (${customer.phone})`;
                        suggestionItem.dataset.name = customer.name;
                        suggestionItem.dataset.phone = customer.phone;
                        suggestionItem.dataset.branchId = customer.branch_id;

                        suggestionItem.addEventListener('mousedown', function() {
                            if (type === 'name') {
                                isMouseDownOnCustomerSuggestion = true;
                            } else {
                                isMouseDownOnPhoneSuggestion = true;
                            }
                        });

                        suggestionItem.addEventListener('click', function(e) {
                            e.preventDefault();
                            customerInput.value = customer.name || '';
                            phoneInput.value = customer.phone || '';
                            // Auto-select branch
                            if (customer.branch_id) {
                                branchSelect.value = customer.branch_id;
                            } else {
                                branchSelect.value = '';
                            }
                            customerSuggestionsBox.style.display = 'none';
                            phoneSuggestionsBox.style.display = 'none';
                            isMouseDownOnCustomerSuggestion = false;
                            isMouseDownOnPhoneSuggestion = false;
                        });

                        suggestionsBox.appendChild(suggestionItem);
                    });
                    suggestionsBox.style.display = 'block';
                } else {
                    suggestionsBox.style.display = 'none';
                }
            })
            .catch(error => console.error('Error fetching customers:', error));
    }

    customerInput.addEventListener('blur', function() {
        setTimeout(() => {
            if (!isMouseDownOnCustomerSuggestion) {
                customerSuggestionsBox.style.display = 'none';
            }
            isMouseDownOnCustomerSuggestion = false;
        }, 200);
    });

    phoneInput.addEventListener('blur', function() {
        setTimeout(() => {
            if (!isMouseDownOnPhoneSuggestion) {
                phoneSuggestionsBox.style.display = 'none';
            }
            isMouseDownOnPhoneSuggestion = false;
        }, 200);
    });

    // Helper functions
    function addTherapyRow(index) {
        const rowHtml = `
            <div class="therapy-row mb-3" data-index="${index}">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <label class="form-label">Therapy</label>
                        <select class="form-control therapy-select" name="therapies[${index}][therapy_id]" required>
                            <option value="">Select Therapy</option>
                            @foreach ($therapies as $therapy)
                                <option value="{{ $therapy->id }}" data-price="{{ $therapy->price }}">{{ $therapy->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Therapist</label>
                        <select class="form-control therapist-select" name="therapies[${index}][therapist_id]" required>
                            <option value="">Select Therapist</option>
                            @foreach ($therapists as $therapist)
                                <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Time In</label>
                        <input type="time" class="form-control time-in" name="therapies[${index}][time_in]" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Time Out</label>
                        <input type="time" class="form-control time-out" name="therapies[${index}][time_out]" required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Price</label>
                        <input type="number" class="form-control price" name="therapies[${index}][price]" min="0" step="10.00" required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Qty</label>
                        <input type="number" class="form-control qty" name="therapies[${index}][qty]" min="1" value="1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Total</label>
                        <input type="number" class="form-control total" name="therapies[${index}][total]" readonly required>
                    </div>
                    <div class="col-md-1">

                        <button type="button" class="btn btn-icon btn-danger remove-therapy mt-5" ${index === 0 ? 'disabled' : ''}>
                            <i class="menu-icon icon-base ti tabler-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#therapy_repeater').append(rowHtml);
    }

    function addPackageTherapyRow(therapy, index) {
        const rowHtml = `
            <div class="therapy-row mb-3" data-index="${index}">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <label class="form-label">Therapy</label>
                        <select class="form-control therapy-select" name="therapies[${index}][therapy_id]" required>
                            <option value="${therapy.id}" data-price="${therapy.price}" selected>${therapy.name}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Therapist</label>
                        <select class="form-control therapist-select" name="therapies[${index}][therapist_id]" required>
                            <option value="">Select Therapist</option>
                            @foreach ($therapists as $therapist)
                                <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Price</label>
                        <input type="number" class="form-control price" name="therapies[${index}][price]" value="${therapy.price}" min="0" step="10.00" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Qty</label>
                        <input type="number" class="form-control qty" name="therapies[${index}][qty]" min="1" value="${therapy.quantity || 1}" readonly required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Total</label>
                        <input type="number" class="form-control total" name="therapies[${index}][total]" value="${(therapy.price * (therapy.quantity || 1)).toFixed(2)}" readonly required>
                    </div>
                </div>
            </div>
        `;
        $('#package_therapy_repeater').append(rowHtml);
    }

    function calculateRowTotal(row) {
        const price = parseFloat(row.find('.price').val()) || 0;
        const qty = parseInt(row.find('.qty').val()) || 1;
        const total = price * qty;
        row.find('.total').val(total.toFixed(2));
    }

    function updateTotalAmount() {
        let total = 0;
        $('.total').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#service_total_amount').val(total.toFixed(2));
    }

    function updateAddButtonState() {
        const therapyCount = $('.therapy-row').length;
        $('#add_therapy').prop('disabled', therapyCount >= maxTherapies);
    }

    function updateTherapyOptions() {
        const selectedTherapyIds = [];
        $('.therapy-select').each(function() {
            const value = $(this).val();
            if (value && value !== '') {
                selectedTherapyIds.push(value);
            }
        });

        $('.therapy-select').each(function() {
            const currentSelect = $(this);
            const currentValue = currentSelect.val();
            currentSelect.find('option').prop('disabled', false);
            currentSelect.find('option').each(function() {
                const optionValue = $(this).val();
                if (optionValue && optionValue !== '' && selectedTherapyIds.includes(optionValue) && optionValue !== currentValue) {
                    $(this).prop('disabled', true);
                }
            });
        });
    }

    function isNumberKey(evt) {
        const charCode = (evt.which) ? evt.which : evt.keyCode;
        return !(charCode > 31 && (charCode < 48 || charCode > 57));
    }

    function validatePhoneLength(input) {
        if (input.value.length > 10) {
            input.value = input.value.slice(0, 10);
        }
    }
});
</script>
