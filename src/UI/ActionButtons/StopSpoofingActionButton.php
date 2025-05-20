<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\UI\ActionButtons;

use Ermakk\MoonshineUserSpoofing\Services\SpoofingManager;
use Ermakk\MoonshineUserSpoofing\Services\Settings;
use MoonShine\UI\Components\ActionButton;

final class StopSpoofingActionButton
{
    public static function resolve(): ActionButton
    {
        return ActionButton::make(
            label: __(Settings::ALIAS.'::ui.buttons.stop.label'),
            url: static fn (mixed $data): string => route(Settings::ALIAS.'.stop'),
        )
            ->canSee(
                callback: fn (): bool => app(SpoofingManager::class)->canStop(),
            )
            ->icon(config(Settings::ALIAS.'.buttons.stop.icon'));
    }
}
