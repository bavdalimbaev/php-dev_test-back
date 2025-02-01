<?php

namespace App\Http\DTOs\Category;

use App\Http\DTOs\ABaseDTO;

class CategoryProductCreateDTO extends ABaseDTO
{
    public function __construct(
        public array $product_ids
    )
    {

    }
}
