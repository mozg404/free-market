<?php

namespace App\Support\Filepond;

use Illuminate\Support\Facades\Storage;

class ImageStub
{
    public const PUBLIC_ASSET_IMAGE_PATH = 'assets/img/image_not_found.png';

    public null $id = null;

    public function delete(): void
    {}

    public function isExists(): bool
    {
        return false;
    }

    public function getUrl(): string
    {
        return asset(self::PUBLIC_ASSET_IMAGE_PATH);
    }
}