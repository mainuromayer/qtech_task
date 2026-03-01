<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class SystemSettingController extends Controller
{
    /**
     * Display the system settings page.
     *
     * @return View
     */
    public function index()
    {
        $setting = SystemSetting::latest('id')->first();
        return view('backend.layouts.settings.system_settings', compact('setting'));

    }

    /**
     * Update the system settings.
     *
     * @param Request $request
     * @return RedirectResponse
     */

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'system_title' => 'nullable|string|max:150',
            'system_short_title' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:150',
            'email' => 'nullable|email|max:150',
            'phone_number' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:50',
            'copyright_text' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,ico,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,jpg,png,ico,svg|max:2048',
            'tag_line' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $setting = SystemSetting::firstOrNew();
            $setting->system_title = $request->system_title;
            $setting->system_short_title = $request->system_short_title;
            $setting->company_name = $request->company_name;
            $setting->email = $request->email;
            $setting->phone_number = $request->phone_number;
            $setting->whatsapp = $request->whatsapp;
            $setting->copyright_text = $request->copyright_text;
            $setting->tag_line = $request->tag_line;

            $uploadPath = public_path('uploads/systems');

            // Create uploads folder if it doesn't exist
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Handle logo upload
            if ($request->hasFile('logo')) {
                if ($setting->logo && File::exists(public_path($setting->logo))) {
                    if (!filter_var($setting->logo, FILTER_VALIDATE_URL)) {
                        File::delete(public_path($setting->logo));
                    }
                }

                $logoName = 'logo_' . time() . '.' . $request->file('logo')->getClientOriginalExtension();
                $request->file('logo')->move($uploadPath, $logoName);
                $setting->logo = 'uploads/systems/' . $logoName;
            }

            // Handle favicon upload
            if ($request->hasFile('favicon')) {
                if ($setting->favicon && File::exists(public_path($setting->favicon))) {
                    if (!filter_var($setting->favicon, FILTER_VALIDATE_URL)) {
                        File::delete(public_path($setting->favicon));
                    }
                }

                $faviconName = 'favicon_' . time() . '.' . $request->file('favicon')->getClientOriginalExtension();
                $request->file('favicon')->move($uploadPath, $faviconName);
                $setting->favicon = 'uploads/systems/' . $faviconName;
            }

            $setting->save();

            ToastMagic::success('System Settings Updated Successfully');
            return back();
        } catch (Exception $e) {
            ToastMagic::warning('System Settings Update Failed');
            return back();
        }
    }

}
