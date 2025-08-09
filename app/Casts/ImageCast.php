<?php

namespace App\Casts;

use App\Support\Image;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ImageCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_string($value) && Image::exists($value)) {
            return Image::from($value);
        }

        return null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof Image) {
            return $value->getRelativePath();
        }

        if (is_string($value) && Image::exists($value)) {
            return $value;
        }

        return null;
    }
}
