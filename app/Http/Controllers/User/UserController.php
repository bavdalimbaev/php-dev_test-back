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
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = User::with(['profile'])->get();

        $items->load(['profile']);
        $this->setResponse(UserResource::collection($items));

        return $this->createResponse();
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $item = User::findOrFail($id);

        $item->delete();

        return $this->createResponse();
    }
}
