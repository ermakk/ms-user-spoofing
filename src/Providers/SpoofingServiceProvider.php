<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Providers;

use Ermakk\MoonshineUserSpoofing\Events\SpoofingStarted;
use Ermakk\MoonshineUserSpoofing\Events\SpoofingStopped;
use Ermakk\MoonshineUserSpoofing\Guards\SessionGuard;
use Ermakk\MoonshineUserSpoofing\Listeners\SpoofingStartedListener;
use Ermakk\MoonshineUserSpoofing\Listeners\SpoofingStoppedListener;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Application;
use Ermakk\MoonshineUserSpoofing\Actions\StartAction;
use Ermakk\MoonshineUserSpoofing\Actions\StopAction;
use Ermakk\MoonshineUserSpoofing\Http\Middleware\SpoofingMiddleware;
use Ermakk\MoonshineUserSpoofing\Services\SpoofingManager;
use Ermakk\MoonshineUserSpoofing\Services\Settings;
use Illuminate\Support\ServiceProvider;


class SpoofingServiceProvider extends ServiceProvider
{

    protected $listen = [
        SpoofingStarted::class => [
            SpoofingStartedListener::class,
        ],
        SpoofingStopped::class => [
            SpoofingStoppedListener::class,
        ],
    ];

    public function register(): void
    {
        $this->app->singleton(
            SpoofingManager::class,
            fn (): SpoofingManager => new SpoofingManager(auth(Settings::moonShineGuard())->user())
        );

        $this->app->bind(
            StartAction::class,
            fn (): StartAction => new StartAction(app(SpoofingManager::class))
        );

        $this->app->bind(
            StopAction::class,
            fn (): StopAction => new StopAction(app(SpoofingManager::class))
        );

        $this->mergeConfigFrom(__DIR__.'/../../config/'.Settings::ALIAS.'.php', Settings::ALIAS);

        $this->registerAuthDriver();
    }

    public function boot(Kernel $kernel): void
    {
        $this->registerMiddleware($kernel);

        $this->registerViews();

        $this->publishImpersonateResources();

        $this->loadRoutesFrom(__DIR__ . '/../../routes/'.Settings::ALIAS.'.php');

        $this->loadTranslationsFrom(__DIR__.'/../../lang', Settings::ALIAS);
    }

    private function registerMiddleware(Kernel $kernel): void
    {
        app('router')->aliasMiddleware(Settings::ALIAS, SpoofingMiddleware::class);

        foreach (config(Settings::ALIAS.'.routes.middleware') as $group) {
            $kernel->appendMiddlewareToGroup($group, SpoofingMiddleware::class);
        }
    }

    private function registerAuthDriver(): void
    {
        $auth = app('auth');

        $auth->extend('session', function (Application $app, string $name, array $config) use ($auth): SessionGuard {
            $provider = $auth->createUserProvider($config['provider']);

            $guard = new SessionGuard($name, $provider, $app['session.store']);

            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($app['cookie']);
            }

            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($app['events']);
            }

            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            }

            return $guard;
        });
    }

    private function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', Settings::ALIAS);

        if ($this->app->runningUnitTests()) {
            $this->loadViewsFrom(__DIR__.'/../../tests/Stubs/resources/views', 'moonshine');
        }
    }

    private function publishImpersonateResources(): void
    {
        if (!$this->app->runningInConsole()) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        // Configuration
        $this->publishes(
            [
                __DIR__.'/../../config/'.Settings::ALIAS.'.php' => config_path(Settings::ALIAS.'.php'),
            ],
            [
                Settings::ALIAS,
                'config',
            ]
        );

        // Localization
        $this->publishes(
            [
                __DIR__.'/../../lang' => $this->app->langPath('vendor/'.Settings::ALIAS)
            ],
            [
                Settings::ALIAS,
                'lang',
            ]
        );
    }
}
