<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Insert a new user in the system
     * @param Request $request
     * @return void
     */
    public function register(Request $request): JsonResponse
    {
        //valite request fields
        $validatedFields = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //create new user   
        $user = User::create($validatedFields);

        //create token to new user
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => UserResource::make($user),
            'token' => $token,
            'token_type' => 'Bearer'
        ], status: 201);
    }

    /**
     * log in the user
     * @param Request $request
     */
    public function login(Request $request): JsonResponse
    {
        //validate request fields
        $validatedFields = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string'
        ]);

        //find the user using email passed by http
        $user = User::where('email', $validatedFields['email'])->first();

        //user must exists and the password passed by http request must be equal to user password
        if (!$user || !Hash::check($validatedFields['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials are incorrect'
            ]);
        }

        //create a token to user
        $token = $user->createToken(name: 'api')->plainTextToken;

        //return response with user data and token
        return response()->json([
            'message' => 'Login successfully',
            'data' => [
                'user' => UserResource::make($user),
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }
}
