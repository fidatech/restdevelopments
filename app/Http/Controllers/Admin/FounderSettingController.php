<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FounderSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FounderSettingController extends Controller
{
    public function index()
    {
        $locales = config('app.locales', ['en', 'fa', 'ps', 'uz']);
        $setting = FounderSetting::with('translations')->first();
        return view('admin.founder.index', compact('setting', 'locales'));
    }

    public function store(Request $request)
    {
        $locales = config('app.locales', ['en', 'fa', 'ps', 'uz']);
        $default = config('app.fallback_locale', 'en');

        $request->validate([
            'status' => 'nullable|boolean',
            'image' => 'nullable|image|max:25600',
            'signature' => 'nullable|image|max:10240',
            'remove_signature' => 'nullable|boolean',
            "translations.$default.name" => 'required|string|max:255',
            "translations.$default.message" => 'required|string',
            'translations.*.name' => 'nullable|string|max:255',
            'translations.*.position' => 'nullable|string|max:255',
            'translations.*.quote' => 'nullable|string|max:1000',
            'translations.*.message' => 'nullable|string',
            'translations.*.biography' => 'nullable|string',
        ]);

        $setting = FounderSetting::first() ?: new FounderSetting();
        $oldImage = $setting->image;
        $oldSignature = $setting->signature;

        DB::beginTransaction();
        try {
            $setting->status = $request->boolean('status', true);
            if ($request->hasFile('image')) {
                $setting->image = $this->saveImage($request->file('image'), 'founder', 900, 1125);
            }
            if ($request->hasFile('signature')) {
                $setting->signature = $this->saveSignature($request->file('signature'));
            } elseif ($request->boolean('remove_signature')) {
                $setting->signature = null;
            }
            $setting->save();

            foreach ($locales as $loc) {
                $in = fn ($field) => $request->input("translations.$loc.$field");
                $empty = collect(['position', 'quote', 'message', 'biography'])
                    ->every(fn ($f) => trim(strip_tags((string) $in($f))) === '');
                if ($loc !== $default && $empty) {
                    // nothing written in this language: the English version is shown instead
                    $setting->translations()->where('locale', $loc)->delete();
                    continue;
                }
                $setting->translations()->updateOrCreate(['locale' => $loc], [
                    'name' => $in('name') ?: $request->input("translations.$default.name"),
                    'position' => $in('position'),
                    'quote' => $in('quote'),
                    'message' => $in('message'),
                    'biography' => $in('biography'),
                ]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return $request->ajax()
                ? response()->json(['ok' => false, 'message' => 'Failed to save the founder page.'], 500)
                : back()->withErrors(['error' => 'Failed to save the founder page.'])->withInput();
        }

        if ($request->hasFile('image') && $oldImage) $this->deleteFile($oldImage);
        if (($request->hasFile('signature') || $request->boolean('remove_signature')) && $oldSignature) $this->deleteFile($oldSignature);

        return $request->ajax()
            ? response()->json(['ok' => true, 'message' => 'Founder page saved'])
            : back()->with('ok', 'Founder page saved');
    }

    protected function saveImage($file, string $prefix, int $w, int $h): string
    {
        File::ensureDirectoryExists(public_path('storage/uploads/founder'), 0755, true);
        $img = (new ImageManager(new Driver()))->read($file->getRealPath());
        if ($img->width() > 4000) $img = $img->scaleDown(4000);
        $name = $prefix.'_'.uniqid().'_'.$w.'x'.$h.'.webp';
        file_put_contents(public_path('storage/uploads/founder/'.$name), (string) $img->cover($w, $h)->toWebp(85));
        return 'uploads/founder/'.$name;
    }

    protected function saveSignature($file): string
    {
        File::ensureDirectoryExists(public_path('storage/uploads/founder'), 0755, true);
        $img = (new ImageManager(new Driver()))->read($file->getRealPath());
        if ($img->width() > 800) $img = $img->scaleDown(800);
        $name = 'signature_'.uniqid().'.png';
        file_put_contents(public_path('storage/uploads/founder/'.$name), (string) $img->toPng());
        return 'uploads/founder/'.$name;
    }

    protected function deleteFile(string $path): void
    {
        foreach ([public_path('storage/'.$path), public_path($path)] as $full) {
            if (is_file($full)) @unlink($full);
        }
    }
}
