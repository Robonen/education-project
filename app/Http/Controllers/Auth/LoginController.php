<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $credentials = $request->only('login', 'password');

        if (!auth()->attempt($credentials))
            return response()->json('You cannot sign with those credentials!', 401);

        $token = auth()->user()->createToken(config('app.name'));
        $token->token->expires_at = (bool)$request->get('remember_me') ? Carbon::now()->addMonth() : Carbon::now()->addDay();
        $token->token->save();

        return response()->json(['access_token' => $token->accessToken], 200);
    }
}
