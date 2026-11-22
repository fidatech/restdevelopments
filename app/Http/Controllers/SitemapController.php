<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [];
        $locales = config('app.available_locales', ['en']);
        $pages = ['home', 'about', 'projects.index', 'latest-launches', 'blog.index', 'team.index', 'careers.index', 'contact'];

        foreach ($locales as $locale) {
            foreach ($pages as $page) {
                $urls[] = route($page, ['locale' => $locale]);
            }
        }

        foreach (Project::active()->pluck('slug') as $slug) {
            foreach ($locales as $locale) {
                $urls[] = route('projects.show', ['locale' => $locale, 'slug' => $slug]);
            }
        }

        foreach (Blog::where('status', true)->pluck('slug') as $slug) {
            foreach ($locales as $locale) {
                $urls[] = route('blog.show', ['locale' => $locale, 'slug' => $slug]);
            }
        }

        return response()->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
