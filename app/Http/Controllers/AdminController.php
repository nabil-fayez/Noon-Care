<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Facility;
use App\Models\Patient;
use App\Models\Role;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = [
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ];

            $validator = Validator::make($credentials, [
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            if (auth()->guard('admin')->attempt($credentials)) {
                if (!auth()->guard('admin')->user()->is_active) {
                    return $this->forceLogout($request);
                }
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            } else {
                return back()->withErrors(['email' => 'بيانات الاعتماد غير صحيحة'])->withInput();
            }
        }
        return view('auth.admin.login');
    }
    public function forceLogout(Request $request)
    {
        auth()->guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with(['error' => 'يوجد تقييد علي حسابك برجاء التواصل مع مديرك المباشر']);
    }
    public function logout(Request $request)
    {
        if ($request->isMethod('post')) {
            auth()->guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login');
        }
        return view('auth.admin.logout');
    }

    public function dashboard()
    {
        $stats = [
            'doctors' => Doctor::count(),
            'patients' => Patient::count(),
            'facilities' => Facility::count(),
            'total_appointments' => Appointment::count(),
            'today_appointments' => Appointment::whereDate('created_at', Date::today())->count(),
            'total_new_appointments' => Appointment::where('status', '=', 'new')->count(),
            'total_confirmed_appointments' => Appointment::where('status', '=', 'confirmed')->count(),
            'total_cancelled_appointments' => Appointment::where('status', '=', 'cancelled')->count(),
            'total_done_appointments' => Appointment::where('status', '=', 'done')->count(),
            'revenue' => 0
        ];

        $recentAppointments = Appointment::with(['patient', 'doctor'])->latest()->take(10)->get();
        $recentDoctors = Doctor::with('specialties')->latest()->take(5)->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentAppointments' => $recentAppointments,
            'recentDoctors' => $recentDoctors
        ]);
    }

    /**
     * عرض قائمة المسؤولين
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Admin::class);

        $search = $request->get('search');
        $status = $request->get('status');

        $admins = Admin::with('role')
            ->when($search, function ($query) use ($search) {
                return $query->search($search);
            })
            ->when($status, function ($query) use ($status) {
                if ($status === 'active') {
                    return $query->active();
                } elseif ($status === 'inactive') {
                    return $query->inactive();
                }
            })
            ->orderBy('name')
            ->paginate(10);

        return view('admin.index', compact('admins', 'search', 'status'));
    }

    /**
     * عرض نموذج إنشاء مسؤول جديد
     */
    public function create()
    {
        $this->authorize('create', Admin::class);

        $roles = Role::all();
        return view('admin.create', compact('roles'));
    }

    /**
     * حفظ المسؤول الجديد
     */
    public function store(Request $request)
    {
        $this->authorize('create', Admin::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'sometimes|boolean'
        ]);

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم إنشاء المسؤول بنجاح.');
    }

    /**
     * عرض تفاصيل المسؤول
     */
    public function show(Admin $admin)
    {
        $this->authorize('view', $admin);

        $admin->load('role', 'logs');
        return view('admin.show', compact('admin'));
    }

    /**
     * عرض نموذج تعديل المسؤول
     */
    public function edit(Admin $admin)
    {
        $this->authorize('update', $admin);

        $roles = Role::all();
        return view('admin.edit', compact('admin', 'roles'));
    }

    /**
     * تحديث بيانات المسؤول
     */
    public function update(Request $request, Admin $admin)
    {
        $this->authorize('update', $admin);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($admin->id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'sometimes|boolean'
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'is_active' => $validated['is_active'] ?? $admin->is_active,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم تحديث المسؤول بنجاح.');
    }

    /**
     * عرض نموذج تأكيد الحذف
     */
    public function delete(Admin $admin)
    {
        $this->authorize('delete', $admin);

        return view('admin.delete', compact('admin'));
    }

    /**
     * حذف المسؤول
     */
    public function destroy(Admin $admin)
    {
        $this->authorize('delete', $admin);

        // لا يمكن حذف المسؤول نفسه
        if ($admin->id === auth('admin')->id()) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف حسابك الشخصي.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم حذف المسؤول بنجاح.');
    }

    /**
     * تغيير حالة المسؤول
     */
    public function toggleStatus(Admin $admin)
    {
        $this->authorize('toggleStatus', $admin);

        // لا يمكن تعطيل المسؤول نفسه
        if ($admin->id === auth('admin')->id()) {
            return redirect()->back()
                ->with('error', 'لا يمكن تعطيل حسابك الشخصي.');
        }

        $admin->update([
            'is_active' => !$admin->is_active
        ]);

        $status = $admin->is_active ? 'تم تفعيل' : 'تم تعطيل';

        return redirect()->back()
            ->with('success', "{$status} المسؤول بنجاح.");
    }

    /**
     * استعادة مسؤول محذوف
     */
    public function restore($id)
    {
        $admin = Admin::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $admin);

        $admin->restore();

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم استعادة المسؤول بنجاح.');
    }

    /**
     * حذف مسؤول نهائيًا
     */
    public function forceDestroy($id)
    {
        $admin = Admin::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $admin);

        $admin->forceDelete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم حذف المسؤول نهائيًا بنجاح.');
    }
}