<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;

class Inn
{
    public string $number;

    private function __construct(string $number)
    {
        $this->number = $number;
    }

    public static function create(string|int $number): self
    {
        if (!self::isValid($number)) {
            throw new InvalidArgumentException('Incorrect INN number');
        }

        return self::restore((string)$number);
    }

    public static function restore(string|int $number): self
    {
        return new self((string)$number);
    }

    public function __toString(): string
    {
        return $this->number;
    }

    public static function isValid(string $number): bool
    {
        $len = strlen($number);

        return in_array($len, [10, 12]) && ctype_digit($number);
    }

    public static function random(): self
    {
        return self::restore(rand(1000000000, 9999999999));
    }
}