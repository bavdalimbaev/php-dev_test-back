<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\DTOs\Product\ProductCategoryCreateDTO;
use App\Http\Requests\Product\ProductCategoryCreate;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function categories(ProductCategoryCreate $request, int $id)
    {
        $data = ProductCategoryCreateDTO::from($request->validated());
        $item = Product::findOrFail($id);

        $item->categories()->sync($data->category_ids);

        $item->fresh();
        $item->with(['user', 'categories']);
        $item->load(['user', 'categories']);

        $this->setResponse(ProductResource::make($item));

        return $this->createResponse();
    }
}
