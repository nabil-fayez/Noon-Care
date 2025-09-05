@extends('layouts.app')

@section('title', 'Noon-Care')

@section('content')
    <!-- قسم الهيرو -->
    <section class="hero-section py-5"
        style="background: linear-gradient(rgba(44, 90, 160, 0.8), rgba(44, 90, 160, 0.8)), url('https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1632&q=80'); background-size: cover; background-position: center; color: white;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold">رعاية طبية أفضل لجميع أفراد الأسرة</h1>
                    <p class="lead">ابحث عن أفضل الأطباء، احجز موعدك بسهولة، واحصل على رعاية طبية متميزة في أي وقت.</p>
                    <div class="mt-4">
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-light btn-lg me-3">ابحث عن طبيب</a>
                        <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline-light btn-lg">استعرض
                            المنشآت</a>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('storage/images/medical_team.png') }}" alt="فريق طبي" class="img-fluid"
                        style="max-height: 400px;">
                </div>
            </div>
        </div>
    </section>

    <!-- قسم الخدمات -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">خدماتنا</h2>
                <p class="text-muted">نوفر مجموعة متكاملة من الخدمات الطبية لتلبية جميع احتياجاتك</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-search-heart display-4 text-primary"></i>
                            </div>
                            <h4 class="card-title">ابحث عن طبيب</h4>
                            <p class="card-text">ابحث عن أفضل الأطباء حسب التخصص، الموقع، والتقييمات.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-calendar-check display-4 text-primary"></i>
                            </div>
                            <h4 class="card-title">احجز موعدك</h4>
                            <p class="card-text">احجز موعدك بسهولة مع الطبيب المناسب في الوقت المناسب.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-file-medical display-4 text-primary"></i>
                            </div>
                            <h4 class="card-title">السجل الطبي</h4>
                            <p class="card-text">احتفظ بسجل طبي كامل يمكنك الوصول إليه في أي وقت.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- قسم الإحصائيات -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6 text-center mb-4">
                    <h2 class="fw-bold text-primary">150+</h2>
                    <p class="text-muted">طبيب معتمد</p>
                </div>
                <div class="col-md-3 col-6 text-center mb-4">
                    <h2 class="fw-bold text-primary">50+</h2>
                    <p class="text-muted">منشأة طبية</p>
                </div>
                <div class="col-md-3 col-6 text-center mb-4">
                    <h2 class="fw-bold text-primary">5,000+</h2>
                    <p class="text-muted">مريض راضٍ</p>
                </div>
                <div class="col-md-3 col-6 text-center mb-4">
                    <h2 class="fw-bold text-primary">10,000+</h2>
                    <p class="text-muted">موعد تم حجزه</p>
                </div>
            </div>
        </div>
    </section>
@endsection
