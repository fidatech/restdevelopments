<?php
// Generate small, versioned slide images from existing CMS uploads. Originals remain untouched.
declare(strict_types=1);

$root = $argv[1] ?? dirname(__DIR__);
chdir($root);
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

if (!function_exists('imagewebp')) {
    fwrite(STDERR, "GD WebP unavailable; original slide images remain in use.\n");
    exit(0);
}

$dir = $root.'/public/assets/img/slider';
if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
    throw new RuntimeException('Cannot create slider image directory');
}

$slides = App\Models\Slider::where('status', 'published')->ordered()->take(4)->get();
foreach ($slides as $slide) {
    foreach (['desktop' => $slide->image, 'mobile' => $slide->image_mobile ?: $slide->image] as $variant => $relative) {
        if (!$relative) continue;
        $source = $root.'/public/storage/'.$relative;
        if (!is_file($source)) continue;
        $hash = substr(sha1($relative), 0, 10);
        $output = $dir.'/slide-'.$slide->id.'-'.$hash.'-'.$variant.'.webp';
        if (is_file($output)) continue;
        $info = @getimagesize($source);
        if (!$info) continue;
        $image = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_WEBP => @imagecreatefromwebp($source),
            IMAGETYPE_PNG => @imagecreatefrompng($source),
            default => false,
        };
        if (!$image) continue;
        $width = imagesx($image);
        $height = imagesy($image);
        $maxWidth = $variant === 'desktop' ? 920 : 640;
        $ratio = min(1, $maxWidth / $width);
        $targetWidth = (int) round($width * $ratio);
        $targetHeight = (int) round($height * $ratio);
        $resized = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
        $temporary = tempnam($dir, '.slide-');
        if (!$temporary || !imagewebp($resized, $temporary, $variant === 'desktop' ? 62 : 60)) {
            throw new RuntimeException('Cannot save optimized slide image');
        }
        chmod($temporary, 0644);
        rename($temporary, $output);
        imagedestroy($resized);
        imagedestroy($image);
        echo basename($output).' '.filesize($output)." bytes\n";
    }
}
