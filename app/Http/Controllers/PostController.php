<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function publicIndex()
    {
        $blogs = Blog::with(['translations'])
            ->orderBy('created_at', 'desc')
            ->paginate(6);
        $categories = BlogCategory::withCount('blogs')
            ->with('translations')
            ->orderBy('order')
            ->get();

            $recent = Blog::with('translations')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('blog.index', compact('blogs', 'categories', 'recent'));
    }

    public function publicShow(string $locale, string $slug)
    {
        $blog = Blog::with(['translations'])->where('slug', $slug)->firstOrFail();

        $categories = BlogCategory::withCount('blogs')
            ->with('translations')
            ->orderBy('order')
            ->get();

        $recent = Blog::with('translations')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('blog.show', compact('blog', 'recent', 'categories'));
    }
}
