<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhyChooseUsItem;
use Illuminate\Http\Request;

class WhyChooseUsItemController extends Controller
{
    public function index()
    {
        $items = WhyChooseUsItem::orderBy('order')->get();
        return view('admin.why-us.items.index', compact('items'));
    }

    public function create()
    {
        $locales = config('app.locales', ['en', 'ps', 'fa', 'uz']);
        return view('admin.why-us.items.form', [
            'item'     => new WhyChooseUsItem(),
            'locales'  => $locales
        ]);
    }

    public function store(Request $request)
    {
        $item             = new WhyChooseUsItem();
        $item->order      = $request->order;

        // Uploads
        if ($request->file('icon')) {
            $item->icon = $request->file('icon')->store('why-us', 'public');
        }
        if ($request->file('image')) {
            $item->image = $request->file('image')->store('why-us', 'public');
        }
        $item->save();

        foreach (config('app.locales', ['en', 'ps', 'fa', 'uz']) as $loc) {
            $item->translations()->create([
                'locale'      => $loc,
                'title'       => $request->input("translations.$loc.title"),
                'description' => $request->input("translations.$loc.description"),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function edit(WhyChooseUsItem $item)
    {
        $locales = config('app.locales', ['en', 'ps', 'fa', 'uz']);
        return view('admin.why-us.items.form', compact('item', 'locales'));
    }

    public function update(Request $request, WhyChooseUsItem $item)
    {
        // Uploads
        if ($request->file('icon')) {
            $item->icon = $request->file('icon')->store('why-us', 'public');
        }
        if ($request->file('image')) {
            $item->image = $request->file('image')->store('why-us', 'public');
        }
        $item->order = $request->order;
        $item->save();

        foreach (config('app.locales', ['en', 'ps', 'fa', 'uz']) as $loc) {
            $item->translations()->updateOrCreate(
                ['locale' => $loc],
                [
                    'title'       => $request->input("translations.$loc.title"),
                    'description' => $request->input("translations.$loc.description"),
                ]
            );
        }

        return response()->json(['success' => true]);
    }

    public function destroy(WhyChooseUsItem $item)
    {
        $item->delete();
        return response()->json(['success' => true]);
    }
}
