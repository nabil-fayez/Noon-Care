<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Service;
use App\Models\InsuranceCompany;
use App\Services\FacilityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FacilityController extends Controller
{
    protected $facilityService;

    public function __construct(FacilityService $facilityService)
    {
        $this->facilityService = $facilityService;
    }

    public function index(Request $request)
    {
        $query = Facility::with(['services', 'doctors']);

        // التصفية حسب التخصص
        if ($request->has('specialty_id')) {
            $query->whereHas('doctors.specialties', function ($q) use ($request) {
                $q->where('specialties.id', $request->specialty_id);
            });
        }

        // التصفية حسب الخدمة
        if ($request->has('service_id')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }

        // التصفية حسب الموقع
        if ($request->has('latitude') && $request->has('longitude')) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $radius = $request->radius ?? 10; // نصف قطر افتراضي 10 كم

            $query->selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$latitude, $longitude, $latitude])
                ->having('distance', '<=', $radius)
                ->orderBy('distance');
        } else {
            $query->orderBy('business_name');
        }

        $facilities = $request->has('per_page')
            ? $query->paginate($request->per_page)
            : $query->get();

        return view('admin.facilities.index', compact('facilities'));
    }

    public function show(Facility $facility)
    {
        $facility->load([
            'services',
            'doctors.specialties',
            'doctors.workingHours' => function ($query) use ($facility) {
                $query->where('facility_id', $facility->id);
            }
        ]);

        return view('admin.facilities.show', compact('facility'));
    }

    public function getAvailableSlots(Facility $facility, Request $request): JsonResponse
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after:today'
        ]);

        $slots = $this->facilityService->getAvailableTimeSlots(
            $facility,
            $request->doctor_id,
            \Carbon\Carbon::parse($request->date)
        );

        return response()->json(['slots' => $slots]);
    }

    public function updatePricing(Facility $facility, Request $request): JsonResponse
    {
        $request->validate([
            'pricings' => 'required|array',
            'pricings.*.service_id' => 'required|exists:services,id',
            'pricings.*.price' => 'required|numeric|min:0',
            'pricings.*.insurance_company_id' => 'nullable|exists:insurance_companies,id'
        ]);

        $this->facilityService->updateServicePricing($facility, $request->pricings);

        return response()->json(['message' => 'تم تحديث الأسعار بنجاح']);
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