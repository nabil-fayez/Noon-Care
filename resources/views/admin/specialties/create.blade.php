<!-- resources/views/admin/specialties/create.blade.php -->
<!-- resources/views/admin/specialties/edit.blade.php -->

@extends('layouts.admin')

@section('title', isset($specialty) ? 'تعديل التخصص: ' . $specialty->name : 'إضافة تخصص جديد - Noon Care')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ isset($specialty) ? 'تعديل التخصص' : 'إضافة تخصص جديد' }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" 
                          action="{{ isset($specialty) ? route('admin.specialties.update', $specialty) : route('admin.specialties.store') }}" 
                          enctype="multipart/form-data">
                        @csrf
                        @if(isset($specialty))
                            @method('PUT')
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم التخصص <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $specialty->name ?? '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color" class="form-label">لون التخصص</label>
                                    <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                           id="color" name="color" value="{{ old('color', $specialty->color ?? '#0d6efd') }}">
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $specialty->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="icon" class="form-label">أيقونة التخصص</label>
                            <input type="file" class="form-control @error('icon') is-invalid @enderror" 
                                   id="icon" name="icon" accept="image/*">
                            <div class="form-text">يسمح بملفات الصور فقط (JPG, PNG, GIF, SVG) - الحجم الأقصى: 2MB</div>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if(isset($specialty) && $specialty->icon_url)
                                <div class="mt-2">
                                    <p>الأيقونة الحالية:</p>
                                    <img src="{{ $specialty->icon_url }}" class="rounded" width="60" height="60" alt="أيقونة التخصص">
                                </div>
                            @endif
                        </div>
                        
                        @if(isset($specialty))
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" 
                                   id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $specialty->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                التخصص نشط
                            </label>
                        </div>
                        @endif
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> {{ isset($specialty) ? 'تحديث' : 'إنشاء' }}
                            </button>
                            <a href="{{ route('admin.specialties.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection