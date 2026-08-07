<?php

namespace App\Http\Controllers;

use App\Datatables\FinanceYearDatatable;
use App\Models\FinanceYear;
use Illuminate\Http\Request;

class FinanceYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, FinanceYearDatatable $datatable)
    {

        return $request->expectsJson()
            ? $datatable->get()
            : view('admin.finance-year.index', [
                'ajaxUrl' => route('finance-year.index'),
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'searchable' => false, 'orderable' => false],
                    ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
                    ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created AT'],
                    ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated AT'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false],
                ],
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FinanceYear $financeYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinanceYear $financeYear)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinanceYear $financeYear)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinanceYear $financeYear)
    {
        //
    }
}
