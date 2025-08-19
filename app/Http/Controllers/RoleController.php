<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return $this->respondWithSuccess($roles);
    }

    public function show($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return $this->respondWithError('Role not found', 404);
        }
        return $this->respondWithSuccess($role);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, [
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
        ]);

        $role = Role::create($request->all());
        return $this->respondWithSuccess($role, 'Role created successfully');
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return $this->respondWithError('Role not found', 404);
        }

        $this->validateRequest($request, [
            'name' => 'sometimes|required|string|max:255',
            'guard_name' => 'sometimes|required|string|max:255',
        ]);

        $role->update($request->all());
        return $this->respondWithSuccess($role, 'Role updated successfully');
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return $this->respondWithError('Role not found', 404);
        }

        $role->delete();
        return $this->respondWithSuccess(null, 'Role deleted successfully');
    }
}
