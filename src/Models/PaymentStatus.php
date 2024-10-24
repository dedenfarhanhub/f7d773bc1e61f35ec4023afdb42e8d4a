<?php

namespace App\Models;

use InvalidArgumentException;

class PaymentStatus
{
    const int PENDING = 1;
    const int PAID = 2;
    const int FAILED = 3;
    const int EXPIRED = 4;

    /**
     * Convert integer payment type to string.
     *
     * @param int $status
     * @return string
     */
    public static function toString(int $status): string
    {
        return match ($status) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::FAILED => 'Failed',
            self::EXPIRED => 'Expired',
            default => '',
        };
    }

    /**
     * Convert string payment type to integer.
     *
     * @param string $status
     * @return int
     * @throws InvalidArgumentException
     */
    public static function fromString(string $status): int
    {
        return match ($status) {
            'pending' => self::PENDING,
            'paid' => self::PAID,
            'failed' => self::FAILED,
            'expired' => self::EXPIRED,
            default => throw new InvalidArgumentException("Invalid payment status: $status"),
        };
    }
}