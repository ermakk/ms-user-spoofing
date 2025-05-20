<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Services\Contracts;

/**
 * @author Losev Ivan
 */
interface Spoofable
{
    public function canSpoofed(): bool;
}
