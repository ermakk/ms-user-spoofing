<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Enums;

use Ermakk\MoonshineUserSpoofing\Services\Settings;

enum State: string
{
    case SPOOFING_STARTED = 'spoofing_started';
    case SPOOFING_STOPPED = 'spoofing_stopped';

    public function toString(): string
    {
        return match($this) {
            self::SPOOFING_STOPPED => __(Settings::ALIAS.'.enum.spoofing_stopped'),
            self::SPOOFING_STARTED => __(Settings::ALIAS.'.enum.spoofing_started'),
        };
    }
}
