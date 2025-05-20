<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Ermakk\MoonshineUserSpoofing\Services\Contracts\BeSpoofable;
use Ermakk\MoonshineUserSpoofing\Services\Contracts\Spoofable;


final class SpoofingManager
{
    private ?Authenticatable $user = null;

    public function __construct(
        public readonly Authenticatable $moonshineUser,
    ) {
        //
    }

    public function findUserById(int $id): Authenticatable
    {
        if ($this->user instanceof Authenticatable && $this->user->getAuthIdentifier() === $id) {
            return $this->user;
        }

        $userModelClass = Settings::userClass();
        $this->user = $userModelClass::query()->findOrFail($id);

        return $this->user;
    }

    public function getUserFromSession(): ?Authenticatable
    {
        try {
            $id = session()->get(Settings::key());
        } catch (\Throwable) {
            return null;
        }

        return $id === null ? null : $this->findUserById($id);
    }

    public function canStart(Authenticatable $user): bool
    {
        if (!$this->canSpoofed()) {
            return false;
        }

        return $this->canBeSpoofed($user);
    }

    public function canStop(): bool
    {
        if (!$this->isSpoofing()) {
            return false;
        }

        return $this->canSpoofed();
    }

    public function isSpoofing(): bool
    {
        return session()->has(Settings::key());
    }

    public function canSpoofed(): bool
    {
        return $this->moonshineUser instanceof Spoofable || $this->moonshineUser->canSpoofed();
    }

    public function canBeSpoofed(Authenticatable $user): bool
    {
        return $user instanceof BeSpoofable || $user->canBeSpoofed();
    }

    public function saveAuthInSession(Authenticatable $user): void
    {
        session([
            Settings::key() => $user->getAuthIdentifier(),
            Settings::spoofingSessionKey() => $this->moonshineUser->getAuthIdentifier(),
            Settings::spoofingSessionGuardKey() => Settings::moonShineGuard(),
        ]);
    }

    public function clearAuthFromSession(): void
    {
        session()->forget([
            Settings::key(),
            Settings::spoofingSessionKey(),
            Settings::spoofingSessionGuardKey(),
        ]);
    }
}
