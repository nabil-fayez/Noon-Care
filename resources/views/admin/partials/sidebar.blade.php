            <div class="col-md-2">
                <div class="list-group">

                    <a href="{{ route('admin.dashboard') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">لوحة
                        التحكم</a>
                    @if (request()->user()->hasPermission('doctors.view'))
                        <a href="{{ route('admin.doctors.index') }}"
                            class="list-group-item list-group-item-action {{ request()->routeIs('admin.doctors.index') ? 'active' : '' }}">الاطباء</a>
                    @endif
                    @if (request()->user()->hasPermission('specialties.view'))
                        <a href="{{ route('admin.specialties.index') }}"
                            class="list-group-item list-group-item-action {{ request()->routeIs('admin.specialties.index') ? 'active' : '' }}">التخصصات</a>
                    @endif
                    @if (request()->user()->hasPermission('patients.view'))
                        <a href="{{ route('admin.patients.index') }}"
                            class="list-group-item list-group-item-action {{ request()->routeIs('admin.patients.index') ? 'active' : '' }}">المرضي</a>
                    @endif
                    @if (request()->user()->hasPermission('facilities.view'))
                        <a href="{{ route('admin.facilities.index') }}"
                            class="list-group-item list-group-item-action {{ request()->routeIs('admin.facilities.index') ? 'active' : '' }}">المنشأت</a>
                    @endif
                    @if (request()->user()->hasPermission('appointments.view'))
                        <a href="{{ route('admin.appointments.index') }}"
                            class="list-group-item list-group-item-action {{ request()->routeIs('admin.appointments.index') ? 'active' : '' }}">المواعيد</a>
                    @endif
                    @if (request()->user()->hasPermission('error_logs.view'))
                        <a href="{{ route('admin.error-logs.index') }}"
                            class="list-group-item list-group-item-action {{ request()->routeIs('admin.error-logs.index') ? 'active' : '' }}">سجل
                            المشاكل</a>
                    @endif
                    @if (request()->user()->hasPermission('admins.view'))
                        <a href="{{ route('admin.admins.index') }}"
                            class="list-group-item list-group-item-action {{ request()->routeIs('admin.admins.index') ? 'active' : '' }}">
                            إدارة المسؤولين
                        </a>
                    @endif
                    @if (request()->user()->hasPermission('roles.view') || request()->user()->hasPermission('permissions.view'))
                        <a href="{{ route('admin.roles.index') }}"
                            class="list-group-item list-group-item-action {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">الأدوار</a>
                    @endif
                    @if (request()->user()->hasPermission('permissions.view'))
                        <a href="{{ route('admin.permissions.index') }}"
                            class="list-group-item list-group-item-action {{ request()->routeIs('admin.permissions.index') ? 'active' : '' }}">الصلاحيات</a>
                    @endif
                    <a href="{{ route('admin.settings') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('admin.settings') ? 'active' : '' }}">الإعدادات</a>
                    <a href="{{ route('admin.logout') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('admin.logout') ? 'active' : '' }}">تسجيل
                        الخروج</a>
                </div>
            </div>
