<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::all();
        return $this->respondWithSuccess($specialties);
    }

    public function show($id)
    {
        $specialty = Specialty::find($id);
        if (!$specialty) {
            return $this->respondWithError('Specialty not found', 404);
        }
        return $this->respondWithSuccess($specialty);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, [
            'name' => 'required|string|max:255',
        ]);

        $specialty = Specialty::create($request->all());
        return $this->respondWithSuccess($specialty, 'Specialty created successfully');
    }

    public function update(Request $request, $id)
    {
        $specialty = Specialty::find($id);
        if (!$specialty) {
            return $this->respondWithError('Specialty not found', 404);
        }

        $this->validateRequest($request, [
            'name' => 'sometimes|required|string|max:255',
        ]);

        $specialty->update($request->all());
        return $this->respondWithSuccess($specialty, 'Specialty updated successfully');
    }

    public function destroy($id)
    {
        $specialty = Specialty::find($id);
        if (!$specialty) {
            return $this->respondWithError('Specialty not found', 404);
        }

        $specialty->delete();
        return $this->respondWithSuccess(null, 'Specialty deleted successfully');
    }
}
