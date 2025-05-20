<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\UI\Components;

use Ermakk\MoonshineUserSpoofing\Services\SpoofingManager;
use Ermakk\MoonshineUserSpoofing\Services\Settings;
use MoonShine\UI\Components\MoonShineComponent;

/**
 *
 * @method static static make(string $route = null, string $label = null, string $icon = null, string $class = null)
 */
final class StopSpoofing extends MoonShineComponent
{
    public function __construct(
        private readonly ?string $route = null,
        private readonly ?string $label = null,
        private readonly ?string $icon = null,
        private readonly ?string $class = null,
    ) {
        parent::__construct();
    }

    public function getView(): string
    {
        return $this->customView ?? Settings::ALIAS.'::components.spoofing-panel';
    }

    /**
     * @return array{canStop: bool, route: string, label: string, icon: string, class: string}
     */
    protected function viewData(): array
    {
        return [
            'canStop' => app(SpoofingManager::class)->canStop(),
            'route' => $this->route ?? route(Settings::ALIAS.'.stop'),
            'label' => $this->label ?? __(Settings::ALIAS.'::ui.buttons.stop.label'),
            'icon' => $this->icon ?? config(Settings::ALIAS.'.buttons.stop.icon'),
            'class' => $this->class ?? config(Settings::ALIAS.'.buttons.stop.class'),
        ];
    }
}
