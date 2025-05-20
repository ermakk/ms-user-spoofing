<?php

declare(strict_types=1);

namespace Ermakk\MoonshineUserSpoofing\Http\Controllers;

use Ermakk\MoonshineUserSpoofing\Services\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Ermakk\MoonshineUserSpoofing\Actions\StartAction;
use Ermakk\MoonshineUserSpoofing\Actions\StopAction;
use Ermakk\MoonshineUserSpoofing\Http\Requests\StartFormRequest;
use Ermakk\MoonshineUserSpoofing\Http\Requests\StopFormRequest;
use MoonShine\Laravel\Http\Controllers\MoonShineController;
use MoonShine\Laravel\MoonShineUI;
use MoonShine\Support\Enums\ToastType;


class SpoofingController extends MoonShineController
{
    /**
     * Enter impersonate action
     * */
    public function start(StartFormRequest $request, StartAction $action): Redirector|RedirectResponse
    {
        try {
            $result = $action->handle(id: $request->safe()->id, shouldValidate: false);

            if (!$result) {
                if(config(Settings::ALIAS.'.show_notification', false)) {
                    MoonShineUI::toast(message: __(Settings::ALIAS.'::validation.start.cannot_be_spoofed'), type: ToastType::ERROR);
                }
            }

            if(config(Settings::ALIAS.'.show_notification', false)) {
                MoonShineUI::toast(message: __(Settings::ALIAS.'::ui.buttons.start.message'), type: ToastType::SUCCESS);
            }

        } catch (\Exception $exception) {
            MoonShineUI::toast(message: $exception->getMessage(), type: ToastType::ERROR);
        }
        return redirect(config(Settings::ALIAS.'.success_redirect_to', '/'));
    }

    /**
     * Stop impersonate action
     * */
    public function stop(StopFormRequest $request, StopAction $action): Redirector|RedirectResponse
    {
        $result = $action->handle();

        if (!$result) {
            if(config(Settings::ALIAS.'.show_notification', false)) {
                MoonShineUI::toast(message: __(Settings::ALIAS.'::validation.stop.is_not_spoofing'), type: ToastType::ERROR);
            }
            return redirect()->back();
        }

        if(config(Settings::ALIAS.'.show_notification', false)) {
            MoonShineUI::toast(message: __(Settings::ALIAS.'::ui.buttons.stop.message'), type: ToastType::SUCCESS);
        }

        return redirect(config(Settings::ALIAS.'.success_redirect_to', '/'));
    }
}
