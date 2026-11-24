<?php
// Read only: prints the projects, their photos and the amenities.
declare(strict_types=1);
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
$root = '/home/restdevelopments/public_html';
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
foreach (DB::table('projects')->orderBy('order')->orderBy('id')->get() as $p) {
    $tr = DB::table('project_translations')->where('project_id', $p->id)->pluck('title', 'locale');
    echo "PROJECT {$p->id} slug={$p->slug} state={$p->state} featured={$p->featured} status={$p->status} order={$p->order} location={$p->location} price={$p->price_starts_from} beds={$p->beds} baths={$p->baths} area={$p->area} image={$p->image}\n";
    echo "  titles ".json_encode($tr, JSON_UNESCAPED_UNICODE)."\n";
    $en = DB::table('project_translations')->where('project_id', $p->id)->where('locale', 'en')->first();
    echo "  desc ".mb_substr(strip_tags((string) ($en->description ?? '')), 0, 160)."\n";
    foreach (DB::table('project_galleries')->where('project_id', $p->id)->orderBy('order')->get() as $g) echo "  gallery {$g->id} {$g->type} order={$g->order} {$g->image}\n";
    echo "  units ".DB::table('project_units')->where('project_id', $p->id)->count()." docs ".DB::table('project_documents')->where('project_id', $p->id)->count()." features ".DB::table('project_features')->where('project_id', $p->id)->count()."\n";
}
foreach (DB::table('project_amenities')->orderBy('order')->get() as $a) {
    echo "AMENITY {$a->id} status={$a->status} image={$a->image} icon={$a->icon} ".json_encode(DB::table('project_amenity_translations')->where('project_amenity_id', $a->id)->pluck('title', 'locale'), JSON_UNESCAPED_UNICODE)."\n";
}
foreach (DB::table('sliders')->get() as $s) echo "SLIDER {$s->id} status={$s->status} image={$s->image} mobile={$s->image_mobile} video={$s->video_url}\n";
