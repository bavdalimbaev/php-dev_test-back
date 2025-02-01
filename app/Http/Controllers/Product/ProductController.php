<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\DTOs\Product\ProductCreateDTO;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product\Product;
use App\Utils\Tables\Product\ProductColumn;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Product::with(['user', 'categories'])->get();

        $items->load(['user', 'categories']);
        $this->setResponse(ProductResource::collection($items));

        return $this->createResponse();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request)
    {
        $data = ProductCreateDTO::from($request->validated());

        $item = Product::create([
            ProductColumn::TITLE => $data->title,
            ProductColumn::USER_ID => $data->user_id,
            ProductColumn::DESCRIPTION => $data->description,
            ProductColumn::PRICE => $data->price,
        ]);

        $item->with(['user', 'categories']);
        $item->load(['user', 'categories']);

        $this->setResponse(ProductResource::make($item));

        return $this->createResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $item = Product::findOrFail($id);
        $item->with(['user', 'categories']);
        $item->load(['user', 'categories']);

        $this->setResponse(ProductResource::make($item));

        return $this->createResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCreateRequest $request, string $id)
    {
        $data = ProductCreateDTO::from($request->validated());

        $item = Product::findOrFail($id);

        $item
            ->fill([
                ProductColumn::TITLE => $data->title,
                ProductColumn::USER_ID => $data->user_id,
                ProductColumn::DESCRIPTION => $data->description,
                ProductColumn::PRICE => $data->price,
            ])
            ->save();

        $item->fresh();

        $item
            ->with([
                'user', 'categories'
            ]);

        $item
            ->load([
                'user', 'categories'
            ]);

        $this->setResponse(ProductResource::make($item));

        return $this->createResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $item = Product::findOrFail($id);

        $item->delete();

        return $this->createResponse();
    }
}
