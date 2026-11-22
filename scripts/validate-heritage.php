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

try {
    $response = $kernel->handle($request);
    $html = $response->getContent();
    $valid = $response->getStatusCode() === 200
        && str_contains((string) $html, 'assets/css/heritage.css')
        && str_contains((string) $html, 'socialRedirectModal');
    echo $uri.' HTTP '.$response->getStatusCode().' theme '.($valid ? 'OK' : 'FAILED').PHP_EOL;
    $kernel->terminate($request, $response);
    exit($valid ? 0 : 1);
} catch (Throwable $exception) {
    fwrite(STDERR, $uri.' '.get_class($exception).': '.$exception->getMessage().PHP_EOL);
    exit(1);
}
