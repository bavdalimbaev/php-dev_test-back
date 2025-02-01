<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\DTOs\Category\CategoryCreateDTO;
use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category\Category;
use App\Utils\Tables\Category\CategoryColumn;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Category::with('products')->get();

        $this->setResponse(CategoryResource::collection($items));

        return $this->createResponse();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryCreateRequest $request)
    {
        $data = CategoryCreateDTO::from($request->validated());

        $item = Category::create([
            CategoryColumn::TITLE => $data->title,
        ]);

        $this->setResponse(CategoryResource::make($item));

        return $this->createResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $item = Category::findOrFail($id);

        $item->with(['products']);
        $item->load(['products']);

        $this->setResponse(CategoryResource::make($item));

        return $this->createResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryCreateRequest $request, int $id)
    {
        $data = CategoryCreateDTO::from($request->validated());

        $item = Category::findOrFail($id);

        $item
            ->fill([
                CategoryColumn::TITLE => $data->title,
            ])
            ->save()
        ;

        $item->fresh();
        $item->with(['products']);
        $item->load(['products']);

        $this->setResponse(CategoryResource::make($item));

        return $this->createResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $item = Category::findOrFail($id);

        $item->delete();

        return $this->createResponse();
    }
}
