<x-app-layout>
    <x-breadcrumbs :render="Breadcrumbs::render('payment.show', $payment)" />
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Payment Details</h4>
            <p class="card-sub-title">Payment #{{ $payment->payment_number }}</p>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Payment Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Payment Number:</strong></td>
                                    <td>{{ $payment->payment_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td>{!! format_money($payment->amount) !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Date:</strong></td>
                                    <td>{{ format_date($payment->payment_date) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                </tr>
                                @if($payment->reference_number)
                                <tr>
                                    <td><strong>Reference Number:</strong></td>
                                    <td>{{ $payment->reference_number }}</td>
                                </tr>
                                @endif
                                @if($payment->notes)
                                <tr>
                                    <td><strong>Notes:</strong></td>
                                    <td>{{ $payment->notes }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ format_date($payment->created_at) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Invoice Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Invoice Number:</strong></td>
                                    <td>
                                        <a href="{{ route('invoice.show', $payment->invoice) }}">
                                            {{ $payment->invoice->invoice_number }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Customer:</strong></td>
                                    <td>{{ optional($payment->invoice->customer)->full_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Invoice Date:</strong></td>
                                    <td>{{ format_date($payment->invoice->invoice_date) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td>{!! format_money($payment->invoice->total) !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Paid:</strong></td>
                                    <td>{!! format_money($payment->invoice->total_paid) !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Remaining:</strong></td>
                                    <td>{!! format_money($payment->invoice->remaining_amount) !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($payment->invoice->status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($payment->invoice->status === 'partial')
                                            <span class="badge bg-warning">Partial</span>
                                        @else
                                            <span class="badge bg-danger">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <a href="{{ route('payment.edit', $payment) }}" class="btn btn-warning">
                            <i class="mdi mdi-pencil me-1"></i> Edit Payment
                        </a>
                        <a href="{{ route('payment.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Back to Payments
                        </a>
                        <a href="{{ route('invoice.show', $payment->invoice) }}" class="btn btn-primary">
                            <i class="mdi mdi-file-document me-1"></i> View Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
