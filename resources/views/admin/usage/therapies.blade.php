@extends('layouts.admin')

@section('content')
<!-- Therapy Usage Management -->
<div class="card">
    <h5 class="card-header">
        Therapy Usage Management
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

    <div class="card-datatable text-nowrap table-responsive">
        <table class="dt-column-search table table-bordered ">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Customer Phone</th>
                    <th>Branch Name</th>
                    <th>Receipt Date</th>
                    <th>Therapies</th>
                    <th>Total Quantity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipts as $receipt)
                    <tr>
                        <td>{{ $receipt->customer->name }}</td>
                        <td>{{ $receipt->customer->phone }}</td>
                        <td>{{ $receipt->customer->branch->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($receipt->date)->format('d-M-Y') }}</td>
                        <td>
                            @foreach($receipt->receiptTherapies as $therapy)
                                {{ $therapy->therapy->name }} ({{ $therapy->original_qty }})<br>
                            @endforeach
                        </td>
                        <td class="text-center">{{ $receipt->receiptTherapies->sum('original_qty') }}</td>
                        <th>
                            @if($receipt->receiptTherapies->every(fn($rt) => $rt->time_in && $rt->time_out))
                                <span class="badge bg-success">All Used</span>
                            @elseif($receipt->receiptTherapies->some(fn($rt) => $rt->time_in || $rt->time_out))
                                <span class="badge bg-warning">Partially Used</span>
                            @else
                                <span class="badge bg-info">Pending</span>
                            @endif
                        </th>
                        <td>
                            @if($receipt->receiptTherapies->every(fn($rt) => $rt->time_in && $rt->time_out))
                            <button class="btn btn-info btn-sm mark-usage-btn"
                                    data-receipt-id="{{ $receipt->id }}"
                                    data-bs-toggle="modal"
                                    data-view-details="true"
                                    data-bs-target="#usageModal">
                                    View Details
                            </button>
                        @else
                            <button class="btn btn-primary btn-sm mark-usage-btn"
                                    data-receipt-id="{{ $receipt->id }}"
                                    data-bs-toggle="modal"
                                    data-view-details="false"
                                    data-bs-target="#usageModal">
                                Mark Usage
                            </button>
                        @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Single Modal for all receipts -->
<div class="modal fade" id="usageModal" tabindex="-1" aria-labelledby="usageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Therapy Usage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBodyContent">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center my-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Loading therapies...</p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    // Handle mark usage button click
    $('.mark-usage-btn').click(function() {
        var receiptId = $(this).data('receipt-id');
        const isViewDetails = $(this).data('view-details') === true;
        loadTherapiesData(receiptId, isViewDetails);
    });

    // Function to load therapies data via AJAX
    function loadTherapiesData(receiptId, isViewDetails) {
        $.ajax({
            url: '/admin/usage/therapies/' + receiptId,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    // Build the modal content
                    var html = `
                        <form action="{{ url('admin/usage/updateTherapyUsage') }}" method="POST">
                            @csrf
                            <input type="hidden" name="receipt_id" value="${receiptId}">

                            <!-- Customer Information -->
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label class="form-label">Customer Name</label>
                                    <input type="text" class="form-control" value="${response.data.customer.name}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Customer Phone</label>
                                    <input type="text" class="form-control" value="${response.data.customer.phone || 'N/A'}" readonly>
                                </div>
                            </div>

                            <!-- Receipt Information -->
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label class="form-label">Receipt Date</label>
                                    <input type="text" class="form-control" value="${new Date(response.data.date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="usage_date" class="form-label">Date of Usage</label>
                                    <input type="date" class="form-control" name="usage_date" value="${new Date().toISOString().split('T')[0]}" required ${isViewDetails ? 'readonly' : ''}>
                                </div>
                            </div>

                            <!-- Therapies List -->
                            <div class="mb-3">
                                <label class="form-label">Therapies</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Therapy Name</th>
                                                <th>Therapist</th>
                                                <th>Quantity</th>
                                                <th>Status</th>
                                                <th>Time In</th>
                                                <th>Time Out</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                    // Add therapies rows
                    response.therapies.forEach(therapy => {
                        html += `
                            <tr>
                                <td>${therapy.name}</td>
                                <td>${therapy.therapist}</td>
                                <td>${therapy.quantity}</td>
                                <td>
                                    <span class="badge ${therapy.status === 'used' ? 'bg-success' : 'bg-info'}">
                                        ${therapy.status === 'used' ? 'Used' : 'Pending'}
                                    </span>
                                </td>
                                <td>`;

                        if (therapy.status === 'used' || isViewDetails) {
                            html += therapy.time_in;
                        } else {
                            html += `<input type="time" class="form-control" name="therapies[${therapy.id}][time_in]" required>`;
                        }

                        html += `</td><td>`;

                        if (therapy.status === 'used' || isViewDetails) {
                            html += therapy.time_out;
                        } else {
                            html += `<input type="time" class="form-control" name="therapies[${therapy.id}][time_out]" required>`;
                        }

                        html += `</td></tr>`;
                    });

                    // Close the form
                    html += `</tbody></table></div></div>`;

                    // Conditionally show buttons based on isViewDetails
                    if (!isViewDetails) {
                        html += `
                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>`;
                    }

                    html += `</form>`;

                    $('#modalBodyContent').html(html);
                }
            },
            error: function(xhr) {
                $('#modalBodyContent').html(`
                    <div class="alert alert-danger">
                        Failed to load therapies data. Please try again.
                    </div>
                    <div class="text-center mb-3">
                        <button class="btn btn-primary" onclick="loadTherapiesData(${receiptId})">Retry</button>
                    </div>
                `);
            }
        });
    }

    // Reload data when modal is shown (in case it was previously failed)
    $('#usageModal').on('show.bs.modal', function() {
        var receiptId = $('.mark-usage-btn.active').data('receipt-id');
        if (receiptId) {
            loadTherapiesData(receiptId);
        }
    });

    // Track active button
    $('.mark-usage-btn').on('click', function() {
        $('.mark-usage-btn').removeClass('active');
        $(this).addClass('active');
    });
});

</script>

@endsection

