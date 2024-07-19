<?php

declare(strict_types=1);

namespace MoonShine\Core;

use Closure;
use Illuminate\Support\Collection;
use MoonShine\Core\Traits\InteractsWithRequest;
use Psr\Http\Message\ServerRequestInterface;

final class Request
{
    use InteractsWithRequest;

    public function __construct(
        protected ServerRequestInterface $request,
        protected ?Closure $session = null,
        protected ?Closure $file = null,
        protected ?Closure $old = null,
    ) {
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
        return value($this->session, $key, $default);
    }

    public function getFile(string $key): mixed
    {
        if (is_null($this->file)) {
            return $this->get($key);
        }

        return value($this->file, $key);
    }

    public function getOld(string $key, mixed $default = null): mixed
    {
        return value($this->old, $key, $default);
    }

    public function has(string $key): bool
    {
        return $this->get($key, $this) !== $this;
    }

    public function getAll(): Collection
    {
        $body  = $this->request->getParsedBody();
        $files = $this->request->getUploadedFiles();
        $query = $this->request->getQueryParams();

        $merged = $this->merge($body, $files);

        return collect(
            $this->merge($merged, $query)
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

    private function merge(array &$first, array &$second): array
    {
        $merged = $first;

        foreach ($second as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->merge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
