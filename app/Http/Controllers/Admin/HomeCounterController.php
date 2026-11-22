<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeCounter;
use App\Models\HomeCounterTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class HomeCounterController extends Controller
{
    protected ImageManager $images;

    public function __construct()
    {
        $this->images = new ImageManager(new Driver());
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(HomeCounter::with('translations'))
                ->addIndexColumn()
                ->addColumn('title', fn($r) => e(optional($r->t())->title))
                ->addColumn('status', fn($r) =>
                    $r->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>'
                )
                ->addColumn('actions', fn($r) => view('admin.home.counters._actions', compact('r'))->render())
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.home.counters.index');
    }

    public function store(Request $request)
    {
        $locales = config('app.locales', ['en','fa','ps','uz']);
        $default = config('app.fallback_locale','en');

        $rules = [
            'number'   => 'required|integer|min:0',
            'status'   => 'nullable|boolean',
            'order'    => 'nullable|integer|min:0',
        ];

        foreach ($locales as $loc) {
            $rules["translations.$loc.title"] =
                ($loc === $default) ? 'required|string|max:255' : 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $data = $request->only(['number']);
            $data['status'] = $request->boolean('status');
            $data['order']  = (int) $request->input('order', 0);

            $counter = HomeCounter::create($data);

            foreach ($locales as $loc) {
                $title = $request->input("translations.$loc.title")
                    ?: $request->input("translations.$default.title");

                HomeCounterTranslation::create([
                    'home_counter_id' => $counter->id,
                    'locale'          => $loc,
                    'title'           => $title,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Counter created']);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function edit($id)
    {
        $c = HomeCounter::with('translations')->findOrFail($id);
        return response()->json($c);
    }

    public function update(Request $request, $id)
    {
        $locales = config('app.locales', ['en','fa','ps','uz']);
        $default = config('app.fallback_locale','en');

        $rules = [
            'icon_set' => 'required|in:feather,bi,image',
            'icon'     => 'nullable|string|max:100',
            'image'    => 'nullable|image|max:4096',
            'number'   => 'required|integer|min:0',
            'prefix'   => 'nullable|string|max:8',
            'suffix'   => 'nullable|string|max:8',
            'status'   => 'nullable|boolean',
            'order'    => 'nullable|integer|min:0',
        ];

        foreach ($locales as $loc) {
            $rules["translations.$loc.title"] =
                ($loc === $default) ? 'required|string|max:255' : 'nullable|string|max:255';

            $rules["translations.$loc.subtitle"] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        $counter = HomeCounter::findOrFail($id);
        $oldImage = $counter->image;

        DB::beginTransaction();
        try {
            $counter->icon_set = $request->input('icon_set');
            $counter->icon     = $request->input('icon');
            $counter->number   = (int)$request->input('number');
            $counter->prefix   = $request->input('prefix');
            $counter->suffix   = $request->input('suffix');
            $counter->status   = $request->boolean('status');
            $counter->order    = (int)$request->input('order', 0);

            // IMAGE UPLOAD / DELETE
            if ($request->input('icon_set') === 'image' && $request->hasFile('image')) {

                if ($oldImage && is_file(public_path($oldImage))) {
                    @unlink(public_path($oldImage));
                }

                $counter->image = $this->processImage($request->file('image'));

            } elseif ($request->input('icon_set') !== 'image') {

                if ($oldImage && is_file(public_path($oldImage))) {
                    @unlink(public_path($oldImage));
                }

                $counter->image = null;
            }

            $counter->save();

            foreach ($locales as $loc) {
                $title = $request->input("translations.$loc.title")
                    ?: $request->input("translations.$default.title");

                $subtitle = $request->input("translations.$loc.subtitle");

                $counter->translations()->updateOrCreate(
                    ['locale' => $loc],
                    [
                        'title'    => $title,
                        'subtitle' => $subtitle,
                    ]
                );
            }

            DB::commit();
            return response()->json(['message' => 'Counter updated']);

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $counter = HomeCounter::findOrFail($id);

        DB::transaction(function () use ($counter) {
            if ($counter->image && is_file(public_path($counter->image))) {
                @unlink(public_path($counter->image));
            }
            $counter->translations()->delete();
            $counter->delete();
        });

        return response()->json(['message' => 'Counter deleted']);
    }

    /*───────────────────────────────────────────────
     | Image Processing — public/uploads/home/counters
     | - If >1MB → WEBP 80
     | - If ≤1MB → original ext + quality 90
     ───────────────────────────────────────────────*/

    private function processImage(\Illuminate\Http\UploadedFile $file)
    {
        $dir = public_path('storage/uploads/home/counters');
        File::ensureDirectoryExists($dir, 0755, true);

        $origSize = $file->getSize();
        $origExt  = strtolower($file->getClientOriginalExtension());

        // resize/crop 100x100 for counter icon
        $img = $this->images->read($file->getRealPath())->cover(100, 100);

        // large → WEBP
        if ($origSize > 1_000_000) {
            $name = 'counter_' . uniqid() . '_100.webp';
            file_put_contents($dir.'/'.$name, (string) $img->toWebp(80));
            return 'uploads/home/counters/'.$name;
        }

        // small → keep extension
        $ext = in_array($origExt, ['jpg','jpeg','png','webp']) ? $origExt : 'jpg';
        $name = 'counter_' . uniqid() . '_100.' . $ext;
        $abs  = $dir.'/'.$name;

        switch ($ext) {
            case 'png':
                file_put_contents($abs, (string) $img->toPng());
                break;
            case 'webp':
                file_put_contents($abs, (string) $img->toWebp(90));
                break;
            default:
                file_put_contents($abs, (string) $img->toJpeg(90));
        }

        return 'uploads/home/counters/'.$name;
    }
}
