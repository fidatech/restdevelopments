<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Interfaces\ImageInterface;

trait ProcessesImages
{
    protected ImageManager $images;

    protected function img(): ImageManager
    {
        return $this->images ??= new ImageManager(new Driver());
    }

    /**
     * Save WEBP under the public web root:
     *   $dir:  'uploads', 'about/banners', 'project/covers', etc. (no leading slash)
     *   return: relative web path like 'uploads/abc.webp' to be used with asset()
     */
    protected function putWebpPublic(ImageInterface $image, string $dir, string $name, int $q = 72): string
    {
        // normalize directory (no leading/trailing slashes)
        $dir = trim($dir, '/');

        // absolute target directory (inside public/)
        $absDir = public_path($dir);
        File::ensureDirectoryExists($absDir, 0755, true);

        $relative = $dir . '/' . $name . '.webp';           // web path for DB
        $absolute = public_path($relative);                 // full path for writing

        // write file (Intervention v3)
        file_put_contents($absolute, (string) $image->toWebp($q));

        return $relative; // store this in DB; render via asset($relative)
    }

    /**
     * Read, optionally scale, cover-crop, and write a WEBP under public/
     * Returns a relative web path like 'uploads/xyz_123_1604x864.webp'
     */
    protected function processCover(UploadedFile $file, int $w, int $h, string $dir, string $prefix): string
    {
        $img = $this->img()->read($file->getRealPath());

        // safety clamp for very large uploads
        if ($img->width() > 5000) {
            $img = $img->scaleDown(5000);
        }

        $out = $img->cover($w, $h);

        $name = $prefix . '_' . uniqid() . "_{$w}x{$h}";

        return $this->putWebpPublic($out, $dir, $name);
    }
}
