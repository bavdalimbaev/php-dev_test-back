<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\DTOs\Category\CategoryProductCreateDTO;
use App\Http\Requests\Category\CategoryProductCreate;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category\Category;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function products(CategoryProductCreate $request, int $id)
    {
        $data = CategoryProductCreateDTO::from($request->validated());
        $item = Category::findOrFail($id);

        $item->products()->sync($data->product_ids);

        $item->fresh();
        $item->with(['products']);
        $item->load(['products']);

        $this->setResponse(CategoryResource::make($item));

        return $this->createResponse();
    }
}
