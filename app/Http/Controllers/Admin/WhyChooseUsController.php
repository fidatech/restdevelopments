<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhyChooseUsSetting;
use App\Models\WhyChooseUsSettingTranslation;
use Illuminate\Http\Request;

class WhyChooseUsController extends Controller
{
    public function edit()
    {
        $setting = WhyChooseUsSetting::first() ?? WhyChooseUsSetting::create();
        $locales = config('app.locales', ['en', 'fa', 'ps', 'uz']);
        return view('admin.why-us.edit', compact('setting', 'locales'));
    }


    public function update(Request $request)
    {
        $locales = config('app.locales', ['en', 'fa', 'ps', 'uz']);

        $setting = WhyChooseUsSetting::first();

        foreach ($locales as $loc) {
            $setting->translations()->updateOrCreate(
                ['locale' => $loc],
                [
                    'title'       => $request->input("translations.$loc.title"),
                    'description' => $request->input("translations.$loc.description"),
                ]
            );
        }

        return back()->with('success', 'Settings updated successfully');
    }
}
