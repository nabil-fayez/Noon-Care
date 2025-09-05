<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * عرض قائمة الأدوار
     */
    public function index()
    {
        $roles = Role::withCount(['admins', 'permissions'])->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * عرض نموذج إنشاء دور جديد
     */
    public function create()
    {
        $permissions = Permission::orderBy('module')->orderBy('permission_name')->get();
        $groupedPermissions = $permissions->groupBy('module');

        return view('admin.roles.create', compact('groupedPermissions'));
    }

    /**
     * حفظ الدور الجديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,role_name',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'role_name' => $request->role_name,
            'description' => $request->description,
            'is_default' => $request->has('is_default'),
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم إنشاء الدور بنجاح.');
    }

    /**
     * عرض تفاصيل الدور
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'admins']);
        $permissions = Permission::orderBy('module')->orderBy('permission_name')->get();
        $groupedPermissions = $permissions->groupBy('module');

        return view('admin.roles.show', compact('role', 'groupedPermissions'));
    }

    /**
     * عرض نموذج تعديل الدور
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('module')->orderBy('permission_name')->get();
        $groupedPermissions = $permissions->groupBy('module');

        return view('admin.roles.edit', compact('role', 'groupedPermissions'));
    }

    /**
     * تحديث بيانات الدور
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,role_name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'role_name' => $request->role_name,
            'description' => $request->description,
            'is_default' => $request->has('is_default'),
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم تحديث الدور بنجاح.');
    }

    /**
     * حذف الدور
     */
    public function destroy(Role $role)
    {
        // منع حذف الأدوراة التي لها مستخدمين مرتبطين بها
        if ($role->admins()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الدور لأنه مرتبط بمسؤولين.');
        }

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم حذف الدور بنجاح.');
    }
}
