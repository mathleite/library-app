<?php

declare(strict_types=1);

use App\ValueObjects\Exceptions\InvalidMoneyException;
use App\ValueObjects\Money;

test('should throw InvalidMoneyException with negative values', function (mixed $values) {
    new Money($values);
})
    ->throws(InvalidMoneyException::class)
    ->with([
        'negative amount' => -100,
        'large negative amount' => -03313131,
    ]);

test('should throw TypeError with invalid types', function (mixed $values) {
    new Money($values);
})
    ->throws(TypeError::class)
    ->with([
        'string amount' => '1000',
        'object amount' => (object)['amount' => 1000],
        'null amount' => null,
        'boolean amount' => true,
    ]);
