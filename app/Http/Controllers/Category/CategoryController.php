<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('products')->get();

        $this->setResponse(CategoryResource::collection($categories));

        return $this->createResponse();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryCreateRequest $request)
    {
        $data = $request->validated();

        $category = Category::create($data);

        $this->setResponse(CategoryResource::make($category));

        return $this->createResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $category = Category::findOrFail($id);

        $category->with('products');

        $this->setResponse(CategoryResource::make($category));

        return $this->createResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryCreateRequest $request, int $id)
    {
        $data = $request->validated();

        $category = Category::findOrFail($id);

        $category
            ->with('products')
            ->fill($data)
            ->save()
            ->fresh()
        ;

        $this->setResponse(CategoryResource::make($category));

        return $this->createResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return $this->createResponse();
    }
}
