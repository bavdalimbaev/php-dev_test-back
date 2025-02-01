<?php

namespace App\Http\DTOs\Product;

use App\Http\DTOs\ABaseDTO;

class ProductCreateDTO extends ABaseDTO
{
    public function __construct(
        public string $title,
        public ?string $description,
        public int $price,
        public int $user_id,
    )
    {
    }
}
