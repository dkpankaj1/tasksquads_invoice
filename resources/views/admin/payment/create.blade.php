<x-app-layout>    
    <x-breadcrumbs :render="Breadcrumbs::render('payment.create')" />

    <form action="{{ route('payment.store') }}" method="post">
        @csrf

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add Payment</h4>
                <p class="card-sub-title">Record a payment for an invoice</p>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <x-input-label name="payment_number" text="Payment Number" />
                                <x-input-field name="payment_number" value="{{ $paymentNumber }}" readonly />
                            </div>

                            <div class="col-md-4 mb-3">
                                <x-input-label name="payment_date" text="Payment Date" />
                                <x-input-field name="payment_date" type="date"
                                    value="{{ old('payment_date', $todayDate) }}" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <x-input-label name="invoice_id" text="Invoice" />
                                <x-select :options="$invoices" name="invoice_id" id="invoice_select" label="Select Invoice"
                                    value="{{ old('invoice_id', $selectedInvoice?->id) }}" />
                            </div>

                            @if ($selectedInvoice)
                                <div class="col-md-12 mb-3">
                                    <div class="alert alert-info">
                                        <h6><strong>Invoice Details:</strong></h6>
                                        <p><strong>Invoice:</strong> {{ $selectedInvoice->invoice_number }}</p>
                                        <p><strong>Customer:</strong>
                                            {{ optional($selectedInvoice->customer)->full_name }}</p>
                                        <p><strong>Total Amount:</strong> {!! format_money($selectedInvoice->total) !!}</p>
                                        <p><strong>Paid Amount:</strong> {!! format_money($selectedInvoice->total_paid) !!}</p>
                                        <p><strong>Remaining:</strong> {!! format_money($selectedInvoice->remaining_amount) !!}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-6 mb-3">
                                <x-input-label name="amount" text="Payment Amount" />
                                <x-input-field name="amount" type="number" step="0.01" min="0.01"
                                    value="{{ old('amount') }}" placeholder="0.00" />
                                @if ($selectedInvoice)
                                    <small class="text-muted">Maximum:
                                        {{ number_format($selectedInvoice->remaining_amount, 2) }}</small>
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-input-label name="payment_method" text="Payment Method" />
                                <x-input-select name="payment_method" label="Select Payment Method">
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method['id'] }}"
                                            :selected="old('payment_method', 'cash') === $method['id']">
                                            {{ $method['label'] }}
                                        </option>
                                    @endforeach
                                </x-input-select>

                            </div>

                            <div class="col-md-12 mb-3">
                                <x-input-label name="reference_number" text="Reference Number" />
                                <x-input-field name="reference_number" value="{{ old('reference_number') }}"
                                    placeholder="Transaction ID, Check number, etc." />
                            </div>

                            <div class="col-md-12 mb-3">
                                <x-input-label name="notes" text="Notes" />
                                <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about this payment">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <x-save-btn />
                <a href="{{ route('payment.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>

    @push('pageScript')
        <script>
            $(document).ready(function() {
                $('#invoice_select').on('change', function() {
                    const invoiceId = $(this).val();
                    if (invoiceId) {
                        window.location.href = '{{ route('payment.create') }}?invoice_id=' + invoiceId;
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
