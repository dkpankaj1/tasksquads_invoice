<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class GetItemController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $item = Item::find($request->id);

        return view('admin.invoice.get-item-html', [
            'item' => $item,
        ]);
    }
}
