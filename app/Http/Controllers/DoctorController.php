<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $total = Doctor::count();
        $pages = ceil($total / $perPage);
        $offset = ($request->page - 1) * $perPage;

        $doctors = Doctor::skip($offset)->take($perPage)->withTrashed()->get();

        return view('admin.doctors.index', [
            'doctors' => $doctors,
            'pages' => $pages,
            'currentPage' => $request->page ?? 1
        ]);
    }
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:doctors,username',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:doctors,email',
                'password' => 'required|string|min:8|confirmed',
                'specializations' => 'required|array|min:1',
                'specializations.*' => 'exists:specialties,id',
                'phone' => 'required|string|max:20',
            ]);

            // إنشاء الطبيب
            $doctor = new Doctor();
            $doctor->username = $validated['username'];
            $doctor->first_name = $validated['first_name'];
            $doctor->last_name = $validated['last_name'];
            $doctor->email = $validated['email'];
            $doctor->password = bcrypt($validated['password']);
            $doctor->save(); // لازم يتم الحفظ هنا أولًا

            // ربط التخصصات بعد الحفظ
            $doctor->specialties()->attach($validated['specializations']);
            return redirect()->route('admin.doctors.index')->with('success', 'Doctor created successfully');
        }
        $specialties = Specialty::all(); // جلب كل التخصصات
        return view('admin.doctors.create', compact('specialties'));
    }

    public function show($id)
    {
        $doctor = Doctor::withTrashed()->find($id);
        if (!$doctor) {
            return $this->respondWithError('Doctor not found', 404);
        }
        return view('admin.doctors.show', ['doctor' => $doctor]);
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('put')) {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:doctors,username,' . $id,
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:doctors,email,' . $id,
                'specializations' => 'required|array|min:1',
                'specializations.*' => 'exists:specialties,id',
            ]);

            $doctor = Doctor::findOrFail($id);
            $doctor->update([
                'username' => $validated['username'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
            ]);
            $doctor->specialties()->sync($validated['specializations']);

            return redirect()->route('admin.doctors.index')->with('success', 'تم تعديل بيانات الطبيب بنجاح');
        }
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return $this->respondWithError('Doctor not found', 404);
        }
        $specialties = Specialty::all(); // جلب كل التخصصات
        $selectedSpecialties = $doctor->specialties->pluck('id')->toArray();

        return view('admin.doctors.update', compact('doctor', 'specialties', 'selectedSpecialties'));
    }

    public function delete(Request $request, $id)
    {
        if ($request->isMethod('delete')) {
            $doctor = Doctor::find($id);
            if (!$doctor) {
                return $this->respondWithError('Doctor not found', 404);
            }
            $doctor->specialties()->detach(); // فصل التخصصات المرتبطة
            $doctor->delete();
            return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully');
        }
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return $this->respondWithError('Doctor not found', 404);
        }
        $specialties = Specialty::all(); // جلب كل التخصصات
        $selectedSpecialties = $doctor->specialties->pluck('id')->toArray();

        return view('admin.doctors.delete', compact('doctor', 'specialties', 'selectedSpecialties'));
    }

    public function restore($id)
    {
        $doctor = Doctor::withTrashed()->find($id);
        if (!$doctor) {
            return $this->respondWithError('Doctor not found', 404);
        }
        $doctor->restore();
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor restored successfully');
    }
    public function destroy($id)
    {
        $doctor = Doctor::withTrashed()->find($id);
        if (!$doctor) {
            return $this->respondWithError('Doctor not found', 404);
        }
        $doctor->specialties()->detach(); // فصل التخصصات المرتبطة
        $doctor->forceDelete();
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor permanently deleted');
    }
}
