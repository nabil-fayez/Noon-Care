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

        $doctors = Doctor::skip($offset)->take($perPage)->get();

        return view('admin.doctors.index', [
            'doctors' => $doctors,
            'pages' => $pages,
            'currentPage' => $request->page ?? 1
        ]);
    }
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validateRequest($request, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:doctors,email',
                'password' => 'required|string|min:8|confirmed',
                'specialization' => 'required|string|max:255',
            ]);

            $doctor = Doctor::create($request->all());
            return redirect()->route('admin.doctors.index')->with('success', 'Doctor created successfully');
        }
        $specialties = Specialty::all(); // جلب كل التخصصات
        return view('admin.doctors.create', compact('specialties'));
    }

    public function show($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return $this->respondWithError('Doctor not found', 404);
        }
        return $this->respondWithSuccess($doctor);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email',
            'password' => 'required|string|min:8|confirmed',
            'specialization' => 'required|string|max:255',
        ]);

        $doctor = Doctor::create($request->all());
        return $this->respondWithSuccess($doctor, 'Doctor created successfully');
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return $this->respondWithError('Doctor not found', 404);
        }

        $this->validateRequest($request, [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:doctors,email,' . $doctor->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'specialization' => 'sometimes|required|string|max:255',
        ]);

        $doctor->update($request->all());
        return $this->respondWithSuccess($doctor, 'Doctor updated successfully');
    }

    public function destroy($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return $this->respondWithError('Doctor not found', 404);
        }

        $doctor->delete();
        return $this->respondWithSuccess(null, 'Doctor deleted successfully');
    }
}
