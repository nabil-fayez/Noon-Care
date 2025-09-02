<?php

namespace App\Services;

use App\Models\Specialty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SpecialtyService
{
    /**
     * الحصول على التخصصات مع التصفية والترتيب
     */
    public function getSpecialties(array $filters = [], $perPage = 10)
    {
        $query = Specialty::withCount('doctors');
        
        // التصفية حسب الحالة
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }
        
        // البحث
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }
        
        // الترتيب
        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDir = $filters['order_dir'] ?? 'desc';
        $query->orderBy($orderBy, $orderDir);
        
        return $query->paginate($perPage);
    }
    
    /**
     * إنشاء تخصص جديد
     */
    public function createSpecialty(array $data): Specialty
    {
        return DB::transaction(function () use ($data) {
            // معالجة الأيقونة إذا وجدت
            if (isset($data['icon'])) {
                $data['icon'] = $this->uploadIcon($data['icon']);
            }
            
            // تعيين لون افتراضي إذا لم يتم提供
            if (empty($data['color'])) {
                $data['color'] = $this->generateRandomColor();
            }
            
            return Specialty::create($data);
        });
    }
    
    /**
     * تحديث بيانات التخصص
     */
    public function updateSpecialty(Specialty $specialty, array $data): Specialty
    {
        return DB::transaction(function () use ($specialty, $data) {
            // معالجة الأيقونة إذا وجدت
            if (isset($data['icon'])) {
                // حذف الأيقونة القديمة إذا كانت موجودة
                if ($specialty->icon) {
                    Storage::delete($specialty->icon);
                }
                
                $data['icon'] = $this->uploadIcon($data['icon']);
            }
            
            $specialty->update($data);
            
            return $specialty;
        });
    }
    
    /**
     * حذف تخصص
     */
    public function deleteSpecialty(Specialty $specialty): bool
    {
        return DB::transaction(function () use ($specialty) {
            // حذف الأيقونة إذا كانت موجودة
            if ($specialty->icon) {
                Storage::delete($specialty->icon);
            }
            
            return $specialty->delete();
        });
    }
    
    /**
     * تبديل حالة التخصص
     */
    public function toggleStatus(Specialty $specialty): Specialty
    {
        $specialty->update(['is_active' => !$specialty->is_active]);
        return $specialty;
    }
    
    /**
     * رفع أيقونة التخصص
     */
    private function uploadIcon($icon): string
    {
        return $icon->store('specialties/icons', 'public');
    }
    
    /**
     * توليد لون عشوائي
     */
    private function generateRandomColor(): string
    {
        $colors = [
            '#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545',
            '#fd7e14', '#ffc107', '#198754', '#20c997', '#0dcaf0'
        ];
        
        return $colors[array_rand($colors)];
    }
    
    /**
     * الحصول على التخصصات الأكثر شيوعاً
     */
    public function getPopularSpecialties($limit = 10)
    {
        return Specialty::withCount('doctors')
            ->active()
            ->orderBy('doctors_count', 'desc')
            ->limit($limit)
            ->get();
    }
}