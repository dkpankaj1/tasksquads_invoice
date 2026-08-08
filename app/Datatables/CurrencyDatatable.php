<?php

namespace App\Datatables;

use App\Models\Currency;
use Yajra\DataTables\DataTableAbstract;

class CurrencyDatatable extends BaseDatatable
{
    public function __construct()
    {
        parent::__construct(Currency::query()->latest());
    }

    public function configure($datatable): DataTableAbstract
    {
        return $datatable
            ->addIndexColumn()
            ->addColumn('exchange_rate', fn ($data) => number_format($data->exchange_rate, 6))
            ->addColumn('major_unit', fn ($data) => $data->major_unit ?: '-')
            ->addColumn('minor_unit', fn ($data) => $data->minor_unit ?: '-')
            ->addColumn('is_base', fn ($data) => $data->is_base
                ? '<span class="badge text-bg-warning px-2"><i class="fas fa-star"></i> base</span>'
                : '<span class="badge text-bg-light px-2">-</span>')
            ->addColumn('active', fn ($data) => $data->active == 1
                ? '<span class="badge text-bg-success px-2">active</span>'
                : '<span class="badge text-bg-danger px-2">in-active</span>')

            ->addColumn('created_at', function ($data) {
                return $data->created_at ? $data->created_at->diffForHumans() : 'N/A';
            })
            ->addColumn('updated_at', function ($data) {
                return $data->updated_at ? $data->updated_at->diffForHumans() : 'N/A';
            })
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex gap-1">';
                $button .= view('admin.action-btn.edit', ['url' => route('currency.edit', $data)]);
                $button .= view('admin.action-btn.delete', ['url' => route('currency.destroy', $data)]);

                return $button .= '</div>';
            })
            ->rawColumns(['is_base', 'active', 'action']);
    }
}
