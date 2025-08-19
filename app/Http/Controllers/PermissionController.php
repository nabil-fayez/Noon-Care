<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return $this->respondWithSuccess($permissions);
    }

    public function show($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return $this->respondWithError('Permission not found', 404);
        }
        return $this->respondWithSuccess($permission);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, [
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
        ]);

        $permission = Permission::create($request->all());
        return $this->respondWithSuccess($permission, 'Permission created successfully');
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return $this->respondWithError('Permission not found', 404);
        }

        $this->validateRequest($request, [
            'name' => 'sometimes|required|string|max:255',
            'guard_name' => 'sometimes|required|string|max:255',
        ]);

        $permission->update($request->all());
        return $this->respondWithSuccess($permission, 'Permission updated successfully');
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return $this->respondWithError('Permission not found', 404);
        }

        $permission->delete();
        return $this->respondWithSuccess(null, 'Permission deleted successfully');
    }
}
