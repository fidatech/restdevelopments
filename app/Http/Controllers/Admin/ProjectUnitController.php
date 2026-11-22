<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUnit;
use App\Models\ProjectUnitTranslation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ProjectUnitController extends Controller
{
    public function index(Project $project)
    {
        $locales = config('app.available_locales', ['en']);
        return view('admin.project-units.index', compact('project', 'locales'));
    }

    public function datatable(Project $project)
    {
        $query = ProjectUnit::with(['translations'])->where('project_id', $project->id)->orderBy('order');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function (ProjectUnit $unit) {
                // Prefer current app locale, fall back to first available
                $current = app()->getLocale();
                $t = $unit->translations->firstWhere('locale', $current)
                    ?: $unit->translations->first();
                return $t?->name ?: '—';
            })
            ->addColumn('bedrooms', fn(ProjectUnit $u) => $u->bedrooms)
            ->addColumn('area', function (ProjectUnit $u) {
                $min = $u->min_area ? number_format($u->min_area) : '—';
                $max = $u->max_area ? number_format($u->max_area) : '—';
                return "{$min} – {$max} sqft";
            })
            ->addColumn('count', fn(ProjectUnit $u) => $u->count ?? '—')
            ->addColumn('order', fn(ProjectUnit $u) => $u->order)
            ->addColumn('actions', function (ProjectUnit $u) use ($project) {
                $editUrl   = route('admin.projects.units.update', [$project->id, $u->id]);
                $deleteUrl = route('admin.projects.units.destroy', [$project->id, $u->id]);
                return view('admin.project-units._actions', compact('u','editUrl','deleteUrl'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request, Project $project)
    {
        $locales = config('app.available_locales', ['en']);

        $baseRules = [
            'bedrooms'   => ['required','integer','min:0','max:255'],
            'min_area'   => ['nullable','integer','min:0'],
            'max_area'   => ['nullable','integer','gte:min_area','max:4294967295'],
            'count'      => ['nullable','integer','min:0'],
            'order'      => ['nullable','integer','min:0'],
        ];

        // translation rules: name[xx], description[xx]
        foreach ($locales as $loc) {
            $baseRules["name.$loc"]        = ['nullable','string','max:255'];
            $baseRules["description.$loc"] = ['nullable','string'];
        }

        $validated = $request->validate($baseRules);

        $unit = ProjectUnit::create([
            'project_id' => $project->id,
            'bedrooms'   => (int) $validated['bedrooms'],
            'min_area'   => $validated['min_area'] ?? null,
            'max_area'   => $validated['max_area'] ?? null,
            'count'      => $validated['count'] ?? null,
            'order'      => $validated['order'] ?? 0,
        ]);

        // translations
        foreach ($locales as $loc) {
            $name = data_get($validated, "name.$loc");
            $desc = data_get($validated, "description.$loc");
            if (!is_null($name) || !is_null($desc)) {
                ProjectUnitTranslation::create([
                    'project_unit_id' => $unit->id,
                    'locale'          => $loc,
                    'name'            => $name,
                    'description'     => $desc,
                ]);
            }
        }

        return response()->json(['message' => 'Unit created successfully.']);
    }

    public function update(Request $request, Project $project, ProjectUnit $unit)
    {
        abort_unless($unit->project_id === $project->id, 404);

        $locales = config('app.available_locales', ['en']);

        $baseRules = [
            'bedrooms'   => ['required','integer','min:0','max:255'],
            'min_area'   => ['nullable','integer','min:0'],
            'max_area'   => ['nullable','integer','gte:min_area','max:4294967295'],
            'count'      => ['nullable','integer','min:0'],
            'order'      => ['nullable','integer','min:0'],
        ];
        foreach ($locales as $loc) {
            $baseRules["name.$loc"]        = ['nullable','string','max:255'];
            $baseRules["description.$loc"] = ['nullable','string'];
        }

        $validated = $request->validate($baseRules);

        $unit->update([
            'bedrooms' => (int) $validated['bedrooms'],
            'min_area' => $validated['min_area'] ?? null,
            'max_area' => $validated['max_area'] ?? null,
            'count'    => $validated['count'] ?? null,
            'order'    => $validated['order'] ?? $unit->order,
        ]);

        foreach ($locales as $loc) {
            $name = data_get($validated, "name.$loc");
            $desc = data_get($validated, "description.$loc");

            // Maintain unique(project_unit_id, locale)
            ProjectUnitTranslation::updateOrCreate(
                ['project_unit_id' => $unit->id, 'locale' => $loc],
                ['name' => $name, 'description' => $desc]
            );
        }

        return response()->json(['message' => 'Unit updated successfully.']);
    }

    public function destroy(Project $project, ProjectUnit $unit)
    {
        abort_unless($unit->project_id === $project->id, 404);
        $unit->delete();
        return response()->json(['message' => 'Unit deleted successfully.']);
    }
}
