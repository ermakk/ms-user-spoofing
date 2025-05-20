<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Services;

class Settings
{
    final public const ALIAS = 'user-spoofing';

    /**
     * @return class-string
     */
    public static function userClass(): string
    {
        return config(self::ALIAS.'.auth.user.model', config('auth.providers.users.model'));
    }

    public static function key(): string
    {
        return config(self::ALIAS.'.key');
    }

    public static function defaultGuard(): string
    {
        return config('auth.defaults.guard', 'web');
    }

    public static function moonShineGuard(): string
    {
        if (config('moonshine.auth.enable', false) === false) {
            return config('auth.defaults.guard');
        }

        return config(
            'moonshine.auth.guard',
            config('auth.defaults.guard')
        );
    }

    public static function spoofingSessionKey(): string
    {
        return 'source-user-spoofing-id';
    }

    public static function spoofingSessionGuardKey(): string
    {
        return 'source-user-spoofing-guard';
    }
}
