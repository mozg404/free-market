<?php

namespace App\Support;

/**
 * Тип касса (без сервиса)
 */
class Sandbox
{
    /**
     * @throws \JsonException
     */
    public static function createId(int $paymentId, int $amount): string
    {
        return base64_encode(json_encode([
            'id' => $paymentId,
            'amount' => $amount,
        ], JSON_THROW_ON_ERROR));
    }

    public static function generateUrlToPay(string $externalId): string
    {
        return route('sandbox', $externalId);
    }
}