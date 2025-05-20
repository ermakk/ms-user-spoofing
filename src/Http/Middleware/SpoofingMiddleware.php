<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ermakk\MoonshineUserSpoofing\Services\SpoofingManager;
use Ermakk\MoonshineUserSpoofing\Services\Settings;

class SpoofingMiddleware
{
    public function handle(Request $request, \Closure $next): mixed
    {
        if (!Auth::guard(Settings::moonShineGuard())->user() || !$request->hasSession()) {
            return $next($request);
        }

        $session = $request->session();
        $key = Settings::key();

        if (!$session->has($key) || empty($session->get($key))) {
            return $next($request);
        }

        $user = app(SpoofingManager::class)->getUserFromSession();
        Auth::guard(Settings::defaultGuard())->quietLogin($user);

        return $next($request);
    }
}
