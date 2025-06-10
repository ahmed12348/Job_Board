<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Debug the incoming request data
        Log::info('Registration request data:', $request->all());
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'type' => ['required', 'string', 'in:employer,job_seeker'],
        ]);

        // Log the validated type value
        Log::info('Validated type value:', ['type' => $request->type]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type' => $request->type,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => $user->type === 'employer' 
                    ? 'Registration successful! Please create your company profile to start posting jobs.'
                    : 'Registration successful! You can now start browsing and applying for jobs.'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Registration error:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => $user->type === 'employer' && !$user->company 
                ? 'Welcome back! Please create your company profile to start posting jobs.'
                : 'Welcome back!'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated user's profile
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        if ($user->type === 'employer') {
            $user->load('company');
        }
        return response()->json($user);
    }

    /**
     * Update the authenticated user's profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'skills' => 'nullable|string',
            'bio' => 'nullable|string'
        ]);

        $user->update($request->only([
            'name',
            'title',
            'skills',
            'bio'
        ]));

        if ($user->type === 'employer') {
            $user->load('company');
        }

        return response()->json($user);
    }
}
