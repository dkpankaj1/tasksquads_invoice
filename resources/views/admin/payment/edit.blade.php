<x-app-layout>
    <x-breadcrumbs :render="Breadcrumbs::render('payment.edit', $payment)" />

    <form action="{{ route('payment.update', $payment) }}" method="post">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Payment</h4>
                <p class="card-sub-title">Update payment #{{ $payment->payment_number }}</p>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-input-label name="payment_number" text="Payment Number" />
                                <x-input-field name="payment_number" value="{{ $payment->payment_number }}" readonly />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-input-label name="payment_date" text="Payment Date" />
                                <x-input-field name="payment_date" type="date"
                                    value="{{ old('payment_date', $payment->payment_date?->format('Y-m-d')) }}" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <span>Payment Amount</span>
                                    (<span class="text-primary">Maximum:
                                        {!! format_money($payment->invoice->remaining_amount, 2) !!}
                                    </span>)
                                </label>
                                <x-input-field name="amount" type="number" step="0.01" min="0.01"
                                    value="{{ old('amount', $payment->amount) }}" placeholder="0.00" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-input-label name="payment_method" text="Payment Method" />
                                <x-input-select name="payment_method" label="Select Payment Method">
                                    @foreach (\App\Models\Payment::getPaymentMethods() as $method)
                                        <option value="{{ $method['id'] }}"
                                            {{ old('payment_method', $payment->payment_method) === $method['id'] ? 'selected' : '' }}>
                                            {{ $method['label'] }}
                                        </option>
                                    @endforeach
                                </x-input-select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <x-input-label name="reference_number" text="Reference Number" />
                                <x-input-field name="reference_number"
                                    value="{{ old('reference_number', $payment->reference_number) }}"
                                    placeholder="Transaction ID, Check number, etc." />
                            </div>

                            <div class="col-md-12 mb-3">
                                <x-input-label name="notes" text="Notes" />
                                <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about this payment">{{ old('notes', $payment->notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-info">
                            <h4><strong>Invoice Details:</strong></h4>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td><strong>Invoice:</strong></td>
                                            <td>{{ $payment->invoice->invoice_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Customer:</strong></td>
                                            <td>{{ optional($payment->invoice->customer)->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Amount:</strong></td>
                                            <td>{!! format_money($payment->invoice->total) !!}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Current Paid Amount:</strong></td>
                                            <td>{!! format_money($payment->invoice->total_paid) !!}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Remaining (excluding this payment):</strong></td>
                                            <td>{!! format_money($payment->invoice->total - ($payment->invoice->total_paid - $payment->amount)) !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <x-save-btn />
                <a href="{{ route('payment.show', $payment) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</x-app-layout>
