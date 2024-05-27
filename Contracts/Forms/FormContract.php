<?php

declare(strict_types=1);

namespace MoonShine\Core\Contracts\Forms;

use MoonShine\UI\Components\FormBuilder;

interface FormContract
{
    public function __invoke(): FormBuilder;
}
