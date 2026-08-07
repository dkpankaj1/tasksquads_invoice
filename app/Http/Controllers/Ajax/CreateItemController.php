<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CreateItemController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'item_hsn_code' => ['required', 'string', 'max:20'],
                'item_name' => ['required', 'string', 'max:255', Rule::unique(Item::class, 'name')],
                'item_category' => ['required', 'exists:categories,id'],
                'item_unit' => ['required', 'exists:units,id'],
                'item_rate' => ['required', 'numeric', 'min:0'],
                'item_additional_cost' => ['required', 'numeric', 'min:0'],
                'item_description' => ['nullable', 'string', 'max:1000'],
            ]);

            $item = Item::create([
                'hsn_code' => $validatedData['item_hsn_code'],
                'name' => $validatedData['item_name'],
                'rate' => $validatedData['item_rate'],
                'additional_cost' => $validatedData['item_additional_cost'],
                'category_id' => $validatedData['item_category'],
                'unit_id' => $validatedData['item_unit'],
                'description' => $validatedData['item_description'],
                'status' => 1,
            ]);

            return view('admin.invoice.get-item-html', [
                'item' => $item,
            ]);

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Item created successfully',
            //     'data' => $item
            // ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the item',
            ], 500);
        }
    }
}
