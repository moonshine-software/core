<?php

declare(strict_types=1);

namespace MoonShine\Core\Collections;

use Illuminate\Support\Collection;
use MoonShine\Contracts\Core\HasComponentsContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\HasFieldsContract;
use Closure;

abstract class BaseCollection extends Collection
{
    public function onlyVisible(): static
    {
        return $this->filter(static fn (ComponentContract $component): bool => $component->isSee());
    }

    /**
     * @param  Closure(ComponentContract): bool  $except
     */
    public function exceptElements(Closure $except): static
    {
        return $this->filter(static function (ComponentContract $element) use ($except): bool {
            if ($except($element) === true) {
                return false;
            }

            if ($element instanceof HasFieldsContract) {
                $element->fields(
                    $element->getFields()->exceptElements($except)->toArray()
                );
            } elseif ($element instanceof HasComponentsContract) {
                $element->setComponents(
                    $element->getComponents()->exceptElements($except)->toArray()
                );
            }

            return true;
        })->filter()->values();
    }

    public function toStructure(bool $withStates = true): array
    {
        return $this->map(
            static fn (ComponentContract $component): array => $component->toStructure($withStates)
        )->toArray();
    }
}
