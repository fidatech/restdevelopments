<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Interfaces\ImageInterface;

class AboutSettingController extends Controller
{
    protected $images;

    public function __construct()
    {
        $this->images = new ImageManager(new Driver());
    }

    public function index()
    {
        $locales = config('app.locales', ['en','fa','ps','uz']);
        $setting = AboutSetting::with('translations')->first();
        return view('admin.about.settings.index', compact('setting','locales'));
    }

    public function store(Request $request)
    {
        $locales = config('app.locales', ['en','fa','ps','uz']);
        $default = config('app.fallback_locale','en');

        $request->validate([
            'status'      => 'nullable|boolean',
            'button_url'  => 'nullable|url',

            'image_left'  => 'nullable|image|max:25600',
            'image_right' => 'nullable|image|max:25600',
            'tag_image'   => 'nullable|image|max:25600',

            "translations.$default.title" => 'required|string|max:255',
            'translations.*.shadow_title' => 'nullable|string|max:255',
            'translations.*.title'        => 'nullable|string|max:255',
            'translations.*.subtitle'     => 'nullable|string',
            'translations.*.about_text'   => 'nullable|string',
            'translations.*.button_text'  => 'nullable|string|max:100',
            'translations.*.circle_text'  => 'nullable|string|max:255',
        ]);

        $setting = AboutSetting::first() ?: new AboutSetting();

        $oldLeft  = $setting->image_left;
        $oldRight = $setting->image_right;
        $oldTag   = $setting->tag_image;

        DB::beginTransaction();
        try {
            $setting->status     = $request->boolean('status', true);
            $setting->button_url = $request->input('button_url');

            if ($request->hasFile('image_left')) {
                $setting->image_left = $this->processRect($request->file('image_left'));
            }
            if ($request->hasFile('image_right')) {
                $setting->image_right = $this->processRect($request->file('image_right'));
            }
            if ($request->hasFile('tag_image')) {
                $setting->tag_image = $this->processRound($request->file('tag_image'));
            }

            $setting->save();

            foreach ($locales as $loc) {
                $setting->translations()->updateOrCreate(
                    ['locale' => $loc],
                    [
                        'shadow_title' => $request->input("translations.$loc.shadow_title"),
                        'title'        => $request->input("translations.$loc.title")
                                             ?: $request->input("translations.$default.title"),
                        'subtitle'     => $request->input("translations.$loc.subtitle"),
                        'about_text'   => $request->input("translations.$loc.about_text"),
                        'button_text'  => $request->input("translations.$loc.button_text"),
                        'circle_text'  => $request->input("translations.$loc.circle_text"),
                    ]
                );
            }

            DB::commit();

            if ($request->hasFile('image_left') && $oldLeft)   $this->deleteOldFile($oldLeft);
            if ($request->hasFile('image_right') && $oldRight) $this->deleteOldFile($oldRight);
            if ($request->hasFile('tag_image') && $oldTag)     $this->deleteOldFile($oldTag);

            if ($request->ajax()) return response()->json(['ok'=>true, 'message'=>'About settings saved']);
            return back()->with('ok','About settings saved');

        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->failJsonOrBack($request, 'Failed to save settings.', $e);
        }
    }

    /*───────────────────────────────────────────────
     | Image Processing (public/uploads)
     ───────────────────────────────────────────────*/

    protected function processRect($file): string
    {
        $dir = 'storage/uploads/about';
        File::ensureDirectoryExists(public_path('storage/uploads/about'), 0755, true);

        $origSize = $file->getSize();
        $ext = strtolower($file->getClientOriginalExtension());

        $img = $this->images->read($file->getRealPath());
        if ($img->width() > 4000) $img = $img->scaleDown(4000);

        $out = $img->cover(636, 336);

        if ($origSize > 1_000_000) {
            $name = 'about_rect_'.uniqid().'_636x336.webp';
            file_put_contents(public_path('storage/uploads/about/'.$name), (string) $out->toWebp(80));
            return 'uploads/about/'.$name;
        }

        $newExt = in_array($ext,['jpg','jpeg','png','webp']) ? $ext : 'jpg';
        $name = 'about_rect_'.uniqid().'_636x336.'.$newExt;

        $abs = public_path('storage/uploads/about/'.$name);

        switch ($newExt) {
            case 'png':
                file_put_contents($abs, (string) $out->toPng());
                break;
            case 'webp':
                file_put_contents($abs, (string) $out->toWebp(90));
                break;
            default:
                file_put_contents($abs, (string) $out->toJpeg(90));
        }

        return 'uploads/about/'.$name;
    }

    protected function processRound($file): string
    {
        $dir = 'storage/uploads/about';
        File::ensureDirectoryExists(public_path('storage/uploads/about'), 0755, true);

        $origSize = $file->getSize();
        $ext = strtolower($file->getClientOriginalExtension());

        $img = $this->images->read($file->getRealPath());
        if ($img->width() > 3000) $img = $img->scaleDown(3000);

        $out = $img->cover(201, 200);

        if ($origSize > 1_000_000) {
            $name = 'about_round_'.uniqid().'_201x200.webp';
            file_put_contents(public_path('storage/uploads/about/'.$name), (string) $out->toWebp(80));
            return 'uploads/about/'.$name;
        }

        $newExt = in_array($ext,['jpg','jpeg','png','webp']) ? $ext : 'jpg';
        $name = 'about_round_'.uniqid().'_201x200.'.$newExt;

        $abs = public_path('storage/uploads/about/'.$name);

        switch ($newExt) {
            case 'png':
                file_put_contents($abs, (string) $out->toPng());
                break;
            case 'webp':
                file_put_contents($abs, (string) $out->toWebp(90));
                break;
            default:
                file_put_contents($abs, (string) $out->toJpeg(90));
        }

        return 'uploads/about/'.$name;
    }

    protected function deleteOldFile(?string $path)
    {
        if (!$path) return;
        $full = public_path($path);
        if (is_file($full)) @unlink($full);
    }

    protected function failJsonOrBack(Request $r, string $msg, \Throwable $e)
    {
        if ($r->ajax()) {
            return response()->json(['ok'=>false,'message'=>$msg,'error'=>$e->getMessage()],500);
        }
        return back()->withErrors(['error'=>$msg])->withInput();
    }
}
