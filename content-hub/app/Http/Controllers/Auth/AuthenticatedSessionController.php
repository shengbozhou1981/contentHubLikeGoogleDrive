<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Services\AuthenticatedSessionService;

class AuthenticatedSessionController extends Controller
{
    protected $authService;

    public function __construct(AuthenticatedSessionService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->login($request);

        // Return a JSON response with the user information
        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        $this->authService->logout($request);
        return response()->noContent();
    }
}