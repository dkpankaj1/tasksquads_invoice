<?php

namespace App\Http\Controllers;

use App\Datatables\TaxDatatable;
use App\Models\Tax;
use App\Support\Toastr;
use App\Support\TryCatchHandler;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TaxDatatable $datatable)
    {
        return $request->expectsJson()
            ? $datatable->get()
            : view('admin.tax.index', [
                'ajaxUrl' => route('tax.index'),
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'searchable' => false, 'orderable' => false],
                    ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
                    ['data' => 'rate', 'name' => 'rate', 'title' => 'Rate'],
                    ['data' => 'treatment', 'name' => 'treatment', 'title' => 'Treatment'],
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
        return view('admin.tax.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', Rule::unique(Tax::class, 'name')],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'treatment' => ['required', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);

        return TryCatchHandler::execute(function () use ($data) {
            Tax::create([
                'name' => $data['name'],
                'rate' => $data['rate'],
                'treatment' => $data['treatment'],
                'active' => $data['status'],
            ]);
            Toastr::success(__('messages.success.created', ['item' => 'Tax type']));

            return redirect()->route('tax.index');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Tax $tax)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tax $tax)
    {
        return view('admin.tax.form', ['tax' => $tax]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tax $tax)
    {
        $data = $request->validate([
            'name' => ['required', 'string', Rule::unique(Tax::class, 'name')->ignore($tax->id)],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'treatment' => ['required', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);

        return TryCatchHandler::execute(function () use ($data, $tax) {
            $tax->update([
                'name' => $data['name'],
                'rate' => $data['rate'],
                'treatment' => $data['treatment'],
                'active' => $data['status'],

            ]);
            Toastr::success(__('messages.success.updated', ['item' => 'Tax type']));

            return redirect()->route('tax.index');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax)
    {
        try {
            $tax->delete();

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
