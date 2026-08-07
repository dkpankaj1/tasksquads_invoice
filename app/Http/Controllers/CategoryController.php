<?php

namespace App\Http\Controllers;

use App\Datatables\CategoryDatatable;
use App\Models\Category;
use App\Support\Toastr;
use App\Support\TryCatchHandler;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CategoryDatatable $datatable)
    {

        return $request->expectsJson()
            ? $datatable->get()
            : view('admin.category.index', [
                'ajaxUrl' => route('category.index'),
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'searchable' => false, 'orderable' => false],
                    ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
                    ['data' => 'name', 'short_name' => 'short_name', 'title' => 'Short Name'],
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
        return view('admin.category.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', Rule::unique(Category::class, 'name')],
            'short_name' => ['required', 'string', Rule::unique(Category::class, 'short_name')],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);

        return TryCatchHandler::execute(function () use ($data) {
            Category::create([
                'name' => $data['name'],
                'short_name' => $data['short_name'],
                'description' => $data['description'],
                'active' => $data['status'],
            ]);
            Toastr::success(__('messages.success.created', ['item' => 'Unit']));

            return redirect()->route('category.index');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.category.form', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', Rule::unique(Category::class, 'name')->ignore($category->id)],
            'short_name' => ['required', 'string', Rule::unique(Category::class, 'short_name')->ignore($category->id)],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);

        return TryCatchHandler::execute(function () use ($data, $category) {
            $category->update([
                'name' => $data['name'],
                'short_name' => $data['short_name'],
                'description' => $data['description'],
                'active' => $data['status'],

            ]);
            Toastr::success(__('messages.success.updated', ['item' => 'Tax type']));

            return redirect()->route('category.index');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

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
