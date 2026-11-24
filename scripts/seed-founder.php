<?php

// Adds the founder page content once. Placeholder text, to be replaced from the admin panel.
// Does nothing if a founder record already exists.

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$root = '/home/restdevelopments/public_html';
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

if (DB::table('founder_settings')->exists()) {
    echo "Founder record already exists, nothing seeded\n";
    exit(0);
}

$now = now();
$id = DB::table('founder_settings')->insertGetId([
    'status' => true, 'image' => null, 'signature' => null, 'created_at' => $now, 'updated_at' => $now,
]);

$message = <<<'HTML'
<p>When I founded Rest Developments, I wanted to build more than buildings. I wanted to create places where families feel safe, where businesses can grow, and where people are proud to come home.</p>
<p>Over the past decade we have grown from a single project into a company working across Afghanistan and Uzbekistan. Through every stage, our principles have stayed the same: build with quality, keep our promises, and treat every buyer as a partner for the long term.</p>
<p>Rest Complex in Termiz and Rest Tower in Kabul show what we believe a modern community should be: homes, workplaces, shopping and wellness, planned together so that daily life is easier. We design for the families who will live there for many years, not only for the day we hand over the keys.</p>
<p>None of this would be possible without the trust of our customers, the dedication of our team, and the support of our partners. To everyone who has chosen Rest, thank you. We will keep working to deserve that trust.</p>
<p>I invite you to visit our projects, meet our team, and see for yourself what we are building.</p>
HTML;

$biography = <<<'HTML'
<p>Habibullah Ahmadzai is the founder of Rest Developments. He leads the company's vision and long-term strategy, and oversees its residential and mixed-use projects in Afghanistan and Uzbekistan.</p>
<p>Under his leadership, Rest Developments has grown into a developer known for modern design, reliable delivery and communities planned around everyday life.</p>
HTML;

DB::table('founder_setting_translations')->insert([
    'founder_setting_id' => $id,
    'locale' => 'en',
    'name' => 'Habibullah Ahmadzai',
    'position' => 'Founder',
    'quote' => 'We build places where families feel safe, businesses grow, and people are proud to come home.',
    'message' => $message,
    'biography' => $biography,
    'created_at' => $now,
    'updated_at' => $now,
]);

echo "Founder seeded (id $id)\n";
