<?php

/**
 * Renders every page of the admin panel with staged templates and the live data, signed in as the
 * first user, without writing anything. Each page is saved as HTML in the output folder, and each
 * DataTables feed of the panel is checked to answer with JSON.
 *
 *   php validate-admin.php <staged views> <output folder>
 */

declare(strict_types=1);

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

[$script, $stagedViews, $out] = $argv;
$root = '/home/restdevelopments/public_html';
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();
// staged helpers (new functions only; the live ones stay as they are)
if (is_file(dirname($stagedViews, 2).'/app/Support/helpers.php')) {
    require_once dirname($stagedViews, 2).'/app/Support/helpers.php';
}
$app->make('view')->getFinder()->setPaths([$stagedViews, $root.'/resources/views']);
$app->make('view')->getFinder()->flush();
// compiled templates of the staged views go to a folder of their own
$compiled = $out.'/compiled';
@mkdir($compiled, 0700, true);
config(['view.compiled' => $compiled]);
$app->forgetInstance('blade.compiler');
$app->forgetInstance('view.engine.resolver');
$app->forgetInstance('view');
$app->make('view')->getFinder()->setPaths([$stagedViews, $root.'/resources/views']);
@mkdir($out, 0700, true);

// nothing a page does may stay in the database
\Illuminate\Support\Facades\DB::beginTransaction();

$failed = 0;
$fetch = function (string $uri, bool $ajax = false, ?string $save = null) use ($app, $out, &$failed) {
    $request = Request::create('https://restdevelopments.com'.$uri, 'GET');
    if ($ajax) {
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->headers->set('Accept', 'application/json');
    }
    $app->instance('request', $request);
    try {
        $response = $app->make('router')->dispatch($request);
        $status = $response->getStatusCode();
        $body = (string) $response->getContent();
        $ok = $status === 200;
        if ($ajax) {
            // a page that has no feed answers with its HTML; only a server error counts
            $ok = $status < 500;
            $status .= json_decode($body) !== null ? ' json' : ' html';
        } elseif ($ok) {
            $ok = str_contains($body, 'admin-ui/css/app.css');
        }
        if ($save) {
            file_put_contents("$out/$save.html", $body);
        }
        printf("%-60s %s %s%s\n", ($ajax ? '[ajax] ' : '').$uri, $ok ? 'OK    ' : 'FAILED', $status, $ok ? '' : ' '.substr(strip_tags($body), 0, 300));
        if (! $ok) {
            $failed++;
        }
    } catch (Throwable $e) {
        $failed++;
        printf("%-60s ERROR  %s: %s (%s:%d)\n", $uri, get_class($e), substr($e->getMessage(), 0, 400), basename($e->getFile()), $e->getLine());
    }
};

// signed out: the sign-in page
$fetch('/login', false, 'login');

$user = App\Models\User::query()->orderBy('id')->first();
if (! $user) {
    echo "No user to sign in with\n";
    exit(1);
}
$app['auth']->guard('web')->setUser($user);

$first = fn (string $model) => class_exists($model) ? $model::query()->orderBy('id')->value('id') : null;
$project = $first(App\Models\Project::class);
$slider = $first(App\Models\Slider::class);
$blog = $first(App\Models\Blog::class);
$team = $first(App\Models\Team::class);
$item = $first(App\Models\WhyChooseUsItem::class);
$client = $first(App\Models\Client::class);
$category = $first(App\Models\BlogCategory::class);

$pages = [
    'dashboard' => '/admin/dashboard',
    'sliders' => '/admin/sliders',
    'slider-create' => '/admin/sliders/create',
    'slider-edit' => $slider ? "/admin/sliders/$slider/edit" : null,
    'counters' => '/admin/home-counters',
    'about-settings' => '/admin/about/settings',
    'about-sections' => '/admin/about/sections',
    'founder' => '/admin/about/founder',
    'projects' => '/admin/projects',
    'units' => $project ? "/admin/projects/$project/units" : null,
    'documents' => $project ? "/admin/projects/$project/documents" : null,
    'gallery' => $project ? "/admin/projects/$project/gallery" : null,
    'amenities' => '/admin/amenities',
    'blog-categories' => '/admin/blog-categories',
    'blogs' => '/admin/blogs',
    'blog-edit' => $blog ? "/admin/blogs/$blog/edit" : null,
    'why-us' => '/admin/why-us',
    'why-items' => '/admin/why-us/items',
    'why-item-create' => '/admin/why-us/items/create',
    'why-item-edit' => $item ? "/admin/why-us/items/$item/edit" : null,
    'team' => '/admin/team',
    'team-create' => '/admin/team/create',
    'team-edit' => $team ? "/admin/team/$team/edit" : null,
    'clients' => '/admin/clients',
    'client-create' => '/admin/clients/create',
    'client-edit' => $client ? "/admin/clients/$client/edit" : null,
    'contact' => '/admin/contact',
    'careers' => '/admin/careers',
    'static-pages' => '/admin/static-pages',
];
foreach ($pages as $name => $uri) {
    if ($uri) {
        $fetch($uri, false, $name);
    }
}

$dt = 'draw=1&start=0&length=10';
$feeds = [
    "/admin/projects?$dt",
    "/admin/amenities?$dt",
    "/admin/amenities/datatable?$dt",
    "/admin/blogs?$dt",
    "/admin/blog-categories?$dt",
    "/admin/home-counters?$dt",
    "/admin/about/sections?$dt",
    "/admin/careers?$dt",
    "/admin/clients?$dt",
];
if ($project) {
    $feeds[] = "/admin/projects/$project/units/datatable?$dt";
    $feeds[] = "/admin/projects/$project/documents/datatable?$dt";
    $feeds[] = "/admin/projects/$project/gallery/datatable?$dt";
}
foreach ($feeds as $uri) {
    $fetch($uri, true);
}

\Illuminate\Support\Facades\DB::rollBack();
echo $failed ? "$failed failed\n" : "All admin pages rendered\n";
exit($failed ? 1 : 0);
