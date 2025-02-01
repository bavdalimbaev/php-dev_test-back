<?php

namespace App\Http\Resources\User;

use App\Models\User\UserProfile;
use App\Utils\App\Core\DateHandler;
use App\Utils\Tables\User\UserProfileColumn;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var UserProfile $this */
        return [
            UserProfileColumn::USER_ID => $this->getKey(),
            UserProfileColumn::BIO => $this->bio,
            UserProfileColumn::CREATED_AT => DateHandler::dateFormat($this->created_at, 'd.m.Y'),
        ];
    }
}
