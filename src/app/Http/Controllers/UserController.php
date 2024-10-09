<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct() {
        $this->authorizeResource(User::class);
    }

    public function index(): AnonymousResourceCollection
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
        request()->validate([
            'email' => 'required|unique:users',
            'name' => 'required',
            'password' => 'required'
        ]);
        $email = request()->input('email');
        $name = request()->input('name');
        $role = request()->input('role');
        $password = request()->input('password');

        $user = new User([
            'email' => $email,
            'name' => $name,
            'role' => $role,
            'password' => Hash::make($password)
        ]);

        $user->save();

        return new UserResource($user);
    }

    public function update(User $user): JsonResource
    {
        $password = request()->input('password');

        $user->update(array_merge(
            request()->only(['email', 'name', 'role']),
            $password ? ['password' => Hash::make($password)]: []
        ));

        return new UserResource($user);
    }

    public function destroy(User $user): Response
    {
        $user->delete();

        return response()->noContent();
    }
}
