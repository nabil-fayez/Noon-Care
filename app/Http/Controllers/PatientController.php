<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{

    public function index(Request $request)
    {
        $perPage = 10;
        $total = Patient::count();
        $pages = ceil($total / $perPage);
        $offset = ($request->page - 1) * $perPage;

        $patients = Patient::skip($offset)->take($perPage)->withTrashed()->get();

        return view('admin.patients.index', [
            'patients' => $patients,
            'pages' => $pages,
            'currentPage' => $request->page ?? 1
        ]);
    }
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:patients,username',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:patients,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $doctor = new Patient();
            $doctor->username = $validated['username'];
            $doctor->first_name = $validated['first_name'];
            $doctor->last_name = $validated['last_name'];
            $doctor->email = $validated['email'];
            $doctor->password = bcrypt($validated['password']);
            $doctor->save();

            return redirect()->route('admin.patients.index')->with('success', 'Doctor created successfully');
        }
        return view('admin.patients.create');
    }
    public function show($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }
        return view('admin.patients.show', ['patient' => $patient]);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }
        if ($request->isMethod('put')) {
            $validated = $request->validate([
                'username' => 'sometimes|required|string|max:255|unique:patients,username,' . $patient->id,
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:patients,email,' . $patient->id,
                'password' => 'sometimes|required|string|min:8',
            ]);
            $patient->update($validated);
            return redirect()->route('admin.patients.index')->with('success', 'Patient updated successfully');
        }
        return view('admin.patients.update', ['patient' => $patient]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->isMethod('delete')) {
            $patient = Patient::find($id);
            if (!$patient) {
                return $this->respondWithError('patient not found', 404);
            }
            $patient->delete();
            return redirect()->route('admin.patients.index')->with('success', 'Doctor deleted successfully');
        }
        $patient = Patient::find($id);
        if (!$patient) {
            return $this->respondWithError('Doctor not found', 404);
        }


        return view('admin.patients.delete', compact('patient'));
    }

    public function restore($id)
    {
        $patient = Patient::withTrashed()->find($id);
        if (!$patient) {
            return $this->respondWithError('Doctor not found', 404);
        }
        $patient->restore();
        return redirect()->route('admin.patients.index')->with('success', 'patient restored successfully');
    }
    public function destroy($id)
    {
        $patient = Patient::withTrashed()->find($id);
        if (!$patient) {
            return $this->respondWithError('Doctor not found', 404);
        }
        $patient->forceDelete();
        return redirect()->route('admin.patients.index')->with('success', 'patient permanently deleted');
    }
}