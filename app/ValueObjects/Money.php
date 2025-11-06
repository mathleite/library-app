<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\ValueObjects\Exceptions\InvalidMoneyException;
use TypeError;

readonly class Money
{
    /**
     * @param int $amount
     * @param string $currency
     * @throws InvalidMoneyException|TypeError
     */
    public function __construct(
        public int    $amount,
        public string $currency = 'BRL'
    ) {
        $this->validateAmount($this->amount);
    }

    /** @return string */
    public function withCurrency(): string
    {
        return match ($this->currency) {
            'BRL' => 'R$ ' . number_format($this->toFloat(), 2, ',', '.'),
            'USD' => '$ ' . number_format($this->toFloat(), 2),
            default => $this->amount . ' ' . $this->currency,
        };
    }

    /** @return float */
    public function toFloat(): float
    {
        return $this->amount / 100;
    }

    /**
     * @param int $amount
     * @return void
     * @throws InvalidMoneyException
     */
    private function validateAmount(int $amount): void
    {
        if ($amount < 0) {
            throw new InvalidMoneyException();
        }
    }
}
