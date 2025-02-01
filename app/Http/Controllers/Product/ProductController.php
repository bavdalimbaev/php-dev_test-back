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
        $products = Product::with('user', 'categories')->get();

        $this->setResponse(ProductResource::collection($products));

        return $this->createResponse();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request)
    {
        $data = ProductCreateDTO::from($request->validated());

        $product = Product::create([
            ProductColumn::TITLE => $data->title,
            ProductColumn::USER_ID => $data->user_id,
            ProductColumn::DESCRIPTION => $data->description,
            ProductColumn::PRICE => $data->price,
        ]);

        $this->setResponse(ProductResource::make($product));

        return $this->createResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $product = Product::findOrFail($id);
        $product->with([
            'user', 'category'
        ]);

        $this->setResponse(ProductResource::make($product));

        return $this->createResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCreateRequest $request, string $id)
    {
        $data = ProductCreateDTO::from($request->validated());

        $product = Product::findOrFail($id);

        $product
            ->with([
                'user', 'category'
            ])
            ->fill([
                ProductColumn::TITLE => $data->title,
                ProductColumn::USER_ID => $data->user_id,
                ProductColumn::DESCRIPTION => $data->description,
                ProductColumn::PRICE => $data->price,
            ])
            ->save()
            ->fresh();

        $this->setResponse(ProductResource::make($product));

        return $this->createResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return $this->createResponse();
    }
}
