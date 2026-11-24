<?php
// Sets the founder portrait, only if none was uploaded from the admin panel yet.
declare(strict_types=1);
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
$root = '/home/restdevelopments/public_html';
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
$path = $argv[1];
$row = DB::table('founder_settings')->first();
if (!$row) { echo "No founder record\n"; exit(1); }
if ($row->image) { echo "Founder already has an image ($row->image), left unchanged\n"; exit(0); }
DB::table('founder_settings')->where('id', $row->id)->update(['image' => $path, 'updated_at' => now()]);
echo "Founder image set to $path\n";
