<?php

namespace App\Datatables;

use App\Models\Invoice;
use Yajra\DataTables\DataTableAbstract;

class InvoiceDatatable extends BaseDatatable
{
    public function __construct()
    {
        parent::__construct(Invoice::query()->latest());
    }

    public function configure($datatable): DataTableAbstract
    {
        $status = [
            Invoice::STATUS_UNPAID => 'badge bg-danger',
            Invoice::STATUS_PAID => 'badge bg-success',
            Invoice::STATUS_PARTIAL => 'badge bg-warning',
        ];

        return $datatable
            ->addIndexColumn()
            ->addColumn('customer', function ($data) {
                return $data->customer->full_name;
            })
            ->addColumn('invoice_date', function ($data) {
                return format_date($data->invoice_date);
            })
            ->addColumn('due_date', function ($data) {
                return format_date($data->due_date);
            })
            ->addColumn('additional_cost', function ($data) {
                return format_money(amount: $data->additional_cost);
            })
            ->addColumn('discount', function ($data) {
                return format_money($data->discount);
            })

            ->addColumn('sub_total', function ($data) {
                return format_money($data->subtotal);
            })

            ->addColumn('total', function ($data) {
                return format_money($data->total);
            })

            ->addColumn('total_paid', function ($data) {
                return format_money($data->total_paid);
            })

            ->addColumn('status', fn ($data) => '<span class="badge p-1 text-bg-'.$status[$data->status].'">'.$data->status.'</span>')

            ->addColumn('created_at', function ($data) {
                return $data->updated_at ? $data->created_at->diffForHumans() : 'N/A';
            })
            ->addColumn('updated_at', function ($data) {
                return $data->updated_at ? $data->updated_at->diffForHumans() : 'N/A';
            })
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex gap-1">';
                $button .= view('admin.action-btn.show', ['url' => route('invoice.show', $data)]);
                $button .= view('admin.action-btn.edit', ['url' => route('invoice.edit', $data)]);
                $button .= view('admin.action-btn.delete', ['url' => route('invoice.destroy', $data)]);

                return $button .= '</div>';
            })

            ->addColumn('more', function ($data) {
                $button = '<div class="d-flex gap-1">';
                $button .= view('admin.action-btn.download', ['url' => route('invoice.pdf', $data)]);

                return $button .= '</div>';
            })
            ->rawColumns(['additional_cost', 'discount', 'sub_total', 'total', 'action', 'more', 'total_paid', 'status']);
    }
}
