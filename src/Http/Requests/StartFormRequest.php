<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Http\Requests;

use Ermakk\MoonshineUserSpoofing\Rules\CanBeSpoofed;
use Ermakk\MoonshineUserSpoofing\Services\Contracts\BeSpoofable;
use Ermakk\MoonshineUserSpoofing\Services\Contracts\Spoofable;
use Ermakk\MoonshineUserSpoofing\Services\Settings;
use Ermakk\MoonshineUserSpoofing\Rules\CanSpoofed;
use Ermakk\MoonshineUserSpoofing\Rules\IsNotSpoofing;
use MoonShine\Laravel\Http\Requests\MoonShineFormRequest;
use MoonShine\Laravel\MoonShineAuth;

class StartFormRequest extends MoonShineFormRequest
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
    protected $stopOnFirstFailure = true;

    /**
     * @return array{id: IsNotSpoofing[]|CanSpoofed[]|CanBeSpoofed[]|string[]}
     */
    public function rules(): array
    {
        return [
            'id' => [
                'bail',
//                new IsNotSpoofing(),
//                new CanSpoofed(),
                'required',
                'int',
                'gt:0',
//                new CanBeSpoofed(),
            ],
        ];
    }

    /**
     * @return array{id: string}
     */
    public function attributes(): array
    {
        return [
            'id' => __(Settings::ALIAS.'::validation.start.id'),
        ];
    }


    protected function prepareForValidation(): void
    {
        $key = config(Settings::ALIAS.'.resource_item_key');

        if ($this->has($key)) {
            $this->merge([
                'id' => (int)$this->get($key),
            ]);
        }
    }
}
