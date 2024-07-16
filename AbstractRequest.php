<?php

declare(strict_types=1);

namespace MoonShine\Core;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use MoonShine\Contracts\Core\DependencyInjection\RequestContract;
use MoonShine\Core\Traits\InteractsWithRequest;
use Psr\Http\Message\ServerRequestInterface;

class AbstractRequest implements RequestContract
{
    use InteractsWithRequest;

    public function __construct(
        protected readonly ServerRequestInterface $request,
    ) {}

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return data_get(
            $this->getAll(),
            $key,
            $default
        );
    }

    public function getSession(string $key, mixed $default = null): mixed
    {
        return $default;
    }

    public function getFormErrors(?string $bag = null): array
    {
        return [];
    }

    public function getOld(string $key, mixed $default = null): mixed
    {
        return $default;
    }

    public function getFile(string $key): mixed
    {
        return Arr::get($this->getRequest()->getUploadedFiles(), $key);
    }

    public function has(string $key): bool
    {
        return $this->get($key, $this) !== $this;
    }

    public function getAll(): Collection
    {
        return collect(
            array_merge(
                $this->getRequest()->getQueryParams(),
                $this->getRequest()->getParsedBody(),
                $this->getRequest()->getUploadedFiles(),
            )
        );
    }

    public function getOnly(array|string $keys): array
    {
        return $this->getAll()->only($keys)->toArray();
    }

    public function getExcept(array|string $keys): array
    {
        return $this->getAll()->except($keys)->toArray();
    }
}
