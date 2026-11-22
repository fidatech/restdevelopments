<?php

namespace App\Http\Controllers;

use App\Models\AboutSetting;
use App\Models\Blog;
use App\Models\Project;
use App\Models\Slider;
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
                ->take(4)->get();
        });

        $about = Cache::remember("site.about.setting.$locale", 60, function () {
            return AboutSetting::with('translations')->first();
        });

        $featuredProjects = Cache::remember("site.projects.featured.$locale", 60, function () {
            return Project::active()
                ->where('featured', true)
                ->orderBy('created_at', 'desc')
                ->with('translations')
                ->take(3)
                ->get();
        });
        $ongoingProjects = $featuredProjects->isEmpty()
            ? Cache::remember("site.projects.ongoing.$locale", 60, fn () => Project::active()->ongoing()->orderBy('order')->with('translations')->take(3)->get())
            : collect();

        $latestBlogs = Cache::remember("site.blogs.latest.$locale", 60, function () {
            return Blog::with(['translations'])
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        });


        return view('home.index', compact('sliders', 'about', 'ongoingProjects', 'featuredProjects', 'latestBlogs'));
    }
}
