<?php

namespace App\Data;

use App\Support\Filepond\Image;
use Spatie\LaravelData\Data;

class ImageData extends Data
{
    public function __construct(
        public bool $isExists,
        public string|null $url = null,
    ) {}

    public static function fromNull(null $null): self
    {
        return new self(false);
    }

    public static function fromString(string $id): self
    {
        if (Image::exists($id)) {
            return new self(true, Image::from($id)->getUrl());
        }

        return new self(false);
    }

    public static function fromObject(Image $image): self
    {
        return new self(true, $image->getUrl());
    }
}
