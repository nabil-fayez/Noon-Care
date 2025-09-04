            <div class="col-md-2">
                <div class="list-group">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action active">لوحة
                        التحكم</a>
                    <a href="{{ route('admin.doctors.index') }}"
                        class="list-group-item list-group-item-action">الأطباء</a>
                    <a href="{{ route('admin.specialties.index') }}"
                        class="list-group-item list-group-item-action">التخصصات
                        الطبية</a>
                    <a href="{{ route('admin.patients.index') }}"
                        class="list-group-item list-group-item-action">المرضى</a>
                    <a href="{{ route('admin.facilities.index') }}"
                        class="list-group-item list-group-item-action">المنشآت</a>
                    <a href="{{ route('admin.appointments.index') }}"
                        class="list-group-item list-group-item-action">المواعيد</a>
                    <a href="{{ route('admin.error-logs.index') }}" class="list-group-item list-group-item-action">سجل
                        المشاكل
                        والاخطاء</a>

                    <a href="{{ route('admin.settings') }}" class="list-group-item list-group-item-action">الإعدادات</a>
                    <a href="{{ route('admin.logout') }}" class="list-group-item list-group-item-action">تسجيل
                        الخروج</a>
                </div>
            </div>
