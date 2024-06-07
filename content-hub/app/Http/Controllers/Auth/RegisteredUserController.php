<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\RegisteredUserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    protected $userService;

    public function __construct(RegisteredUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:App\Models\User'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $this->userService->registerUser($request->all());

        // Return a JSON response to indicate success
        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
}