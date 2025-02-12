<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        @if (Auth::user()->usertype !== 'SDM')
            @php
                if (Auth::user()->usertype === 'ADM') {
                    $parent = DB::table('tblmodule')
                        ->select('tbluser_module_privileges.mod_read', 'tbluser_module_privileges.userid', 'tblmodule.mod_name', 'tblmodule.mod_label', 'tblmodule.mod_url', 'tblmodule.mod_icon', 'tblmodule.mod_id')
                        ->join('tbluser_module_privileges', 'tblmodule.mod_id', 'tbluser_module_privileges.mod_id')
                        ->where('tbluser_module_privileges.userid', Auth::user()->email)
                        ->where('tbluser_module_privileges.school_code', Auth::user()->school_code)
                        ->where('tblmodule.mod_status', '1')
                        ->where('tblmodule.system_mod', '1')
                        ->where('tbluser_module_privileges.mod_read', '1')
                        ->orderBy('tblmodule.arrange', 'ASC')
                        ->get();
                     
                    $parentMods = ['parent' => $parent];
                }
                
                if (Auth::user()->usertype === 'STA') {
                    $parent = DB::table('tblmodule')
                        ->select('tbluser_module_privileges.mod_read', 'tbluser_module_privileges.userid', 'tblmodule.mod_name', 'tblmodule.mod_label', 'tblmodule.mod_url', 'tblmodule.mod_icon', 'tblmodule.mod_id')
                        ->join('tbluser_module_privileges', 'tblmodule.mod_id', 'tbluser_module_privileges.mod_id')
                        ->where('tbluser_module_privileges.userid', Auth::user()->email)
                        ->where('tbluser_module_privileges.school_code', Auth::user()->school_code)
                        // ->where('tblmodule.mod_status','1')
                        ->where('tblmodule.teacher_mod', '1')
                        ->where('tbluser_module_privileges.mod_read', '1')
                        ->orderBy('tbluser_module_privileges.mod_id', 'ASC')
                        ->get();
                       
                    $parentMods = ['parent' => $parent];
                }

                if (Auth::user()->usertype === 'STU') {
                    $parent = DB::table('tblmodule')
                        ->select('tbluser_module_privileges.mod_read', 'tbluser_module_privileges.userid', 'tblmodule.mod_name', 'tblmodule.mod_label', 'tblmodule.mod_url', 'tblmodule.mod_icon', 'tblmodule.mod_id')
                        ->join('tbluser_module_privileges', 'tblmodule.mod_id', 'tbluser_module_privileges.mod_id')
                        ->where('tbluser_module_privileges.userid', Auth::user()->email)
                        ->where('tbluser_module_privileges.school_code', Auth::user()->school_code)
                        // ->where('tblmodule.mod_status','1')
                        ->where('tblmodule.student_mod', '1')
                        ->where('tbluser_module_privileges.mod_read', '1')
                        ->orderBy('tbluser_module_privileges.mod_id', 'ASC')
                        ->get();
                       
                    $parentMods = ['parent' => $parent];

                }
                
            @endphp
            @foreach ($parent as $parentMod)
                <li class="nav-item @if (Route::currentRouteName() === strtolower($parentMod->mod_name)) active @endif">
                    <a class="nav-link" href="{{ config('app.url') }}/{{ $parentMod->mod_url }}">
                        <i class="mr-3 {{ $parentMod->mod_icon }}"></i>
                        <span>{{ $parentMod->mod_label }}</span></a>
                </li>
            @endforeach
        @endif


        @if (Auth::user()->usertype === 'SDM')
            <li class="nav-item @if (Route::currentRouteName() === 'dashboard') active @endif">
                <a class="nav-link" href="{{ config('app.url') }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Nav Item - assessment -->
            <li class="nav-item @if (Route::currentRouteName() === 'schools') active @endif">
                <a class="nav-link" href="{{ config('app.url') }}/super_admin/schools">
                    <i class="fas fa-building"></i>
                    <span>Schools</span></a>
             </li>


            <li class="nav-item @if (Route::currentRouteName() === 'student') active @endif">
                <a class="nav-link" href="{{ config('app.url') }}/super_admin/student">
                    <i class="fas fa-user"></i>
                    <span>Student</span></a>
            </li>

            <li class="nav-item @if (Route::currentRouteName() === 'staff') active @endif">
                <a class="nav-link" href="{{ config('app.url') }}/super_admin/staff">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Staff</span></a>
            </li>

            <li class="nav-item @if (Route::currentRouteName() === 'messaging') active @endif">
                <a class="nav-link" href="{{ config('app.url') }}/super_admin/messaging">
                    <i class="fas fa-comment-alt"></i>
                    <span>Messaging</span></a>
            </li>

            <li class="nav-item @if (Route::currentRouteName() === 'settings') active @endif">
                <a class="nav-link" href="{{ config('app.url') }}/super_admin/settings">
                    <i class="fas fa-cogs"></i>
                    <span>Settings</span></a>
            </li>
        @endif
    </ul>
</nav>
