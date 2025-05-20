<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Actions;

use Ermakk\MoonshineUserSpoofing\Events\SpoofingStarted;
use Ermakk\MoonshineUserSpoofing\Services\SpoofingManager;
use MoonShine\Laravel\MoonShineAuth;

final readonly class StartAction
{
    public function __construct(
        private SpoofingManager $manager
    ) {
        //
    }

    public function handle(int $id, bool $shouldValidate = true): bool
    {
        $user = $this->manager->findUserById($id);

        if ($shouldValidate && !$this->manager->canStart($user)) {
            return false;
        }


        $this->manager->saveAuthInSession($user);
        MoonShineAuth::getGuard()->login($user);
        session()->regenerate();

        SpoofingStarted::dispatch($this->manager->moonshineUser, $user);

        return true;
    }
}
