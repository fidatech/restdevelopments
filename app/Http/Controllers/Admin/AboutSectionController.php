<?php

// app/Http/Controllers/Admin/AboutSectionController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Interfaces\ImageInterface;
use Illuminate\Support\Facades\File;

class AboutSectionController extends Controller
{
    /** @var ImageManager */
    protected $images;

    public function __construct()
    {
        // Match your SliderController stack
        $this->images = new ImageManager(new Driver());
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(AboutSection::with('translations'))
                ->addIndexColumn()
                ->addColumn('icon_preview', function ($row) {
                    if ($row->icon_set === 'image' && $row->image) {
                        return '<img src="' . asset($row->image) . '" style="height:32px;border-radius:6px">';
                    }
                    if ($row->icon_set === 'feather' && $row->icon) return '<i data-feather="' . e($row->icon) . '"></i>';
                    if ($row->icon_set === 'bi' && $row->icon) return '<i class="bi ' . e($row->icon) . ' fs-5"></i>';
                    return '-';
                })
                ->addColumn('title', fn($r) => e(optional($r->t())->title))
                ->addColumn('type', fn($r) => ucfirst($r->type))
                ->addColumn('status', fn($r) => $r->status
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>')
                ->addColumn('actions', fn($r) => view('admin.about.sections._actions', compact('r'))->render())
                ->rawColumns(['icon_preview', 'status', 'actions'])
                ->make(true);
        }
        return view('admin.about.sections.index');
    }

