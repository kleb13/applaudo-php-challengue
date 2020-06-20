<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Tymon\JWTAuth\JWTAuth as Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * AuthController constructor.
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }


    public function login(LoginRequest $request){

        $credentials = $request->validated();
        if (! $token = $this->auth->attempt($credentials)) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        return new JsonResponse([
           'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->auth->factory()->getTTL() * 60
        ]);

    }
}
