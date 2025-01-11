@extends('layout.app')
@section('page-name', 'Program')
@section('page-content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Program</li>
                    </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4 class="card-title">List of all programs</h4>
        </div>
        <div class="card-body">
            <div class="table-">
                <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                    width='100%' id="program-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Prog.Type</th>
                            <th>Duration</th>
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

    @include('modules.program.modals.add_program')
    @include('modules.program.modals.edit_program')
    @include('modules.program.modals.program_list')
    <script>
        var programTable = $('#program-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/program/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            columns: [{
                    data: "code"
                },
                {
                    data: "desc"
                },
                {
                    data: "type"
                },
                {
                    data: "duration"
                },
                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - program List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - program List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - program List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - program List`,
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

                {
                    text: "Add Program",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-program-modal").modal("show")
                    }
                },
            ]
        });

        $("#program-table").on("click", ".delete-btn", function() {
            let data = programTable.row($(this).parents('tr')).data();

            Swal.fire({
                title: "Are you sure you want to delete this program?",
                text: "Or you can click cancel to abort!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete"

            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        text: "Deleting please wait...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    $.ajax({
                        url: `${appUrl}/api/program/delete/${data.id}`,
                        type: "POST",
                    }).done(function(data) {
                        if (!data.ok) {
                            Swal.fire({
                                text: data.msg,
                                type: "error"
                            });
                            return;
                        }
                        Swal.fire({
                            text: "Program deleted successfully",
                            type: "success"
                        });
                        programTable.ajax.reload(false, null);

                    }).fail(() => {
                        alert('Processing failed');
                    })
                }
            })
        });

        $("#program-table").on("click", ".edit-btn", function() {
            let data = programTable.row($(this).parents('tr')).data();

            $("#update-program-modal").modal("show");
            $("#update-prog-code").val(data.code);
            $("#update-prog-desc").val(data.desc);
            $("#update-prog-duration").val(data.durationCode).trigger("change");
            $("#update-prog-type").val(data.typeCode).trigger("change");
            $("#update-prog-id").val(data.id);
        });

        var progListTable = $('#prog-list-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            processing: true,
            responsive: true,
            columns: [{
                    data: "picture"
                },
                {
                    data: "student"
                },
                {
                    data: "prog"
                },
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Department List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Department List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Department List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Department List`,
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
        $('#program-table').on("click", ".prog-btn", function() {
            var data = programTable.row($(this).parents('tr')).data();
            $("#prog-list-modal").modal("show")
            progListTable.ajax.url(`${appUrl}/api/program/program_list/${school_code}/${data.code}`).load();
        });
    </script>
@endsection
