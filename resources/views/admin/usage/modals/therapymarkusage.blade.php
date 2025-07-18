<div class="modal fade" id="usageModal{{ $receipt->id }}" tabindex="-1" aria-labelledby="usageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Therapy Usage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin/usage/updateTherapyUsage') }}" method="POST">
                    @csrf
                    <input type="hidden" name="receipt_id" value="{{ $receipt->id }}">

                    <!-- Customer Information -->
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" value="{{ $receipt->customer->name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Customer Phone</label>
                            <input type="text" class="form-control" value="{{ $receipt->customer->phone }}" readonly>
                        </div>
                    </div>

                    <!-- Receipt Information -->
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label class="form-label">Receipt Date</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($receipt->date)->format('d-M-Y') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="usage_date" class="form-label">Date of Usage</label>
                            <input type="date" class="form-control" name="usage_date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
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
                                <tbody>
                                    @foreach($receipt->receiptTherapies as $therapy)
                                    <tr>
                                        <td>{{ $therapy->therapy->name }}</td>
                                        <td>{{ $therapy->therapist->name }}</td>
                                        <td>{{ $therapy->original_qty }}</td>
                                        <td>
                                            @if($therapy->time_in && $therapy->time_out)
                                                <span class="badge bg-success">Used</span>
                                            @else
                                                <span class="badge bg-info">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$therapy->time_in)
                                                <input type="time" class="form-control"
                                                       name="therapies[{{ $therapy->id }}][time_in]"
                                                       required>
                                            @else
                                                {{ $therapy->time_in }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$therapy->time_out)
                                                <input type="time" class="form-control"
                                                       name="therapies[{{ $therapy->id }}][time_out]"
                                                       required>
                                            @else
                                                {{ $therapy->time_out }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
