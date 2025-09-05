<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * عرض قائمة الصلاحيات
     */
    public function index()
    {
        $permissions = Permission::orderBy('module')->orderBy('permission_name')->get();
        $groupedPermissions = $permissions->groupBy('module');

        return view('admin.permissions.index', compact('groupedPermissions'));
    }

    /**
     * عرض نموذج إنشاء صلاحية جديدة
     */
    public function create()
    {
        $modules = [
            'doctors' => 'الأطباء',
            'patients' => 'المرضى',
            'facilities' => 'المنشآت',
            'appointments' => 'المواعيد',
            'medical_records' => 'السجلات الطبية',
            'specialties' => 'التخصصات',
            'error_logs' => 'سجلات الأخطاء',
            'reports' => 'التقارير',
            'settings' => 'الإعدادات',
            'roles' => 'الأدوار',
            'permissions' => 'الصلاحيات',
        ];

        return view('admin.permissions.create', compact('modules'));
    }

    /**
     * حفظ الصلاحية الجديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'permission_name' => 'required|string|max:255|unique:permissions,permission_name',
            'description' => 'nullable|string',
            'module' => 'required|string|max:255'
        ]);

        Permission::create($request->all());

        return redirect()->route('admin.permissions.index')
            ->with('success', 'تم إنشاء الصلاحية بنجاح.');
    }

    /**
     * عرض تفاصيل الصلاحية
     */
    public function show(Permission $permission)
    {
        $permission->load('roles.admins');

        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * عرض نموذج تعديل الصلاحية
     */
    public function edit(Permission $permission)
    {
        $modules = [
            'doctors' => 'الأطباء',
            'patients' => 'المرضى',
            'facilities' => 'المنشآت',
            'appointments' => 'المواعيد',
            'medical_records' => 'السجلات الطبية',
            'specialties' => 'التخصصات',
            'error_logs' => 'سجلات الأخطاء',
            'reports' => 'التقارير',
            'settings' => 'الإعدادات',
            'roles' => 'الأدوار',
            'permissions' => 'الصلاحيات',
        ];

        return view('admin.permissions.edit', compact('permission', 'modules'));
    }

    /**
     * تحديث بيانات الصلاحية
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'permission_name' => 'required|string|max:255|unique:permissions,permission_name,' . $permission->id,
            'description' => 'nullable|string',
            'module' => 'required|string|max:255'
        ]);

        $permission->update($request->all());

        return redirect()->route('admin.permissions.index')
            ->with('success', 'تم تحديث الصلاحية بنجاح.');
    }

    /**
     * حذف الصلاحية
     */
    public function destroy(Permission $permission)
    {
        // منع حذف الصلاحية إذا كانت مرتبطة بأدوار
        if ($permission->roles()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الصلاحية لأنها مرتبطة بأدوار.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'تم حذف الصلاحية بنجاح.');
    }
}
