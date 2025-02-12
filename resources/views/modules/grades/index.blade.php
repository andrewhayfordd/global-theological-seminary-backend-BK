@extends('layouts.app')
@section('page-name', 'Grade')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grades</title>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Student Grades</h2>
        @if($grades->isEmpty())
            <div class="alert alert-warning">No grades found for this student.</div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Total Test</th>
                        <th>Total Exam</th>
                        <th>Total Score</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $grade)
                        <tr>
                            <td>{{ $grade->course_title }}</td>
                            <td>{{ $grade->total_test }}</td>
                            <td>{{ $grade->total_exam }}</td>
                            <td>{{ $grade->total_score }}</td>
                            <td>{{ $grade->letter_grade }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
@endsection