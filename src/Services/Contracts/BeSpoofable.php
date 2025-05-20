<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Services\Contracts;

/**
 * @author Losev Ivan
 */
interface BeSpoofable
{
    public function canBeSpoofed(): bool;
}
