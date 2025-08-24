<?php

namespace App\Http\Controllers\My\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\MySettings\ChangeAvatarRequest;
use App\Services\Toaster;
use App\Services\User\AvatarChanger;
use App\Support\Image;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ChangeAvatarController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('common/ImageUploaderModal', [
            'imageUrl' => auth()->user()->avatar->getUrl(),
            'aspectRatio' => 1,
            'saveRoute' => route('my.settings.change.avatar.update'),
        ]);
    }

    public function update(
        ChangeAvatarRequest $request,
        AvatarChanger $avatarChanger,
        Toaster $toaster,
    ): RedirectResponse {
        $avatarChanger->change(
            auth()->user(),
            Image::createFromUploadedFile($request->file('image'))
        );
        $toaster->success('Аватар обновлен');

        return redirect()->back();
    }
}
