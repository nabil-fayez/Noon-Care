<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        $admins = Admin::all();
        return $this->respondWithSuccess($admins);
    }

    public function show($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return $this->respondWithError('Admin not found', 404);
        }
        return $this->respondWithSuccess($admin);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,role_id',
        ]);

        $admin = Admin::create($request->all());
        return $this->respondWithSuccess($admin, 'Admin created successfully');
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return $this->respondWithError('Admin not found', 404);
        }

        $this->validateRequest($request, [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:admins,email,' . $admin->admin_id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'role_id' => 'sometimes|required|exists:roles,role_id',
        ]);

        $admin->update($request->all());
        return $this->respondWithSuccess($admin, 'Admin updated successfully');
    }

    public function destroy($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return $this->respondWithError('Admin not found', 404);
        }

        $admin->delete();
        return $this->respondWithSuccess(null, 'Admin deleted successfully');
    }
}
