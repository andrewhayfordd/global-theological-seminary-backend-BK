@extends('layout.app')

@section('page-content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <h4>Inventory</h4>
                    </ul>
            </div>
        </div>
    </div>
    <!-- Content Row -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="gradeBill-tab" data-toggle="tab" href="#gradeBill" role="tab"
                aria-controls="gradeBill" aria-selected="false">Requests</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="studentBill-tab" data-toggle="tab" href="#studentBill" role="tab"
                aria-controls="studentBill" aria-selected="false">Items</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <!--Table requisition-->
        <div class="tab-pane fade show active mt-3" id="gradeBill" role="tabpanel" aria-labelledby="gradeBill-tab">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                </div>
                <div class="card-body">
                    <!-- requisition datatable -->
                    <div class="table-responsive">
                        <table width="100%"
                            class="table table-bordered table-sm table-striped table-hover dataTable js-exportable"
                            id="requisition-table">
                            <thead class="">
                                <tr>
                                    <th>Item</th>
                                    <th>Req. Quantity</th>
                                    <th>Date Requested</th>
                                    <th>Requestor</th>
                                    {{-- <th>Academic Year/Term</th> --}}
                                    <th>Status</th>
                                    <th>Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data is fetched here using ajax -->
                            </tbody>
                        </table>
                    </div>
                    <!-- End of requisition datatable -->
                </div>
            </div>
        </div>
        <!--End Deparment-->

        <!--Staff requisition-->
        <div class="tab-pane fade" id="studentBill" role="tabpanel" aria-labelledby="studentBill-tab">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                </div>
                <div class="card-body">
                    <!-- requisition datatable -->
                    <div class="table-responsive">
                        <table width="100%"
                            class="table table-bordered table-sm table-striped table-hover dataTable js-exportable"
                            id="cat-table">
                            <thead class="">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data is fetched here using ajax -->
                            </tbody>
                        </table>
                    </div>
                    <!-- End of requisition datatable -->

                </div>
            </div>
        </div>
    </div>


    @include('modules.req.modals.add_exp')
    @include('modules.req.modals.view_exp')
    @include('modules.req.modals.add_cat')
    @include('modules.req.modals.edit_cat')

    <script>
        var requisitionTable = $('#requisition-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/requisition/fetch/${school_code}`,
                type: "GET",
            },
            processing: true,
            columns: [{
                    data: "item"
                },
                {
                    data: "req_quantity"
                },
                {
                    data: "req_date"
                },
                {
                    data: "staff"
                },
                {
                    data: "status"
                },
                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: ` requisition List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: ` requisition List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: ` requisition List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: ` requisition List`,
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
                        dt.ajax.reload(null, false);
                    }
                },
                {
                    text: "Request",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-req-modal").modal("show")
                    }
                },
            ]
        });

        //Delete expense
        $("#requisition-table").on("click", ".delete-btn", function() {
            let data = requisitionTable.row($(this).parents('tr')).data();

            Swal.fire({
                title: "Are you sure you want to delete request?",
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
                        url: `${appUrl}/api/requisition/delete/${data.id}/${school_code}`,
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
                            text: "Deleted successfully",
                            type: "success"
                        });
                        requisitionTable.ajax.reload(null, false);

                    }).fail(() => {
                        alert('Processing failed');
                    })
                }
            })
        });

        //request info
        $("#requisition-table").on("click", ".info-btn", function() {
            let data = requisitionTable.row($(this).parents('tr')).data();

            $("#info-modal").modal("show");

            $('#full-details-item').html(data.item);
            $('#full-details-quantity').html(data.req_quantity);
            $('#full-details-staff').html(data.staff);
            $('#full-details-date-req').html(data.req_date);
            $('#full-details-del-quantity').html(data.del_quantity);
            $('#full-details-del-staff').html(data.del_staff);
            $('#full-details-del-date').html(data.del_date);
            $('#full-details-semester').html(data.semester);
            $('#full-details-status').html(data.status);



        });

        var requisitionCatTable = $('#cat-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/requisition/fetch_category/${school_code}`,
                type: "GET",
            },
            processing: true,
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
                    title: ` requisition List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, ]
                    }
                },
                {
                    extend: 'copy',
                    title: ` requisition List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, ]
                    }
                },
                {
                    extend: 'excel',
                    title: ` requisition List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, ]
                    }
                },
                {
                    extend: 'pdf',
                    title: ` requisition List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, ]
                    }
                },
                {
                    text: "Refresh",
                    attr: {
                        class: "ml-2 btn-secondary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        dt.ajax.reload(null, false);
                    }
                },
                {
                    text: "Add item",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-cat-modal").modal("show")
                    }
                },
            ]
        });

        $("#cat-table").on("click", ".delete-req-btn", function() {
            let data = requisitionCatTable.row($(this).parents('tr')).data();

            Swal.fire({
                title: "Are you sure you want to delete item?",
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
                        url: `${appUrl}/api/requisition/delete/${data.code}`,
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
                            text: "Deleted successfully",
                            type: "success"
                        });
                        requisitionCatTable.ajax.reload(null, false);

                    }).fail(() => {
                        alert('Processing failed');
                    })
                }
            })
        });

        $("#cat-table").on("click", ".edit-req-btn", function() {
            let data = requisitionCatTable.row($(this).parents('tr')).data();

            $("#edit-cat-modal").modal("show");

            $('#edit-cat-id').val(data.code);
            $('#edit-cat-exp').val(data.desc);

        });
    </script>
@endsection
