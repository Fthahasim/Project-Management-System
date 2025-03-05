<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * @group Authentication
     * Register a new user
     *
     * @bodyParam name string required The user's full name.
     * @bodyParam email string required The user's email.
     * @bodyParam password string required The user's password.
     *
     * @response {
     *      "message": "User registered successfully",
     *      "user": {
     *          "name": "thomas",
     *          "email": "thomas@email.com",
     *          "updated_at": "2025-03-05T10:54:34.000000Z",
     *          "created_at": "2025-03-05T10:54:34.000000Z",
     *          "id": 1
     *      },
     *      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvcmVnaXN0ZXIiLCJpYXQiOjE3NDExNzIwNzQsImV4cCI6MTc0MTE3NTY3NCwibmJmIjoxNzQxMTcyMDc0LCJqdGkiOiJTSzh4NDAzT25CUHBUN0RIIiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.lysM1zLkcI80OY6n2yzl2RiRmiI3EEeN5ZY6_zTnu9Y",
     *      "token_type": "bearer"
     * }
     */
    public function register(UserRegisterRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token , 'User registered Successfully!');
    }
    
    /**
     * @group Authentication
     * User Login
     *
     * @bodyParam email string required The user's email.
     * @bodyParam password string required The user's password.
     *
     * @response {
     *      "message": "User Logged in Successfully!",
     *      "user": {
     *          "name": "Thahasim",
     *          "email": "email@email.com",
     *          "updated_at": "2025-03-05T10:54:34.000000Z",
     *          "created_at": "2025-03-05T10:54:34.000000Z",
     *          "id": 1
     *      },
     *      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvcmVnaXN0ZXIiLCJpYXQiOjE3NDExNzIwNzQsImV4cCI6MTc0MTE3NTY3NCwibmJmIjoxNzQxMTcyMDc0LCJqdGkiOiJTSzh4NDAzT25CUHBUN0RIIiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.lysM1zLkcI80OY6n2yzl2RiRmiI3EEeN5ZY6_zTnu9Y",
     *      "token_type": "bearer"
     * }
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return $this->respondWithToken($token , 'User Logged in Successfully!');
    }

   /**
     * @group Authentication
     * User Logout
     *
     * @bodyParam 
     *
     * @response {
     *      "message": "Successfully logged out",
     * }
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $message)
    {
        return response()->json([
            'message' => $message,
            'user' => auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }
}