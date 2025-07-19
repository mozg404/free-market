<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class Phone implements Arrayable
{
    readonly public string $number;
    private PhoneNumber $phone;

    private function __construct(PhoneNumber $phone)
    {
        $this->phone = $phone;
        $this->number = PhoneNumberUtil::getInstance()->format($this->phone, PhoneNumberFormat::E164);
    }

    public function __toString(): string
    {
        return $this->get();
    }

    public function toArray(): array
    {
        return [
            'number' => $this->number,
            'formatted' => $this->withFormatted()
        ];
    }

    public function get(): string
    {
        return $this->number;
    }

    public function withFormatted(): string
    {
        preg_match('/^\+7(\d{3})(\d{3})(\d{2})(\d{2})$/', $this->number, $matches);

        return sprintf(
            "+7 (%s) %s-%s-%s",
            $matches[1],
            $matches[2],
            $matches[3],
            $matches[4]
        );
    }

    /**
     * @throws NumberParseException
     */
    public static function restore(string $number): self
    {
        return new self(PhoneNumberUtil::getInstance()->parse($number, 'RU'));
    }

    /**
     * @throws NumberParseException
     * @throws InvalidArgumentException
     */
    public static function create(string $number): self
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $parsedPhone = $phoneUtil->parse($number, 'RU');

        if (!$phoneUtil->isValidNumber($parsedPhone)) {
            throw new InvalidArgumentException('Incorrect phone number');
        }

        return new self($parsedPhone);
    }

    public static function random(): self
    {
        return self::restore('+7'.rand(900,920).rand(1000000, 9999999));
    }
}