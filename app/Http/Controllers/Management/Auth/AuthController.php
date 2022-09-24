<?php

namespace App\Http\Controllers\Management\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {

        $post_data = $request->all();
        $user = $this->userService->savePostData($post_data);
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'User created successfully.',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Login information is invalid.'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'User logged in successfully.',
            'user' => $user
        ]);
    }

    // logout laravel santum

    public function logout(Request $request)
    {
        // $request->user()->currentAccessToken()->delete();
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'User logged out successfully.'
        ]);
    }
}
