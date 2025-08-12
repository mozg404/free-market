<?php

namespace App\Http\Controllers\My;

use App\Data\User\UserData;
use App\Http\Controllers\Controller;
use App\Services\Toaster;
use App\Support\Image;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster,
    ) {
    }

    public function index()
    {
        $user = Auth::user();

        return Inertia::render('my/SettingsIndexPage', [
            'user' => UserData::from($user),
        ]);
    }

    public function changeAvatar(Request $request)
    {
        $data = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,svg|max:5120',
        ]);

        Auth::user()->changeAvatar(Image::createFromUploadedFile($data['avatar']));
        $this->toaster->success('Новый аватар установлен');

        return Inertia::location(route('my.settings'));
    }
}
