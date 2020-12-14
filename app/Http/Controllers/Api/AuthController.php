<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status_code' => 401, 'message' => 'Invalid Credentials']);
        }
        $accessToken = $user->createToken("WebApp")->plainTextToken;
        return response()->json(
            [
                'status_code' => 200,
                'token' => $accessToken,
                'user' => $user,
            ]
        );
    }

    public function user(Request $request)
    {
        return response()->json(
            [
                'user' => auth()->user()
            ],
            200
        );
    }


}
