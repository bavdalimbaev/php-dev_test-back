<?php

namespace App\Http\Resources\User;

use App\Models\User\User;
use App\Utils\Tables\User\UserColumn;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User $this */
        return [
            UserColumn::ID => $this->getKey(),
            UserColumn::NAME => $this->name,
            UserColumn::EMAIL => $this->email,
            UserColumn::CREATED_AT => $this->created_at,

            'profile' => empty($this->profile) ? UserProfileResource::make($this->profile) : null,
        ];
    }
}
