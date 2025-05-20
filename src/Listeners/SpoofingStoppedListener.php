<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Ermakk\MoonshineUserSpoofing\Enums\State;
use Ermakk\MoonshineUserSpoofing\Events\SpoofingStopped;


class SpoofingStoppedListener implements ShouldQueue
{

    public function handle(SpoofingStopped $event): void
    {
        $event->target->changeLogs()->create([
            'moonshine_user_id' => $event->source->getAuthIdentifier(),
            'states_before' => State::SPOOFING_STARTED->value,
            'states_after' => State::SPOOFING_STOPPED->value,
        ]);
    }

    public function shouldQueue(SpoofingStopped $event): bool
    {
        return method_exists($event->target, 'changeLogs');
    }
}
