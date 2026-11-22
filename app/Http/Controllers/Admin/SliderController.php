<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SliderController extends Controller
{
    protected ImageManager $images;

    public function __construct()
    {
        $this->images = new ImageManager(new Driver());
    }

    public function index()
    {
        $sliders = Slider::with('translations')->ordered()->paginate(12);
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'status' => 'required|in:published,draft',
            'sort'   => 'nullable|integer|min:0|max:65535',
            'image'  => 'required|image|max:25600',
            'image_mobile' => 'nullable|image|max:25600',
            'translations.*.headline'    => 'nullable|string|max:255',
            'translations.*.subheadline' => 'nullable|string|max:255',
            'translations.*.button_text' => 'nullable|string|max:255',
            'translations.*.button_url'  => 'nullable|url|max:255',
        ]);

        DB::beginTransaction();

        try {
            $slider = Slider::create([
                'status'    => $r->status,
                'sort'      => (int) ($r->sort ?? 0),
                'video_url' => $r->video_url,
            ]);

            // ------------ Desktop Image ------------
            if ($r->hasFile('image')) {
                $slider->image = $this->saveSliderImage($r->file('image'), 1604, 864);
            }

            // ------------ Mobile Image ------------
            if ($r->hasFile('image_mobile')) {
                $slider->image_mobile = $this->saveMobileImage($r->file('image_mobile'));
            }

            $slider->save();

            // ------------ Translations ------------
            $posted = $r->input('translations', []);
            if (empty($posted)) {
                $posted = [
                    'en' => [
                        'headline' => $r->headline,
                        'subheadline' => $r->subheadline,
                        'button_text' => $r->button_text,
                        'button_url' => $r->button_url,
                    ]
                ];
            }

            foreach ($posted as $loc => $data) {
                $slider->translations()->updateOrCreate(
                    ['locale' => $loc],
                    [
                        'headline'    => $data['headline']    ?? null,
                        'subheadline' => $data['subheadline'] ?? null,
                        'button_text' => $data['button_text'] ?? null,
                        'button_url'  => $data['button_url']  ?? null,
                    ]
                );
            }

            DB::commit();

            return $r->ajax()
                ? response()->json(['ok' => true, 'message' => 'Slider created'])
                : redirect()->route('admin.sliders.index')->with('ok', 'Slider created');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return $this->failJsonOrBack($r, 'Failed to create slider.', $e);
        }
    }

    public function edit(Slider $slider)
    {
        $slider->load('translations');
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $r, Slider $slider)
    {
        $r->validate([
            'status' => 'required|in:published,draft',
            'sort'   => 'nullable|integer|min:0|max:65535',
            'image'  => 'nullable|image|max:25600',
            'image_mobile' => 'nullable|image|max:25600',
            'video_url' => 'nullable|url|max:255',
            'translations' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $old = $slider->image;
            $oldM = $slider->image_mobile;

            if ($r->hasFile('image')) {
                $slider->image = $this->saveSliderImage($r->file('image'), 1604, 864);
            }
            if ($r->hasFile('image_mobile')) {
                $slider->image_mobile = $this->saveMobileImage($r->file('image_mobile'));
            }

            $slider->status    = $r->status;
            $slider->sort      = (int) ($r->sort ?? 0);
            $slider->video_url = $r->video_url;
            $slider->save();

            $posted = $r->input('translations', []);
            foreach ($posted as $loc => $data) {
                $slider->translations()->updateOrCreate(
                    ['locale' => $loc],
                    [
                        'headline'    => $data['headline']    ?? null,
                        'subheadline' => $data['subheadline'] ?? null,
                        'button_text' => $data['button_text'] ?? null,
                        'button_url'  => $data['button_url']  ?? null,
                    ]
                );
            }

            DB::commit();

            if ($r->hasFile('image') && $old) $this->deleteFile($old);
            if ($r->hasFile('image_mobile') && $oldM) $this->deleteFile($oldM);

            return $r->ajax()
                ? response()->json(['ok' => true, 'message' => 'Slider updated'])
                : redirect()->route('admin.sliders.index')->with('ok', 'Slider updated');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return $this->failJsonOrBack($r, 'Update failed.', $e);
        }
    }

    public function destroy(Request $r, Slider $slider)
    {
        try {
            $img = $slider->image;
            $imgM = $slider->image_mobile;

            $slider->translations()->delete();
            $slider->delete();

            if ($img)  $this->deleteFile($img);
            if ($imgM) $this->deleteFile($imgM);

            return $r->ajax()
                ? response()->json(['ok' => true])
                : back()->with('ok', 'Deleted');

        } catch (\Throwable $e) {
            return $this->failJsonOrBack($r, 'Delete failed.', $e);
        }
    }

    // ========================================================================
    // IMAGE HELPERS (PUBLIC_PATH STORAGE + 1MB RULE)
    // ========================================================================

    protected function saveSliderImage(\Illuminate\Http\UploadedFile $file, int $w, int $h): string
    {
        $dir = public_path('storage/uploads/sliders');
        if (!is_dir($dir)) mkdir($dir, 0775, true);

        $originalSize = $file->getSize();
        $ext = strtolower($file->getClientOriginalExtension());

        // load & crop
        $img = $this->images->read($file->getRealPath())->cover($w, $h);
        $binary = null;

        if ($originalSize < 1_000_000) {
            // keep original extension
            $ext = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? $ext : 'jpg';
            $filename = 'slider_' . uniqid() . "_{$w}x{$h}.$ext";

            if ($ext === 'png')      $binary = (string) $img->toPng();
            elseif ($ext === 'webp') $binary = (string) $img->toWebp(90);
            else                     $binary = (string) $img->toJpeg(90);

        } else {
            // convert to webp (bigger than 1MB)
            $filename = 'slider_' . uniqid() . "_{$w}x{$h}.webp";
            $binary = (string) $img->toWebp(80);
        }

        file_put_contents("$dir/$filename", $binary);

        return "uploads/sliders/$filename";
    }

    protected function saveMobileImage(\Illuminate\Http\UploadedFile $file): string
    {
        $dir = public_path('storage/uploads/sliders');
        if (!is_dir($dir)) mkdir($dir, 0775, true);

        $originalSize = $file->getSize();
        $ext = strtolower($file->getClientOriginalExtension());

        $img = $this->images->read($file->getRealPath())->scaleDown(width: 768);
        $binary = null;

        if ($originalSize < 1_000_000) {
            $ext = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? $ext : 'jpg';
            $filename = 'slider_mobile_' . uniqid() . "_w768.$ext";

            if ($ext === 'png')      $binary = (string) $img->toPng();
            elseif ($ext === 'webp') $binary = (string) $img->toWebp(90);
            else                     $binary = (string) $img->toJpeg(90);

        } else {
            $filename = 'slider_mobile_' . uniqid() . "_w768.webp";
            $binary = (string) $img->toWebp(80);
        }

        file_put_contents("$dir/$filename", $binary);

        return "uploads/sliders/$filename";
    }

    protected function deleteFile(?string $path): void
    {
        $abs = public_path($path);
        if ($path && file_exists($abs)) {
            unlink($abs);
        }
    }

    protected function failJsonOrBack(Request $r, string $msg, \Throwable $e)
    {
        return $r->ajax()
            ? response()->json(['ok' => false, 'message' => $msg, 'error' => $e->getMessage()], 500)
            : back()->withErrors(['error' => $msg])->withInput();
    }

    public function reorder(Request $r)
    {
        $r->validate([
            'order' => 'required|array',
            'order.*' => 'integer',
        ]);

        DB::transaction(function () use ($r) {
            foreach ($r->order as $i => $id) {
                Slider::whereKey($id)->update(['sort' => $i * 10]);
            }
        });

        return response()->json(['ok' => true, 'message' => 'Order updated']);
    }
}
