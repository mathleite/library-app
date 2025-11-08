<?php

declare(strict_types=1);

use App\Domain\Exceptions\InvalidMoneyException;
use App\Domain\ValueObjects\Money;

test('should throw InvalidMoneyException with negative values', function (mixed $values) {
    new Money($values);
})->throws(InvalidMoneyException::class)
    ->with([
        'negative amount' => -100,
        'large negative amount' => -03313131,
    ]);

test('should throw TypeError with invalid types', function (mixed $values) {
    new Money($values);
})->throws(TypeError::class)
    ->with([
        'string amount' => '1000',
        'object amount' => (object)['amount' => 1000],
        'null amount' => null,
        'boolean amount' => true,
    ]);

test('should return a formatted string with currency', function (int $amount, string $currency, string $expected) {
    $money = new Money($amount, $currency);
    expect($money->withCurrency())->toBe($expected);
})->with([
    'brazilian currency' => [123456, 'BRL', 'R$ 1.234,56'],
    'us dollar currency' => [123456, 'USD', '$ 1,234.56'],
    'euro currency' => [123456, 'EUR', '123456 EUR']
]);
