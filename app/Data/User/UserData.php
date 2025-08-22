<?php

namespace App\Data\User;

use App\Models\User;
use App\Support\Image;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public int $balance,
        public ?Image $avatar,
        public Carbon $created_at,
        public ?string $avatar_url = null,
        public ?float $rating = null,
        public ?float $positive_feedback_count = null,
        public ?float $negative_feedback_count = null,
    ) {
        if (isset($avatar)) {
            $this->avatar_url = $this->avatar->getUrl();
        }
    }
}
