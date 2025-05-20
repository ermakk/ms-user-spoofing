<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Rules;

use Closure;
use Ermakk\MoonshineUserSpoofing\Services\Settings;
use Illuminate\Contracts\Validation\ValidationRule;
use Ermakk\MoonshineUserSpoofing\Services\SpoofingManager;

class IsNotSpoofing implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $manager = app(SpoofingManager::class);

        if ($manager->isSpoofing()) {
            $fail(Settings::ALIAS.'::validation.start.is_spoofing')->translate();
        }
    }
}
