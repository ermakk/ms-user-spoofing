<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\UI\ActionButtons;

use Ermakk\MoonshineUserSpoofing\Services\Settings;
use Illuminate\Contracts\Auth\Authenticatable;
use Ermakk\MoonshineUserSpoofing\Services\SpoofingManager;
use MoonShine\UI\Components\ActionButton;

final class SpoofingActionButton
{
    public static function resolve(): ActionButton
    {
        return ActionButton::make(
            label: __(Settings::ALIAS.'::ui.buttons.start.label'),
            url: static fn (mixed $data): string => route(Settings::ALIAS.'.enter', [
                config(Settings::ALIAS.'.resource_item_key') => $data->getKey(),
            ]),
        )
            ->canSee(
                callback: fn (Authenticatable $item): bool => app(SpoofingManager::class)->canStart($item),
            )
            ->icon(config(Settings::ALIAS.'.buttons.start.icon'));
    }
}
