<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        // return JSON to datatable when requested
        if ($request->has('datatable')) {
            return $this->datatable();
        }

        return view('admin.blog-categories.index');
    }

    protected function datatable()
    {
        $q = BlogCategory::orderBy('order');

        return DataTables::of($q)
            ->addIndexColumn()
            ->addColumn('title', fn($c) => $c->t()?->title)
            ->addColumn('order', fn($c) => $c->order)
            ->addColumn('actions', function ($c) {
                return view('admin.blog-categories._actions', ['c' => $c])->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order' => ['nullable','integer'],
            'translations' => ['required','array'],
        ]);

        $category = BlogCategory::create([
            'order' => $request->order ?? 0
        ]);

        foreach ($request->translations as $loc => $t) {
            $category->translations()->create([
                'locale' => $loc,
                'title'  => $t['title'] ?? ''
            ]);
        }

        return response()->json(['message'=>'Category saved']);
    }

    public function update(Request $request, BlogCategory $category)
    {
        $request->validate([
            'order'        => ['nullable','integer'],
            'translations' => ['required','array'],
        ]);

        $category->order = $request->order ?? $category->order;
        $category->save();

        foreach ($request->translations as $loc => $t) {
            $category->translations()->updateOrCreate(
                ['locale'=>$loc],
                ['title'=>$t['title'] ?? '']
            );
        }

        return response()->json(['message' => 'Category updated']);
    }

    public function destroy(BlogCategory $category)
    {
        $category->translations()->delete();
        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}
