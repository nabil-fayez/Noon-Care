<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DoctorSpecialty;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorSpecialtyController extends Controller
{
    public function index()
    {
        $doctorSpecialties = DoctorSpecialty::all();
        return $this->respondWithSuccess($doctorSpecialties);
    }

    public function show($id)
    {
        $doctorSpecialty = DoctorSpecialty::find($id);
        if (!$doctorSpecialty) {
            return $this->respondWithError('Doctor Specialty not found', 404);
        }
        return $this->respondWithSuccess($doctorSpecialty);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, [
            'doctor_id' => 'required|exists:doctors,id',
            'specialty_id' => 'required|exists:specialties,id',
        ]);

        $doctorSpecialty = DoctorSpecialty::create($request->all());
        return $this->respondWithSuccess($doctorSpecialty, 'Doctor Specialty created successfully');
    }

    public function update(Request $request, $id)
    {
        $doctorSpecialty = DoctorSpecialty::find($id);
        if (!$doctorSpecialty) {
            return $this->respondWithError('Doctor Specialty not found', 404);
        }

        $this->validateRequest($request, [
            'doctor_id' => 'sometimes|required|exists:doctors,id',
            'specialty_id' => 'sometimes|required|exists:specialties,id',
        ]);

        $doctorSpecialty->update($request->all());
        return $this->respondWithSuccess($doctorSpecialty, 'Doctor Specialty updated successfully');
    }

    public function destroy($id)
    {
        $doctorSpecialty = DoctorSpecialty::find($id);
        if (!$doctorSpecialty) {
            return $this->respondWithError('Doctor Specialty not found', 404);
        }

        $doctorSpecialty->delete();
        return $this->respondWithSuccess(null, 'Doctor Specialty deleted successfully');
    }

    // public function topSpecialties()
    // {
    //     $topSpecialties = DoctorSpecialty::select('specialty_id', DB::raw('COUNT(doctor_id) as doctor_count'))
    //         ->groupBy('specialty_id')
    //         ->orderByDesc('doctor_count')
    //         ->limit(10)
    //         ->get();

    //     // جلب أسماء التخصصات المرتبطة بكل ID
    //     $topSpecialties = $topSpecialties->map(function ($item) {
    //         $specialty = Specialty::find($item->specialty_id);
    //         return [
    //             'name' => $specialty ? $specialty->name : 'غير معروف',
    //             'count' => $item->doctor_count,
    //         ];
    //     });
    // }
}
