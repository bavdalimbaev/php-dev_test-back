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
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Получить список категорий",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Category Title"),
     *                 @OA\Property(property="created_at", type="string", format="date", example="01.01.2023"),
     *                 @OA\Property(
     *                     property="products",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="user_id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Product Title"),
     *                         @OA\Property(property="description", type="string", example="Product Description"),
     *                         @OA\Property(property="price", type="integer", example=100),
     *                         @OA\Property(property="created_at", type="string", format="date", example="01.01.2021")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Категории не найдены"
     *     )
     * )
     */
    public function index()
    {
        $items = Category::with('products')->get();

        $this->setResponse(CategoryResource::collection($items));

        return $this->createResponse();
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="category_title"),
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Category Title"),
     *                     @OA\Property(property="created_at", type="string", format="date", example="01.01.2023"),
     *                     @OA\Property(
     *                         property="products",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="user_id", type="integer", example=1),
     *                             @OA\Property(property="title", type="string", example="Product Title"),
     *                             @OA\Property(property="description", type="string", example="Product Description"),
     *                             @OA\Property(property="price", type="integer", example=100),
     *                             @OA\Property(property="created_at", type="string", format="date", example="01.01.2021")
     *                         )
     *                     )
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
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Get category by ID",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *           response=200,
     *           description="Успешный ответ",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Category Title"),
     *                     @OA\Property(property="created_at", type="string", format="date", example="01.01.2023"),
     *                     @OA\Property(
     *                         property="products",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="user_id", type="integer", example=1),
     *                             @OA\Property(property="title", type="string", example="Product Title"),
     *                             @OA\Property(property="description", type="string", example="Product Description"),
     *                             @OA\Property(property="price", type="integer", example=100),
     *                             @OA\Property(property="created_at", type="string", format="date", example="01.01.2021")
     *                         )
     *                     )
     *           )
     *       ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category not found.")
     *         )
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Update an existing category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"title"},
     *              @OA\Property(property="title", type="string", example="category_title"),
     *          )
     *      ),
     *     @OA\Response(
     *            response=200,
     *            description="Успешный ответ",
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Category Title"),
     *                     @OA\Property(property="created_at", type="string", format="date", example="01.01.2023"),
     *                     @OA\Property(
     *                         property="products",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="user_id", type="integer", example=1),
     *                             @OA\Property(property="title", type="string", example="Product Title"),
     *                             @OA\Property(property="description", type="string", example="Product Description"),
     *                             @OA\Property(property="price", type="integer", example=100),
     *                             @OA\Property(property="created_at", type="string", format="date", example="01.01.2021")
     *                         )
     *                     )
     *            )
     *        ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category not found.")
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
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Delete a category by ID",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ok"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category not found.")
     *         )
     *     )
     * )
     */
    public function destroy(int $id)
    {
        $item = Category::findOrFail($id);

        $item->delete();

        return $this->createResponse();
    }
}
