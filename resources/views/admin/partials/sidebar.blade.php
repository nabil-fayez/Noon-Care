            <div class="col-md-2">
                <div class="list-group">

                    <a href="{{ route('admin.dashboard') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">لوحة
                        التحكم</a>
                    <a href="{{ route('admin.doctors.index') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('admin.doctors.index') ? 'active' : '' }}">الأطباء</a>
                    <a href="{{ route('admin.specialties.index') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('admin.specialties.index') ? 'active' : '' }}">التخصصات
                        الطبية</a>
                    <a href="{{ route('admin.patients.index') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('admin.patients.index') ? 'active' : '' }}">المرضى</a>
                    <a href="{{ route('admin.facilities.index') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('admin.facilities.index') ? 'active' : '' }}">المنشآت</a>
                    <a href="{{ route('admin.appointments.index') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('admin.appointments.index') ? 'active' : '' }}">المواعيد</a>
                    <a href="{{ route('admin.error-logs.index') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('admin.error-logs.index') ? 'active' : '' }}">سجل
                        المشاكل
                        والاخطاء</a>

                    <a href="{{ route('admin.settings') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('admin.settings') ? 'active' : '' }}">الإعدادات</a>
                    <a href="{{ route('admin.logout') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('admin.logout') ? 'active' : '' }}">تسجيل
                        الخروج</a>
                </div>
            </div>
