<?php

declare(strict_types=1);

namespace App\Data;

use App\Support\Filepond\Image;
use App\Support\Filepond\ImageStub;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public string $imageUrl;
    public string $url;

    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public float $price,
        public Image|ImageStub $image,
    )
    {
        $this->imageUrl = $this->image->getUrl();
        $this->url = route('product.show', $this->id);
    }
}
