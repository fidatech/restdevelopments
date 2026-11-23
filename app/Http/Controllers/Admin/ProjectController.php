<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class ProjectController extends Controller
{
    protected ImageManager $images;

    public function __construct()
    {
        $this->images = new ImageManager(
            extension_loaded('imagick') ? new ImagickDriver() : new GdDriver()
        );
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Project::with('translations'))
                ->addIndexColumn()
                ->addColumn('thumb', fn($r) =>
                    $r->image ? '<img src="'.media_url($r->image).'" style="height:80px;border-radius:6px">' : '-')
                ->addColumn('title', fn($r) => e(optional($r->t())->title))
                ->addColumn('meta', function($r){
                    $bits = [];
                    if ($r->beds) $bits[] = "Bed {$r->beds}";
                    if ($r->baths) $bits[] = "Bath {$r->baths}";
                    if ($r->area) $bits[] = "{$r->area} sqft";
                    return e(implode(' · ', $bits));
                })
                ->addColumn('state', fn($r) => ucfirst($r->state))
                ->addColumn('status', fn($r) =>
                    $r->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>'
                )
                ->addColumn('actions', fn($r) => view('admin.projects._actions', compact('r'))->render())
                ->rawColumns(['thumb','status','actions'])
                ->make(true);
        }
        return view('admin.projects.index');
    }

    public function store(Request $r)
    {
        $locales = config('app.locales', ['en','fa','ps','uz']);
        $default = config('app.fallback_locale','en');

        $validated = $r->validate([
            'state'   => 'required|in:ongoing,completed',
            'featured'          => ['nullable','boolean'],
            'price_starts_from' => ['nullable','string','max:255'],
            'status'  => 'nullable|boolean',
            'order'   => 'nullable|integer|min:0',
            'location'=> 'nullable|string|max:120',
            'beds'    => 'nullable|integer|min:0|max:99',
            'baths'   => 'nullable|integer|min:0|max:99',
            'area'    => 'nullable|integer|min:0|max:10000000',
            'image'   => ['nullable','image','max:25600'],
            "translations.$default.title" => 'required|string|max:255',
            'translations.*.title'        => 'nullable|string|max:255',
            'translations.*.description'  => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $data = $validated;
            $data['status']   = $r->boolean('status', true);
            $data['order']    = (int) ($validated['order'] ?? 0);
            $data['featured'] = (bool) ($validated['featured'] ?? false);
            $data['price_starts_from'] = $validated['price_starts_from'] ?? null;
            $data['slug']     = Str::slug($validated['translations'][$default]['title']);

            // create project first (needs ID)
            $project = Project::create($data);

            // upload main image
            if ($r->hasFile('image')) {
                $project->image = $this->processProjectImage($r->file('image'), $project->id);
                $project->save();
            }

            // translations
            foreach ($locales as $loc) {
                $project->translations()->create([
                    'locale'      => $loc,
                    'title'       => $r->input("translations.$loc.title") ?: $validated["translations"][$default]["title"],
                    'description' => $r->input("translations.$loc.description"),
                ]);
            }

            DB::commit();
            return response()->json(['message'=>'Project created']);
        }
        catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function edit($id)
    {
        return response()->json(Project::with('translations')->findOrFail($id));
    }

    public function update(Request $r, $id)
    {
        $locales = config('app.locales', ['en','fa','ps','uz']);
        $default = config('app.fallback_locale','en');

        $validated = $r->validate([
            'state'   => 'required|in:ongoing,completed',
            'featured'=> ['nullable','boolean'],
            'price_starts_from' => ['nullable','string','max:255'],
            'status'  => 'nullable|boolean',
            'order'   => 'nullable|integer|min:0',
            'location'=> 'nullable|string|max:120',
            'beds'    => 'nullable|integer|min:0|max:99',
            'baths'   => 'nullable|integer|min:0|max:99',
            'area'    => 'nullable|integer|min:0|max:10000000',
            'image'   => ['nullable','image','max:25600'],
            "translations.$default.title" => 'required|string|max:255',
            'translations.*.title'        => 'nullable|string|max:255',
            'translations.*.description'  => 'nullable|string',
        ]);

        $project = Project::findOrFail($id);
        $old = $project->image;

        DB::beginTransaction();
        try {
            $project->fill($validated);
            $project->status   = $r->boolean('status', true);
            $project->featured = (bool) ($validated['featured'] ?? false);
            $project->price_starts_from = $validated['price_starts_from'] ?? null;
            $project->order = (int) ($validated['order'] ?? 0);

            // update image
            if ($r->hasFile('image')) {
                $project->image = $this->processProjectImage($r->file('image'), $project->id);
            }

            $project->save();

            // translations
            foreach ($locales as $loc) {
                $project->translations()->updateOrCreate(
                    ['locale'=>$loc],
                    [
                        'title'       => $r->input("translations.$loc.title") ?: $validated["translations"][$default]["title"],
                        'description' => $r->input("translations.$loc.description"),
                    ]
                );
            }

            DB::commit();

            // delete old file
            if ($r->hasFile('image') && $old) $this->deleteOldFile($old);

            return response()->json(['message'=>'Project updated']);
        }
        catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        // delete main image
        if ($project->image) $this->deleteOldFile($project->image);

        DB::transaction(function () use ($project, $id) {

            // delete all related files:
            // documents
            DB::table('project_documents')
                ->where('project_id', $id)
                ->orderBy('id')
                ->select('file','preview_image')
                ->chunkById(200, function ($rows) {
                    foreach ($rows as $r) {
                        if ($r->file) $this->deleteOldFile($r->file);
                        if ($r->preview_image) $this->deleteOldFile($r->preview_image);
                    }
                });

            DB::table('project_documents')->where('project_id', $id)->delete();

            // galleries
            DB::table('project_galleries')
                ->where('project_id', $id)
                ->orderBy('id')
                ->select('image')
                ->chunkById(300, function ($rows) {
                    foreach ($rows as $r) {
                        if ($r->image) $this->deleteOldFile($r->image);
                    }
                });

            DB::table('project_galleries')->where('project_id', $id)->delete();

            // units translations
            DB::table('project_unit_translations')->whereIn(
                'project_unit_id',
                DB::table('project_units')->where('project_id', $id)->select('id')
            )->delete();

            DB::table('project_units')->where('project_id', $id)->delete();

            // features
            if (Schema::hasTable('project_features')) {
                DB::table('project_feature_translations')->whereIn(
                    'project_feature_id',
                    DB::table('project_features')->where('project_id', $id)->select('id')
                )->delete();

                DB::table('project_features')->where('project_id',$id)->delete();
            }

            DB::table('project_translations')->where('project_id', $id)->delete();
            $project->delete();
        });

        return response()->json(['message'=>'Project deleted']);
    }

    /* ----------------------------------------------------
     *  IMAGE PROCESSOR — stores inside:
     *  public/uploads/projects/{project_id}/...
     * ---------------------------------------------------- */
    protected function processProjectImage(\Illuminate\Http\UploadedFile $file, int $projectId): string
    {
        $dir = "uploads/projects/$projectId";
        $absDir = public_path("storage/".$dir);

        if (!is_dir($absDir)) mkdir($absDir, 0775, true);

        $origSize = $file->getSize();
        $origExt  = strtolower($file->getClientOriginalExtension());

        $img = $this->images->read($file->getRealPath())->cover(800, 440);

        // choose extension
        if ($origSize > 1_000_000) {
            $name = "project_" . uniqid() . "_800x440.webp";
            file_put_contents("$absDir/$name", (string) $img->toWebp(72));
            return "$dir/$name";
        }

        $ext = in_array($origExt, ['jpg','jpeg','png','webp']) ? $origExt : 'jpg';
        $name = "project_" . uniqid() . "_800x440.$ext";

        switch ($ext) {
            case 'png':
                file_put_contents("$absDir/$name", (string) $img->toPng());
                break;
            case 'webp':
                file_put_contents("$absDir/$name", (string) $img->toWebp(85));
                break;
            default:
                file_put_contents("$absDir/$name", (string) $img->toJpeg(88));
        }

        return "$dir/$name";
    }

    protected function deleteOldFile(?string $relativePath): void
    {
        if (!$relativePath) return;

        $path = public_path($relativePath);
        if (file_exists($path)) unlink($path);
    }
}
