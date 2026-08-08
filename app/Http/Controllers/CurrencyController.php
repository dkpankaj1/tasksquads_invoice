<?php

namespace App\Http\Controllers;

use App\Datatables\CurrencyDatatable;
use App\Models\Currency;
use App\Support\Toastr;
use App\Support\TryCatchHandler;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CurrencyDatatable $datatable)
    {
        return $request->expectsJson()
            ? $datatable->get()
            : view('admin.currency.index', [
                'ajaxUrl' => route('currency.index'),
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'searchable' => false, 'orderable' => false],
                    ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
                    ['data' => 'code', 'name' => 'code', 'title' => 'Code'],
                    ['data' => 'symbol', 'name' => 'symbol', 'title' => 'Symbol'],
                    ['data' => 'exchange_rate', 'name' => 'exchange_rate', 'title' => 'Exchange Rate'],
                    ['data' => 'major_unit', 'name' => 'major_unit', 'title' => 'Major Unit'],
                    ['data' => 'minor_unit', 'name' => 'minor_unit', 'title' => 'Minor Unit'],
                    ['data' => 'is_base', 'name' => 'is_base', 'title' => 'Base'],
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
        return view('admin.currency.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:10', Rule::unique(Currency::class, 'code')],
            'symbol' => ['required', 'string', 'max:10'],
            'exchange_rate' => ['required', 'numeric', 'min:0.000001'],
            'major_unit' => ['required', 'string', 'max:50'],
            'minor_unit' => ['required', 'string', 'max:50'],
            'status' => ['required', 'in:0,1'],
        ]);

        return TryCatchHandler::execute(function () use ($data) {
            Currency::create([
                'name' => $data['name'],
                'code' => $data['code'],
                'symbol' => $data['symbol'],
                'exchange_rate' => $data['exchange_rate'],
                'major_unit' => $data['major_unit'],
                'minor_unit' => $data['minor_unit'],
                'active' => $data['status'],
            ]);
            Toastr::success(__('messages.success.created', ['item' => 'Currency']));

            return redirect()->route('currency.index');
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        return view('admin.currency.form', ['currency' => $currency]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Currency $currency)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:10', Rule::unique(Currency::class, 'code')->ignore($currency->id)],
            'symbol' => ['required', 'string', 'max:10'],
            'exchange_rate' => ['required', 'numeric', 'min:0.000001'],
            'major_unit' => ['required', 'string', 'max:50'],
            'minor_unit' => ['required', 'string', 'max:50'],
            'status' => ['required', 'in:0,1'],
        ]);

        return TryCatchHandler::execute(function () use ($data, $currency) {

            $currency->update([
                'name' => $data['name'],
                'code' => $data['code'],
                'symbol' => $data['symbol'],
                'exchange_rate' => $data['exchange_rate'],
                'major_unit' => $data['major_unit'],
                'minor_unit' => $data['minor_unit'],
                'active' => $data['status'],
            ]);
            Toastr::success(__('messages.success.updated', ['item' => 'Currency']));

            return redirect()->route('currency.index');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        try {
            $currency->delete();

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
