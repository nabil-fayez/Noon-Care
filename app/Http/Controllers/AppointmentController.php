<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Facility;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Whoops\Exception\Formatter;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([]);
        dd('add data vildation roles');
        $doctorId = Doctor::where('username', '=', $validated['doctor_username'])->id();
        $patientId = Auth::guard('patient')->user()->id();
        $facilityId = Facility::where('username', '=', $validated['facility_username'])->id();
        $datetime = $validated['date'] . ' ' . $validated['time'];
        $notes = $validated['notes'];

        $appointment = $this->appointmentService->bookAppointment($patientId, $doctorId, $facilityId, $datetime, $notes);

        return response()->json($appointment, 201);
    }

    public function cancel(Appointment $appointment)
    {
        // إلغاء الموعد (سيتم تنفيذ المنطق في Service)
    }
}