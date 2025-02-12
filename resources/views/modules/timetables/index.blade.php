<!-- resources/views/modules/timetables/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Timetable Management</h2>
        <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#createTimetableModal">Add Timetable</a>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timetables as $timetable)
                    <tr>
                        <td>{{ $timetable->fname }} {{ $timetable->lname }}</td>
                        <td>{{ $timetable->course_title }}</td>
                        <td>{{ $timetable->day }}</td>
                        <td>{{ $timetable->start_time }} - {{ $timetable->end_time }}</td>
                        <td>{{ $timetable->location }}</td>
                        <td>
                            <a href="javascript:void(0)" class="btn btn-warning" data-toggle="modal" data-target="#editTimetableModal" data-id="{{ $timetable->id }}">Edit</a>
                            <form action="{{ route('timetables.destroy', $timetable->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('modules.timetables.modals.create')
    @include('modules.timetables.modals.edit')
@endsection
