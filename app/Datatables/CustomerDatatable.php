<?php

namespace App\Datatables;

use App\Models\Customer;
use Yajra\DataTables\DataTableAbstract;

class CustomerDatatable extends BaseDatatable
{
    public function __construct()
    {
        parent::__construct(Customer::query()->latest());
    }

    public function configure($datatable): DataTableAbstract
    {
        return $datatable
            ->addIndexColumn()
            ->addColumn('fullname', function ($data) {
                return $data->full_name;
            })
            ->addColumn('balance', function ($data) {
                return format_money($data->balance);
            })
            ->addColumn('status', fn ($data) => $data->active == 1
                ? '<span class="badge text-bg-success px-2">active</span>'
                : '<span class="badge text-bg-danger px-2">in-active</span>')

            ->addColumn('created_at', function ($data) {
                return $data->updated_at ? $data->created_at->diffForHumans() : 'N/A';
            })
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex gap-1">';
                $button .= view('admin.action-btn.show', ['url' => route('customer.show', $data)]);
                $button .= view('admin.action-btn.edit', ['url' => route('customer.edit', $data)]);
                $button .= view('admin.action-btn.delete', ['url' => route('customer.destroy', $data)]);

                return $button .= '</div>';
            })
            ->rawColumns(['balance', 'status', 'action']);
    }
}
