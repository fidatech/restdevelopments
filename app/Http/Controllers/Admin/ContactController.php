<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ContactController extends Controller
{
    protected ImageManager $images;

    public function __construct()
    {
        $this->images = new ImageManager(new Driver());
    }

    public function edit()
    {
        $locales = config('app.locales', ['en', 'ps', 'fa', 'uz']);
        $setting = ContactSetting::with('translations')->first() ?: new ContactSetting();
        return view('admin.contact.form', compact('setting', 'locales'));
    }

    public function update(Request $request)
    {
        $locales = config('app.locales', ['en', 'ps', 'fa', 'uz']);

        $data = $request->validate([
            'email_1'   => 'nullable|email',
            'email_2'   => 'nullable|email',
            'phone_1'   => 'nullable|string',
            'phone_2'   => 'nullable|string',
            'facebook'  => 'nullable|url',
            'twitter'   => 'nullable|url',
            'linkedin'  => 'nullable|url',
            'instagram' => 'nullable|url',
            'youtube'   => 'nullable|url',
            'map_iframe' => 'nullable|string',
            'logo_dark'  => 'nullable|image',
            'logo_white' => 'nullable|image',
        ]);

        $setting = ContactSetting::first() ?: new ContactSetting();

        // Handle iframe
        $rawIframe = $data['map_iframe'] ?? null;
        unset($data['map_iframe']);
        $setting->fill($data);

        if ($rawIframe) {
            if (preg_match('/src="([^"]+)"/', $rawIframe, $m)) {
                $setting->map_iframe = $m[1];
            } else {
                $setting->map_iframe = $rawIframe;
            }
        } else {
            $setting->map_iframe = null;
        }

        // Upload logos
        foreach (['logo_dark', 'logo_white'] as $field) {
            if ($request->file($field)) {
                if ($setting->$field && is_file(public_path($setting->$field))) {
                    @unlink(public_path($setting->$field));
                }
                $setting->$field = $this->processImage($request->file($field));
            }
        }

        $setting->save();

        // translations
        foreach ($locales as $loc) {
            $setting->translations()->updateOrCreate(
                ['locale' => $loc],
                [
                    'company_intro' => $request->input("translations.$loc.company_intro"),
                    'address'       => $request->input("translations.$loc.address"),
                ]
            );
        }

        return back()->with('success', 'Saved');
    }

    /*───────────────────────────────────────────────
     | Updated Logo Processor (public/uploads/contact)
     | - Resize to width 400 (proportional)
     | - If >1MB → compress WEBP (80)
     | - If ≤1MB → keep original format (90)
     ───────────────────────────────────────────────*/

    private function processImage(\Illuminate\Http\UploadedFile $file)
    {
        $dir = public_path('storage/uploads/contact');
        File::ensureDirectoryExists($dir, 0755, true);

        $origSize = $file->getSize();
        $origExt  = strtolower($file->getClientOriginalExtension());

        $img = $this->images->read($file->getRealPath())->scale(width: 400);

        // Large → compress to WEBP
        if ($origSize > 1_000_000) {
            $name = 'contact_'.uniqid().'.webp';
            file_put_contents($dir.'/'.$name, (string) $img->toWebp(80));
            return 'uploads/contact/'.$name;
        }

        // Small → keep original safe extension
        $ext = in_array($origExt, ['jpg','jpeg','png','webp']) ? $origExt : 'jpg';
        $name = 'contact_'.uniqid().'.'.$ext;
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

        return 'uploads/contact/'.$name;
    }

    public function publicIndex()
    {
        $setting = ContactSetting::with('translations')->first();
        $locales = config('app.locales');
        return view('pages.contact', compact('setting', 'locales'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string',
            'email'   => 'required|email',
            'phone'   => 'required|string',
            'subject' => 'required|string',
            'message' => 'nullable|string',
        ]);

        $fullPhone = $request->input('country_code', '') .' '. $data['phone'];

        $body = "
            Name: {$data['name']}\n
            Email: {$data['email']}\n
            Phone: {$fullPhone}\n
            Subject: {$data['subject']}\n
            Message: {$data['message']}
        ";

        Mail::raw($body, function ($mail) {
            $mail->to('info@restdevelopments.com')
                 ->subject('Website Contact Message');
        });

        return back()->with('success', 'Message sent successfully.');
    }
}
