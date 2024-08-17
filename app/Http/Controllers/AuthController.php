<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
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
        try {
            $user = Auth::user();

            $tokens = $user->tokens;

            if ($tokens === null) {
                return response()->json(['error' => 'Tokens not found'], 404);
            }

            return response()->json([
                'user' => $user,
                'tokens' => $tokens
            ], 201);
        } catch (\Exception $e) {
            Log::error('AuthController@getAuthUser: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    public function updateUser(Request $request)
    {
        try {
            if (!$request->has('email') || !$request->has('password')) {
                return response()->json(['error' => 'Missing email or password'], 400);
            }

            $credentials = $request->only('email', 'password');
            $request->user()->fill($credentials);
            $request->user()->save();

            return response()->json(['message' => 'Profile Updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
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
            return response()->json(['error' => 'An error occurred while logging out the user'], 500);
        }
    }
}
