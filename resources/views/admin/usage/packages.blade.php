@extends('layouts.admin')

@section('content')
<!-- Package Usage Management -->
<div class="card">
    <h5 class="card-header">
        Package Usage Management
        <!-- Add any header buttons here if needed -->
    </h5>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-datatable text-nowrap">
        <table class="dt-column-search table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Package</th>
                    <th>Total Amount</th>
                    <th>Date</th>
                    <th>Used Therapies</th>
                    <th>Remaining Therapies</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($receipts as $receipt)
                    @php
                        // Get total assigned and used therapies
                        $totalTherapies = $receipt->receiptPackageTherapies->sum('original_qty');
                        $usedTherapies = $receipt->receiptPackageTherapies->sum('redeem_qty');
                        $remainingTherapies = $totalTherapies - $usedTherapies;

                        // Get validity details
                        $validityCount = $receipt->package->validity_count ?? 0;
                        $validityUnit = $receipt->package->validity_unit ?? 'day';
                        $purchaseDate = \Carbon\Carbon::parse($receipt->date);
                        $expiryDate = $purchaseDate->copy()->add($validityCount, $validityUnit);
                        $daysToExpire = now()->diffInDays($expiryDate, false);

                        // Determine status
                        $status = 'Pending';
                        if ($daysToExpire < 0) {
                            $status = 'Expired';
                        } elseif ($daysToExpire <= 3) {
                            $status = 'Expiring Soon';
                        }
                        if ($remainingTherapies == 0) {
                            $status = 'Used';
                        }
                    @endphp
                    <tr>
                        <td>{{ $receipt->customer->name ?? 'N/A' }}</td>
                        <td>{{ $receipt->package->name ?? 'N/A' }}</td>
                        <td>{{ $receipt->total_amount }}</td>
                        <td>{{ $receipt->date }}</td>
                        <td>{{ $usedTherapies }}</td>
                        <td>{{ $remainingTherapies }}</td>
                        <td>
                            <span class="badge
                                {{ $status == 'Expired' ? 'bg-danger' :
                                   ($status == 'Expiring Soon' ? 'bg-warning' :
                                   ($status == 'Used' ? 'bg-success' : 'bg-primary')) }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td>
                            @if ($status != 'Expired' && $status != 'Used')
                                <button class="btn btn-sm btn-primary mark-as-used"
                                        data-bs-toggle="modal"
                                        data-bs-target="#usageModal"
                                        data-receipt-id="{{ $receipt->id }}"
                                        data-view-details="false">
                                    Mark as Used
                                </button>
                            @else
                                <button class="btn btn-sm btn-info mark-as-used"
                                        data-bs-toggle="modal"
                                        data-bs-target="#usageModal"
                                        data-receipt-id="{{ $receipt->id }}"
                                        data-view-details="true">
                                    View Details
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="usageModal" tabindex="-1" aria-labelledby="usageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="usageModalLabel">Package Usage Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Customer and Package Info -->
                <div id="customer-info"></div>
                <hr>
                <!-- Package Therapies Table -->
                <form id="usageForm" method="POST" action="{{ url('admin/usage/updatePackageUsage') }}">
                    @csrf
                    <input type="hidden" name="receipt_id" id="receipt_id">
                    <table class="table table-bordered" id="therapies-table">
                        <thead>
                            <tr>
                                <th>Therapy</th>
                                <th>Therapist</th>
                                <th>Qty</th>
                                <th>Redeem Qty</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        const modal = document.getElementById('usageModal');
        const customerInfo = document.getElementById('customer-info');
        const therapiesTableBody = document.querySelector('#therapies-table tbody');
        const receiptIdInput = document.getElementById('receipt_id');
        const submitButton = document.querySelector('#usageForm button[type="submit"]');
        const cancelButton = document.querySelector('#usageForm button[type="button"]');

        $('body').on('click', '.mark-as-used', function () {
            const receiptId = $(this).data('receipt-id');
            const isViewDetails = $(this).data('view-details') === true;

            receiptIdInput.value = receiptId;

            // Show or hide buttons based on isViewDetails
            if (isViewDetails) {
                submitButton.style.display = 'none';
                cancelButton.style.display = 'none';
            } else {
                submitButton.style.display = 'inline-block';
                cancelButton.style.display = 'inline-block';
            }

            // Perform AJAX request to get receipt details
            $.ajax({
                url: `/admin/receipt/${receiptId}/details`,
                method: 'GET',
                success: function (data) {
                    customerInfo.innerHTML = `
                        <p><strong>Customer Name:</strong> ${data.customer?.name ?? 'N/A'}</p>
                        <p><strong>Phone:</strong> ${data.customer?.phone ?? 'N/A'}</p>
                        <p><strong>Package Name:</strong> ${data.package?.name ?? 'N/A'}</p>
                        <p><strong>Receipt Date:</strong> ${data.date ?? 'N/A'}</p>
                    `;

                    therapiesTableBody.innerHTML = '';
                    data.receipt_package_therapies.forEach((therapy) => {
                        const timeIn = therapy.time_in ? therapy.time_in : '';
                        const timeOut = therapy.time_out ? therapy.time_out : '';
                        const date = therapy.date ? therapy.date : '';
                        const originalQty = therapy.original_qty || 0;
                        const redeemQty = therapy.redeem_qty !== null ? therapy.redeem_qty : 0;
                        const therapistName = therapy.therapist ? therapy.therapist.name : 'N/A';

                        // Determine if we should show input fields or just display values
                        // Always show redeem_qty input if not in view mode and there are remaining therapies
                        const showRedeemQtyInput = isViewDetails ? false : (redeemQty < originalQty);
                        const showTimeInInput = isViewDetails ? false : (!timeIn);
                        const showTimeOutInput = isViewDetails ? false : (!timeOut);
                        const showDateInput = isViewDetails ? false : (!date);

                        therapiesTableBody.innerHTML += `
                            <tr>
                                <td>${therapy.therapy?.name ?? 'N/A'}
                                    <input type="hidden" name="therapy_id[${therapy.id}]" value="${therapy.id}">
                                </td>
                                <td>${therapistName}</td>
                                <td>${originalQty}</td>
                                <td>
                                    ${showRedeemQtyInput ?
                                        `<input type="number" name="redeem_qty[${therapy.id}]"
                                               class="form-control redeem-qty"
                                               data-max="${originalQty - redeemQty}"
                                               min="0"
                                               max="${originalQty}"
                                               value="${redeemQty}" >` :
                                        redeemQty}
                                    ${!showRedeemQtyInput && !isViewDetails ?
                                        `<input type="hidden" name="redeem_qty[${therapy.id}]" value="${redeemQty}">` : ''}
                                </td>
                                <td>
                                    ${showTimeInInput ?
                                        `<input type="time" name="time_in[${therapy.id}]"
                                               class="form-control"
                                               value="" >` :
                                        timeIn}
                                </td>
                                <td>
                                    ${showTimeOutInput ?
                                        `<input type="time" name="time_out[${therapy.id}]"
                                               class="form-control"
                                               value="" >` :
                                        timeOut}
                                </td>
                                <td>
                                    ${showDateInput ?
                                        `<input type="date" name="date[${therapy.id}]"
                                               class="form-control"
                                               value="" >` :
                                        date}
                                </td>
                            </tr>
                        `;
                    });

                    // Add validation for redeem_qty inputs
                    // $('.redeem-qty').on('input', function () {
                    //     const maxQty = parseInt($(this).data('max'));
                    //     const value = parseInt($(this).val());
                    //     if (value > maxQty) {
                    //         $(this).val(maxQty);
                    //         alert(`You can only redeem up to ${maxQty} more therapies`);
                    //     } else if (value < 1) {
                    //         $(this).val(1);
                    //         alert('You must redeem at least 1 therapy');
                    //     }
                    // });
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching receipt details:', error);
                    customerInfo.innerHTML = '<p>Error loading data. Please try again.</p>';
                    therapiesTableBody.innerHTML = '<tr><td colspan="7">Error loading therapies</td></tr>';
                }
            });
        });
    });
    </script>

@endsection
