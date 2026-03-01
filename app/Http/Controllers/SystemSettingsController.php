<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'app_logo', 'app_favicon']);

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Handle Logo Upload
        if ($request->hasFile('app_logo')) {
            $logo = $request->file('app_logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/settings'), $logoName);

            // Delete old logo if exists
            $oldLogo = SystemSetting::get('app_logo');
            if ($oldLogo && File::exists(public_path('uploads/settings/' . $oldLogo))) {
                File::delete(public_path('uploads/settings/' . $oldLogo));
            }

            SystemSetting::updateOrCreate(['key' => 'app_logo'], ['value' => $logoName]);
        }

        // Handle Favicon Upload
        if ($request->hasFile('app_favicon')) {
            $favicon = $request->file('app_favicon');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('uploads/settings'), $faviconName);

            // Delete old favicon if exists
            $oldFavicon = SystemSetting::get('app_favicon');
            if ($oldFavicon && File::exists(public_path('uploads/settings/' . $oldFavicon))) {
                File::delete(public_path('uploads/settings/' . $oldFavicon));
            }

            SystemSetting::updateOrCreate(['key' => 'app_favicon'], ['value' => $faviconName]);
        }

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
    public function ajaxToggleVisibility(Request $request)
    {
        $current = SystemSetting::where('key', 'admin_settings_visible')->first();
        $newValue = ($current && $current->value == '1') ? '0' : '1';

        SystemSetting::updateOrCreate(
            ['key' => 'admin_settings_visible'],
            ['value' => $newValue]
        );

        return response()->json([
            'success' => true,
            'visible' => $newValue == '1'
        ]);
    }
}
