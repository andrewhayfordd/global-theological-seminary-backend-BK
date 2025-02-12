@extends('layout.app')

@section('page-content')
    <!-- modules-->
    <div class="row">
        <div class="col-lg-4 col-xl-4 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small">Courses</div>
                            <div class="text-lg fw-bold"></div>
                        </div>
                        <i class="feather-xl text-white-50" data-feather="calendar"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between small">
                    <a class="text-white stretched-link" href="{{ config('app.url') }}/courses">View Courses</a>
                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xl-4 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small">Programmes</div>
                            <div class="text-lg fw-bold"></div>
                        </div>
                        <i class="feather-xl text-white-50" data-feather="calendar"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between small">
                    <a class="text-white stretched-link" href="{{config('app.url')}}/program">View programmes</a>
                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xl-4 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small">Student</div>
                            <div class="text-lg fw-bold"></div>
                        </div>
                        <i class="feather-xl text-white-50" data-feather="dollar-sign"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between small">
                    <a class="text-white stretched-link" href="{{ config('app.url') }}/student">View students</a>
                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
    </div>

    
    {{-- <footer class="footer">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2020 <a
                            href="https://www.bootstrapdash.com/" class="text-muted" target="_blank">Bootstrapdash</a>.
                        All rights reserved.</span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center text-muted">Free <a
                            href="https://www.bootstrapdash.com/" class="text-muted" target="_blank">Bootstrap
                            dashboard</a> templates from Bootstrapdash.com</span>
                </div>
            </div>
        </div>
    </footer> --}}
@endsection