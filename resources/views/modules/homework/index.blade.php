@extends('layouts.app')
@section('page-name', 'Send Assignment')
@section('content')
<div class="container">
<div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Send Assignment</li>
                    </ul>
            </div>

    <!-- Button to Open Create homework Modal -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createHomeworkModal">
        Send Assignments
    </button>

    <div class="card">
        <div class="card-header bg-primary text-white">Assignments You Sent</div>
        <div class="card-body">
            @if($user_homeworks->isEmpty())
                <p class="text-muted">You haven't created any homeworks.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Recipient</th>
                                <th>Title</th>
                                <th>Details</th>
                                <th>Posted On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user_homeworks as $homework)
                                <tr>
                                    <td>{{ $homework->course_recipient }}</td>
                                    <td>{{ $homework->homework_title }}</td>
                                    <td>{{ Str::limit($homework->homework_details, 50) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($homework->date_posted)->format('M d, Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editHomeworkModal-{{ $homework->transid }}">Edit</button>
                                        <form action="{{ route('homework.delete', $homework->transid) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                @include('modules.homework.modals.edit', ['homework' => $homework])

                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        
    </div>








    <div class="container my-5">

    @if($submitedhomeworks->isEmpty())
        <div class="alert alert-info text-center shadow-sm rounded-pill">
            <i class="fas fa-info-circle"></i> No homework available.
        </div>
    @else
        
    <h3>Submited Assignments</h3>
        <div class="card shadow-lg border-0 rounded-4 bg-light" style="margin-top: 20px;">
            <div class="card-body px-5 py-4">
                <ul class="list-group list-group-flush">
                    @foreach($submitedhomeworks as $homework)
                        <li class="list-group-item border-0 rounded-3 mb-3 p-3 bg-white shadow-sm d-flex justify-content-between align-items-center" data-bs-toggle="modal" data-bs-target="#homeworkModal{{ $homework->transid }}">
                            <div class="d-flex align-items-center">
                                <!-- Envelope Icon -->
                                <i class="fas fa-envelope text-primary me-3" style="font-size: 1.3rem;"></i>
                                
                                <!-- homework Title -->
                                <h5 class=" mb-0" >{{ $homework->course_recipient ?? '' }}</h5>
                            </div>
                            
                            <!-- Relative Time Posted -->
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($homework->date_posted)->diffForHumans() }}
                            </small>
                        </li>

                        <!-- Modal for homework -->
                        
                        <div class="modal fade" id="homeworkModal{{ $homework->transid }}" tabindex="-1" aria-labelledby="homeworkModalLabel{{ $homework->transid }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg border-0">
            
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold">{{ $homework->homework_title }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
            <h5 class=" mb-0" >Course Code: {{ $homework->course_recipient ?? '' }}</h5>
            <h5 class=" mb-0" >Student ID: {{ $homework->userid ?? '' }}</h5>
            <h5 class=" mb-0" >Student Name: {{ $homework->fname ?? '' }} {{ $homework->lname ?? '' }}</h5>

                <!-- File Download Button -->
                @if($homework->file_path)
                <div class="text-center my-3">
                    <a href="{{ asset('storage/' . $homework->file_path) }}" class="btn btn-outline-primary d-flex align-items-center justify-content-center w-100" download>
                        <i class="fas fa-download me-2"></i> Download Assignment
                    </a>
                </div>
                @endif

                <!-- Date Posted -->
                <div class="mt-4 text-muted small text-center">
                    <i class="far fa-clock"></i> Posted {{ \Carbon\Carbon::parse($homework->date_posted)->diffForHumans() }}
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light rounded-bottom-4 border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>

        </div>
    </div>
</div> 


                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>
    
</div>

@include('modules.homework.modals.create')

@endsection
