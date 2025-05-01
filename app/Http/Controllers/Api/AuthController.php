<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign default role to new user (User role)
            $userRole = \Spatie\Permission\Models\Role::where('name', 'User')->first();
            if ($userRole) {
                $user->assignRole($userRole);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            // Load user roles and permissions for frontend authorization
            $user->load('roles.permissions');

            // Get a flat list of permissions for easier checking on the frontend
            $permissions = $user->getAllPermissions()->pluck('name');

            return $this->success([
                'user' => $user,
                'token' => $token,
                'permissions' => $permissions,
                'redirect' => [
                    'path' => '/dashboard',
                    'name' => 'Dashboard'
                ]
            ], 'User registered successfully', 201);
        } catch (ValidationException $e) {
            return $this->validationError($e);
        } catch (\Exception $e) {
            return $this->error('Registration failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Login user and create token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return $this->error('The provided credentials are incorrect.', 401, null, [
                    'email' => ['The provided credentials are incorrect.']
                ]);
            }

            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            // Load user roles and permissions for frontend authorization
            $user->load('roles.permissions');

            // Get a flat list of permissions for easier checking on the frontend
            $permissions = $user->getAllPermissions()->pluck('name');

            return $this->success([
                'user' => $user,
                'token' => $token,
                'permissions' => $permissions,
                'redirect' => [
                    'path' => '/dashboard',
                    'name' => 'Dashboard'
                ]
            ], 'User logged in successfully');
        } catch (ValidationException $e) {
            return $this->validationError($e);
        } catch (\Exception $e) {
            return $this->error('Login failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Logout user (revoke token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'User logged out successfully');
    }

    /**
     * Get the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        // Load user roles and permissions for frontend authorization
        $user->load('roles.permissions');

        // Get a flat list of permissions for easier checking on the frontend
        $permissions = $user->getAllPermissions()->pluck('name');

        return $this->success([
            'user' => $user,
            'permissions' => $permissions
        ]);
    }
}
