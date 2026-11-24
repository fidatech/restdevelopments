<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectPublicController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();

        $query = Project::with(['translations', 'units'])->latest('id');

        // name/title search
        if ($q = trim($request->input('q'))) {
            $query->where(function ($qq) use ($q, $locale) {
                $qq->where('slug', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%")
                    ->orWhereHas('translations', function ($t) use ($q, $locale) {
                        $t->where('locale', $locale)->where('title', 'like', "%{$q}%");
                    });
            });
        }

        // location search (separate field)
        if ($loc = trim($request->input('location'))) {
            $query->where('location', 'like', "%{$loc}%");
        }

        // state filter (ongoing/completed)
        if ($state = $request->input('state')) {
            $query->where('state', $state);
        }

        // bedrooms: the project's own range ("1-4") or one of its units
        if ($beds = (int) $request->input('beds')) {
            $query->where(function ($qq) use ($beds) {
                $qq->where('beds', 'like', "%{$beds}%")
                    ->orWhereHas('units', fn ($u) => $u->where('bedrooms', $beds));
            });
        }

        // order (keep it simple: latest|oldest)
        $orderby = $request->input('orderby');
        if ($orderby === 'oldest') {
            $query->oldest('id');
        } else {
            $query->latest('id');
        }

        $projects = $query->paginate(9)->withQueryString();

        return view('projects.index', compact('projects'));
    }
    public function show(string $locale, string $slug)
    {
        // addresses that changed: keep old links working
        $moved = ['duplix-in-termiz' => 'rest-tower'];
        if (isset($moved[$slug]) && ! Project::where('slug', $slug)->exists()) {
            return redirect()->route('projects.show', ['locale' => $locale, 'slug' => $moved[$slug]], 301);
        }

        $project = Project::query()
            ->where('slug', $slug)
            ->with([
                // gallery
                'galleries' => fn($q) => $q->orderBy('order'),
                // documents
                'documents' => fn($q) => $q->orderBy('order'),
                // units + their translations
                'units.translations',
                // project translations (assuming you use it via ->t())
                'translations',
                // highlights (amenities with a photo)
                'features' => fn($q) => $q->orderBy('order'),
                'features.translations',
            ])
            ->firstOrFail();

        // Places inside this development, e.g. "Gym & Fitness at Rest Complex, Termiz"
        $enTitle = (string) ($project->translations->firstWhere('locale', 'en')?->title ?? '');
        $shortName = trim(preg_split('/\s[–—-]\s|,/u', $enTitle)[0] ?? '');
        $places = collect();
        if ($shortName !== '' && ! preg_match('/\sat\s/i', $enTitle)) {
            $places = Project::active()
                ->where('id', '!=', $project->id)
                ->whereHas('translations', fn ($t) => $t->where('locale', 'en')->where('title', 'like', '% at '.$shortName.'%'))
                ->with('translations')
                ->orderBy('order')
                ->get();
        }

        // Group documents into buckets
        $docsByType = $project->documents->groupBy('type');
        $docs = [
            'floor_plan'  => $docsByType->get('floor_plan', collect())->values(),
            'master_plan' => $docsByType->get('master_plan', collect())->values(),
            'brochure'    => $docsByType->get('brochure', collect())->values(),
        ];

        // Units: resolve name/description for current locale
        $currentLocale = app()->getLocale();
        $units = $project->units
            ->sortBy('order')
            ->map(function ($u) use ($currentLocale) {
                $t = $u->translations->firstWhere('locale', $currentLocale) ?: $u->translations->first();
                return (object)[
                    'id'          => $u->id,
                    'bedrooms'    => $u->bedrooms,
                    'min_area'    => $u->min_area,
                    'max_area'    => $u->max_area,
                    'count'       => $u->count,
                    'order'       => $u->order,
                    'name'        => $t?->name,
                    'description' => $t?->description,
                ];
            })
            ->values();

        // Featured/random other projects (exclude current)
        $featuredProjects = Project::featured()
            ->where('id', '!=', $project->id)
            ->with(['galleries', 'translations'])
            ->inRandomOrder()
            ->take(9)
            ->get();

        // If you use amenities/facilities later, pass them here too
        $amenities  = collect(); // fill later
        $facilities = collect(); // fill later

        return view('projects.show', compact('project', 'docs', 'units', 'featuredProjects', 'amenities', 'facilities', 'places', 'shortName'));
    }

    public function latestLaunches()
    {
        $projects = Project::active()
            ->where('featured', true)
            ->with(['translations', 'galleries', 'units'])
            ->latest()
            ->paginate(9);

        return view('home.latest-launches', compact('projects'));
    }
}
