<x-app-layout>

    <x-breadcrumbs :render="Breadcrumbs::render('invoice.show', $invoice)" />

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Invoice Details</h4>
            <p class="card-sub-title">View invoice information</p>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Invoice Information</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-bold">Invoice Number:</td>
                            <td>{{ $invoice->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Invoice Date:</td>
                            <td>{{ format_date($invoice->invoice_date) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Due Date:</td>
                            <td>{{ format_date($invoice->due_date) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status:</td>
                            <td>
                                <span
                                    class="badge 
                                    @if ($invoice->status === 'paid') bg-success
                                    @elseif($invoice->status === 'partial') bg-warning
                                    @else bg-danger @endif">
                                    {{ text_capitalize($invoice->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Customer Information</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-bold">Name:</td>
                            <td>{{ $invoice->customer?->full_name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email:</td>
                            <td>{{ $invoice->customer?->email }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Phone:</td>
                            <td>{{ $invoice->customer?->phone }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Address:</td>
                            <td>{{ $invoice->customer?->address }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Invoice Items</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="bg-secondary">
                        <tr>
                            <th class="text-light">Item</th>
                            <th class="text-light">HSN Code</th>
                            <th class="text-light">Unit</th>
                            <th class="text-light text-end">Quantity</th>
                            <th class="text-light text-end">Rate</th>
                            <th class="text-light text-end">Add. Cost</th>
                            <th class="text-light text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->invoiceItems as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->item?->name }}</strong>
                                    @if ($item->description)
                                        <br><small class="text-muted">{{ $item->description }}</small>
                                    @endif
                                </td>
                                <td>{{ $item->item?->hsn_code }}</td>
                                <td>{{ $item->unit->name }}</td>
                                <td class="text-end">{{ number_format((float) $item->quantity, 2) }}
                                    {{ $item->unit->short_name }}</td>
                                <td class="text-end">{!! format_money((float) $item->rate) !!}</td>
                                <td class="text-end">{!! format_money((float) $item->additional_cost) !!}</td>
                                <td class="text-end">{!! format_money((float) $item->amount) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title">Notes</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $invoice->notes ?? 'No notes available' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0 ">
                        <i class="mdi mdi-calculator me-2"></i>Invoice Summary
                    </h6>
                </div>
                <div class="card-body bg-light">
                    <table class="table table-sm mb-0">
                        @if ($invoice->taxes && $invoice->taxes->count())
                            @foreach ($invoice->taxes as $tax)
                                <tr class="bg-info bg-opacity-10">
                                    <td class="text-end border-0">{{ $tax->name }} ({{ $tax->rate }}%):</td>
                                    <td class="text-end fw-bold border-0 text-info">{!! format_money((float) $tax->amount) !!}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr class="bg-secondary bg-opacity-25">
                            <td class="text-end border-0 fw-semibold">Sub Total:</td>
                            <td class="text-end fw-bold border-0">{!! format_money((float) $invoice->subtotal) !!}</td>
                        </tr>
                        <tr class="bg-warning bg-opacity-25">
                            <td class="text-end border-0">Additional Cost:</td>
                            <td class="text-end fw-bold border-0 text-warning">{!! format_money((float) $invoice->additional_cost) !!}</td>
                        </tr>
                        <tr class="bg-danger bg-opacity-25">
                            <td class="text-end border-0">Discount( {!! $invoice->discount_type == 'percentage'
                                ? $invoice->discount . '%'
                                : format_money((float) $invoice->discount) !!}):</td>
                            <td class="text-end fw-bold border-0 text-danger">
                                {!! $invoice->discount_type == 'percentage'
                                    ? format_money((float) ($invoice->subtotal * $invoice->discount / 100))
                                    : format_money((float) $invoice->discount) !!}</td>
                        </tr>
                        <tr class="bg-success text-white">
                            <td class="text-end border-0">Total:</td>
                            <td class="text-end fw-bold border-0 text-dark">{!! format_money((float) $invoice->total) !!}</td>
                        </tr>
                        <tr class="bg-primary bg-opacity-25">
                            <td class="text-end border-0">Paid Amount:</td>
                            <td class="text-end fw-bold border-0 text-success">{!! format_money((float) $invoice->total_paid) !!}</td>
                        </tr>
                        <tr class="bg-danger text-white">
                            <td class="text-end border-0 fw-semibold">Due Amount:</td>
                            <td class="text-end fw-bold border-0">{!! format_money((float) ($invoice->total - $invoice->total_paid)) !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if ($invoice->payments && $invoice->payments->count())
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Payment History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Payment #</th>
                                <th>Date</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th class="text-end">Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_number }}</td>
                                    <td>{{ format_date($payment->payment_date) }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td>{{ $payment->reference_number ?? '-' }}</td>
                                    <td class="text-end">{!! format_money($payment->amount) !!}</td>
                                    <td>
                                        <a href="{{ route('payment.show', $payment) }}" class="btn btn-sm btn-primary">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body text-center">
            <a href="{{ route('invoice.index') }}" class="btn btn-secondary me-2">
                <i class="mdi mdi-arrow-left me-1"></i> Back to List
            </a>
            <a href="{{ route('invoice.edit', $invoice) }}" class="btn btn-primary me-2">
                <i class="mdi mdi-pencil me-1"></i> Edit Invoice
            </a>
            <a href="{{ route('invoice.pdf', $invoice) }}" class="btn btn-info me-2" target="_blank">
                <i class="mdi mdi-file-pdf me-1"></i> Download PDF
            </a>
            @if ($invoice->status !== 'paid')
                <a href="{{ route('payment.create', ['invoice_id' => $invoice->id]) }}" class="btn btn-success me-2">
                    <i class="mdi mdi-cash-multiple me-1"></i> Add Payment
                </a>
            @endif
            <a href="{{ route('payment.index') }}" class="btn btn-outline-primary">
                <i class="mdi mdi-format-list-bulleted me-1"></i> All Payments
            </a>

        </div>
    </div>

</x-app-layout>
