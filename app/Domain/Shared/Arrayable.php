<?php

namespace App\Domain\Shared;

trait Arrayable
{
    public function toArray(bool $snakeCase = false): array
    {
        $vars = get_object_vars($this);
        $result = [];

        foreach ($vars as $key => $value) {
            $keyName = $snakeCase ? $this->toSnakeCase($key) : $key;
            $result[$keyName] = $this->transformValue($value, $snakeCase);
        }

        return $result;
    }

    private function transformValue(mixed $value, bool $snakeCase): mixed
    {
        if (is_array($value)) {
            return array_map(fn ($v) => $this->transformValue($v, $snakeCase), $value);
        }

        if (is_object($value)) {
            if (method_exists($value, 'toArray')) {
                return $value->toArray($snakeCase);
            }

            return get_object_vars($value);
        }

        return $value;
    }

    private function toSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
