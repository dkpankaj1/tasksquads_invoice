<?php

namespace App\Datatables;

use App\Models\FinanceYear;
use Yajra\DataTables\DataTableAbstract;

class FinanceYearDatatable extends BaseDatatable
{
    public function __construct()
    {
        parent::__construct(FinanceYear::query());
    }

    public function configure($datatable): DataTableAbstract
    {
        return $datatable
            ->addIndexColumn()
            ->addColumn('created_at', function ($data) {
                return $data->updated_at ? $data->created_at->diffForHumans() : 'N/A';
            })
            ->addColumn('updated_at', function ($data) {
                return $data->updated_at ? $data->updated_at->diffForHumans() : 'N/A';
            })
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex gap-1">';
                $button .= view('admin.action-btn.show', ['url' => route('finance-year.show', $data)]);
                $button .= view('admin.action-btn.edit', ['url' => route('finance-year.edit', $data)]);
                $button .= view('admin.action-btn.delete', ['url' => route('finance-year.destroy', $data)]);

                return $button .= '</div>';
            })
            ->rawColumns(['action']);
    }
}
