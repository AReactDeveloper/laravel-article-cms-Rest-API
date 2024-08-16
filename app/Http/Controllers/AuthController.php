<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    /**
     * Returns the currently authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function getAuthUser()
    {
        $user = Auth::user();
        $tokens = $user->tokens;

        return response()->json([
            'user' => $user,
            'tokens' => $tokens
        ], 201);
    }
    public function login(Request $request)
    {
        try {
            if (!$request->has('email') || !$request->has('password')) {
                return response()->json(['error' => 'Missing email or password'], 400);
            }

            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token], 200);
        } catch (\Exception $e) {
            Log::error('AuthController@login: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Logout a user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Get the currently authenticated user
            $user = Auth::user();

            // Revoke all tokens issued to the user
            $user->tokens()->delete();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            // Log the exception and return an error response
            Log::error('Exception occurred while logging out the user: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while logging out the user'], 500);
        }
    }
}
