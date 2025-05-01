<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $roles = Role::with('permissions')->get();
            
            return $this->success($roles);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve roles: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:roles,name',
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id',
            ]);
            
            $role = Role::create(['name' => $validated['name']]);
            $role->syncPermissions($validated['permissions']);
            
            return $this->success($role->load('permissions'), 'Role created successfully', 201);
        } catch (ValidationException $e) {
            return $this->validationError($e);
        } catch (\Exception $e) {
            return $this->error('Role creation failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);
            
            return $this->success($role);
        } catch (\Exception $e) {
            return $this->error('Role not found', 404);
        }
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|unique:roles,name,' . $id,
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id',
            ]);
            
            $role->update(['name' => $validated['name']]);
            $role->syncPermissions($validated['permissions']);
            
            return $this->success($role->load('permissions'), 'Role updated successfully');
        } catch (ValidationException $e) {
            return $this->validationError($e);
        } catch (\Exception $e) {
            return $this->error('Role update failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);
            
            // Don't allow deleting the admin role
            if ($role->name === 'Admin') {
                return $this->error('Cannot delete the Admin role', 403);
            }
            
            $role->delete();
            
            return $this->success(null, 'Role deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Role deletion failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get all permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissions(): JsonResponse
    {
        try {
            $permissions = Permission::all();
            
            return $this->success($permissions);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve permissions: ' . $e->getMessage(), 500);
        }
    }
}
