@extends('layout.app')
{{-- @section('pageName', 'Messaging') --}}

@section('page-content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Message Center</li>
                    </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid my-5">


        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Message Center</h1>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Send Messages to All Users </h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            {{-- <li class="nav-item" role="presentation">
                                <a class="nav-link active text-info" id="notifications-tab" data-toggle="pill"
                                    href="#notifications" role="tab" aria-controls="profile"
                                    aria-selected="false">SMS</a>
                            </li> --}}

                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-info " id="home-tab" data-toggle="tab" href="#email-ads-tab"
                                    role="tab" aria-controls="home" aria-selected="true">Email</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">

                            {{-- Email tab --}}
                            <div class="tab-pane fade show active" id="email-ads-tab" role="tabpanel"
                                aria-labelledby="home-tab">

                                <div class="row">
                                    <div class="col p-3 rounded border border-light shadow-sm">
                                        <form id="staff-email-form">
                                            <div class="row shadow-sm border border-light p-2 rounded">
                                                <!-- Notification types -->
                                                <div class="col-6 MT-2">
                                                    <h5 class="border-bottom">Select Message Type</h5>
                                                    <!-- Bulk EMAIL notification -->
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            id="email-notification-type" required form="staff-email-form"
                                                            name="emailType" value="email">
                                                        <label class="form-check-label" for="email-notification-type">Bulk
                                                            EMAIL</label>
                                                    </div>
                                                </div>

                                                <!-- EMAIL recipients -->
                                                <div class="col-6 border-left">
                                                    <div class="h5 border-bottom">
                                                        Select Recipients
                                                        <small id="email-recipients-error"
                                                            class="font-weight-bold p-0 mx-2 mb-2">
                                                            <!-- If no recipient is selected, an error message is inserted here -->
                                                        </small>
                                                    </div>

                                                    <div id="email-recipients-holder" class="p-1 rounded">

                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="">Students</label>
                                                                <select name="student" class="form-control select2">
                                                                    <option value="">Select Option</option>
                                                                    <option value="all_students">All Students</option>
                                                                    @foreach ($students as $item)
                                                                        <option value="{{ $item->student_no }}">
                                                                            {{ $item->fname . ' ' . $item->mname . ' ' . $item->lname }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <input type="text" hidden name="staff_code"
                                                    value="{{ Auth::user()->userid }}">
                                                <div class="form-group col-8 mt-3">
                                                    <div>
                                                        <p id="feedback-message">
                                                        </p>
                                                    </div>
                                                    <label for="email-description">Subject</label>
                                                    <input name="subject" class="form-control form-control-sm"
                                                        id="staff-email-form-subject">
                                                    <label for="email-description">Message</label>
                                                    <textarea name="email" class="form-control" id="" cols="30" rows="10"></textarea>
                                                    <button type="submit"
                                                        class="btn btn-sm btn-primary btn-block mt-3">Send</button>
                                                </div>
                                                {{-- <div class="col-md-7 mt-2 p-3 mx-auto">
                                                    <div class="table-responsive">
                                                        <table class="datatable table-bordered table table-stripped"
                                                            width='100%' id="email-table">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th>Code</th>
                                                                    <th>Message</th>
                                                                    <th>Acyear</th>
                                                                    <th>Acterm</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div> --}}
                                            </div>
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- <script src="{{asset('js/modules/message/message_datatable.js') }}"></script> --}}
    <script>
        //#################### EMAIL SCRIPT ######################################
        var emailTable = $("#email-table").DataTable({
            dom: "Bfrtip",
            buttons: [],
            ordering: true,
            order: [],
            ajax: {
                url: `${appUrl}/api/message/email/${school_code}`,
                type: "GET",
            },
            columns: [{
                    data: "code"
                },
                {
                    data: "details"
                },
                {
                    data: "acterm"
                },
                {
                    data: "acyear"
                },
            ],
        });

        const emaillForm = document.forms['staff-email-form'];
        $('#staff-email-form').submit(function(e) {
            e.preventDefault();
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            });
            let bulkEmail = new FormData(emaillForm);

            bulkEmail.append("createuser", `${createuser}`);
            bulkEmail.append("school_code", `${school_code}`);

            swalWithBootstrapButtons.fire({
                title: '',
                text: "Are you sure?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Submit',
                reverseButtons: true

            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        text: "Sending message...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    fetch(`${appUrl}/api/staff_message/email`, {
                        method: "post",
                        body: bulkEmail,
                    }).then(function(res) {
                        return res.json();
                    }).then(function(data) {
                        if (!data.ok) {
                            swalWithBootstrapButtons.fire({
                                showCancelButton: false,
                                title: '',
                                text: data.msg,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                            return;
                        }
                        swalWithBootstrapButtons.fire({
                            showCancelButton: false,
                            showConfirmButton: false,
                            timer: 1500,
                            title: '',
                            text: 'Email has been sent',
                            icon: 'success',
                        });
                        emaillForm.reset();
                        emailTable.ajax.reload(false, null);

                        $('.select2').val(null).trigger('change');

                    });
                }
            }).catch(function(err) {
                console.log(err);
                if (err) {
                    Swal.fire({
                        text: err
                    });
                }
            })

        })
    </script>
@endsection
