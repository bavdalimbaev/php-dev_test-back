<?php

namespace App\Http\Controllers\User;

use App\Events\User\UserCreated;
use App\Http\Controllers\Controller;
use App\Http\DTOs\User\UserCreateDTO;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User\User;
use App\Models\User\UserProfile;
use App\Utils\Tables\User\UserColumn;
use App\Utils\Tables\User\UserProfileColumn;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Получить список пользователей",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date", example="01.01.2021"),
     *                 @OA\Property(property="profile",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="bio", type="string", example="This is my bio"),
     *                     @OA\Property(property="created_at", type="string", format="date", example="01.01.2021")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Пользователи не найдены"
     *     )
     * )
     */
    public function index()
    {
        $items = User::with(['profile'])->get();

        $items->load(['profile']);
        $this->setResponse(UserResource::collection($items));

        return $this->createResponse();
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", example="secret"),
     *             @OA\Property(property="bio", type="string", example="A brief bio about John.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(

     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date", example="01.01.2021"),
     *                 @OA\Property(property="profile",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="bio", type="string", example="This is my bio"),
     *                     @OA\Property(property="created_at", type="string", format="date", example="01.01.2021")
     *                 )
     *
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
    public function store(UserCreateRequest $request)
    {
        $data = UserCreateDTO::from($request->validated());

        $item = User::create([
            UserColumn::NAME => $data->name,
            UserColumn::EMAIL => $data->email,
            UserColumn::PASSWORD => bcrypt($data->password),
        ]);

        if (!empty($data->bio)) {
            UserProfile::create([
                UserProfileColumn::USER_ID => $item->getKey(),
                UserProfileColumn::BIO => $data->bio,
            ]);

            $item->fresh();
        }

        $item->with(['profile']);
        $item->load(['profile']);

        broadcast(new UserCreated($item))->toOthers();

        $this->setResponse(UserResource::make($item));

        return $this->createResponse();
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get user by ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(

     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date", example="01.01.2021"),
     *                 @OA\Property(property="profile",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="bio", type="string", example="This is my bio"),
     *                     @OA\Property(property="created_at", type="string", format="date", example="01.01.2021")
     *                 )
     *             )
     *
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        $item = User::findOrFail($id);

        $item->with(['profile']);
        $item->load(['profile']);

        $this->setResponse(UserResource::make($item));

        return $this->createResponse();
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update an existing user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", example="newsecret"),
     *             @OA\Property(property="bio", type="string", example="Updated bio about John.")
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(

     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
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
     *
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found.")
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
    public function update(UserCreateRequest $request, int $id)
    {
        $data = UserCreateDTO::from($request->validated());

        $item = User::findOrFail($id);

        $item
            ->fill([
                UserColumn::NAME => $data->name,
                UserColumn::EMAIL => $data->email,
                UserColumn::PASSWORD => bcrypt($data->password),
            ])
            ->save();

        $item->with(['profile']);
        if (!empty($data->bio)) {
            if ($item->profile) {
                $item->profile->fill([
                    UserProfileColumn::BIO => $data->bio,
                ])
                    ->save();
            } else {
                UserProfile::create([
                    UserProfileColumn::USER_ID => $item->getKey(),
                    UserProfileColumn::BIO => $data->bio,
                ]);
            }
        }

        $item->fresh();
        $item->load(['profile']);

        $this->setResponse(UserResource::make($item));

        return $this->createResponse();
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user by ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ok"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     )
     * )
     */
    public function destroy(int $id)
    {
        $item = User::findOrFail($id);

        $item->delete();

        return $this->createResponse();
    }
}
