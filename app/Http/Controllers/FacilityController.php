<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $total = Facility::count();
        $pages = ceil($total / $perPage);
        $offset = ($request->page - 1) * $perPage;

        $patients = Facility::skip($offset)->take($perPage)->withTrashed()->get();

        return view('admin.facilities.index', [
            'facilities' => $patients,
            'pages' => $pages,
            'currentPage' => $request->page ?? 1
        ]);
    }
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:facilities,username',
                'business_name' => 'required|string|max:255|unique:facilities,business_name',
                'email' => 'required|email|unique:facilities,email',
                'phone' => 'required|string|unique:facilities,phone',
                'address' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
                'type' => 'required',
            ]);

            $facility = new Facility();
            $facility->username = $validated['username'];
            $facility->business_name = $validated['business_name'];
            $facility->email = $validated['email'];
            $facility->phone = $validated['phone'];
            $facility->address = $validated['address'];
            $facility->password = bcrypt($validated['password']);
            $facility->type = $validated['type'];

            $facility->save();

            return redirect()->route('admin.facilities.index')->with('success', 'facility created successfully');
        }
        return view('admin.facilities.create');
    }
    public function show($id)
    {
        $facility = Facility::find($id);
        if (!$facility) {
            return response()->json(['message' => 'Facility not found'], 404);
        }
        return view('admin.facilities.show', ['facility' => $facility]);
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::find($id);
        if (!$facility) {
            return response()->json(['message' => 'facility not found'], 404);
        }
        if ($request->isMethod('put')) {
            $validated = $request->validate([
                'business_name' => 'required|string|max:255|unique:facilities,business_name,' . $facility->id,
                'phone' => 'required|string|unique:facilities,phone,' . $facility->id,
                'address' => 'required|string|max:255',
                'type' => 'required',
            ]);
            $facility->update($validated);
            return redirect()->route('admin.facilities.index')->with('success', 'facility updated successfully');
        }
        return view('admin.facilities.update', ['facility' => $facility]);
    }
    public function delete(Request $request, $id)
    {
        if ($request->isMethod('delete')) {
            $facility = Facility::find($id);
            if (!$facility) {
                return $this->respondWithError('facility not found', 404);
            }
            $facility->delete();
            return redirect()->route('admin.facilities.index')->with('success', 'Doctor deleted successfully');
        }
        $facility = Facility::find($id);
        if (!$facility) {
            return $this->respondWithError('Doctor not found', 404);
        }
        return view('admin.facilities.delete', compact('facility'));
    }

    public function restore($id)
    {
        $facility = Facility::withTrashed()->find($id);
        if (!$facility) {
            return $this->respondWithError('facility not found', 404);
        }
        $facility->restore();
        return redirect()->route('admin.facilities.index')->with('success', 'facility restored successfully');
    }
    public function destroy($id)
    {
        $facility = Facility::withTrashed()->find($id);
        if (!$facility) {
            return $this->respondWithError('Facility not found', 404);
        }
        $facility->forceDelete();
        return redirect()->route('admin.facilities.index')->with('success', 'facility permanently deleted');
    }
}