<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $paginator = User::jsonPaginate();

        return UserResource::collection($paginator);
    }


    public function show(User $user): JsonResource
    {
        $user = User::where('id', $user->id)
            ->first();

        return new UserResource($user);
    }

    public function store(): JsonResource
    {
        $email = request()->input('email');
        $name = request()->input('name');
        $password = request()->input('password');

        $user = new User([
            'email' => $email,
            'name' => $name,
            'password' => Hash::make($password)
        ]);

        $user->save();

        return new UserResource($user);
    }


    public function update(User $user): JsonResource
    {
        $userData = request()->only(
            'email',
            'name'
        );

        $user->update($userData);

        return new UserResource($user);
    }


    public function destroy(User $user): Response
    {
        $user->delete();

        return response()->noContent();
    }


}
