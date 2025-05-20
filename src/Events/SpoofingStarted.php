<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class SpoofingStarted
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly Authenticatable $source,
        public readonly Authenticatable $target,
    ) {
        //
    }
}
