<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - #{{ $receipt->id }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/DejaVuSans.ttf') }}") format('truetype');
        }

        html, body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .invoice-box {
            width: 100%;
            max-width: 550px; /* A5 width */
            margin: auto;
            padding: 20px 20px 30px 20px;
            box-sizing: border-box;
            border: 1px solid #eee;
            page-break-inside: avoid;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .invoice-header h2 {
            margin: 0;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .invoice-total {
            text-align: right;
            font-weight: bold;
            margin-top: 15px;
        }

        .footer-text {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: #666;
        }

        @media print {
            @page {
                size: A5 portrait;
                margin: 0;
            }

            html, body {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .invoice-box {
                border: none;
                box-shadow: none;
                page-break-after: always;
            }

            .invoice-table th,
            .invoice-table td {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="invoice-box">
        <div class="invoice-header">
            <h2>CreativeSpa</h2>
            <p><strong>Receipt No:</strong> #{{ $receipt->id }}</p>
        </div>

        <p><strong>Date:</strong> {{ $receipt->date }}</p>
        <p><strong>Customer:</strong> {{ $receipt->customer->name }} ({{ $receipt->customer->phone }})</p>

        @if($receipt->service_type == 'therapy')
            <h4>Therapy Details</h4>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Therapy Name</th>
                        <th>Employee</th>
                        <th>Price (₹)</th>
                        <th>Qty</th>
                        <th>Total (₹)</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipt->receiptTherapies as $therapy)
                        <tr>
                            <td>{{ $therapy->therapy->name }}</td>
                            <td>{{ $therapy->therapist ? $therapy->therapist->name : '-' }}</td>
                            <td>₹{{ number_format($therapy->price, 2) }}</td>
                            <td>{{ $therapy->original_qty }}</td>
                            <td>₹{{ number_format($therapy->total, 2) }}</td>
                            <td>{{ $therapy->time_in ? \Carbon\Carbon::parse($therapy->time_in)->format('h:i A') : '-' }}</td>
                            <td>{{ $therapy->time_out ? \Carbon\Carbon::parse($therapy->time_out)->format('h:i A') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h4>Package Details</h4>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Package Name</th>
                        <th>Total Price (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $receipt->package ? $receipt->package->name : 'N/A' }}</td>
                        <td>₹{{ number_format($receipt->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <h4>Associated Therapies</h4>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Therapy Name</th>
                        <th>Employee</th>
                        <th>Price (₹)</th>
                        <th>Qty</th>
                        <th>Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipt->receiptPackageTherapies as $packageTherapy)
                        <tr>
                            <td>{{ $packageTherapy->therapy->name }}</td>
                            <td>{{ $packageTherapy->therapist ? $packageTherapy->therapist->name : '-' }}</td>
                            <td>₹{{ number_format($packageTherapy->price, 2) }}</td>
                            <td>{{ $packageTherapy->original_qty }}</td>
                            <td>₹{{ number_format($packageTherapy->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <p class="invoice-total">Total: ₹{{ number_format($receipt->total_amount, 2) }}</p>
        <p><strong>Payment Method:</strong> {{ ucfirst($receipt->payment_method) }}</p>

        <p class="footer-text">Thank you for visiting CreativeSpa! Have a great day.</p>
    </div>

</body>
</html>
