<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorFacilityController extends Controller
{
    public function index()
    {
        $doctorFacilities = DoctorFacility::all();
        return $this->respondWithSuccess($doctorFacilities);
    }

    public function show($id)
    {
        $doctorFacility = DoctorFacility::find($id);
        if (!$doctorFacility) {
            return $this->respondWithError('Doctor Facility not found', 404);
        }
        return $this->respondWithSuccess($doctorFacility);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, [
            'doctor_id' => 'required|exists:doctors,id',
            'facility_id' => 'required|exists:facilities,id',
        ]);

        $doctorFacility = DoctorFacility::create($request->all());
        return $this->respondWithSuccess($doctorFacility, 'Doctor Facility created successfully');
    }

    public function update(Request $request, $id)
    {
        $doctorFacility = DoctorFacility::find($id);
        if (!$doctorFacility) {
            return $this->respondWithError('Doctor Facility not found', 404);
        }

        $this->validateRequest($request, [
            'doctor_id' => 'sometimes|required|exists:doctors,id',
            'facility_id' => 'sometimes|required|exists:facilities,id',
        ]);

        $doctorFacility->update($request->all());
        return $this->respondWithSuccess($doctorFacility, 'Doctor Facility updated successfully');
    }

    public function destroy($id)
    {
        $doctorFacility = DoctorFacility::find($id);
        if (!$doctorFacility) {
            return $this->respondWithError('Doctor Facility not found', 404);
        }

        $doctorFacility->delete();
        return $this->respondWithSuccess(null, 'Doctor Facility deleted successfully');
    }
}
