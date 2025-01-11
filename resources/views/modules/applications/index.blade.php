@extends('layout.app')
@section('page-name', 'Applications')
@section('page-content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Applications</li>
                    </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="card-header py-3">

                {{-- Student breakdown start --}}
                <ul class="list-group list-group-horizontal-sm" id="info-panel">
                    <li class="list-group-item">
                        <div class="m-0 p-0 font-weight-bold text-primary">
                            Total Applicants:
                            <span class="text-secondary" id="info-panel-total-students">{{$total}}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="m-0 p-0 font-weight-bold text-primary">
                            Total Females:
                            <span class="text-secondary" id="info-panel-total-females">{{$f}}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="m-0 p-0 font-weight-bold text-primary">
                            Total Males:
                            <span class="text-secondary" id="info-panel-total-males">{{$m}}</span>
                        </div>
                    </li>
                </ul>
                {{-- Student breakdown ends --}}

            </div>
        </div>
        <div class="card-body">
            <div class="table-">
                <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                    width='100%' id="app-table">
                    <thead>
                        <tr>
                            <th>Application No.</th>
                            <th>Application Date.</th>
                            <th>Name</th>
                            <th>Programme</th>
                            {{-- <th>Batch</th>
                            <th>Session</th> --}}
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data is fetched here using ajax --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- @include('modules.students.modals.add_student')
    @include('modules.students.modals.edit_student')
    @include('modules.students.modals.info_student') --}}
    <script>
        var appTable = $('#app-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/application/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            // pageLength: 15,
            columns: [{
                    data: "app_no"
                },
                {
                    data: "date"
                },
                {
                    data: "name"
                },
                {
                    data: "prog"
                },
                // {
                //     data: "batch"
                // },
                // {
                //     data: "session"
                // },
                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Application List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Application List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Application List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Application List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    text: "Refresh",
                    attr: {
                        class: "ml-2 btn-secondary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        dt.ajax.reload(false, null);
                    }
                },
            ]
        });


        $('#app-table').on('click', '.delete-btn', function() {
            let data = appTable.row($(this).parents('tr')).data()
            swal.fire({
                title: '',
                text: 'Are you sure you want to delete applicant?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.value) {
                    swal.fire({
                        text: 'Deleting...',
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    $.ajax({
                        url: `${appUrl}/api/application/delete/${data.id}`,
                        type: 'post'
                    }).done(function(data) {
                        if (!data.ok) {
                            Swal.fire({
                                text: data.msg,
                                type: "error"
                            });
                            return;
                        }
                        Swal.fire({
                            text: "Student deleted successfully",
                            type: "success"
                        });
                        appTable.ajax.reload(false, null);
                    }).fail(() => {
                        alert('Processing failed!')
                    })
                }
            })
        })

        $("#app-table").on("click", ".info-btn", function() {
            let data = appTable.row($(this).parents('tr')).data();
            $("#full-student-details-modal").modal("show")
            $("#full-details-student-code").html(data.student_no);
            $("#full-details-name").html(data.name);
            $("#full-details-email").html(data.email);
            $("#full-details-phone").html(data.phone);
            $("#full-details-dob").html(data.dob);
            $("#full-details-gender").html(data.gender);
            $("#full-details-session").html(data.sessionDesc);
            $("#full-details-level").html(data.level);
            $("#full-details-batch").html(data.batchDesc);
            $("#full-details-prog").html(data.prog);
            // document.getElementById('full-details-student-image').setAttribute('src', `${data.picture}`)
        });

    </script>
@endsection
