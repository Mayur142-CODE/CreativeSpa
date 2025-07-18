<!-- Edit Receipt Modal -->
<div class="modal fade" id="editReceiptModal" tabindex="-1" aria-labelledby="editReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReceiptModalLabel">Edit Receipt #<span id="edit-receipt-id"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editReceiptForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Customer Name, Phone & Branch -->
                    <div class="row mb-3">
                        <div class="col-md-6 position-relative">
                            <label for="edit_customer_name" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="edit_customer_name" name="customer_name" autocomplete="off" required>
                            <div id="edit_customer_suggestions" class="dropdown-menu w-100" style="z-index: 1000;"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="edit_customer_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="edit_customer_phone" name="customer_phone" required
                                onkeypress="return isNumberKey(event)"
                                oninput="validatePhoneLength(this)">
                        </div>
                        <div class="col-md-3">
                            <label for="edit_branch_id" class="form-label">Branch</label>
                            <select class="form-control" id="edit_branch_id" name="branch_id" required>
                                <option value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Service Type & Date -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_service_type" class="form-label">Service Type</label>
                            <select class="form-control" id="edit_service_type" name="service_type" required>
                                <option value="">Select Service Type</option>
                                <option value="therapy">Therapy</option>
                                <option value="package">Package</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="edit_date" name="date" required>
                        </div>
                    </div>

                    <!-- Therapy Fields with Repeater -->
                    <div id="edit_therapy_fields" style="display: none;">
                        <h6 class="mb-3">Therapy Services</h6>
                        <div id="edit_therapy_repeater"></div>
                        <button type="button" class="btn btn-success mb-3 d-none" id="edit_add_therapy" >Add Therapy</button>
                    </div>

                    <!-- Package Fields -->
                    <div id="edit_package_fields" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_package_id" class="form-label">Select Package</label>
                                <select class="form-control service-select" id="edit_package_id" name="package_id">
                                    <option value="">Select Package</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}" data-price="{{ $package->price }}">{{ $package->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_package_price" class="form-label">Package Price</label>
                                <input type="number" class="form-control" id="edit_package_price" name="package_price" min="0" step="0.01">
                            </div>
                        </div>
                        <h6 class="mb-3">Package Therapies</h6>
                        <div id="edit_package_therapy_repeater"></div>
                    </div>

                    <!-- Payment Method -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_payment_method" class="form-label">Payment Method</label>
                            <select class="form-control" id="edit_payment_method" name="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="cash">Cash</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_service_total_amount" class="form-label">Total Amount (₹)</label>
                            <input type="text" class="form-control" id="edit_service_total_amount" name="total_amount" readonly required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Receipt</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Edit Modal Logic
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editReceiptModal');
        if (!editModal) {
            console.error('Edit modal element not found');
            return;
        }

        let therapyIndex = 1;
        const totalTherapies = {{ count($therapies) }};

        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const receiptId = button.getAttribute('data-id');
            if (!receiptId) {
                console.error('No data-id found on the button');
                return;
            }

            document.getElementById('edit-receipt-id').textContent = receiptId;
            document.getElementById('editReceiptForm').action = `/admin/receipts/${receiptId}`;

            // Fetch receipt data
            fetch(`/admin/receipts/${receiptId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Fetched receipt data:', data);

                    // Populate basic fields
                    document.getElementById('edit_customer_name').value = data.customer?.name || '';
                    document.getElementById('edit_customer_phone').value = data.customer?.phone || '';
                    document.getElementById('edit_branch_id').value = data.customer?.branch_id || '';
                    document.getElementById('edit_service_type').value = data.service_type || '';
                    document.getElementById('edit_date').value = data.date || '';
                    document.getElementById('edit_payment_method').value = data.payment_method || '';
                    document.getElementById('edit_service_total_amount').value = data.total_amount || '';

                    const therapyFields = document.getElementById('edit_therapy_fields');
                    const packageFields = document.getElementById('edit_package_fields');
                    const therapyRepeater = document.getElementById('edit_therapy_repeater');
                    const packageTherapyRepeater = document.getElementById('edit_package_therapy_repeater');
                    const addTherapyBtn = document.getElementById('edit_add_therapy');

                    // Initialize fields based on service_type
                    if (data.service_type === 'therapy') {
                        therapyFields.style.display = 'block';
                        packageFields.style.display = 'none';
                        addTherapyBtn.style.display = 'block';
                        therapyRepeater.innerHTML = '';

                        if (Array.isArray(data.receipt_therapies)) {
                            data.receipt_therapies.forEach((therapy, index) => {
                                therapyRepeater.innerHTML += `
                                    <div class="therapy-row mb-3" data-index="${index}">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <label class="form-label">Therapy</label>
                                                <select class="form-control therapy-select" name="therapies[${index}][therapy_id]" required>
                                                    <option value="">Select Therapy</option>
                                                    @foreach ($therapies as $t)
                                                        <option value="{{ $t->id }}" data-price="{{ $t->price }}" ${therapy.therapy_id === {{ $t->id }} ? 'selected' : ''}>{{ $t->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">Therapist</label>
                                                <select class="form-control therapist-select" name="therapies[${index}][therapist_id]" required>
                                                    <option value="">Select Therapist</option>
                                                    @foreach ($therapists as $therapist)
                                                        <option value="{{ $therapist->id }}" ${therapy.therapist_id === {{ $therapist->id }} ? 'selected' : ''}>{{ $therapist->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Time In</label>
                                                <input type="time" class="form-control time-in" name="therapies[${index}][time_in]" value="${therapy.time_in || ''}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Time Out</label>
                                                <input type="time" class="form-control time-out" name="therapies[${index}][time_out]" value="${therapy.time_out || ''}" required>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">Price</label>
                                                <input type="number" class="form-control price" name="therapies[${index}][price]" min="0" step="0.01" value="${therapy.price || ''}" required>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">Qty</label>
                                                <input type="number" class="form-control qty" name="therapies[${index}][qty]" min="1" value="${therapy.original_qty || 1}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Total</label>
                                                <input type="number" class="form-control total" name="therapies[${index}][total]" value="${therapy.total || 0}" readonly required>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-icon btn-danger remove-therapy mt-5" ${index === 0 ? 'disabled' : ''}>
                                                    <i class="menu-icon icon-base ti tabler-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            therapyIndex = data.receipt_therapies.length;
                        } else {
                            console.warn('receipt_therapies is not an array or is undefined:', data.receipt_therapies);
                        }
                    } else if (data.service_type === 'package') {
                        packageFields.style.display = 'block';
                        therapyFields.style.display = 'none';
                        addTherapyBtn.style.display = 'none';
                        document.getElementById('edit_package_id').value = data.package_id || '';
                        document.getElementById('edit_package_price').value = data.total_amount || '';
                        packageTherapyRepeater.innerHTML = '';

                        if (Array.isArray(data.receipt_package_therapies)) {
                            data.receipt_package_therapies.forEach((therapy, index) => {
                                packageTherapyRepeater.innerHTML += `
                                    <div class="therapy-row mb-3" data-index="${index}">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <label class="form-label">Therapy</label>
                                                <select class="form-control therapy-select" name="therapies[${index}][therapy_id]" required>
                                                    <option value="${therapy.therapy_id || ''}" data-price="${therapy.price || therapy.therapy?.price || 0}" selected>${therapy.therapy?.name || 'Unknown'}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Therapist</label>
                                                <select class="form-control therapist-select" name="therapies[${index}][therapist_id]" required>
                                                    <option value="">Select Therapist</option>
                                                    @foreach ($therapists as $therapist)
                                                        <option value="{{ $therapist->id }}" ${therapy.therapist_id === {{ $therapist->id }} ? 'selected' : ''}>{{ $therapist->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Price</label>
                                                <input type="number" class="form-control price" name="therapies[${index}][price]" min="0" step="0.01" value="${therapy.price || ''}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Qty</label>
                                                <input type="number" class="form-control qty" name="therapies[${index}][qty]" min="1" value="${therapy.original_qty || 1}" required readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Total</label>
                                                <input type="number" class="form-control total" name="therapies[${index}][total]" value="${therapy.total || 0}" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            therapyIndex = data.receipt_package_therapies.length;
                        } else {
                            console.warn('receipt_package_therapies is not an array or is undefined:', data.receipt_package_therapies);
                        }
                    } else {
                        therapyFields.style.display = 'none';
                        packageFields.style.display = 'none';
                    }

                    updateTotalPrice();
                    updateTherapyOptions();
                    checkAddTherapyButton();

                    // Add event listeners for therapy selects, price, and qty inputs
                    document.querySelectorAll('.therapy-select, .price, .qty').forEach(input => {
                        input.addEventListener('change', function() {
                            calculateTotal(this);
                        });
                    });

                    // Add validation for time in/out
                    document.querySelectorAll('.time-in, .time-out').forEach(input => {
                        input.addEventListener('change', function() {
                            validateTime(this);
                        });
                    });
                })
                .catch(error => {
                    console.error('Error fetching receipt data:', error);
                });
        });

        document.getElementById('edit_service_type').addEventListener('change', function () {
            const therapyFields = document.getElementById('edit_therapy_fields');
            const packageFields = document.getElementById('edit_package_fields');
            const totalAmountField = document.getElementById('edit_service_total_amount');
            const therapyRepeater = document.getElementById('edit_therapy_repeater');
            const packageTherapyRepeater = document.getElementById('edit_package_therapy_repeater');
            const addTherapyBtn = document.getElementById('edit_add_therapy');
            const packagePriceField = document.getElementById('edit_package_price');

            packagePriceField.value = '';
            packagePriceField.required = false;

            if (this.value === 'therapy') {
                therapyFields.style.display = 'block';
                packageFields.style.display = 'none';
                totalAmountField.value = '';
                addTherapyBtn.style.display = 'block';
                therapyRepeater.innerHTML = `
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
                            <div class="col-md-1">
                                <label class="form-label">Therapist</label>
                                <select class="form-control therapist-select" name="therapies[0][therapist_id]" required>
                                    <option value="">Select Therapist</option>
                                    @foreach ($therapists as $therapist)
                                        <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Time In</label>
                                <input type="time" class="form-control time-in" name="therapies[0][time_in]" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Time Out</label>
                                <input type="time" class="form-control time-out" name="therapies[0][time_out]" required>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Price</label>
                                <input type="number" class="form-control price" name="therapies[0][price]" min="0" step="0.01" required>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Qty</label>
                                <input type="number" class="form-control qty" name="therapies[0][qty]" min="1" value="1" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Total</label>
                                <input type="number" class="form-control total" name="therapies[0][total]" readonly required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-icon btn-danger remove-therapy mt-5" disabled>
                                    <i class="menu-icon icon-base ti tabler-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                packageTherapyRepeater.innerHTML = '';
                document.getElementById('edit_package_id').value = '';
                therapyIndex = 1;

                // Set default time values for new row
                const now = new Date();
                const minutes = Math.ceil(now.getMinutes() / 15) * 15;
                now.setMinutes(minutes);
                const timeString = now.toTimeString().slice(0, 5);

                const endTime = new Date(now);
                endTime.setHours(endTime.getHours() + 1);
                const endTimeString = endTime.toTimeString().slice(0, 5);

                $('.therapy-row').last().find('.time-in').val(timeString);
                $('.therapy-row').last().find('.time-out').val(endTimeString);

                // Add event listeners for the new therapy select, price, and qty input
                document.querySelectorAll('.therapy-select, .price, .qty').forEach(input => {
                    input.addEventListener('change', function() {
                        calculateTotal(this);
                    });
                });

                // Add validation for time in/out
                document.querySelectorAll('.time-in, .time-out').forEach(input => {
                    input.addEventListener('change', function() {
                        validateTime(this);
                    });
                });
            } else if (this.value === 'package') {
                packageFields.style.display = 'block';
                therapyFields.style.display = 'none';
                totalAmountField.value = '';
                addTherapyBtn.style.display = 'none';
                therapyRepeater.innerHTML = '';
                packageTherapyRepeater.innerHTML = '';
                document.getElementById('edit_package_id').value = '';
                packagePriceField.required = true;
            } else {
                therapyFields.style.display = 'none';
                packageFields.style.display = 'none';
                totalAmountField.value = '';
                addTherapyBtn.style.display = 'block';
                therapyRepeater.innerHTML = '';
                packageTherapyRepeater.innerHTML = '';
                document.getElementById('edit_package_id').value = '';
            }
            updateTotalPrice();
            checkAddTherapyButton();
        });

        document.getElementById('edit_package_id').addEventListener('change', function () {
            const packageId = this.value;
            const totalAmountField = document.getElementById('edit_service_total_amount');
            const therapyRepeater = document.getElementById('edit_package_therapy_repeater');
            const packagePriceField = document.getElementById('edit_package_price');
            const selectedPrice = this.options[this.selectedIndex]?.getAttribute('data-price') || '';

            packagePriceField.value = selectedPrice;

            if (packageId) {
                fetch(`/admin/receipts/package-therapies/${packageId}`)
                    .then(response => response.json())
                    .then(therapies => {
                        therapyRepeater.innerHTML = '';
                        therapyIndex = 0;
                        if (therapies.length > 0) {
                            therapies.forEach((therapy, index) => {
                                const newRow = document.createElement('div');
                                newRow.classList.add('therapy-row', 'mb-3');
                                newRow.setAttribute('data-index', index);
                                newRow.innerHTML = `
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
                                        <div class="col-md-1">
                                            <label class="form-label">Price</label>
                                            <input type="number" class="form-control price" name="therapies[${index}][price]" min="0" step="0.01" value="${therapy.price}" required>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">Qty</label>
                                            <input type="number" class="form-control qty" name="therapies[${index}][qty]" min="1" value="${therapy.quantity || 1}" required readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Total</label>
                                            <input type="number" class="form-control total" name="therapies[${index}][total]" value="${(therapy.price * (therapy.quantity || 1)).toFixed(2)}" readonly required>
                                        </div>
                                    </div>
                                `;
                                therapyRepeater.appendChild(newRow);
                                therapyIndex++;

                                // Add event listeners for the new therapy select, price, and qty input
                                newRow.querySelectorAll('.therapy-select, .price, .qty').forEach(input => {
                                    input.addEventListener('change', function() {
                                        calculateTotal(this);
                                    });
                                });
                            });
                            totalAmountField.value = selectedPrice;
                            updateTotalPrice();
                        } else {
                            therapyRepeater.innerHTML = '<p>No therapies found for this package.</p>';
                            totalAmountField.value = selectedPrice;
                        }
                    })
                    .catch(error => console.error('Error fetching package therapies:', error));
            } else {
                therapyRepeater.innerHTML = '';
                totalAmountField.value = '';
                packagePriceField.value = '';
            }
        });

        // document.getElementById('edit_add_therapy').addEventListener('click', function () {
        //     const repeater = document.getElementById('edit_therapy_repeater');
        //     const newRow = document.createElement('div');
        //     newRow.classList.add('therapy-row', 'mb-3');
        //     newRow.setAttribute('data-index', therapyIndex);
        //     newRow.innerHTML = `
        //         <div class="row align-items-center">
        //             <div class="col-md-2">
        //                 <label class="form-label">Therapy</label>
        //                 <select class="form-control therapy-select" name="therapies[${therapyIndex}][therapy_id]" required>
        //                     <option value="">Select Therapy</option>
        //                     @foreach ($therapies as $therapy)
        //                         <option value="{{ $therapy->id }}" data-price="{{ $therapy->price }}">{{ $therapy->name }}</option>
        //                     @endforeach
        //                 </select>
        //             </div>
        //             <div class="col-md-1">
        //                 <label class="form-label">Therapist</label>
        //                 <select class="form-control therapist-select" name="therapies[${therapyIndex}][therapist_id]" required>
        //                     <option value="">Select Therapist</option>
        //                     @foreach ($therapists as $therapist)
        //                         <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
        //                     @endforeach
        //                 </select>
        //             </div>
        //             <div class="col-md-2">
        //                 <label class="form-label">Time In</label>
        //                 <input type="time" class="form-control time-in" name="therapies[${therapyIndex}][time_in]" required>
        //             </div>
        //             <div class="col-md-2">
        //                 <label class="form-label">Time Out</label>
        //                 <input type="time" class="form-control time-out" name="therapies[${therapyIndex}][time_out]" required>
        //             </div>
        //             <div class="col-md-1">
        //                 <label class="form-label">Price</label>
        //                 <input type="number" class="form-control price" name="therapies[${therapyIndex}][price]" min="0" step="0.01" required>
        //             </div>
        //             <div class="col-md-1">
        //                 <label class="form-label">Qty</label>
        //                 <input type="number" class="form-control qty" name="therapies[${therapyIndex}][qty]" min="1" value="1" required>
        //             </div>
        //             <div class="col-md-2">
        //                 <label class="form-label">Total</label>
        //                 <input type="number" class="form-control total" name="therapies[${therapyIndex}][total]" readonly required>
        //             </div>
        //             <div class="col-md-1">
        //                 <button type="button" class="btn btn-icon btn-danger remove-therapy mt-5">
        //                     <i class="menu-icon icon-base ti tabler-trash"></i>
        //                 </button>
        //             </div>
        //         </div>
        //     `;
        //     repeater.appendChild(newRow);
        //     updateTherapyOptions();
        //     therapyIndex++;
        //     document.querySelectorAll('.remove-therapy').forEach(btn => btn.disabled = false);
        //     checkAddTherapyButton();

        //     // Set default time values for new row
        //     const now = new Date();
        //     const minutes = Math.ceil(now.getMinutes() / 15) * 15;
        //     now.setMinutes(minutes);
        //     const timeString = now.toTimeString().slice(0, 5);

        //     const endTime = new Date(now);
        //     endTime.setHours(endTime.getHours() + 1);
        //     const endTimeString = endTime.toTimeString().slice(0, 5);

        //     newRow.querySelector('.time-in').value = timeString;
        //     newRow.querySelector('.time-out').value = endTimeString;

        //     // Add event listeners for the new therapy select, price, and qty input
        //     newRow.querySelectorAll('.therapy-select, .price, .qty').forEach(input => {
        //         input.addEventListener('change', function() {
        //             calculateTotal(this);
        //         });
        //     });

        //     // Add validation for time in/out
        //     newRow.querySelectorAll('.time-in, .time-out').forEach(input => {
        //         input.addEventListener('change', function() {
        //             validateTime(this);
        //         });
        //     });
        // });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-therapy')) {
                e.target.closest('.therapy-row').remove();
                updateTherapyOptions();
                updateTotalPrice();
                if (document.querySelectorAll('.therapy-row').length === 1) {
                    document.querySelector('.remove-therapy').disabled = true;
                }
                checkAddTherapyButton();
            }
        });

        function calculateTotal(element) {
            const row = element.closest('.therapy-row');
            if (!row) return;

            const price = parseFloat(row.querySelector('.price').value) || 0;
            const qty = parseFloat(row.querySelector('.qty').value) || 1;
            const total = price * qty;

            row.querySelector('.total').value = total > 0 ? total.toFixed(2) : 0;
            updateTotalPrice();
        }

        function validateTime(element) {
            const row = element.closest('.therapy-row');
            if (!row) return;

            const timeIn = row.querySelector('.time-in').value;
            const timeOut = row.querySelector('.time-out').value;

            if (timeIn && timeOut && timeOut <= timeIn) {
                alert('Time Out must be after Time In');
                row.querySelector('.time-out').value = '';
            }
        }

        function updateTotalPrice() {
            const totals = document.querySelectorAll('.total');
            let grandTotal = 0;
            totals.forEach(total => grandTotal += parseFloat(total.value) || 0);
            const serviceType = document.getElementById('edit_service_type').value;
            const totalAmountField = document.getElementById('edit_service_total_amount');

            if (serviceType === 'package') {
                const packageId = document.getElementById('edit_package_id').value;
                if (packageId && totals.length === 0) {
                    totalAmountField.value = document.getElementById('edit_package_price').value || '';
                } else {
                    totalAmountField.value = grandTotal.toFixed(2);
                }
            } else {
                totalAmountField.value = grandTotal.toFixed(2);
            }
        }

        function updateTherapyOptions() {
            const selectedTherapies = Array.from(document.querySelectorAll('.therapy-select'))
                .map(select => select.value)
                .filter(value => value !== '');

            document.querySelectorAll('.therapy-select').forEach(select => {
                const currentValue = select.value;
                Array.from(select.options).forEach(option => {
                    if (option.value && option.value !== currentValue) {
                        option.disabled = selectedTherapies.includes(option.value);
                    }
                });
            });
        }

        function checkAddTherapyButton() {
            const therapyRows = document.querySelectorAll('.therapy-row');
            const addTherapyBtn = document.getElementById('edit_add_therapy');

            // Disable if in package mode
            const serviceType = document.getElementById('edit_service_type').value;
            if (serviceType === 'package') {
                addTherapyBtn.disabled = true;
                return;
            }

            // Disable if we've reached max therapies
            addTherapyBtn.disabled = therapyRows.length >= totalTherapies;

            // Also check if all therapies are already selected
            const selectedTherapies = Array.from(document.querySelectorAll('.therapy-select'))
                .map(select => select.value)
                .filter(value => value !== '');

            const allTherapiesSelected = selectedTherapies.length >= totalTherapies;
            addTherapyBtn.disabled = addTherapyBtn.disabled || allTherapiesSelected;
        }

        // Customer Suggestions
        const customerInput = document.getElementById('edit_customer_name');
        const phoneInput = document.getElementById('edit_customer_phone');
        const branchSelect = document.getElementById('edit_branch_id');
        const suggestionsBox = document.getElementById('edit_customer_suggestions');
        let isMouseDownOnSuggestion = false;

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!customerInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.style.display = 'none';
            }
        });

        customerInput.addEventListener('input', function () {
            fetchCustomers(this.value.trim());
        });

        customerInput.addEventListener('focus', function () {
            fetchCustomers(this.value.trim());
        });

        function fetchCustomers(query = '') {
            fetch(`/admin/receipts/search-customers?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsBox.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(customer => {
                            const suggestionItem = document.createElement('a');
                            suggestionItem.href = '#';
                            suggestionItem.classList.add('dropdown-item');
                            suggestionItem.textContent = `${customer.name} (${customer.phone})`;
                            suggestionItem.dataset.name = customer.name;
                            suggestionItem.dataset.phone = customer.phone;
                            suggestionItem.dataset.branchId = customer.branch_id;

                            suggestionItem.addEventListener('mousedown', function() {
                                isMouseDownOnSuggestion = true;
                            });

                            suggestionItem.addEventListener('click', function (e) {
                                e.preventDefault();
                                customerInput.value = customer.name || '';
                                phoneInput.value = customer.phone || '';
                                branchSelect.value = customer.branch_id || '';
                                suggestionsBox.style.display = 'none';
                                isMouseDownOnSuggestion = false;
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

        customerInput.addEventListener('blur', function () {
            setTimeout(() => {
                if (!isMouseDownOnSuggestion) {
                    suggestionsBox.style.display = 'none';
                }
                isMouseDownOnSuggestion = false;
            }, 200);
        });

        suggestionsBox.addEventListener('mousedown', function (e) {
            e.preventDefault();
        });
    });

    // Helper functions
    function isNumberKey(evt) {
        const charCode = (evt.which) ? evt.which : evt.keyCode;
        return !(charCode > 31 && (charCode < 48 || charCode > 57));
    }

    function validatePhoneLength(input) {
        if (input.value.length > 10) {
            input.value = input.value.slice(0, 10);
        }
    }
</script>
