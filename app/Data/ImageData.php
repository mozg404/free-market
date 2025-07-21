<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ImageData extends Data
{
    public function __construct(
        public bool $isExists,
        public string|null $url = null,
    ) {}
}
