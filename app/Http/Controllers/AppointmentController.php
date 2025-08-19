<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::all();
        return $this->respondWithSuccess($appointments);
    }

    public function show($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return $this->respondWithError('Appointment not found', 404);
        }
        return $this->respondWithSuccess($appointment);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        $appointment = Appointment::create($request->all());
        return $this->respondWithSuccess($appointment, 'Appointment created successfully');
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return $this->respondWithError('Appointment not found', 404);
        }

        $this->validateRequest($request, [
            'patient_id' => 'sometimes|required|exists:patients,id',
            'doctor_id' => 'sometimes|required|exists:doctors,id',
            'appointment_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|string|max:255',
        ]);

        $appointment->update($request->all());
        return $this->respondWithSuccess($appointment, 'Appointment updated successfully');
    }

    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return $this->respondWithError('Appointment not found', 404);
        }

        $appointment->delete();
        return $this->respondWithSuccess(null, 'Appointment deleted successfully');
    }
}
