<?php

namespace App\Http\DTOs\Product;

use App\Http\DTOs\ABaseDTO;

class ProductCategoryCreateDTO extends ABaseDTO
{
    public function __construct(
        public array $category_ids
    )
    {

    }
}
