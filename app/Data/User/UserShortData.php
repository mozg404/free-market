<?php

namespace App\Data\User;

use App\Models\User;
use App\Support\Image;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class UserShortData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?Image $avatar,
        public ?string $avatar_url = null,
        public Carbon $created_at,
    ) {
        if (isset($avatar)) {
            $this->avatar_url = $this->avatar->getUrl();
        }
    }
}
