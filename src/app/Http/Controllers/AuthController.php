<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\LoginBrowserRequest;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class AuthController extends Controller
{
    public function loginBrowser(LoginBrowserRequest $request): JsonResource
    {
        $rememberMe = $request->input('remember_me');

        if (! Auth::attempt($request->only(['email', 'password']), $rememberMe)) {
            throw new UnauthorizedHttpException('', 'The provided credentials do not match our records');
        }

        // Fixes https://github.com/laravel/framework/issues/37393
        collect(Arr::wrap(config('sanctum.guard')))
            ->each(function ($driver) {
                Session::remove('password_hash_'.$driver);
            });
        $user = Auth::user();

        return new UserResource($user);
    }

    public function logoutBrowser(): void
    {
        Auth::guard('web')->logout();
    }

    public function getLoggedInUser(): JsonResource
    {
        return new UserResource(Auth::user());
    }

    public function initializeCsrfCookie(Request $request)
    {
        if ($request->expectsJson()) {
            return new JsonResponse(null, HttpResponse::HTTP_NO_CONTENT);
        }

        return new Response('', HttpResponse::HTTP_NO_CONTENT);
    }

}
