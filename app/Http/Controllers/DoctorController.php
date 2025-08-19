<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::all();
        return $this->respondWithSuccess($doctors);
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
