<?php

declare(strict_types=1);

namespace MoonShine\Core\Contracts;

interface MoonShineEndpoints
{
    public function toPage(
        string|PageContract|null $page = null,
        string|ResourceContract|null $resource = null,
        array $params = [],
        array $extra = [],
    ): mixed;

    public function updateColumn(
        ?ResourceContract $resource = null,
        ?PageContract $page = null,
        array $extra = []
    ): string;

    public function asyncMethod(
        string $method,
        ?string $message = null,
        array $params = [],
        ?PageContract $page = null,
        ?ResourceContract $resource = null
    ): string;

    public function asyncComponent(
        string $name,
        array $additionally = []
    ): string;

    public function reactive(
        ?PageContract $page = null,
        ?ResourceContract $resource = null,
        array $extra = []
    ): string;

    public function home(): string;
}
