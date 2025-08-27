<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = 10;
        $doctors = Doctor::paginate($perPage, ['*'], 'page', $page);
        return view('admin.doctors.index', ['doctors' => $doctors]);
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
        return view('admin.doctors.create');
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