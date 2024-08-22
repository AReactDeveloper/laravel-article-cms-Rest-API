<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function getAuthUser()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $tokens = $user->tokens;

            return response()->json([
                'user' => $user,
                'tokens' => $tokens
            ], 200);
        } catch (\Exception $e) {
            Log::error('AuthController@getAuthUser: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $user->fill($request->only('email', 'password')); // Ensure password hashing if necessary
            $user->save();

            return response()->json(['message' => 'Profile Updated'], 200);
        } catch (\Exception $e) {
            Log::error('AuthController@updateUser: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                $errorMessages = implode(', ', $validator->errors()->all());
                return response()->json([
                    'error' => 'Validation failed',
                    'messages' => $errorMessages
                ], 400);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'error' => 'Invalid credentials',
                    'message' => 'Authentication failed'
                ], 401);
            }

            $user = Auth::user(); // Get authenticated user
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'Login successful'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user !== null) {
                $user->tokens()->delete(); // Delete all tokens
            }

            return response()->json(['message' => 'Logged out'], 204); // No content
        } catch (\Throwable $e) {
            Log::error('AuthController@logout: ' . $e->getMessage());

            if ($e instanceof \Error) {
                // If it's a fatal error, make sure to return a 500 status code
                return response()->json(['error' => 'An error occurred'], 500);
            }

            // For other exceptions, return the error message
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
