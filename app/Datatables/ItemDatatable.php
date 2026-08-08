<?php

namespace App\Datatables;

use App\Models\Item;
use Yajra\DataTables\DataTableAbstract;

class ItemDatatable extends BaseDatatable
{
    public function __construct()
    {
        parent::__construct(Item::query());
    }

    public function configure($datatable): DataTableAbstract
    {
        return $datatable
            ->addIndexColumn()
            
            ->addColumn('category', function ($data) {
                return $data->category->name;
            })
            ->addColumn('unit', function ($data) {
                return $data->unit->name;
            })
            ->addColumn('rate', function ($data) {
                return format_money($data->rate);
            })

            ->addColumn('additional_cost', function ($data) {
                return format_money($data->additional_cost);
            })

            ->addColumn('total_amt', function ($data) {
                return format_money($data->rate + $data->additional_cost);
            })

            ->addColumn('created_at', function ($data) {
                return $data->updated_at ? $data->created_at->diffForHumans() : 'N/A';
            })
            ->addColumn('updated_at', function ($data) {
                return $data->updated_at ? $data->updated_at->diffForHumans() : 'N/A';
            })
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex gap-1">';
                $button .= view('admin.action-btn.edit', ['url' => route('item.edit', $data)]);
                $button .= view('admin.action-btn.delete', ['url' => route('item.destroy', $data)]);

                return $button .= '</div>';
            })
            ->rawColumns(['additional_cost', 'total_amt', 'rate', 'action']);
    }
}
