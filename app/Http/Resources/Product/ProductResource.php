<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\User\UserResource;
use App\Models\Product\Product;
use App\Utils\App\Core\DateHandler;
use App\Utils\Tables\Product\ProductColumn;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Product $this */
        return [
            ProductColumn::ID => $this->getKey(),
            ProductColumn::USER_ID => $this->user_id,
            ProductColumn::TITLE => $this->title,
            ProductColumn::DESCRIPTION => $this->description,
            ProductColumn::PRICE => $this->price,
            ProductColumn::CREATED_AT => DateHandler::dateFormat($this->created_at, 'd.m.Y'),

            'user' => UserResource::make($this->user),
            'categories' => CategoryResource::collection($this->categories),
        ];
    }
}
