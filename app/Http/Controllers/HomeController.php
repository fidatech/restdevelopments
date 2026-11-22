<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\AboutSetting;
use App\Models\Blog;
use App\Models\HomeCounter;
use App\Models\Project;
use App\Models\ProjectAmenity;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();

        $sliders = Cache::remember("site.sliders.$locale", 60, function () {
            return Slider::with('translations')
                ->where('status', 'published')
                ->ordered()
                ->get();
        });
        // counters (active, ordered). keep translations eager-loaded for t() fallback.
        $counters = Cache::remember("site.counters.$locale", 60, function () {
            return HomeCounter::active()
                ->orderBy('order')
                ->with('translations')
                ->get();
        });

        $about = Cache::remember("site.about.setting.$locale", 60, function () {
            return AboutSetting::with('translations')->first();
        });

        $sections = Cache::remember("site.about.sections.$locale", 60, function () {
            return AboutSection::active()->orderBy('order')->with('translations')->get();
        });

        $ongoingProjects = Cache::remember("site.projects.ongoing.$locale", 60, function () {
            return Project::active()
                ->ongoing()
                ->orderBy('order')
                ->with('translations')
                ->take(12)
                ->get();
        });

        $featuredProjects = Cache::remember("site.projects.featured.$locale", 60, function () {
            return Project::active()
                ->where('featured', true)
                ->orderBy('created_at', 'desc')
                ->with(['translations', 'galleries'])
                ->take(10)
                ->get();
        });

        $amenities = Cache::remember("site.amenities.$locale", 60, function () {
            return ProjectAmenity::active()
                ->ordered()
                ->with('translations')
                ->get();
        });

        $latestBlogs = Cache::remember("site.blogs.latest.$locale", 60, function () {
            return Blog::with(['translations'])
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
        });


        return view('home.index', compact('sliders', 'counters', 'about', 'sections', 'ongoingProjects', 'featuredProjects', 'amenities', 'latestBlogs'));
    }
}
