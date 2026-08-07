<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\FinanceYear;
use App\Models\SystemSetting;
use App\Support\Toastr;
use Exception;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function edit()
    {
        $financeYears = FinanceYear::all();
        $currencies = Currency::all();
        $setting = SystemSetting::first();

        return view('admin.settings.system', [
            'setting' => $setting,
            'financeYears' => $financeYears,
            'currencies' => $currencies,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'finance_year' => 'required|exists:finance_years,id',
            'currency' => 'required|exists:currencies,id',
            'date_format' => 'required|string|max:10',
        ]);

        try {
            $setting = SystemSetting::first();
            $setting->update([
                'finance_year_id' => $data['finance_year'],
                'currency_id' => $data['currency'],
                'date_format' => $data['date_format'],
            ]);
            Toastr::success(__('messages.success.default'));

            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error($e->getMessage());

            return redirect()->back();
        }
    }
}
