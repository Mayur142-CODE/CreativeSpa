@extends('layouts.admin')

@section('content')
<div class="card">
    @if(auth()->user()->role_id != 0)

    <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addReceiptModal">
        Create Receipt
    </button>


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Table -->
    <div class="card-datatable text-nowrap table-responsive">
        <table class="dt-column-search table table-bordered">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Branch</th>
                    <th>Phone</th>
                    <th>Service Type</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($receipts as $receipt)
                    <tr>
                        <td>{{ $receipt->customer->name }}</td>
                        <td>{{ $receipt->customer->branch->name }}</td>
                        <td>{{ $receipt->customer->phone }}</td>
                        <td>{{ ucfirst($receipt->service_type) }}</td>
                        <td>
                            @if($receipt->service_type == 'therapy' && $receipt->receiptTherapies->isNotEmpty())
                                @foreach($receipt->receiptTherapies as $therapy)
                                    @if($therapy->time_in)
                                        {{ \Carbon\Carbon::parse($therapy->time_in)->format('h:i A') }}
                                    @else
                                        -
                                    @endif
                                    <br>
                                @endforeach
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($receipt->service_type == 'therapy' && $receipt->receiptTherapies->isNotEmpty())
                                @foreach($receipt->receiptTherapies as $therapy)
                                    @if($therapy->time_out)
                                        {{ \Carbon\Carbon::parse($therapy->time_out)->format('h:i A') }}
                                    @else
                                        -
                                    @endif
                                    <br>
                                @endforeach
                            @else
                                N/A
                            @endif
                        </td>

                        <td>{{ $receipt->date }}</td>

                        <td>₹{{ number_format($receipt->total_amount, 2) }}</td>
                        <td>{{ ucfirst($receipt->payment_method) }}</td>
                        <td>
                            {{-- <a href="{{ url('admin/receipts/show/'. $receipt->id) }}" target="_blank" class="btn btn-sm btn-info">View</a> --}}
                            <button class="btn btn-sm btn-primary print-receipt-btn" data-url="{{ url('admin/receipts/show/'. $receipt->id) }}">Print</button>
                            {{-- <button class="btn btn-warning btn-sm edit-receipt-btn" data-id="{{ $receipt->id }}" data-bs-toggle="modal" data-bs-target="#editReceiptModal">Edit</button> --}}
                            {{-- <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteReceiptModal" data-id="{{ $receipt->id }}">
                                Delete
                            </button> --}}
                            {{-- <a href="{{ url('admin/receipts/'. $receipt->id.'/download') }}" class="btn btn-sm btn-secondary">Download</a> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @else

    <h5 class="card-header">
        Receipt Management
        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addReceiptModal">
            Create Receipt
        </button>
    </h5>


    <!-- Display Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Table -->
    <div class="card-datatable text-nowrap table-responsive">
        <table class="dt-column-search table table-bordered">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Branch</th>
                    <th>Phone</th>
                    <th>Service Type</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($receipts as $receipt)
                    <tr>
                        <td>{{ $receipt->customer->name }}</td>
                        <td>{{ $receipt->customer->branch->name }}</td>
                        <td>{{ $receipt->customer->phone }}</td>
                        <td>{{ ucfirst($receipt->service_type) }}</td>
                        <td>
                            @if($receipt->service_type == 'therapy' && $receipt->receiptTherapies->isNotEmpty())
                                @foreach($receipt->receiptTherapies as $therapy)
                                    @if($therapy->time_in)
                                        {{ \Carbon\Carbon::parse($therapy->time_in)->format('h:i A') }}
                                    @else
                                        -
                                    @endif
                                    <br>
                                @endforeach
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($receipt->service_type == 'therapy' && $receipt->receiptTherapies->isNotEmpty())
                                @foreach($receipt->receiptTherapies as $therapy)
                                    @if($therapy->time_out)
                                        {{ \Carbon\Carbon::parse($therapy->time_out)->format('h:i A') }}
                                    @else
                                        -
                                    @endif
                                    <br>
                                @endforeach
                            @else
                                N/A
                            @endif
                        </td>

                        <td>{{ $receipt->date }}</td>

                        <td>₹{{ number_format($receipt->total_amount, 2) }}</td>
                        <td>{{ ucfirst($receipt->payment_method) }}</td>
                        <td>
                            <a href="{{ url('admin/receipts/show/'. $receipt->id) }}" target="_blank" class="btn btn-sm btn-info">View</a>
                            <button class="btn btn-sm btn-primary print-receipt-btn" data-url="{{ url('admin/receipts/show/'. $receipt->id) }}">Print</button>
                            <button class="btn btn-warning btn-sm edit-receipt-btn" data-id="{{ $receipt->id }}" data-bs-toggle="modal" data-bs-target="#editReceiptModal">Edit</button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteReceiptModal" data-id="{{ $receipt->id }}">
                                Delete
                            </button>
                            <a href="{{ url('admin/receipts/'. $receipt->id.'/download') }}" class="btn btn-sm btn-secondary">Download</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @endif


</div>
<script>
    $(document).ready(function() {
        $('.print-receipt-btn').on('click', function() {
            const printUrl = $(this).data('url');
            console.log(printUrl);
            const printWindow = window.open(printUrl, '_blank');
            printWindow.focus();
            $(printWindow).on('load', function() {
                printWindow.print();
            });
        });
    });
</script>

<!-- Modals -->
@include("admin.receipts.modals.create")
@include("admin.receipts.modals.edit")
@include("admin.receipts.modals.delete")

@endsection
