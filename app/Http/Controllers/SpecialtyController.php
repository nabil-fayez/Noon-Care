<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $total = Specialty::count();
        $pages = ceil($total / $perPage);
        $offset = ($request->page - 1) * $perPage;

        $specialties = Specialty::skip($offset)->take($perPage)->get();

        return view('admin.specialties.index', [
            'specialties' => $specialties,
            'pages' => $pages,
            'currentPage' => $request->page ?? 1
        ]);
    }


    public function show($id)
    {
        $specialty = Specialty::find($id);
        if (!$specialty) {
            return $this->respondWithError('Specialty not found', 404);
        }
        return view('admin.specialties.show', ['specialty' => $specialty]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validateRequest($request, [
                'name' => 'required|string|max:255|unique:specialties,name',
            ]);
            $specialty = Specialty::create($request->all());
            return redirect()->route('admin.specialties.index');
        }
        return view('admin.specialties.create');
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $this->validateRequest($request, [
                'name' => 'required|string|max:255|unique:specialties,name,' . $id,
            ]);
            $specialty = Specialty::find($id);
            if (!$specialty) {
                return $this->respondWithError('Specialty not found', 404);
            }
            $specialty->update($request->all());
            return redirect()->route('admin.specialties.index');
        }
        $specialty = Specialty::find($id);
        if (!$specialty) {
            return $this->respondWithError('Specialty not found', 404);
        }
        return view('admin.specialties.update', ['specialty' => $specialty]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $specialty = Specialty::find($id);
            if (!$specialty) {
                return $this->respondWithError('Specialty not found', 404);
            }
            $specialty->delete();
            return redirect()->route('admin.specialties.index');
        }
        $specialty = Specialty::find($id);
        if (!$specialty) {
            return $this->respondWithError('Specialty not found', 404);
        }
        return view('admin.specialties.delete', ['specialty' => $specialty]);
    }
}