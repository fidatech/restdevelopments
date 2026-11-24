<?php

// Rebuilds the Rest Complex project with the new photos.
// Saves everything it changes to a JSON backup first. Old image files stay on disk.
// Usage: php rebuild-rest-complex.php <manifest.json> <folder under uploads, e.g. uploads/projects/2/rc-2026>

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

[$script, $manifestFile, $folder] = $argv;
$root = '/home/restdevelopments/public_html';
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$m = json_decode(file_get_contents($manifestFile), true, 512, JSON_THROW_ON_ERROR);
$folder = trim($folder, '/');
foreach (array_merge([$m['main']], $m['exterior'], $m['interior'], $m['highlights']) as $f) {
    if (! is_file("$root/public/storage/$folder/$f")) { fwrite(STDERR, "Missing uploaded file $folder/$f\n"); exit(1); }
}

// Find the projects by their English titles, not by id
$titleOf = fn ($id) => DB::table('project_translations')->where('project_id', $id)->where('locale', 'en')->value('title');
$complex = DB::table('projects')->get()->first(fn ($p) => preg_match('/^Rest Complex\b/i', (string) $titleOf($p->id)) && ! preg_match('/\sat\s/i', (string) $titleOf($p->id)));
$tower = DB::table('projects')->get()->first(fn ($p) => preg_match('/^Rest Tower\b/i', (string) $titleOf($p->id)));
if (! $complex) { fwrite(STDERR, "Rest Complex not found\n"); exit(1); }
echo "Rest Complex is project {$complex->id} (slug {$complex->slug}); Rest Tower is ".($tower ? "project {$tower->id} (slug {$tower->slug})" : 'not found')."\n";

$featureIds = DB::table('project_features')->where('project_id', $complex->id)->pluck('id');
$backupDir = getenv('HOME').'/restdevelopments-backups';
@mkdir($backupDir, 0700, true);
$backup = $backupDir.'/rest-complex-before-rebuild-'.date('Ymd-His').'.json';
file_put_contents($backup, json_encode([
    'projects' => DB::table('projects')->whereIn('id', array_filter([$complex->id, $tower?->id]))->get(),
    'project_galleries' => DB::table('project_galleries')->where('project_id', $complex->id)->get(),
    'project_features' => DB::table('project_features')->where('project_id', $complex->id)->get(),
    'project_feature_translations' => DB::table('project_feature_translations')->whereIn('project_feature_id', $featureIds)->get(),
    'slider_translations' => DB::table('slider_translations')->get(['id', 'slider_id', 'locale', 'button_url']),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
chmod($backup, 0600);
echo "Backup: $backup\n";

$now = now();
$highlightText = [
    'rc-hl-garden' => ['Central garden and fountains', 'A landscaped courtyard with palm trees, fountains and shaded seating at the heart of the complex, away from the street.'],
    'rc-hl-playground' => ['Children’s play park', 'Green, safe play areas with climbing towers and slides, overlooked by the homes around them.'],
    'rc-hl-mall' => ['Shopping mall', 'A multi-level mall with an open atrium and escalators, a few steps from your front door.'],
    'rc-hl-retail' => ['Retail boulevard', 'Shops and showrooms along the street front, including a large electronics store, with parking at the door.'],
];

DB::transaction(function () use ($complex, $tower, $m, $folder, $now, $highlightText) {
    // 1. Addresses: Rest Complex had "rest-tower", Rest Tower had "duplix-in-termiz"
    $oldComplexSlug = $complex->slug;
    if ($oldComplexSlug !== 'rest-complex' && ! DB::table('projects')->where('slug', 'rest-complex')->exists()) {
        DB::table('projects')->where('id', $complex->id)->update(['slug' => 'rest-complex']);
        echo "Rest Complex address: $oldComplexSlug -> rest-complex\n";
    }
    if ($tower && $tower->slug !== 'rest-tower' && ! DB::table('projects')->where('slug', 'rest-tower')->exists()) {
        DB::table('projects')->where('id', $tower->id)->update(['slug' => 'rest-tower']);
        echo "Rest Tower address: {$tower->slug} -> rest-tower\n";
    }
    // slider buttons that pointed at the old addresses
    foreach (DB::table('slider_translations')->whereNotNull('button_url')->get() as $st) {
        $url = $st->button_url;
        $new = preg_replace('#/projects/'.preg_quote($oldComplexSlug, '#').'(?=$|[/?\#])#', '/projects/rest-complex', $url);
        if ($tower) $new = preg_replace('#/projects/'.preg_quote($tower->slug, '#').'(?=$|[/?\#])#', '/projects/rest-tower', $new);
        if ($new !== $url) {
            DB::table('slider_translations')->where('id', $st->id)->update(['button_url' => $new]);
            echo "Slider {$st->slider_id} ({$st->locale}) button: $url -> $new\n";
        }
    }

    // 2. Main photo, shown in the launches and as the flagship
    DB::table('projects')->where('id', $complex->id)->update([
        'image' => "$folder/{$m['main']}", 'featured' => true, 'updated_at' => $now,
    ]);

    // 3. Gallery: exterior first, then interiors
    DB::table('project_galleries')->where('project_id', $complex->id)->delete();
    $order = 1;
    foreach (['exterior', 'interior'] as $type) {
        foreach ($m[$type] as $file) {
            DB::table('project_galleries')->insert([
                'project_id' => $complex->id, 'image' => "$folder/$file", 'type' => $type, 'order' => $order++,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }
    echo 'Gallery: '.($order - 1)." photos\n";

    // 4. Highlights
    $ids = DB::table('project_features')->where('project_id', $complex->id)->pluck('id');
    DB::table('project_feature_translations')->whereIn('project_feature_id', $ids)->delete();
    DB::table('project_features')->where('project_id', $complex->id)->delete();
    foreach ($m['highlights'] as $i => $file) {
        $key = preg_replace('/_\d+x\d+\.webp$/', '', $file);
        [$title, $text] = $highlightText[$key];
        $fid = DB::table('project_features')->insertGetId([
            'project_id' => $complex->id, 'type' => 'amenity', 'icon_set' => 'image', 'icon' => null,
            'image' => "$folder/$file", 'order' => $i + 1, 'created_at' => $now, 'updated_at' => $now,
        ]);
        DB::table('project_feature_translations')->insert([
            'project_feature_id' => $fid, 'locale' => 'en', 'title' => $title, 'description' => $text,
            'created_at' => $now, 'updated_at' => $now,
        ]);
        echo "Highlight: $title\n";
    }
});

foreach (['en', 'fa', 'ps', 'uz'] as $l) Cache::forget("site.home.projects.$l");
Cache::forget('site.menu.launches');
echo "Done\n";