    public function store(Request $request)
    {
        $locales = config('app.locales', ['en', 'fa', 'ps', 'uz']);
        $default = config('app.fallback_locale', 'en');

        $rules = [
            'type'     => 'nullable|string|in:vision,mission,values,custom',
            'status'   => 'nullable|boolean',
            'order'    => 'nullable|integer|min:0',
            'icon_set' => 'required|in:feather,bi,image',
            'icon'     => 'nullable|string|max:100',
            'image'    => 'nullable|image|max:25600',
        ];
        foreach ($locales as $loc) {
            $rules["translations.$loc.title"] = $loc === $default ? 'required|string|max:255' : 'nullable|string|max:255';
            $rules["translations.$loc.body"]  = 'nullable|string';
        }
        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $data = $request->only(['type', 'icon_set', 'icon']);
            $data['status'] = $request->boolean('status', true);
            $data['order']  = (int) $request->input('order', 0);

            if ($request->input('icon_set') === 'image' && $request->hasFile('image')) {
                // compress & crop to 526x370 into public/uploads/about/sections
                $data['image'] = $this->processSectionImage($request->file('image'));
            }

            $section = AboutSection::create($data);

            foreach ($locales as $loc) {
                $section->translations()->create([
                    'locale' => $loc,
                    'title'  => $request->input("translations.$loc.title") ?: $request->input("translations.$default.title"),
                    'body'   => $request->input("translations.$loc.body"),
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Section created']);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (!empty($data['image'])) $this->deleteOldFilePublic($data['image']);
            throw $e;
        }
    }

    public function edit($id)
    {
        $row = AboutSection::with('translations')->findOrFail($id);
        return response()->json($row);
    }

    public function update(Request $request, $id)
    {
        $locales = config('app.locales', ['en', 'fa', 'ps', 'uz']);
        $default = config('app.fallback_locale', 'en');

        $rules = [
            'type'     => 'nullable|string|in:vision,mission,values,custom',
            'status'   => 'nullable|boolean',
            'order'    => 'nullable|integer|min:0',
            'icon_set' => 'required|in:feather,bi,image',
            'icon'     => 'nullable|string|max:100',
            'image'    => 'nullable|image|max:25600',
        ];
        foreach ($locales as $loc) {
            $rules["translations.$loc.title"] = $loc === $default ? 'required|string|max:255' : 'nullable|string|max:255';
            $rules["translations.$loc.body"]  = 'nullable|string';
        }
        $validated = $request->validate($rules);

        $section = AboutSection::findOrFail($id);

        // keep old for safe delete after commit
        $oldImage = $section->image;

        DB::beginTransaction();
        try {
            $section->type     = $request->input('type', 'custom');
            $section->status   = $request->boolean('status', true);
            $section->order    = (int) $request->input('order', 0);
            $section->icon_set = $request->input('icon_set');
            $section->icon     = $request->input('icon');

            if ($request->input('icon_set') === 'image' && $request->hasFile('image')) {
                // new compressed image (526x370) under public/
                $section->image = $this->processSectionImage($request->file('image'));
            }

            // If switched away from image, drop existing file
            if ($request->input('icon_set') !== 'image' && $section->image) {
                $oldImage = $section->image;
                $section->image = null;
            }

            $section->save();

            foreach ($locales as $loc) {
                $section->translations()->updateOrCreate(
                    ['locale' => $loc],
                    [
                        'title' => $request->input("translations.$loc.title") ?: $request->input("translations.$default.title"),
                        'body'  => $request->input("translations.$loc.body"),
                    ]
                );
            }

            DB::commit();

            // unlink old AFTER successful commit
            if ($request->hasFile('image') && $oldImage) {
                $this->deleteOldFilePublic($oldImage);
            }
            if ($request->input('icon_set') !== 'image' && $oldImage) {
                $this->deleteOldFilePublic($oldImage);
            }

            return response()->json(['message' => 'Section updated']);
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->hasFile('image') && $section->image && $section->image !== $oldImage) {
                $this->deleteOldFilePublic($section->image);
            }
            throw $e;
        }
    }

    public function destroy($id)
    {
        $section = AboutSection::findOrFail($id);
        DB::transaction(function () use ($section) {
            if ($section->image) $this->deleteOldFilePublic($section->image);
            $section->translations()->delete();
            $section->delete();
        });
        return response()->json(['message' => 'Section deleted']);
    }

    // ---------------- image helpers (WebP 72, 526x370 cover) ----------------

    /**
     * Process and save an about-section image into public/uploads/about/sections
     * Returns a relative web path, e.g. 'uploads/about/sections/about_section_xxx_526x370.webp'
     */
    /**
     * Process About Section image:
     * - If image > 1MB → compress to WEBP (quality 80)
     * - If image <= 1MB → keep original extension, no compression
     * - Always resize/crop to 526x370
     */
    protected function processSectionImage(\Illuminate\Http\UploadedFile $file): string
    {
        $dir = 'storage/uploads/about/sections';
        $absDir = public_path($dir);
        File::ensureDirectoryExists($absDir, 0755, true);

        $origSize = $file->getSize(); // bytes
        $origExt  = strtolower($file->getClientOriginalExtension());

        // Read image
        $img = $this->images->read($file->getRealPath());

        // prevent extreme memory usage
        if ($img->width() > 4000) {
            $img = $img->scaleDown(4000);
        }

        // final target size
        $out = $img->cover(526, 370);

        // 📌 CASE 1: Large image (> 1MB) → Compress WEBP
        if ($origSize > 1_000_000) {
            $name = 'about_section_' . uniqid() . '_526x370.webp';
            $absolute = $absDir . '/' . $name;

            file_put_contents($absolute, (string) $out->toWebp(80));

            return $dir . '/' . $name;
        }

        // 📌 CASE 2: Small image (<= 1MB) → Save original extension (no compression)
        $newExt = in_array($origExt, ['jpg', 'jpeg', 'png', 'webp']) ? $origExt : 'jpg';
        $name   = 'about_section_' . uniqid() . '_526x370.' . $newExt;
        $absolute = $absDir . '/' . $name;

        // save in original format
        switch ($newExt) {
            case 'png':
                file_put_contents($absolute, (string) $out->toPng());
                break;

            case 'webp':
                file_put_contents($absolute, (string) $out->toWebp(90));
                break;

            default: // jpg, jpeg
                file_put_contents($absolute, (string) $out->toJpeg(90));
        }

        return $dir . '/' . $name;
    }


    /**
     * Delete existing public file safely.
     */
    protected function deleteOldFilePublic(?string $path): void
    {
        if (!$path) return;

        $full = public_path($path);
        if (is_file($full)) @unlink($full);
    }


    /**
     * Save WEBP under public web root and return relative web path
     */
    protected function putWebpPublic(ImageInterface $image, string $dir, string $name, int $quality = 72): string
    {
        $dir = trim($dir, '/');
        $absDir = public_path($dir);
        File::ensureDirectoryExists($absDir, 0755, true);

        $relative = $dir . '/' . $name . '.webp';
        $absolute = public_path($relative);

        file_put_contents($absolute, (string) $image->toWebp($quality));

        return $relative; // store this in DB; render via asset($relative)
    }
}
