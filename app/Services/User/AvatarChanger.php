<?php

namespace App\Services\User;

use App\Models\User;
use App\Support\Image;

class AvatarChanger
{
    public function change(User $user, Image $image): void
    {
        $user->avatar = $image->publishIfTemporary();
        $user->save();
    }
}