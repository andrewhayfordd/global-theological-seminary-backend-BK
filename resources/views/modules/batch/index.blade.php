@extends('layout.app')
@section('page-content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Batch</li>
                    </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="card-header py-3">
                List Of Batches
            </div>
        </div>
        <div class="card-body">
            <div class="table-">
                <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                    width='100%' id="batch-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
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
    @include('modules.batch.modals.add_batch')
    @include('modules.batch.modals.edit_batch')
    @include('modules.batch.modals.batch_list')
    <script>
        var batchTable = $('#batch-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/batch/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            // pageLength: 15,
            columns: [{
                    data: "code"
                },
                {
                    data: "desc"
                },

                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - batch List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - batch List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - batch List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - batch List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1]
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
                    text: "Add batch",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-batch-modal").modal("show")
                    }
                },
            ]
        });
        //storing data
      
        //deleting batch
        $('#batch-table').on("click", ".delete-btn", function() {
            var data = batchTable.row($(this).parents('tr')).data();
            Swal.fire({
                title: "Are you sure you want to delete this batch?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete"
            }).then(function(result) {
                if (result.value) {
                    Swal.fire({
                        text: "Deleting please wait...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    $.ajax({
                        url: `${appUrl}/api/batch/delete/${data.code}`,
                        type: "POST"
                    }).done(function(data) {
                        if (!data.ok) {
                            Swal.fire({
                                text: data.msg,
                                type: "error"
                            });
                            return;
                        }
                        Swal.fire({
                            text: "Batch deleted successfully",
                            type: "success"
                        });
                        batchTable.ajax.reload(false, null);
                    }).fail(function() {
                        console.log('processing failed');
                    })
                }
            })

        });

        $('#batch-table').on("click", ".edit-btn", function() {
            $('#edit-batch-modal').modal('show')
            var data = batchTable.row($(this).parents('tr')).data()
            $('#edit-batch-name').val(data.desc)
            $('#edit-batch-code').val(data.code)
        })
        

        var batchListTable = $('#batch-list-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            processing: true,
            responsive: true,
            columns: [{
                    data: "batch"
                },
                {
                    data: "student"
                },
                {
                    data: "program"
                },
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - batch List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - batch List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - batch List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - batch List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2]
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

        $('#batch-table').on("click", ".list-btn", function() {
            var data = batchTable.row($(this).parents('tr')).data();
            $("#batch-list-modal").modal("show");
            batchListTable.ajax.url(`${appUrl}/api/batch/batch_list/${school_code}/${data.code}`).load();
        });

    </script>
@endsection
