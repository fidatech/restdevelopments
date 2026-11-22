<?php
// app/Http/Middleware/AdminEnglish.php
namespace App\Http\Middleware;
use Closure;

class AdminEnglish {
  public function handle($request, Closure $next) {
    app()->setLocale('en');
    return $next($request);
  }
}
