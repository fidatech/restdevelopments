<?php
// app/Http/Controllers/Admin/ProjectGalleryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGallery;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Yajra\DataTables\Facades\DataTables;

class ProjectGalleryController extends Controller
{
    protected ImageManager $images;

    public function __construct()
    {
        $this->images = new ImageManager(new Driver());
    }

    public function index(Project $project)
    {
        return view('admin.project-gallery.index', compact('project'));
    }

    public function datatable(Project $project)
    {
        $q = ProjectGallery::where('project_id', $project->id)->orderBy('order');

        return DataTables::of($q)
            ->addIndexColumn()
            ->addColumn('preview', function (ProjectGallery $g) {
                return '<img src="'.media_url($g->image).'" alt="gallery"
                        class="img-fluid rounded" style="max-width:160px">';
            })
            ->addColumn('type', fn(ProjectGallery $g) => ucwords($g->type))
            ->addColumn('order', fn(ProjectGallery $g) => $g->order)
            ->addColumn('actions', function (ProjectGallery $g) use ($project) {
                $editUrl   = route('admin.projects.gallery.update', [$project->id, $g->id]);
                $deleteUrl = route('admin.projects.gallery.destroy', [$project->id, $g->id]);
                return view('admin.project-gallery._actions', compact('g','editUrl','deleteUrl'))->render();
            })
            ->rawColumns(['preview','actions'])
            ->make(true);
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'type'  => ['required', 'in:interior,exterior'],
            'order' => ['nullable', 'integer', 'min:0'],
            'image' => ['required', 'image', 'mimes:webp,jpg,jpeg,png', 'max:8192'],
        ]);

        $imgPath = $this->processProjectImage($request->file('image'), $project->id);

        ProjectGallery::create([
            'project_id' => $project->id,
            'type'       => $validated['type'],
            'order'      => $validated['order'] ?? 0,
            'image'      => $imgPath,
        ]);

        return response()->json(['message' => 'Gallery image added successfully.']);
    }

    public function update(Request $request, Project $project, ProjectGallery $item)
    {
        abort_unless($item->project_id === $project->id, 404);

        $validated = $request->validate([
            'type'  => ['required', 'in:interior,exterior'],
            'order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:webp,jpg,jpeg,png', 'max:8192'],
        ]);

        if ($request->hasFile('image')) {
            // delete old
            $this->deleteOldFile($item->image);

            // upload new
            $item->image = $this->processProjectImage($request->file('image'), $project->id);
        }

        $item->type  = $validated['type'];
        $item->order = $validated['order'] ?? $item->order;
        $item->save();

        return response()->json(['message' => 'Gallery image updated successfully.']);
    }

    public function destroy(Project $project, ProjectGallery $item)
    {
        abort_unless($item->project_id === $project->id, 404);

        $this->deleteOldFile($item->image);
        $item->delete();

        return response()->json(['message' => 'Gallery image deleted successfully.']);
    }

    /* --------------------------------------------------------
     * Process + save image to:
     *  public/uploads/projects/{project_id}/gallery/
     * -------------------------------------------------------- */
    protected function processProjectImage(\Illuminate\Http\UploadedFile $file, int $projectId): string
    {
        $dir      = "storage/uploads/projects/$projectId/gallery";
        $absDir   = public_path($dir);

        if (!is_dir($absDir)) mkdir($absDir, 0775, true);

        $img = $this->images->read($file->getRealPath());

        if ($img->width() > 5000) {
            $img = $img->scaleDown(5000);
        }

        $out = $img->cover(800, 440);

        $name = "gallery_" . uniqid() . "_800x440.webp";
        file_put_contents("$absDir/$name", (string) $out->toWebp(72));

        return "$dir/$name";
    }

    protected function deleteOldFile(?string $relativePath): void
    {
        if (!$relativePath) return;

        $path = public_path($relativePath);
        if (file_exists($path)) unlink($path);
    }
}
