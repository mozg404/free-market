<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

class AvatarChanger
{
    public function change(User $user, UploadedFile $image): void
    {
        $user->clearMediaCollection($user::MEDIA_COLLECTION_AVATAR);
        $user->addMedia($image)->toMediaCollection($user::MEDIA_COLLECTION_AVATAR);
    }
}