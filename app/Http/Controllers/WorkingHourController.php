<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkingHourController extends Controller
{
    public function index()
    {
        $workingHours = WorkingHour::all();
        return $this->respondWithSuccess($workingHours);
    }

    public function show($id)
    {
        $workingHour = WorkingHour::find($id);
        if (!$workingHour) {
            return $this->respondWithError('Working Hour not found', 404);
        }
        return $this->respondWithSuccess($workingHour);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, [
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $workingHour = WorkingHour::create($request->all());
        return $this->respondWithSuccess($workingHour, 'Working Hour created successfully');
    }

    public function update(Request $request, $id)
    {
        $workingHour = WorkingHour::find($id);
        if (!$workingHour) {
            return $this->respondWithError('Working Hour not found', 404);
        }

        $this->validateRequest($request, [
            'doctor_id' => 'sometimes|required|exists:doctors,id',
            'day_of_week' => 'sometimes|required|integer|min:0|max:6',
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time' => 'sometimes|required|date_format:H:i|after:start_time',
        ]);

        $workingHour->update($request->all());
        return $this->respondWithSuccess($workingHour, 'Working Hour updated successfully');
    }

    public function destroy($id)
    {
        $workingHour = WorkingHour::find($id);
        if (!$workingHour) {
            return $this->respondWithError('Working Hour not found', 404);
        }

        $workingHour->delete();
        return $this->respondWithSuccess(null, 'Working Hour deleted successfully');
    }
}
