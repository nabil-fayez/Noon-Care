<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use App\Services\SpecialtyService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SpecialtyController extends Controller
{
    protected $specialtyService;

    public function __construct(SpecialtyService $specialtyService)
    {
        $this->specialtyService = $specialtyService;
    }

    /**
     * عرض قائمة التخصصات
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $specialties = $this->specialtyService->getSpecialties($request->all(), $perPage);
            
            return view('admin.specialties.index', compact('specialties'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب التخصصات: ' . $e->getMessage());
        }
    }
/**
 * عرض التخصصات للواجهة الرئيسية
 */
public function publicIndex(Request $request)
{
    try {
        $perPage = $request->get('per_page', 12);
        $specialties = Specialty::active()
            ->withCount('doctors')
            ->when($request->has('search'), function($query) use ($request) {
                return $query->search($request->search);
            })
            ->orderBy('doctors_count', 'desc')
            ->paginate($perPage);
        
        return view('specialties.index', compact('specialties'));
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'حدث خطأ أثناء جلب التخصصات: ' . $e->getMessage());
    }
}
    /**
     * عرض نموذج إنشاء تخصص جديد
     */
    public function create()
    {
        return view('admin.specialties.create');
    }

    /**
     * حفظ تخصص جديد
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validateSpecialtyData($request);
            
            $specialty = $this->specialtyService->createSpecialty($validated);
            
            return redirect()->route('admin.specialties.index')
                ->with('success', 'تم إنشاء التخصص بنجاح.');
        } catch (\Exception $e) {

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء التخصص: ' . $e->getMessage());
        }
    }

    /**
     * عرض بيانات تخصص
     */
    public function show(Specialty $specialty)
    {
        try {
            $specialty->load(['doctors' => function($query) {
                $query->withCount('appointments');
            }]);
            
            return view('admin.specialties.show', compact('specialty'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات التخصص: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج تعديل تخصص
     */
    public function edit(Specialty $specialty)
    {
        return view('admin.specialties.update', compact('specialty'));
    }

    /**
     * تحديث بيانات تخصص
     */
    public function update(Request $request, Specialty $specialty)
    {
        try {
            $validated = $this->validateSpecialtyData($request, $specialty->id);

            $this->specialtyService->updateSpecialty($specialty, $validated);

            return redirect()->route('admin.specialty.show', $specialty)
                ->with('success', 'تم تحديث بيانات التخصص بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث التخصص: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج تأكيد الحذف
     */
    public function delete(Specialty $specialty)
    {
        try {
            return view('admin.specialties.delete', compact('specialty'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل صفحة الحذف: ' . $e->getMessage());
        }
    }

    /**
     * حذف تخصص
     */
    public function destroy(Specialty $specialty)
    {
        try {
            $this->specialtyService->deleteSpecialty($specialty);
            
            return redirect()->route('admin.specialties.index')
                ->with('success', 'تم حذف التخصص بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف التخصص: ' . $e->getMessage());
        }
    }

    /**
     * تبديل حالة التخصص
     */
    public function toggleStatus(Specialty $specialty)
    {
        try {
            $this->specialtyService->toggleStatus($specialty);
            
            $status = $specialty->is_active ? 'مفعل' : 'معطل';
            return redirect()->back()->with('success', "تم تغيير حالة التخصص إلى: $status");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تغيير حالة التخصص: ' . $e->getMessage());
        }
    }

    /**
     * التحقق من صحة بيانات التخصص
     */
    private function validateSpecialtyData(Request $request, $specialtyId = null)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('specialties')->ignore($specialtyId)
            ],
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean'
        ];

        return $request->validate($rules);
    }

    /**
     * API: الحصول على التخصصات
     */
    public function getSpecialtiesApi(Request $request)
    {
        try {
            $specialties = Specialty::active()
                ->when($request->has('search'), function($query) use ($request) {
                    return $query->search($request->search);
                })
                ->get(['id', 'name', 'icon']);
            
            return response()->json([
                'success' => true,
                'data' => $specialties
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب التخصصات'
            ], 500);
        }
    }
}