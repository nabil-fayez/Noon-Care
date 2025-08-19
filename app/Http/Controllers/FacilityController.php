<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::all();
        return $this->respondWithSuccess($facilities);
    }

    public function show($id)
    {
        $facility = Facility::find($id);
        if (!$facility) {
            return $this->respondWithError('Facility not found', 404);
        }
        return $this->respondWithSuccess($facility);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer',
        ]);

        $facility = Facility::create($request->all());
        return $this->respondWithSuccess($facility, 'Facility created successfully');
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::find($id);
        if (!$facility) {
            return $this->respondWithError('Facility not found', 404);
        }

        $this->validateRequest($request, [
            'name' => 'sometimes|required|string|max:255',
            'location' => 'sometimes|required|string|max:255',
            'capacity' => 'sometimes|required|integer',
        ]);

        $facility->update($request->all());
        return $this->respondWithSuccess($facility, 'Facility updated successfully');
    }

    public function destroy($id)
    {
        $facility = Facility::find($id);
        if (!$facility) {
            return $this->respondWithError('Facility not found', 404);
        }

        $facility->delete();
        return $this->respondWithSuccess(null, 'Facility deleted successfully');
    }
}
