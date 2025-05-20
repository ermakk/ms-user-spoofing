<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Ermakk\MoonshineUserSpoofing\Events\SpoofingStopped;
use Ermakk\MoonshineUserSpoofing\Services\SpoofingManager;
use Ermakk\MoonshineUserSpoofing\Services\Settings;


final readonly class StopAction
{
    public function __construct(
        private SpoofingManager $manager
    ) {
        //
    }

    public function handle(): bool
    {
        $user = $this->manager->getUserFromSession();

        if (!$user instanceof Authenticatable) {
            return false;
        }

        Auth::guard(Settings::defaultGuard())->quietLogout();
        $this->manager->clearAuthFromSession();

        SpoofingStopped::dispatch($this->manager->moonshineUser, $user);

        return true;
    }
}
