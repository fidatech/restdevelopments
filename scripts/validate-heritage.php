<?php

declare(strict_types=1);

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

[$script, $stagedViews, $uri] = $argv;
$root = '/home/restdevelopments/public_html';
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$request = Request::create('https://restdevelopments.com'.$uri, 'GET');
$app->instance('request', $request);
$kernel->bootstrap();
$finder = $app->make('view')->getFinder();
$finder->setPaths([$stagedViews, $root.'/resources/views']);
$finder->flush();
echo 'Template: '.$finder->find('home.index').PHP_EOL;
echo 'Layout: '.$finder->find('layouts.heritage').PHP_EOL;
echo 'Factory: '.$app->make('view')->getFinder()->find('home.index').PHP_EOL;
$details = [
    '/__project' => [App\Models\Project::class, '/en/projects/'],
    '/__blog' => [App\Models\Blog::class, '/en/blog/'],
    '/__career' => [App\Models\Vacancy::class, '/en/careers/'],
];
if (isset($details[$uri])) {
    [$model, $prefix] = $details[$uri];
    $slug = $model::query()->value('slug');
    if (!$slug) { echo $uri.' no content to validate'.PHP_EOL; exit(0); }
    $uri = $prefix.rawurlencode($slug);
    $request = Request::create('https://restdevelopments.com'.$uri, 'GET');
    $app->instance('request', $request);
}

try {
    $response = $app->make('router')->dispatch($request);
    $html = $response->getContent();
    $valid = $response->getStatusCode() === 200
        && str_contains((string) $html, 'assets/css/heritage.css')
        && str_contains((string) $html, 'socialRedirectModal');
    preg_match_all('/<link[^>]+stylesheet[^>]*>/i', (string) $html, $styleTags);
    echo 'Stylesheets: '.implode(' | ', array_slice($styleTags[0], 0, 3)).PHP_EOL;
    echo $uri.' HTTP '.$response->getStatusCode().' CSS '.(str_contains((string) $html, 'assets/css/heritage.css') ? 'yes' : 'no').' popup '.(str_contains((string) $html, 'socialRedirectModal') ? 'yes' : 'no').' theme '.($valid ? 'OK' : 'FAILED').PHP_EOL;

    if (!$valid) {
        $log = $root.'/storage/logs/laravel.log';
        if (is_file($log)) {
            $tail = file_get_contents($log, false, null, max(0, filesize($log) - 65536));
            preg_match_all('/^\\[20[^\\n]+\\] [^\\n]*ERROR: [^\\n]+/m', (string) $tail, $matches);
            echo 'Recent error: '.substr((string) (end($matches[0]) ?: ''), 0, 500).PHP_EOL;
            $compiled = Illuminate\\Support\\Facades\\Blade::compileString(file_get_contents($stagedViews.'/home/index.blade.php'));
            foreach (array_slice(explode(PHP_EOL, $compiled), 0, 12) as $lineNumber => $line) echo 'Blade '.($lineNumber + 1).': '.$line.PHP_EOL;
        }
    }
    exit($valid ? 0 : 1);
} catch (Throwable $exception) {
    fwrite(STDERR, $uri.' '.get_class($exception).': '.$exception->getMessage().PHP_EOL);
    exit(1);
}
