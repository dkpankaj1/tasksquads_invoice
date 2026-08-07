<?php

namespace App\Http\Controllers;

use App\Models\Customization;
use App\Support\Toastr;
use App\Support\TryCatchHandler;
use Illuminate\Http\Request;

class CustomizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customizations = Customization::all();

        return view('admin.customization.index', ['customizations' => $customizations]);
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
    public function show(Customization $customization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customization $customization)
    {
        return view('admin.customization.form', ['customization' => $customization]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customization $customization)
    {
        $data = $request->validate([
            'series' => ['required', 'string'],
            'sequence' => ['required', 'numeric', 'min:0', 'max:100'],
            'delimiter' => ['required', 'string'],
            'note' => ['nullable', 'string'],
        ]);

        return TryCatchHandler::execute(function () use ($data, $customization) {
            $customization->update([
                'series' => $data['series'],
                'delimiter' => $data['delimiter'],
                'sequence' => $data['sequence'],
                'note' => $data['note'],

            ]);
            Toastr::success(__('messages.success.updated', ['item' => 'Tax type']));

            return redirect()->route('customization.index');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customization $customization)
    {
        //
    }
}
