<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Ermakk\MoonshineUserSpoofing\Services\SpoofingManager;
use Ermakk\MoonshineUserSpoofing\Services\Settings;

class CanSpoofed implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $manager = app(SpoofingManager::class);

        if (!$manager->canSpoofing()) {
            $fail(Settings::ALIAS.'::validation.start.cannot_spoofing')->translate();
        }
    }
}
