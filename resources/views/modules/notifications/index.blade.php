@extends('layouts.app')
@section('page-name', 'Notifications')
@section('content')

<div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Notifications</li>
                    </ul>
            </div>
        </div>
    </div>
<div class="container my-5">

    @if($notices->isEmpty())
        <div class="alert alert-info text-center shadow-sm rounded-pill">
            <i class="fas fa-info-circle"></i> No notifications available.
        </div>
    @else
        <div class="card shadow-lg border-0 rounded-4 bg-light" style="margin-top: -50px;">
            <div class="card-body px-5 py-4">
                <ul class="list-group list-group-flush">
                    @foreach($notices as $notice)
                        <li class="list-group-item border-0 rounded-3 mb-3 p-3 bg-white shadow-sm d-flex justify-content-between align-items-center" data-bs-toggle="modal" data-bs-target="#noticeModal{{ $notice->transid }}">
                            <div class="d-flex align-items-center">
                                <!-- Envelope Icon -->
                                <i class="fas fa-envelope text-primary me-3" style="font-size: 1.3rem;"></i>
                                
                                <!-- Notice Title -->
                                <h5 class=" mb-0" >{{ $notice->notice_title }}</h5>
                            </div>
                            
                            <!-- Relative Time Posted -->
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($notice->date_posted)->diffForHumans() }}
                            </small>
                        </li>

                        <!-- Modal for Notice -->
                        <div class="modal fade" id="noticeModal{{ $notice->transid }}" tabindex="-1" aria-labelledby="noticeModalLabel{{ $notice->transid }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 shadow-xl">
                                    <div class="modal-header border-bottom-0">
                                        <h5 class="modal-title">{{ $notice->notice_title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body py-4 px-5">
                                        <p class="text-dark">{!! nl2br(e($notice->notice_details)) !!}</p>
                                        <small class="text-muted">
                                            <i class="far fa-clock"></i> Posted {{ \Carbon\Carbon::parse($notice->date_posted)->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="modal-footer border-top-0">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
