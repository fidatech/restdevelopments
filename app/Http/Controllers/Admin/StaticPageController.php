<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Models\StaticPageTranslation;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function index()
    {
        // Get all 3 pages keyed by slug
        $pages = StaticPage::with('translations')->get()->keyBy('slug');

        return view('admin.static_pages.index', [
            'pages' => $pages,
            'locales' => ['en','fa','ps','uz'],
            'default' => config('app.fallback_locale', 'en')
        ]);
    }

    public function update(Request $request, $slug)
    {
        $page = StaticPage::where('slug', $slug)->firstOrFail();

        foreach (['en','fa','ps','uz'] as $locale) {
            $content = $request->input("content.$locale");

            // Skip if empty
            if (!$content) {
                continue;
            }

            StaticPageTranslation::updateOrCreate(
                ['static_page_id' => $page->id, 'locale' => $locale],
                ['title' => ucfirst(str_replace('-', ' ', $slug)), 'content' => $content]
            );
        }

        return response()->json(['success' => true]);
    }
}
