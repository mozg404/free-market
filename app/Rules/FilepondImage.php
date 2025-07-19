<?php

namespace App\Rules;

use App\Support\Filepond\Image;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FilepondImage implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value)) {
            if (!Image::exists($value)) {
                $fail('Изображение не найдено');
            }
        }
    }
}
