<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->segment(1);

        if (in_array($locale, ['en', 'es'])) {
            App::setLocale($locale);
            URL::defaults(['locale' => $locale]);
            
            // Forget the parameter so it doesn't need to be injected into controllers
            $request->route()->forgetParameter('locale');
        } else {
            // Fallback default locale
            App::setLocale('es');
            URL::defaults(['locale' => 'es']);
        }

        return $next($request);
    }
}
