<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Type\Time;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = [
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ];
            if (auth()->guard('admin')->attempt($credentials)) {
                return redirect()->route('admin.dashboard');
            } else {
                return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
            }
        }
        return view('auth.admin.login');
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
            'today_appointments' => Appointment::where('created_at', '=', Date::today())->count(),
            'total_appointments' => Appointment::count(),
            'revenue' => 0
        ];
        $recentAppointments = Appointment::latest()->take(10)->get();
        return view('admin.dashboard', ['stats' => $stats, 'recentAppointments' => $recentAppointments]);
    }
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
