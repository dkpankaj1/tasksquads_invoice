<?php

namespace App\Http\Controllers;

use App\Datatables\ItemDatatable;
use App\Models\Category;
use App\Models\Item;
use App\Models\Unit;
use App\Support\Toastr;
use App\Support\TryCatchHandler;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ItemDatatable $datatable)
    {
        if ($request->expectsJson()) {
            return $datatable->get();
        }

        return view('admin.item.index', [
            'ajaxUrl' => route('item.index'),
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'searchable' => false, 'orderable' => false],
                ['data' => 'hsn_code', 'name' => 'hsn_code', 'title' => 'Hsn Code'],
                ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
                ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
                ['data' => 'unit', 'name' => 'unit', 'title' => 'Unit'],
                ['data' => 'rate', 'name' => 'rate', 'title' => 'Rate'],
                ['data' => 'additional_cost', 'name' => 'additional_cost', 'title' => 'Additional Cost'],
                ['data' => 'total_amt', 'name' => 'total_amt', 'title' => 'Total Amount'],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created AT'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.item.form', [
            'categories' => Category::where('active', 1)->get(),
            'units' => Unit::where('active', 1)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'hsn_code' => ['required', 'string'],
            'name' => ['required', 'string', Rule::unique(Item::class, 'name')],
            'category' => ['required', 'exists:categories,id'],
            'unit' => ['required', 'exists:units,id'],
            'rate' => ['required', 'numeric', 'min:0'],
            'additional_cost' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:1,0'],
        ]);

        return TryCatchHandler::execute(function () use ($data) {
            Item::create([
                'hsn_code' => $data['hsn_code'],
                'name' => $data['name'],
                'rate' => $data['rate'],
                'additional_cost' => $data['additional_cost'],
                'category_id' => $data['category'],
                'unit_id' => $data['unit'],
                'description' => $data['description'],
                'status' => $data['status'],
            ]);

            Toastr::success(__('messages.success.created', ['item' => 'Item']));

            return redirect()->route('item.index');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return view('admin.item.form', [
            'categories' => Category::where('active', 1)->get(),
            'units' => Unit::where('active', 1)->get(),
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'hsn_code' => ['required', 'string'],
            'name' => ['required', 'string', Rule::unique(Item::class, 'name')->ignore($item->id)],
            'category' => ['required', 'exists:categories,id'],
            'unit' => ['required', 'exists:units,id'],
            'rate' => ['required', 'numeric', 'min:0'],
            'additional_cost' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:1,0'],
        ]);

        return TryCatchHandler::execute(function () use ($data, $item) {
            $item->update([
                'hsn_code' => $data['hsn_code'],
                'name' => $data['name'],
                'rate' => $data['rate'],
                'additional_cost' => $data['additional_cost'],
                'category_id' => $data['category'],
                'unit_id' => $data['unit'],
                'description' => $data['description'],
                'status' => $data['status'],
            ]);

            Toastr::success(__('messages.success.updated', ['item' => 'Item']));

            return redirect()->route('item.index');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        try {
            $item->delete();

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
