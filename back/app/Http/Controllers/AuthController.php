<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use stdClass;
class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $guard = Auth::guard();
        $userIsLoggedIn = $guard->attempt([
            'email' => $request['email'],
            'password' => $request['password'],
        ]);

        if (! $userIsLoggedIn) {
            return response()->json([
                'status' => "error",
                'data' => "Логин или пароль неверный"
            ]);
        }

        $request->session()->regenerate();
        return response()->json([
            'status' => "success",
            'data' => auth()->user()
        ]);  
    }

    public function logout( Request $request )
    {
        return response()->json (['status' => 'success', 'data' =>  auth()->logout()]);
    }

    public function user( Request $request)
    {
        return $request->user() ?: new stdClass;
    }
}

