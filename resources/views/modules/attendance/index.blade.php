@extends('layouts.app')
@section('page-name', 'Attendance')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Attendance</li>
                    </ul>
            </div>

        <a href="{{ route('attendance.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus"></i> Take Attendance
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.index') }}">
                <div class="mb-3">
                    <label for="course_code" class="form-label fw-semibold">Select Course:</label>
                    <select name="course_code" id="course_code" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Select Course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->course_code }}" {{ $selectedCourse == $course->course_code ? 'selected' : '' }}>
                                {{ $course->course_code }} - {{ $course->course_title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if ($selectedCourse)
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="fw-bold">Attendance Record</h5>
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center">
                    <thead class="card-header bg-primary text-white">
                        <tr>
                            <th>Student</th>
                            @foreach($dates as $date)
                                <th>{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td class="fw-semibold">{{ $student->fname }} {{ $student->lname }}</td>
                                @foreach($dates as $date)
                                    <td>
                                        <span class="badge {{ strtolower($attendanceData[$student->student_no][$date]) }}">
                                            {{ $attendanceData[$student->student_no][$date] }}
                                        </span>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .present { background-color: #28a745 !important; color: white !important; font-weight: bold; padding: 6px; border-radius: 5px; }
    .late { background-color: #ffc107 !important; color: black !important; font-weight: bold; padding: 6px; border-radius: 5px; }
    .absent { background-color: #dc3545 !important; color: white !important; font-weight: bold; padding: 6px; border-radius: 5px; }
    .holiday { background-color: #6c757d !important; color: white !important; font-weight: bold; padding: 6px; border-radius: 5px; }
    .not-taken { background-color: #6c757d !important; color: white !important; font-weight: bold; padding: 6px; border-radius: 5px; }
</style>
@endsection

