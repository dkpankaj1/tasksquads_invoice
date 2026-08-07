<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class SearchItemController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $search = $request->search ?? '';
        $itemQuery = Item::query()->where('status', 1);

        $items = $itemQuery->where('name', 'like', "%$search%")
            ->orWhere('hsn_code', 'like', "%$search%")
            ->get();

        return view('admin.invoice.search-result-html', [
            'items' => $items,
        ]);
    }
}
