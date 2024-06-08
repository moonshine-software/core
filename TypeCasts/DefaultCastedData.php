<?php

declare(strict_types=1);

namespace MoonShine\Core\TypeCasts;

use MoonShine\Core\Contracts\CastedData;

final readonly class DefaultCastedData implements CastedData
{
    public function __construct(private mixed $data)
    {
    }

    public function getOriginal(): mixed
    {
        return $this->data;
    }

    public function getKey(): int|string|null
    {
        return null;
    }

    public function toArray(): array
    {
        if(is_object($this->data) && method_exists($this->data, 'toArray')) {
            return $this->data->toArray();
        }

        return (array) $this->data;
    }
}
