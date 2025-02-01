<?php

namespace App\Http\DTOs\Category;

use App\Http\DTOs\ABaseDTO;

class CategoryCreateDTO extends ABaseDTO
{
    public function __construct(public string $title)
    {

    }
}
