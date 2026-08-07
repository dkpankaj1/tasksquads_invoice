<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\ImageHandler;
use App\Support\Toastr;
use Exception;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.setting', ['setting' => Setting::first()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'cin' => 'nullable|string',
            'gstin' => 'nullable|string',

            'beneficiary_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_type' => 'nullable|string|max:50',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            'swift_bic_code' => 'nullable|string|max:20',
            'branch' => 'nullable|string|max:255',

            'logo' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_sm' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'favicon' => 'nullable|mimes:ico,png|max:1024',
            'stamp' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:1024',

            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',

            'facebook_link' => 'nullable|url|max:255',
            'twitter_link' => 'nullable|url|max:255',
            'instagram_link' => 'nullable|url|max:255',
            'linkedin_link' => 'nullable|url|max:255',
            'meta_title' => 'required|string|max:255',
            'meta_description' => 'required|string|max:500',
            'meta_keywords' => 'required|string|max:500',
        ]);

        try {

            $setting = Setting::first();
            $imageHandler = new ImageHandler('logo');

            $data = $request->except(['logo', 'logo_sm', 'favicon', 'stamp']);

            if ($request->hasFile('logo')) {

                if (isset($setting->logo)) {
                    $imageHandler->delete($setting->logo);
                }
                $data['logo'] = $imageHandler->upload($request->file('logo'));
            }

            if ($request->hasFile('logo_sm')) {

                if (isset($setting->logo)) {
                    $imageHandler->delete($setting->logo_sm);
                }
                $data['logo_sm'] = $imageHandler->upload($request->file('logo_sm'));
            }

            if ($request->hasFile('favicon')) {

                if (isset($setting->favicon)) {
                    $imageHandler->delete($setting->favicon);
                }
                $data['favicon'] = $imageHandler->upload($request->file('favicon'));
            }

            if ($request->hasFile('stamp')) {
                $stampHandler = new ImageHandler('stamps');

                if (isset($setting->stamp)) {
                    $stampHandler->delete($setting->stamp);
                }
                $data['stamp'] = $stampHandler->upload($request->file('stamp'));
            }

            $setting->update($data);
            Toastr::success(__('messages.success.default'));

            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error($e->getMessage());

            return redirect()->back();
        }
    }
}
