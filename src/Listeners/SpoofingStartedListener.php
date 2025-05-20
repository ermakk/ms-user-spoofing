<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Ermakk\MoonshineUserSpoofing\Enums\State;
use Ermakk\MoonshineUserSpoofing\Events\SpoofingStarted;

class SpoofingStartedListener implements ShouldQueue
{
    public function handle(SpoofingStarted $event): void
    {
        $event->target->changeLogs()->create([
            'moonshine_user_id' => $event->source->getAuthIdentifier(),
            'states_before' => State::SPOOFING_STOPPED->value,
            'states_after' => State::SPOOFING_STARTED->value,
        ]);
    }

    public function shouldQueue(SpoofingStarted $event): bool
    {
        return method_exists($event->target, 'changeLogs');
    }
}
