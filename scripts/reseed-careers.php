<?php

// Replaces the careers (vacancies) with five past positions, closed last year.
// The current rows are saved to a JSON file first, so they can be put back.

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$root = '/home/restdevelopments/public_html';
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$backupDir = getenv('HOME').'/restdevelopments-backups';
@mkdir($backupDir, 0700, true);
$backup = $backupDir.'/careers-before-reseed-'.date('Ymd-His').'.json';
file_put_contents($backup, json_encode([
    'vacancies' => DB::table('vacancies')->get(),
    'vacancy_translations' => DB::table('vacancy_translations')->get(),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
chmod($backup, 0600);
echo 'Backup: '.$backup.' ('.DB::table('vacancies')->count().' vacancies, '.DB::table('vacancy_translations')->count().' translations)'.PHP_EOL;

$ul = fn (array $items) => '<ul><li>'.implode('</li><li>', $items).'</li></ul>';

$roles = [
    [
        'slug' => 'site-engineer-civil-termiz', 'deadline' => '2025-03-15', 'posted' => '2025-02-10',
        'title' => 'Site Engineer (Civil)', 'department' => 'Construction', 'location' => 'Termiz, Uzbekistan', 'type' => 'Full-time',
        'description' => '<p>We were looking for a civil site engineer to supervise daily construction work at Rest Complex, Termiz, and keep the works on programme, on quality and safe.</p>',
        'responsibilities' => $ul(['Supervise structural and finishing works on site', 'Check work against drawings and specifications', 'Coordinate subcontractors and daily site reports', 'Enforce health and safety rules on site']),
        'requirements' => $ul(['Bachelor’s degree in civil engineering', '3+ years of site experience on residential or commercial buildings', 'Good command of AutoCAD and reading structural drawings', 'Uzbek or Russian; English is a plus']),
    ],
    [
        'slug' => 'sales-executive-kabul', 'deadline' => '2025-05-31', 'posted' => '2025-04-20',
        'title' => 'Sales Executive', 'department' => 'Sales', 'location' => 'Kabul, Afghanistan', 'type' => 'Full-time',
        'description' => '<p>We were looking for a sales executive to guide buyers through our apartments at Rest Tower, from the first visit to signing.</p>',
        'responsibilities' => $ul(['Meet buyers and present available units', 'Follow up leads from calls, WhatsApp and social media', 'Prepare offers and payment plans with the sales manager', 'Keep the customer records up to date']),
        'requirements' => $ul(['2+ years in real estate or other sales', 'Fluent Dari and Pashto; English is a plus', 'Friendly, organised and confident with clients']),
    ],
    [
        'slug' => 'project-manager-termiz', 'deadline' => '2025-07-20', 'posted' => '2025-06-15',
        'title' => 'Project Manager', 'department' => 'Projects', 'location' => 'Termiz, Uzbekistan', 'type' => 'Full-time',
        'description' => '<p>We were looking for a project manager to lead the delivery of a mixed-use phase of Rest Complex, from planning through handover.</p>',
        'responsibilities' => $ul(['Plan the programme, budget and resources for the phase', 'Lead the site, design and procurement teams', 'Report progress, costs and risks to management', 'Manage handover to buyers and tenants']),
        'requirements' => $ul(['Degree in engineering, architecture or construction management', '7+ years in construction, 3+ leading projects', 'Experience with Primavera or MS Project', 'English and Russian or Uzbek']),
    ],
    [
        'slug' => 'quantity-surveyor-kabul', 'deadline' => '2025-09-10', 'posted' => '2025-08-05',
        'title' => 'Quantity Surveyor', 'department' => 'Commercial', 'location' => 'Kabul, Afghanistan', 'type' => 'Full-time',
        'description' => '<p>We were looking for a quantity surveyor to manage costs and contracts on our Kabul projects.</p>',
        'responsibilities' => $ul(['Prepare bills of quantities and cost estimates', 'Measure work done and check contractor invoices', 'Track variations and the project budget', 'Support tendering and contract negotiations']),
        'requirements' => $ul(['Degree in quantity surveying or civil engineering', '3+ years of experience as a QS', 'Strong Excel skills', 'Dari or Pashto and English']),
    ],
    [
        'slug' => 'marketing-coordinator-kabul', 'deadline' => '2025-11-30', 'posted' => '2025-10-25',
        'title' => 'Marketing Coordinator', 'department' => 'Marketing', 'location' => 'Kabul, Afghanistan', 'type' => 'Full-time',
        'description' => '<p>We were looking for a marketing coordinator to run our social media, launches and sales materials.</p>',
        'responsibilities' => $ul(['Plan and publish content on our social media channels', 'Coordinate photography, video and brochures for launches', 'Track leads from campaigns with the sales team', 'Keep the website news and projects up to date']),
        'requirements' => $ul(['2+ years in marketing or social media', 'Good writing in Dari and English', 'Experience with Canva, Photoshop or similar', 'Real estate experience is a plus']),
    ],
];

DB::transaction(function () use ($roles) {
    DB::table('vacancy_translations')->delete();
    DB::table('vacancies')->delete();
    foreach ($roles as $r) {
        $id = DB::table('vacancies')->insertGetId([
            'deadline' => $r['deadline'], 'salary' => null, 'slug' => $r['slug'], 'status' => 'active',
            'created_at' => $r['posted'].' 09:00:00', 'updated_at' => $r['deadline'].' 18:00:00',
        ]);
        DB::table('vacancy_translations')->insert([
            'vacancy_id' => $id, 'locale' => 'en', 'title' => $r['title'], 'department' => $r['department'],
            'location' => $r['location'], 'type' => $r['type'], 'description' => $r['description'],
            'requirements' => $r['requirements'], 'responsibilities' => $r['responsibilities'],
        ]);
    }
});

foreach (DB::table('vacancies')->orderBy('deadline')->get() as $v) {
    echo $v->id.'  '.$v->deadline.'  '.$v->slug.PHP_EOL;
}
