@extends('layouts.app')
@section('page-name', 'Notice')
@section('content')
<div class="container">
<div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Notice</li>
                    </ul>
            </div>

    <!-- Button to Open Create Notice Modal -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createNoticeModal">
        Create Notice
    </button>

    <div class="card">
        <div class="card-header bg-primary text-white">Notices You Created</div>
        <div class="card-body">
            @if($user_notices->isEmpty())
                <p class="text-muted">You haven't created any notices.</p>
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
                            @foreach($user_notices as $notice)
                                <tr>
                                    <td>{{ $notice->notice_recipient }}</td>
                                    <td>{{ $notice->notice_title }}</td>
                                    <td>{{ Str::limit($notice->notice_details, 50) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($notice->date_posted)->format('M d, Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editNoticeModal-{{ $notice->transid }}">Edit</button>
                                        <form action="{{ route('notice.delete', $notice->transid) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                @include('modules.notice.modals.edit', ['notice' => $notice])

                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@include('modules.notice.modals.create')
@include('modules.notice.modals.edit', ['notice' => $notice])

@endsection
