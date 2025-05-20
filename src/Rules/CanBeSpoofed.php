<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Ermakk\MoonshineUserSpoofing\Services\SpoofingManager;
use Ermakk\MoonshineUserSpoofing\Services\Settings;

class CanBeSpoofed implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $manager = app(SpoofingManager::class);
        $user = $manager->findUserById($value);

        if (!$manager->canBeSpoofed($user)) {
            $fail(Settings::ALIAS.'::validation.start.cannot_be_spoofed')->translate();
        }
    }
}
