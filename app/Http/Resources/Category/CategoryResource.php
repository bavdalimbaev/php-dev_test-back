<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Product\ProductResource;
use App\Models\Category\Category;
use App\Utils\Tables\Category\CategoryColumn;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Category $this */
        return [
            CategoryColumn::ID => $this->getKey(),
            CategoryColumn::TITLE => $this->title,
            CategoryColumn::CREATED_AT => $this->created_at,

            'categories' => ProductResource::collection($this->products),
        ];
    }
}
