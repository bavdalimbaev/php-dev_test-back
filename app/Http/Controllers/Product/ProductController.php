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
     * @OA\Get(
     *     path="/api/products",
     *     summary="Получить список товаров",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="title"),
     *                 @OA\Property(property="description", type="string", example="description"),
     *                 @OA\Property(property="price", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date", example="01.01.2021"),
     *                 @OA\Property(property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="John Doe"),
     *                  @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                  @OA\Property(property="created_at", type="string", format="date", example="01.01.2021"),
     *                  @OA\Property(property="profile",
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="user_id", type="integer", example=1),
     *                      @OA\Property(property="bio", type="string", example="This is my bio"),
     *                      @OA\Property(property="created_at", type="string", format="date", example="01.01.2021")
     *                  )
     *                 ),
     *                 @OA\Property(property="categories",
     *                      type="array",
     *                      @OA\Items(
     *                            type="object",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="title", type="string", example="Category Title"),
     *     @OA\Property(property="created_at", type="string", format="date", example="01.01.2023")
     *                      )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Товары не найдены"
     *     )
     * )
     */
    public function index()
    {
        $items = Product::with(['user', 'categories'])->get();

        $items->load(['user', 'categories']);
        $this->setResponse(ProductResource::collection($items));

        return $this->createResponse();
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Create a new product",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "price", "user_id"},
     *             @OA\Property(property="title", type="string", example="product_title"),
     *             @OA\Property(property="price", type="integer", example="1"),
     *             @OA\Property(property="user_id", type="integer", example="1"),
     *             @OA\Property(property="description", type="string", example="description")
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="user_id", type="integer", example=1),
     *                  @OA\Property(property="title", type="string", example="title"),
     *                  @OA\Property(property="description", type="string", example="description"),
     *                  @OA\Property(property="price", type="integer", example=1),
     *                  @OA\Property(property="created_at", type="string", format="date", example="01.01.2021"),
     *                  @OA\Property(property="user",
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                   @OA\Property(property="name", type="string", example="John Doe"),
     *                   @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                   @OA\Property(property="created_at", type="string", format="date", example="01.01.2021"),
     *                   @OA\Property(property="profile",
     *                       type="object",
     *                       @OA\Property(property="id", type="integer", example=1),
     *                       @OA\Property(property="user_id", type="integer", example=1),
     *                       @OA\Property(property="bio", type="string", example="This is my bio"),
     *                       @OA\Property(property="created_at", type="string", format="date", example="01.01.2021")
     *                   )
     *                  ),
     *                  @OA\Property(property="categories",
     *                       type="array",
     *                       @OA\Items(
     *                             type="object",
     *      @OA\Property(property="id", type="integer", example=1),
     *      @OA\Property(property="title", type="string", example="Category Title"),
     *      @OA\Property(property="created_at", type="string", format="date", example="01.01.2023")
     *                       )
     *                  )
     *
     *          )
     *      ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Get product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *           response=200,
     *           description="Успешный ответ",
     *           @OA\JsonContent(
     *                   type="object",
     *                   @OA\Property(property="id", type="integer", example=1),
     *                   @OA\Property(property="user_id", type="integer", example=1),
     *                   @OA\Property(property="title", type="string", example="title"),
     *                   @OA\Property(property="description", type="string", example="description"),
     *                   @OA\Property(property="price", type="integer", example=1),
     *                   @OA\Property(property="created_at", type="string", format="date", example="01.01.2021"),
     *                   @OA\Property(property="user",
     *                       type="object",
     *                       @OA\Property(property="id", type="integer", example=1),
     *                    @OA\Property(property="name", type="string", example="John Doe"),
     *                    @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                    @OA\Property(property="created_at", type="string", format="date", example="01.01.2021"),
     *                    @OA\Property(property="profile",
     *                        type="object",
     *                        @OA\Property(property="id", type="integer", example=1),
     *                        @OA\Property(property="user_id", type="integer", example=1),
     *                        @OA\Property(property="bio", type="string", example="This is my bio"),
     *                        @OA\Property(property="created_at", type="string", format="date", example="01.01.2021")
     *                    )
     *                   ),
     *                   @OA\Property(property="categories",
     *                        type="array",
     *                        @OA\Items(
     *                              type="object",
     *       @OA\Property(property="id", type="integer", example=1),
     *       @OA\Property(property="title", type="string", example="Category Title"),
     *       @OA\Property(property="created_at", type="string", format="date", example="01.01.2023")
     *                        )
     *                   )
     *
     *           )
     *       ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product not found.")
     *         )
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update an existing product",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"title", "price", "user_id"},
     *              @OA\Property(property="title", type="string", example="product_title"),
     *              @OA\Property(property="price", type="integer", example="1"),
     *              @OA\Property(property="user_id", type="integer", example="1"),
     *              @OA\Property(property="description", type="string", example="description")
     *          )
     *      ),
     *     @OA\Response(
     *           response=200,
     *           description="Успешный ответ",
     *           @OA\JsonContent(
     *                   type="object",
     *                   @OA\Property(property="id", type="integer", example=1),
     *                   @OA\Property(property="user_id", type="integer", example=1),
     *                   @OA\Property(property="title", type="string", example="title"),
     *                   @OA\Property(property="description", type="string", example="description"),
     *                   @OA\Property(property="price", type="integer", example=1),
     *                   @OA\Property(property="created_at", type="string", format="date", example="01.01.2021"),
     *                   @OA\Property(property="user",
     *                       type="object",
     *                       @OA\Property(property="id", type="integer", example=1),
     *                    @OA\Property(property="name", type="string", example="John Doe"),
     *                    @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                    @OA\Property(property="created_at", type="string", format="date", example="01.01.2021"),
     *                    @OA\Property(property="profile",
     *                        type="object",
     *                        @OA\Property(property="id", type="integer", example=1),
     *                        @OA\Property(property="user_id", type="integer", example=1),
     *                        @OA\Property(property="bio", type="string", example="This is my bio"),
     *                        @OA\Property(property="created_at", type="string", format="date", example="01.01.2021")
     *                    )
     *                   ),
     *                   @OA\Property(property="categories",
     *                        type="array",
     *                        @OA\Items(
     *                              type="object",
     *       @OA\Property(property="id", type="integer", example=1),
     *       @OA\Property(property="title", type="string", example="Category Title"),
     *       @OA\Property(property="created_at", type="string", format="date", example="01.01.2023")
     *                        )
     *                   )
     *
     *           )
     *       ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete a product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ok"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product not found.")
     *         )
     *     )
     * )
     */
    public function destroy(int $id)
    {
        $item = Product::findOrFail($id);

        $item->delete();

        return $this->createResponse();
    }
}
