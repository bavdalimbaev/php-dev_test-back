<?php

namespace App\Http\DTOs\User;

use App\Http\DTOs\ABaseDTO;

class UserCreateDTO extends ABaseDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $bio,
    )
    {

    }
}
