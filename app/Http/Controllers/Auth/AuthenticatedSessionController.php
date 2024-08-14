<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use  \Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request The incoming authentication request.
     * @return Response The response to the authentication request.
     * @throws \Illuminate\Validation\ValidationException If the request is invalid.
     */
    public function store(LoginRequest $request): Response
    {
        // Authenticate the user.
        if (! $request->authenticate()) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        // Regenerate the session.
        try {
            $request->session()->regenerate();
        } catch (\Exception $e) {
            // Throw an exception if the session cannot be regenerated.
            throw new \RuntimeException('Session regeneration failed.', 0, $e);
        }

        // Return a no content response.
        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
