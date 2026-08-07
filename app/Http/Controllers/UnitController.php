<?php

namespace App\Http\Controllers;

use App\Datatables\UnitDatatable;
use App\Models\Unit;
use App\Support\Toastr;
use App\Support\TryCatchHandler;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UnitDatatable $datatable)
    {
        return $request->expectsJson()
            ? $datatable->get()
            : view('admin.unit.index', [
                'ajaxUrl' => route('unit.index'),
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'searchable' => false, 'orderable' => false],
                    ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
                    ['data' => 'short_name', 'name' => 'short_name', 'title' => 'Short Name'],
                    ['data' => 'active', 'name' => 'active', 'title' => 'Status'],
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
        return view('admin.unit.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', Rule::unique(Unit::class, 'name')],
            'short_name' => ['required', 'string', Rule::unique(Unit::class, 'short_name')],
            'status' => ['required', 'in:0,1'],
        ]);

        return TryCatchHandler::execute(function () use ($data) {
            Unit::create([
                'name' => $data['name'],
                'short_name' => $data['short_name'],
                'active' => $data['status'],
            ]);
            Toastr::success(__('messages.success.created', ['item' => 'Unit']));

            return redirect()->route('unit.index');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        return view('admin.unit.form', ['unit' => $unit]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'name' => ['required', 'string', Rule::unique(Unit::class, 'name')->ignore($unit->id)],
            'short_name' => ['required', 'string', Rule::unique(Unit::class, 'short_name')->ignore($unit->id)],
            'status' => ['required', 'in:0,1'],
        ]);

        return TryCatchHandler::execute(function () use ($data, $unit) {
            $unit->update([
                'name' => $data['name'],
                'short_name' => $data['short_name'],
                'active' => $data['status'],

            ]);
            Toastr::success(__('messages.success.updated', ['item' => 'Tax type']));

            return redirect()->route('unit.index');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        try {
            $unit->delete();

            return response()->json([
                'message' => __('messages.success.default'),
                'status' => 'success',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => __('messages.error.default'),
                'status' => 'error',
            ]);
        }
    }
}
