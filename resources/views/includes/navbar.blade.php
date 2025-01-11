<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
            {{-- <a class="navbar-brand brand-logo text-white"
                href="{{ config('app.url') }}/">{{ Auth::user()->school->school_name }}</a> --}}
            <a class="navbar-brand brand-logo-mini" href="{{ config('app.url') }}/"><img src="images/logo-mini.svg"
                    alt="logo" /></a>
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="typcn typcn-th-menu"></span>
            </button>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
            <li class="nav-item nav-profile">
                <a class="navbar-brand brand-logo text-primary"
                    href="{{ config('app.url') }}/">{{ Auth::user()->school->school_name }}</a>
            </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link d-flex justify-content-center align-items-center" href="#"
                    data-toggle="dropdown" id="profileDropdown">
                    @if (Auth::user()->picture === '' or Auth::user()->picture === null)
                        <img src="{{ asset('images/faces/face5.jpg') }}" alt="profile" />
                    @else
                        <img src="{{ asset('images/faces/' . Auth::user()->picture) }}" alt="profile" />
                    @endif
                    <span class="nav-profile-name">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="profile" data-toggle="modal" data-target="#profile-modal">
                        <i class="fa fa-user text-primary"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" data-toggle="modal" data-target="#password-modal">
                        <i class="fa fa-lock text-primary"></i>
                        Change Password
                    </a>
                    <a class="dropdown-item" href="logout">
                        <i class="typcn typcn-eject text-primary"></i>
                        Logout
                    </a>
                </div>
            </li>
            <li class="nav-item nav-date dropdown">
                <a class="nav-link d-flex justify-content-center align-items-center" href="javascript:;">
                    <h6 class="date mb-0">Today : {{ date('m-d') }}</h6>
                    <i class="typcn typcn-calendar"></i>
                </a>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="typcn typcn-th-menu"></span>
        </button>
    </div>
</nav>
