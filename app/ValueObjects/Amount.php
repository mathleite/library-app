<?php

namespace App\ValueObjects;

readonly class Amount
{
    public function __construct(
        public int    $amount,
        public string $currency = 'BRL'
    ) {
    }

    public function withCurrency(): string
    {
        return match ($this->currency) {
            'BRL' => 'R$ '.number_format($this->toFloat(), 2, ',', '.'),
            'USD' => '$ '.number_format($this->toFloat(), 2),
            default => $this->amount.' '.$this->currency,
        };
    }

    public function toFloat(): float
    {
        return $this->amount / 100;
    }
}
