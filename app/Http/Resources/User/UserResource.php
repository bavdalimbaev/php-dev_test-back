<?php

namespace App\Http\Resources\User;

use App\Models\User\User;
use App\Utils\App\Core\DateHandler;
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
            UserColumn::CREATED_AT => DateHandler::dateFormat($this->created_at, 'd.m.Y'),

            'profile' => UserProfileResource::make($this->whenLoaded('profile')),
        ];
    }
}
