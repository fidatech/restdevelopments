<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Highlights: the amenities of a project shown with a photo, a title and a short text
 * ("Central garden", "Shopping mall"…). Stored in project_features.
 */
class ProjectHighlightController extends Controller
{
    protected function locales(): array
    {
        return config('app.locales', ['en', 'fa', 'ps', 'uz']);
    }

    public function index(Project $project)
    {
        $project->load(['translations', 'features' => fn ($q) => $q->orderBy('order'), 'features.translations']);
        $locales = $this->locales();
        $edit = request('edit') ? $project->features->firstWhere('id', (int) request('edit')) : null;
        return view('admin.project-highlights.index', compact('project', 'locales', 'edit'));
    }

    public function store(Request $request, Project $project)
    {
        return $this->save($request, $project, new ProjectFeature(['project_id' => $project->id]));
    }

    public function update(Request $request, Project $project, ProjectFeature $highlight)
    {
        abort_unless($highlight->project_id === $project->id, 404);
        return $this->save($request, $project, $highlight);
    }

    public function destroy(Project $project, ProjectFeature $highlight)
    {
        abort_unless($highlight->project_id === $project->id, 404);
        $image = $highlight->image;
        $highlight->delete();
        $this->deleteFile($image);
        return redirect()->route('admin.projects.highlights.index', $project)->with('ok', 'Highlight deleted');
    }

    protected function save(Request $request, Project $project, ProjectFeature $highlight)
    {
        $default = config('app.fallback_locale', 'en');
        $request->validate([
            'image' => ($highlight->exists ? 'nullable' : 'required').'|image|max:25600',
            'order' => 'nullable|integer|min:0|max:999',
            "translations.$default.title" => 'required|string|max:255',
            'translations.*.title' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string|max:600',
        ]);

        $old = $highlight->image;
        DB::transaction(function () use ($request, $project, $highlight, $default) {
            $highlight->project_id = $project->id;
            $highlight->type = 'amenity';
            $highlight->icon_set = 'image';
            $highlight->order = (int) $request->input('order', $highlight->order ?? ($project->features()->max('order') + 1));
            if ($request->hasFile('image')) {
                $highlight->image = $this->saveImage($request->file('image'), $project->id);
            }
            $highlight->save();

            foreach ($this->locales() as $loc) {
                $title = trim((string) $request->input("translations.$loc.title"));
                $desc = trim((string) $request->input("translations.$loc.description"));
                if ($loc !== $default && $title === '' && $desc === '') {
                    $highlight->translations()->where('locale', $loc)->delete();
                    continue;
                }
                $highlight->translations()->updateOrCreate(['locale' => $loc], [
                    'title' => $title ?: $request->input("translations.$default.title"),
                    'description' => $desc ?: null,
                ]);
            }
        });
        if ($request->hasFile('image') && $old) $this->deleteFile($old);

        return redirect()->route('admin.projects.highlights.index', $project)->with('ok', 'Highlight saved');
    }

    protected function saveImage($file, int $projectId): string
    {
        $dir = "uploads/projects/$projectId/highlights";
        File::ensureDirectoryExists(public_path("storage/$dir"), 0755, true);
        $img = (new ImageManager(new Driver()))->read($file->getRealPath());
        if ($img->width() > 6000) $img = $img->scaleDown(6000);
        $name = 'highlight_'.uniqid().'_1440x1080.webp';
        file_put_contents(public_path("storage/$dir/$name"), (string) $img->cover(1440, 1080)->toWebp(80));
        return "$dir/$name";
    }

    protected function deleteFile(?string $path): void
    {
        if (! $path) return;
        foreach ([public_path('storage/'.ltrim($path, '/')), public_path($path)] as $full) {
            if (is_file($full)) @unlink($full);
        }
    }
}
