<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Http\Requests;

use Ermakk\MoonshineUserSpoofing\Services\Contracts\BeSpoofable;
use Ermakk\MoonshineUserSpoofing\Services\Contracts\Spoofable;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Ermakk\MoonshineUserSpoofing\Services\SpoofingManager;
use Ermakk\MoonshineUserSpoofing\Services\Settings;
use MoonShine\Laravel\MoonShineAuth;

class StopFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return
            MoonShineAuth::getGuard()->check()
            && auth(Settings::moonShineGuard())->user() instanceof BeSpoofable
            && auth(Settings::moonShineGuard())->user() instanceof Spoofable
            && auth(Settings::moonShineGuard())->user()?->canBeSpoofed()
            && auth(Settings::moonShineGuard())->user()?->canSpoofed();
    }
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (!app(SpoofingManager::class)->isSpoofing()) {
                $validator->errors()->add(
                    Settings::key(),
                    __(Settings::ALIAS.'::validation.stop.is_not_spoofing')
                );
            }
        });
    }
}
