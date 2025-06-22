@php
    $currenturl =Request::segment(1);
@endphp

<style>
    .active1{
        color: var(--sidebar-hover) !important;
        background-color: #DDEDFF;
    }
    /* .active2{
        color: var(--sidebar-hover) !important;
        background-color: #DDEDFF;
    } */
</style>
       <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu">
            <div class="h-100" data-simplebar>
                <div id="sidebar-menu">
                    @php
                        $user = auth()->user();
                        $user_role = auth()->user()->role_id;
                    @endphp

                    @if($user_role > '1')
                        <ul id="side-menu">
                            {{-- @if ($user->haspermission(['dashboard-all'])) --}}
                                <li>
                                    <a href="{{url("home")}}" class="{{$currenturl== 'Admin' || $currenturl== 'home' ? 'active1' : '' }}">
                                    <i class="fontello icon-dashboard"></i>
                                        <span> Dashboard </span>
                                    </a>
                                </li>
                            {{-- @endif --}}
                            @if ($user->haspermission(['employee-all']))
                                <li>
                                    <a href="#employeeDropdown" data-toggle="collapse" class="{{ request()->is('add-resignation') || $currenturl == 'edit-resignation' || request()->is('employee/add-termination') || $currenturl == 'edit-termination' || request()->is('employee/add-promotion') || $currenturl == 'edit-promotion' || request()->is( 'employee/directory/*') || request()->is('employee/directory/edit-employee*') || request()->is('employee/directory/add-education*') || request()->is('employee/directory/add-employment*') || request()->is('employee/directory/add-account-detail*') || request()->is('employee/shift-management*') ? 'active1' : '' }}">
                                    <i class="fontello icon-briefcase-compound"></i>
                                        <span class=" badge-pill float-right"><i class="fontello icon-plus2"></i></span>
                                        <span>Employee</span>
                                    </a>
                                    <div class="collapse {{ request()->is('add-resignation') || $currenturl == 'edit-resignation' || request()->is('employee/add-termination') || $currenturl == 'edit-termination' || request()->is('employee/add-promotion') || $currenturl == 'edit-promotion' || request()->is( 'employee/directory/*') || request()->is('employee/directory/edit-employee*') || request()->is('employee/directory/add-education*') || request()->is('employee/directory/add-employment*') || request()->is('employee/directory/add-account-detail*') || request()->is('employee/shift-management*') ? 'show' : '' }}"" id="employeeDropdown">
                                        <ul class="nav-second-level">
                                            @if ($user->haspermission(['directory-all','directory-read','directory-delete']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt" ></div>
                                                <a href="{{url("employee/directory")}}" class="{{ request()->is('employee/directory/*') ? 'active1' : '' }}">Directory</a>
                                            </li>
                                            @endif
                                            @if ($user->haspermission(['designation-all','designation-read','designation-delete']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt"></div>
                                                <a href="{{url("employee/designation")}}">Designation</a>
                                            </li>
                                            @endif
                                            @if ($user->haspermission(['department-all','department-read','department-delete']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt"></div>
                                                <a href="{{url("employee/department")}}">Department</a>
                                            </li>
                                            @endif
                                            @if ($user->haspermission(['resignation-all','resignation-read','resignation-delete']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt"></div>
                                                <a href="{{url("employee/resignation")}}" class="{{request()->is( 'add-resignation') || $currenturl== 'edit-resignation' ? 'active1':''}}">Resignation</a>
                                            </li>
                                            @endif
                                            @if ($user->haspermission(['termination-all','termination-read','termination-delete']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt"></div>
                                                <a href="{{url("employee/termination")}}" class="{{request()->is( 'employee/add-termination') || $currenturl== 'edit-termination' ? 'active1':''}}">Termination</a>
                                            </li>
                                            @endif
                                            @if ($user->haspermission(['promotion-all','promotion-read','promotion-delete']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt" style="margin-left:0px;"></div>
                                                <a href="{{url("employee/promotion")}}" class="{{request()->is('employee/add-promotion') || $currenturl == 'edit-promotion' ? 'active1':''}}">Promotion</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if ($user->haspermission(['time-sheet-all']))
                                <li>
                                    <a href="#sidebarDashboards12" data-toggle="collapse" >
                                    <i class="fontello icon-attendance"></i>
                                        <span class=" badge-pill float-right"><i class="fontello icon-plus2"></i></span>
                                        <span>Time Sheet</span>
                                    </a>

                                    <div class="collapse" id="sidebarDashboards12">
                                        <ul class="nav-second-level">
                                            @if ($user->haspermission(['daily-all','daily-read']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt" ></div>
                                                <a href="{{route("daily.attend.sheet")}}">Daily</a>
                                            </li>
                                            @endif
                                            @if ($user->haspermission(['monthly-all','monthly-read']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt"></div>
                                                <a href="{{route("monthly.attend.sheet")}}">Monthly</a>
                                            </li>
                                            @endif
                                            @if ($user->haspermission(['yearly-all','yearly-read']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt" style="margin-left:0px;"></div>
                                                <a href="{{route("yearly.attend.sheet")}}">Yearly</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if($user->haspermission(['leave-request-all','leave-request-read']))
                                <li>
                                    <a href="#sidebarDashboards20" data-toggle="collapse" class="{{ request()->is('leave-request/add-leave*') || request()->is('leave-request/edit-leave*') || request()->is('leave-settings/add-leave-setup*') || request()->is('leave-settings/edit-leave-setup*') ? 'active1' : '' }}">
                                    <i class="fontello icon-statistics"></i>
                                        <span class=" badge-pill float-right"><i class="fontello icon-plus2"></i></span>
                                        <span>Leaves Management</span>
                                    </a>
                                    <div class="collapse {{ request()->is('leave-request/add-leave*') || request()->is('leave-request/edit-leave*') || request()->is('leave-settings/add-leave-setup*') || request()->is('leave-settings/edit-leave-setup*')  ? 'show' : '' }}" id="sidebarDashboards20">
                                        <ul class="nav-second-level">
                                            {{-- <li class="border-left d-flex">
                                                <div class="curved-box_vt" ></div>
                                                <a href="#">Leave Status</a>
                                            </li> --}}
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt" ></div>
                                                <a href="{{route('leave.request')}}" class="{{ request()->is('leave-request/add-leave*') || request()->is('leave-request/edit-leave*') ? 'active1' : '' }}">Leave Request</a>
                                            </li>
                                            {{-- <li class="border-left d-flex">
                                                <div class="curved-box_vt"></div>
                                                <a href="{{route("leave.setting")}}" >Leave Settings</a>
                                            </li> --}}
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if ($user->haspermission(['holidays-all','holidays-read']))
                                <li>
                                    <a href="{{route('holidays.list')}}">
                                        <i class="fontello icon-holidays1"></i>
                                            <span> Holidays </span>
                                        </a>
                                </li>
                            @endif
                            @if ($user->haspermission(['payroll-all','payroll-read']))
                            <li>
                                <a href="#sidebarDashboards30" data-toggle="collapse" class="{{ request()->is('payroll*') ? 'active1' : '' }}">
                                    <i class="fontello icon-dollar"></i>
                                    <span class=" badge-pill float-right"><i class="fontello icon-plus2"></i></span>
                                    <span>Payroll</span>
                                </a>
                                <div class="collapse {{ request()->is('payyroll/add-salary*') || request()->is('payyroll/edit-salary*') || request()->is('leave-settings/add-leave-setup*') ? 'show' : '' }}" id="sidebarDashboards30">
                                    <ul class="nav-second-level">
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{ route('payroll.emp_salary')}}" class="{{ request()->is('payyroll/edit-salary*') || request()->is('payyroll/add-salary*') ? 'active1' : '' }}">Employee Salary</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="#" class="">Monthly Payroll</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="#" class="">Payslip</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="#" class="">Payroll Items</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            @endif
                            @if($user->haspermission(['user-management-all','user-management-read']))
                                <li>
                                    <a href="{{route("user.management")}}" class="{{request()->is( 'add-user') || $currenturl== 'edit-user' ? 'active1':''}}">
                                    <i class="fontello icon-users"></i>
                                        <span> User Management </span>
                                    </a>
                                </li>
                            @endif
                            @if($user->haspermission(['branch-management-all','branch-management-read']))
                                <li>
                                    <a href="{{route("branch.management")}}" class="{{ request()->is( 'branch-management/*') ? 'active1':''}}">
                                        <i class="fontello icon-branch1"></i>
                                            <span> Branch Management </span>
                                        </a>
                                </li>
                            @endif
                            @if($user->haspermission(['communication-system-all','communication-system-read']))
                                <li>
                                    <a href="{{url("communication")}}" class="{{ request()->is( 'add-email-notifications') || request()->is( 'add-sms-notifications')||request()->is( 'add-mobile-app-notifications') ||request()->is( 'communication/*') ? 'active1' : '' }}">
                                    <i class="fontello icon-book-alt"></i>
                                        <span> Communication System </span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{route("device.management")}}" class="{{ request()->is('device-management/*')  ? 'active1' : '' }}">
                                    <i class="fontello icon-device"></i>
                                    <span> Device Management </span>
                                </a>
                            </li>
                            @if($user->haspermission(['global-settings-all']))
                                <li>
                                    <a href="#sidebarDashboards1" data-toggle="collapse" class="{{ request()->is('company-setting*') || request()->is('update-appearance*') || request()->is('new-appearance-setting') || request()->is('device-management/*') ? 'active1' : '' }}">
                                        <i class="fontello icon-users1"></i>
                                            <span class=" badge-pill float-right"><i class="fontello icon-plus2"></i></span>
                                            <span> Global Setting </span>
                                    </a>
                                    <div class="collapse {{ request()->is( 'company-setting*') || request()->is( 'update-appearance/*') || request()->is( 'new-appearance-setting') || request()->is( 'device-management/*') ? 'show' : '' }}" id="sidebarDashboards1">
                                        <ul class="nav-second-level">
                                            
                                            @if($user->haspermission(['configuration-all','configuration-read']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt"></div>
                                                <a href="{{url("company-setting")}}" class="{{ request()->is('company-setting*')  ? 'active1' : '' }}">Configuration</a>
                                            </li>
                                            @endif
                                            @if($user->haspermission(['smtp-gateway-all','smtp-gateway-read']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt" style="margin-left:0px;"></div>
                                                <a href="{{url("SMTP")}}" >SMTP Gateway</a>
                                            </li>
                                            @endif
                                            @if($user->haspermission(['appearance-settings-all','appearance-settings-read']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt" style="margin-left:0px;"></div>
                                                <a href="{{route('appearance.setting')}}" class="{{request()->is( 'new-appearance-setting') || request()->is( 'update-appearance/*') ? 'active1':''}}">Appearance Settings</a>
                                            </li>
                                            @endif
                                            @if($user->haspermission(['theme-settings-all','theme-settings-read']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt" style="margin-left:0px;"></div>
                                                <a href="{{url("Theme-settings")}}" >Theme Settings</a>
                                            </li>
                                            @endif
                                            @if($user->haspermission(['web-version-history-all','web-version-history-read']))
                                            <li class="border-left d-flex">
                                                <div class="curved-box_vt" style="margin-left:0px;"></div>
                                                <a href="{{route('version.history')}}" >Web Version History</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    @else
                        <ul id="side-menu">
                            <li>
                                <a href="{{url("home")}}" class="{{$currenturl== 'Admin' || $currenturl== 'home' ? 'active1' : '' }}">
                                <i class="fontello icon-dashboard"></i>
                                    <span> Dashboard </span>
                                </a>
                            </li>
                            <li>
                                <a href="#employeeDropdown" data-toggle="collapse" class="{{ request()->is('add-resignation') || $currenturl == 'edit-resignation' || request()->is('employee/add-termination') || $currenturl == 'edit-termination' || request()->is('employee/add-promotion') || $currenturl == 'edit-promotion' || request()->is( 'employee/directory/*') || request()->is('employee/directory/edit-employee*') || request()->is('employee/directory/add-education*') || request()->is('employee/directory/add-employment*') || request()->is('employee/directory/add-account-detail*') || request()->is('employee/shift-management*') ? 'active1' : '' }}">
                                    <i class="fontello icon-briefcase-compound"></i>
                                    <span class=" badge-pill float-right"><i class="fontello icon-plus2"></i></span>
                                    <span>Employee</span>
                                </a>
                                <div class="collapse {{ request()->is('add-resignation') || $currenturl == 'edit-resignation' || request()->is('employee/add-termination') || $currenturl == 'edit-termination' || request()->is('employee/add-promotion') || $currenturl == 'edit-promotion' || request()->is( 'employee/directory/*') || request()->is('employee/directory/edit-employee*') || request()->is('employee/directory/add-education*') || request()->is('employee/directory/add-employment*') || request()->is('employee/directory/add-account-detail*') || request()->is('employee/shift-management*') ? 'show' : '' }}" id="employeeDropdown">
                                    <ul class="nav-second-level">
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt" ></div>
                                            <a href="{{ url("employee/directory") }}" class="{{ request()->is('employee/directory/*') ? 'active1' : '' }}">Directory</a>

                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{url("employee/designation")}}">Designation</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{url("employee/department")}}">Department</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{url("employee/resignation")}}" class="{{request()->is( 'add-resignation') || $currenturl== 'edit-resignation' ? 'active1':''}}">Resignation</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{url("employee/termination")}}" class="{{request()->is( 'employee/add-termination') || $currenturl== 'edit-termination' ? 'active1':''}}">Termination</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt" style="margin-left:0px;"></div>
                                            <a href="{{url("employee/promotion")}}" class="{{request()->is('employee/add-promotion') || $currenturl == 'edit-promotion' ? 'active1':''}}">Promotion</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt" style="margin-left:0px;"></div>
                                            <a href="{{url("employee/shift-management")}}" class="{{request()->is('employee/shift-management*') ? 'active1':''}}">Shift And Schedule</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#sidebarDashboards12" data-toggle="collapse" >
                                <i class="fontello icon-attendance"></i>
                                    <span class=" badge-pill float-right"><i class="fontello icon-plus2"></i></span>
                                    <span>Time Sheet</span>
                                </a>
                                <div class="collapse" id="sidebarDashboards12">
                                    <ul class="nav-second-level">
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt" ></div>
                                            <a href="{{route("daily.attend.sheet")}}">Daily</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{route("monthly.attend.sheet")}}">Monthly</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt" style="margin-left:0px;"></div>
                                            <a href="{{route("yearly.attend.sheet")}}">Yearly</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#sidebarDashboards20" data-toggle="collapse" class="{{ request()->is('leave-request/add-leave*') || request()->is('leave-request/edit-leave*') || request()->is('leave-settings/add-leave-setup*') || request()->is('leave-settings/edit-leave-setup*') ? 'active1' : '' }}">
                                    <i class="fontello icon-statistics"></i>
                                    <span class=" badge-pill float-right"><i class="fontello icon-plus2"></i></span>
                                    <span>Leaves Management</span>
                                </a>
                                <div class="collapse {{ request()->is('leave-request/add-leave*') || request()->is('leave-request/edit-leave*') || request()->is('leave-settings/add-leave-setup*') || request()->is('leave-settings/edit-leave-setup*')  ? 'show' : '' }}" id="sidebarDashboards20">
                                    <ul class="nav-second-level">
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="#">Leave Status</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{ route('leave.request') }}" class="{{ request()->is('leave-request/add-leave*') || request()->is('leave-request/edit-leave*') ? 'active1' : '' }}">Leave Request</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{ route('leave.setting') }}" class="{{ request()->is('leave-settings/add-leave-setup*') || request()->is('leave-settings/edit-leave-setup*') ? 'active1' : '' }}">Leave Settings</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="{{route('holidays.list')}}">
                                <i class="fontello icon-holidays1"></i>
                                    <span> Holidays </span>
                                </a>
                            </li>
                            <li>
                                <a href="#sidebarDashboards30" data-toggle="collapse" class="{{ request()->is('payroll*') ? 'active1' : '' }}">
                                    <i class="fontello icon-dollar"></i>
                                    <span class=" badge-pill float-right"><i class="fontello icon-plus2"></i></span>
                                    <span>Payroll</span>
                                </a>
                                <div class="collapse {{ request()->is('payyroll/add-salary*') || request()->is('payyroll/edit-salary*') || request()->is('leave-settings/add-leave-setup*') ? 'show' : '' }}" id="sidebarDashboards30">
                                    <ul class="nav-second-level">
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{ route('payroll.emp_salary')}}" class="{{ request()->is('payyroll/edit-salary*') || request()->is('payyroll/add-salary*') ? 'active1' : '' }}">Employee Salary</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{ route('monthly.payroll.emp_salary')}}" class="">Monthly Payroll</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="#" class="">Payslip</a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="#" class="">Payroll Items</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="{{route("user.management")}}" class="{{request()->is( 'add-user') || $currenturl== 'edit-user' || request()->is( 'roles-permissions') || request()->is( 'add-roles-permissions') || request()->is( 'edit-roles-permissions/*') ? 'active1':''}}">
                                <i class="fontello icon-users"></i>
                                    <span> User Management </span>
                                </a>
                            </li>
                            <li>
                                <a href="#sidebar12" data-toggle="collapse" class="{{ request()->is( 'company-management/*') || request()->is( 'branch-management/*') ? 'active1':''}}" style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center;">
                                        <i class="fontello icon-branch1"></i>
                                        <span style="white-space: nowrap;">Company Management</span>
                                    </div>
                                    <span class="badge-pill float-right"><i class="fontello icon-plus2"></i></span>
                                </a>
                                <div class="collapse {{ request()->is( 'company-management/*') || request()->is( 'branch-management/*')  ? 'show' : '' }}" id="sidebar12">
                                    <ul class="nav-second-level">
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{route("company.management")}}" class="{{ request()->is( 'company-management/*') ? 'active1':''}}"> 
                                                <span> Company Settings </span>
                                            </a>
                                        </li>
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{route("branch.management")}}" class="{{ request()->is( 'branch-management/*') ? 'active1':''}}">
                                                <span> Office Location </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            {{-- <li>
                                <a href="{{route("branch.management")}}">
                                <i class="fontello icon-branch1"></i>
                                    <span> Branch Management </span>
                                </a>
                            </li> --}}
                            <li>
                                <a href="{{route('notification.management')}}" class="{{ request()->is( 'all-notification') ? 'active1' : '' }}">
                                <i class="fontello icon-Communication1"></i>
                                    <span> Notification Management </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{url("communication")}}" class="{{ request()->is( 'add-email-notifications') || request()->is( 'add-sms-notifications')||request()->is( 'add-mobile-app-notifications') ||request()->is( 'communication/*') ? 'active1' : '' }}">
                                <i class="fontello icon-book-alt"></i>
                                    <span> Communication System </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route("device.management")}}" class="{{ request()->is('device-management/*')  ? 'active1' : '' }}">
                                    <i class="fontello icon-device"></i>
                                    <span> Device Management </span>
                                </a>
                            </li>
                            <li>
                                <a href="#sidebarDashboards1" data-toggle="collapse" class="{{ request()->is('company-setting*') || request()->is('update-appearance*') || request()->is('new-appearance-setting') ? 'active1' : '' }}" >
                                <i class="fontello icon-users1"></i>
                                    <span class=" badge-pill float-right"><i class="fontello icon-plus2"></i></span>
                                    <span> Global Setting </span>
                                </a>
                                <div class="collapse {{ request()->is( 'company-setting*') || request()->is( 'update-appearance/*') || request()->is( 'new-appearance-setting') ? 'show' : '' }}" id="sidebarDashboards1">
                                    <ul class="nav-second-level">
                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt"></div>
                                            <a href="{{url("company-setting")}}" class="{{ request()->is('company-setting*')  ? 'active1' : '' }}">Configuration</a>
                                        </li>

                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt" style="margin-left:0px;"></div>
                                            <a href="{{url("SMTP")}}" >SMTP Gateway</a>
                                        </li>

                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt" style="margin-left:0px;"></div>
                                            <a href="{{route('appearance.setting')}}" class="{{request()->is( 'new-appearance-setting') || request()->is( 'update-appearance/*') ? 'active1':''}}" >Appearance Settings</a>
                                        </li>

                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt" style="margin-left:0px;"></div>
                                            <a href="{{url("Theme-settings")}}">Theme Settings</a>
                                        </li>

                                        <li class="border-left d-flex">
                                            <div class="curved-box_vt" style="margin-left:0px;"></div>
                                            <a href="{{route('version.history')}}" >Web Version History</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    @endif
                </div>
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->
        <script>
            // Ensure the parent dropdown remains active and open for 'add-resignation' and 'edit-resignation'
            document.addEventListener('DOMContentLoaded', function() {
                const employeeDropdown = document.getElementById('employeeDropdown');
                if (employeeDropdown.classList.contains('show')) {
                    const parentLink = document.querySelector('.active1');
                    if (parentLink) {
                        parentLink.classList.add('active1');
                    }
                }
            });
        </script>