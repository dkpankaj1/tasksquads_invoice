<?php

namespace App\Datatables;

use App\Models\Tax;
use Yajra\DataTables\DataTableAbstract;

class TaxDatatable extends BaseDatatable
{
    public function __construct()
    {
        parent::__construct(Tax::query()->latest());
    }

    public function configure($datatable): DataTableAbstract
    {
        return $datatable
            ->addIndexColumn()
            ->addColumn('active', fn ($data) => $data->active == 1
                ? '<span class="badge text-bg-success px-2">active</span>'
                : '<span class="badge text-bg-danger px-2">in-active</span>')
            ->addColumn('rate', fn ($data) => format_rate($data->rate))

            ->addColumn('created_at', function ($data) {
                return $data->updated_at ? $data->created_at->diffForHumans() : 'N/A';
            })
            ->addColumn('updated_at', function ($data) {
                return $data->updated_at ? $data->updated_at->diffForHumans() : 'N/A';
            })
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex gap-1">';
                $button .= view('admin.action-btn.edit', ['url' => route('tax.edit', $data)]);
                $button .= view('admin.action-btn.delete', ['url' => route('tax.destroy', $data)]);

                return $button .= '</div>';
            })
            ->rawColumns(['active', 'action']);
    }
}
