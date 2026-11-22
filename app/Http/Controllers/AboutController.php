<?php

namespace App\Http\Controllers;

use App\Models\AboutSetting;
use App\Models\AboutSection;
use App\Models\Client;
use App\Models\Team;
use App\Models\WhyChooseUsItem;
use App\Models\WhyChooseUsSetting;
use Illuminate\Support\Facades\Cache;

class AboutController extends Controller
{
    public function index()
{
    $locale = app()->getLocale();

    $about = Cache::remember("site.about.setting.$locale", 60, function () {
        return AboutSetting::with('translations')->first();
    });

    $sections = Cache::remember("site.about.sections.$locale", 60, function () {
        return AboutSection::active()->orderBy('order')->with('translations')->get();
    });

    $whyUsSetting = WhyChooseUsSetting::with('translations')->first();
    $whyUsItems   = WhyChooseUsItem::orderBy('order')->with('translations')->get();

    $byType = $sections->groupBy('type');

    $team = Team::with(['translations'])->orderBy('order')->get();

    $clients = Client::orderBy('order')->get();
    return view('pages.about', compact(
        'about', 'sections', 'byType', 'team', 'clients',
        'whyUsSetting', 'whyUsItems'
    ));
}
}
