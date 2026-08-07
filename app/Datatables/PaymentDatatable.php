<?php

namespace App\Datatables;

use App\Models\Payment;

class PaymentDatatable extends BaseDatatable
{
    public function __construct()
    {
        $query = Payment::with(['invoice.customer'])
            ->select('payments.*');
        parent::__construct($query);
    }

    protected function configure($dataTable)
    {
        return $dataTable
            ->addIndexColumn()
            ->addColumn('invoice_number', function ($payment) {
                return optional($payment->invoice)->invoice_number ?? 'N/A';
            })
            ->addColumn('customer', function ($payment) {
                return optional(optional($payment->invoice)->customer)->full_name ?? 'N/A';
            })
            ->addColumn('amount', function ($payment) {
                return $payment->formatted_amount;
            })
            ->addColumn('payment_method', function ($payment) {
                return $payment->payment_method_label;
            })
            ->addColumn('payment_date', function ($payment) {
                return $payment->formatted_date;
            })
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex gap-1">';
                $button .= view('admin.action-btn.show', ['url' => route('payment.show', $data)]);
                $button .= view('admin.action-btn.edit', ['url' => route('payment.edit', $data)]);
                $button .= view('admin.action-btn.delete', ['url' => route('payment.destroy', $data)]);

                return $button .= '</div>';
            })
            ->rawColumns(['action', 'amount']);
    }
}
