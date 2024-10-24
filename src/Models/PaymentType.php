<?php

namespace App\Models;

use InvalidArgumentException;

class PaymentType
{
    const int VIRTUAL_ACCOUNT = 1;
    const int CREDIT_CARD = 2;

    /**
     * Convert integer payment type to string.
     *
     * @param int $type
     * @return string
     */
    public static function toString(int $type): string
    {
        return match ($type) {
            self::VIRTUAL_ACCOUNT => 'virtual_account',
            self::CREDIT_CARD => 'credit_card',
            default => '',
        };
    }

    /**
     * Convert string payment type to integer.
     *
     * @param string $type
     * @return int
     * @throws InvalidArgumentException
     */
    public static function fromString(string $type): int
    {
        return match ($type) {
            'virtual_account' => self::VIRTUAL_ACCOUNT,
            'credit_card' => self::CREDIT_CARD,
            default => throw new InvalidArgumentException("Invalid payment type: $type"),
        };
    }
}