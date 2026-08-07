<?php

namespace App\Datatables;

use App\Models\Unit;
use Yajra\DataTables\DataTableAbstract;

class UnitDatatable extends BaseDatatable
{
    public function __construct()
    {
        parent::__construct(Unit::query());
    }

    public function configure($datatable): DataTableAbstract
    {
        return $datatable
            ->addIndexColumn()
            ->addColumn('active', fn ($data) => $data->active == 1
                ? '<span class="badge text-bg-success">active</span>'
                : '<span class="badge text-bg-danger">in-active</span>')

            ->addColumn('created_at', function ($data) {
                return $data->updated_at ? $data->created_at->diffForHumans() : 'N/A';
            })
            ->addColumn('updated_at', function ($data) {
                return $data->updated_at ? $data->updated_at->diffForHumans() : 'N/A';
            })
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex gap-1">';
                $button .= view('admin.action-btn.edit', ['url' => route('unit.edit', $data)]);
                $button .= view('admin.action-btn.delete', ['url' => route('unit.destroy', $data)]);

                return $button .= '</div>';
            })
            ->rawColumns(['active', 'action']);
    }
}
