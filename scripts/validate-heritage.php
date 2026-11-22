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
$finder = $app->make('view.finder');
$finder->setPaths([$stagedViews, $root.'/resources/views']);
$finder->flush();
echo 'Template: '.$finder->find('home.index').PHP_EOL;
echo 'Layout: '.$finder->find('layouts.heritage').PHP_EOL;
echo 'Factory: '.$app->make('view')->getFinder()->find('home.index').PHP_EOL;

try {
    $response = $app->make('router')->dispatch($request);
    $html = $response->getContent();
    $valid = $response->getStatusCode() === 200
        && str_contains((string) $html, 'assets/css/heritage.css')
        && str_contains((string) $html, 'socialRedirectModal');
    preg_match_all('/<link[^>]+stylesheet[^>]*>/i', (string) $html, $styleTags);
    echo 'Stylesheets: '.implode(' | ', array_slice($styleTags[0], 0, 3)).PHP_EOL;
    echo $uri.' HTTP '.$response->getStatusCode().' CSS '.(str_contains((string) $html, 'assets/css/heritage.css') ? 'yes' : 'no').' popup '.(str_contains((string) $html, 'socialRedirectModal') ? 'yes' : 'no').' theme '.($valid ? 'OK' : 'FAILED').PHP_EOL;

    exit($valid ? 0 : 1);
} catch (Throwable $exception) {
    fwrite(STDERR, $uri.' '.get_class($exception).': '.$exception->getMessage().PHP_EOL);
    exit(1);
}
