@extends('layouts.app')
@section('page-name', 'Assignments')
@section('content')

<div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Assignments</li>
                    </ul>
            </div>
        </div>
    </div>
<div class="container my-5">

    @if($studenthomeworks->isEmpty())
        <div class="alert alert-info text-center shadow-sm rounded-pill">
            <i class="fas fa-info-circle"></i> No Assignment Available.
        </div>
    @else
        <div class="card shadow-lg border-0 rounded-4 bg-light" style="margin-top: -50px;">
            <div class="card-body px-5 py-4">
                <ul class="list-group list-group-flush">
                    @foreach($studenthomeworks as $homework)
                        <li class="list-group-item border-0 rounded-3 mb-3 p-3 bg-white shadow-sm d-flex justify-content-between align-items-center" data-bs-toggle="modal" data-bs-target="#homeworkModal{{ $homework->transid }}">
                            <div class="d-flex align-items-center">
                                <!-- Envelope Icon -->
                                <i class="fas fa-envelope text-primary me-3" style="font-size: 1.3rem;"></i>
                                
                                <!-- homework Title -->
                                <h5 class=" mb-0" >{{ $homework->homework_title }}</h5>
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
                <p class="text-dark fs-6">{!! nl2br(e($homework->homework_details)) !!}</p>

                <!-- File Download Button -->
                @if($homework->file_path)
                <div class="text-center my-3">
                    <a href="{{ asset('storage/' . $homework->file_path) }}" class="btn btn-outline-primary d-flex align-items-center justify-content-center w-100" download>
                        <i class="fas fa-download me-2"></i> Download Assignment
                    </a>
                </div>
                @endif

                <!-- Homework Submission Form -->
                <form action="{{ route('studenthomeworks.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <div class="form-group" hidden>
                        <label for="homework_title">Title</label>
                        <input type="text" name="homework_title" value="{{ $homework->homework_title ?? '' }}" class="form-control" required>
                    </div>

                    <div class="form-group" hidden>
                        <label for="course_code">Details</label>
                        <input name="course_code" value="{{ $homework->course_recipient ?? '' }}" class="form-control" required></input>
                    </div>

                    <div class="form-group" hidden>
                        <label for="submit_to">Posted By</label>
                        <input name="submit_to" value="{{ $homework->posted_by ?? '' }}" class="form-control" required></input>
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label fw-semibold">Upload Completed Assignment</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload"></i> Submit Assignment
                        </button>
                    </div>
                </form>

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

<!-- Include Bootstrap Icons & FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- Include Bootstrap 5 CSS and JS (Ensure it's loaded for modal functionality) -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Styles -->
<style>
    .custom-modal-width {
        max-width: 500px; /* Adjust as needed */
    }
</style>
<style>
    /* Luxury design with modern gradients and white space */
    .container {
        background-color: #f4f7fb;
        border-radius: 16px;
        padding: 40px;
    }

    /* Card Styling */
    .card {
        border-radius: 16px;
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 30px;
    }

    /* Elegant typography */
    h2, h5 {
        font-family: 'Poppins', sans-serif;
    }

    h2 {
        font-size: 2.4rem;
        color: #2c3e50;
    }

    h5 {
        font-size: 16px;
        color: #34495e;
        font-weight: 500;
        text-transform: uppercase;
    }

    /* Clean and sleek text */
    .text-muted {
        font-size: 0.95rem;
        color: #bdc3c7;
    }

    .text-secondary {
        font-size: 0.75rem;
        color: #7f8c8d;
    }

    /* List Item Styling */
    .list-group-item {
        padding: 15px 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .list-group-item:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        transform: translateY(-5px);
    }

    /* Button Styling */
    .btn-gradient {
        background: linear-gradient(45deg, #3498db, #9b59b6);
        border: none;
        font-size: 0.9rem;
        transition: transform 0.2s ease-in-out, background 0.3s ease;
    }

    .btn-gradient:hover {
        background: linear-gradient(45deg, #2980b9, #8e44ad);
        transform: scale(1.05);
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 0.875rem;
        border-radius: 50px;
    }

    /* Hover Effects for Buttons */
    .hover-scale:hover {
        transform: scale(1.05);
        transition: transform 0.2s ease-in-out;
    }

    /* Modal Styling */
    .modal-content {
        background-color: #ecf0f1;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    /* Hover effect for the card */
    .hover-card:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        transform: translateY(-8px);
    }

    /* Enhance the modal and card titles */
    .modal-title{
        font-weight: 600;
        font-size: 15px;
        color: #2c3e50;
    }

    .text-dark {
        font-weight: 500;
        font-size: 14px;
        color: #2c3e50;
    }

    /* For a cleaner feel, all icons */
    .fas, .far {
        color: #3498db;
    }
</style>
@endsection
