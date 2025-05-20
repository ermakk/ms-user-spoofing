<?php

declare(strict_types=1);

use Ermakk\MoonShineUserSpoofing\Http\Controllers\SpoofingController;
use Ermakk\MoonShineUserSpoofing\Services\Settings;
use Illuminate\Support\Facades\Route;
use MoonShine\Laravel\Http\Middleware\ChangeLocale;

$middlewares = config(Settings::ALIAS.'.routes.middleware');
$middlewares[] = ChangeLocale::class;
$middlewares[] = moonshineConfig()->getAuthMiddleware();

Route::controller(SpoofingController::class)
    ->name(Settings::ALIAS.'.')
    ->prefix(config(Settings::ALIAS.'.routes.prefix'))
    ->middleware($middlewares)
    ->group(function (): void {
        Route::get('/start', 'start')->name('start');
        Route::get('/stop', 'stop')->name('stop');
    });
