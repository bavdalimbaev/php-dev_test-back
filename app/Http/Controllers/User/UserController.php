<?php

namespace App\Http\Controllers\User;

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
        $users = User::with('profile')->get();

        $this->setResponse(UserResource::collection($users));

        return $this->createResponse();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
    {
        $data = UserCreateDTO::from($request->validated());

        $user = User::create([
            UserColumn::NAME => $data->name,
            UserColumn::EMAIL => $data->email,
            UserColumn::PASSWORD => bcrypt($data->password),
        ]);

        if (!empty($data->bio)) {
            UserProfile::create([
                UserProfileColumn::USER_ID => $user->getKey(),
                UserProfileColumn::BIO => $data->bio,
            ]);

            $user->fresh();
        }

        $this->setResponse(UserResource::make($user));

        return $this->createResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = User::findOrFail($id);

        $user->with('profile');

        $this->setResponse(UserResource::make($user));

        return $this->createResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserCreateRequest $request, int $id)
    {
        $data = UserCreateDTO::from($request->validated());

        $user = User::findOrFail($id);

        $user
            ->with('profile')
            ->fill([
                UserColumn::NAME => $data->name,
                UserColumn::EMAIL => $data->email,
                UserColumn::PASSWORD => bcrypt($data->password),
            ])
            ->save();

        if (!empty($data->bio)) {
            if ($user->profile) {
                $user->profile->fill([
                    UserProfileColumn::BIO => $data->bio,
                ])
                ->save();
            } else {
                UserProfile::create([
                    UserProfileColumn::USER_ID => $user->getKey(),
                    UserProfileColumn::BIO => $data->bio,
                ]);
            }
        }

        $user->fresh();

        $this->setResponse(UserResource::make($user));

        return $this->createResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return $this->createResponse();
    }
}
