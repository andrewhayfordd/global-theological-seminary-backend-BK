@extends('layout.app')
@section('page-name', 'Notice')

@section('page-content')

    <div class="container-fluid my-5">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Notice Board</h1>
            <div>
                <a href="#" class="btn btn-sm btn-primary shadow-sm mx-0" data-toggle="modal"
                    data-target="#notice-modal"><i class=""></i>Send Notice</a>
            </div>
        </div>

        <!-- Content Column -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="grade-tab" data-toggle="tab" href="#grade" role="tab"
                    aria-controls="grade" aria-selected="false">Current Notice</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="division-tab" data-toggle="tab" href="#division" role="tab"
                    aria-controls="contact" aria-selected="false">Previous Notice</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="all-notice-tab" data-toggle="tab" href="#all-notice" role="tab"
                    aria-controls="contact" aria-selected="false">All Notices</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!--Curr notice Tab-->
            <div class="tab-pane fade show active mt-3" id="grade" role="tabpanel" aria-labelledby="grade-tab">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="m-0 font-weight-bold text-primary">
                            {{-- All Current Notices: --}}
                            <span class="text-secondary"></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table cellspacing="0" width="100%"
                                class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                                id="curr-notice-table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>News Type</th>
                                        <th>News Recipient </th>
                                        <th>News Title</th>
                                        <th>News Details</th>
                                        {{-- <th>Start Date</th>
                                    <th>End Date</th> --}}
                                        {{-- <th>Action </th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data is fetched here using ajax -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--Prev notice tab-->
            <div class="tab-pane fade mt-3" id="division" role="tabpanel" aria-labelledby="division-tab">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="m-0 font-weight-bold text-primary">
                            {{-- All Previous Notices: --}}
                            <span class="text-secondary"></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table width="100%"
                                class="table table-bordered table-sm table-striped table-hover dataTable js-exportable"
                                id="prev-notice-table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>News Type</th>
                                        <th>News Recipient </th>
                                        <th>News Title</th>
                                        <th>News Details</th>
                                        {{-- <th>Start Date</th>
                                    <th>End Date</th> --}}
                                        {{-- <th>Action </th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data is fetched here using ajax -->
                                </tbody>
                            </table>
                        </div>
                        <!-- End of my prev notice datatable -->
                    </div>
                </div>
            </div>

            <!--All notice tab-->
            {{-- <div class="tab-pane fade mt-3" id="all-notice" role="tabpanel" aria-labelledby="all-notice-tab">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="m-0 font-weight-bold text-primary">
                            <span class="text-secondary"></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-md mt-3 ">
                            <table width="100%" class="table table-striped table-bordered" id="all-notice-table"
                                cellspacing="0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>News Type</th>
                                        <th>News Recipient </th>
                                        <th>News Title</th>
                                        <th>News Details</th>
                                        
                                        <th >Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="tab-pane fade  " id="all-notice" role="tabpanel" aria-labelledby="all-notice-tab">

                <div class="table-responsive mt-3 ">
                    <table id="all-notice-table" class="table table-striped table-bordered " cellspacing="0" width="100%">
                        <thead class="thead-dark">
                            <th>News Type</th>
                            <th>News Recipient </th>
                            <th>News Title</th>
                            <th>News Details</th>
                            <th>Action </th>

                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <!--End of Main Content-->

    </div>

    @include('staff.notice.modals.send_notice')
    @include('staff.notice.modals.edit_notice')

    <script src="{{ asset('js/modules/notice/delete.js') }}"></script>
    <script>
        // Grade table
        var currNoticeTable = $('#curr-notice-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/notice/fetch_curr_notice/${school_code}`,
                type: "GET",
                headers: {
                    "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE"
                }
            },
            processing: true,
            columns: [{
                    data: "type_desc"
                },
                {
                    data: "recipient"
                },
                {
                    data: "news_title"
                },
                {
                    data: "news_details"
                },
                // {
                //     data: "date_start"
                // },
                // {
                //     data: "date_end"
                // }
            ],
            // pageLength: 15,
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Notice List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Notice List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Notice List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Notice List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
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

        var prevNoticeTable = $('#prev-notice-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/notice/fetch_prev_notice/${school_code}`,
                type: "GET",
                headers: {
                    "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE"
                }
            },
            processing: true,
            columns: [{
                    data: "type_desc"
                },
                {
                    data: "recipient"
                },
                {
                    data: "news_title"
                },
                {
                    data: "news_details"
                },
                // {
                //     data: "date_start"
                // },
                // {
                //     data: "date_end"
                // }
            ],
            // pageLength: 15,
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Notice List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded ml-2"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Notice List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded ml-2"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Notice List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded ml-2"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Notice List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded ml-2"
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

        var allNoticeTable = $('#all-notice-table').DataTable({
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/notice/fetch_all_notice/${school_code}`,
                type: "GET",
            },
            ordering: true,
            order: [],
            processing: true,
            // responsive: true,
            columns: [{
                    data: "type_desc"
                },
                {
                    data: "recipient"
                },
                {
                    data: "news_title"
                },
                {
                    data: "news_details"
                },
                {
                    data: null,
                    defaultContent: "<button href = '#' class='btn btn-outline-info btn-sm action-btn' > <i class='fas fa-edit'></i> </button> <button href = '#' class='btn btn-outline-danger btn-sm delete-btn' onclick = deleteNotice()> <i class='far fa-trash-alt'></i> </button>",
                },
            ],
            // pageLength: 15,
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Notice List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded ml-2"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Notice List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded ml-2"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Notice List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded ml-2"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Notice List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded ml-2"
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

        $("#all-notice-table").on("click", ".action-btn", function() {
            const data = allNoticeTable.row($(this).parents('tr')).data();
            $("#edit-notice-modal").modal("show")
            $("#edit-notice-title").val(data.news_title);
            $("#edit-notice-recipient").val(data.rec).trigger('change');
            $("#edit-notice-details").val(data.news_details);
            $("#edit-notice-start_date").val(data.date_s);
            $("#edit-notice-end_date").val(data.date_e);
            $("#edit-notice-post").val(data.post);
            $("#edit-notice-type").val(data.type).trigger('change');
            $("#edit-transid").val(data.transid);
        });

        $("#all-notice-table").on("click", ".delete-btn", function() {
            const deleteData = allNoticeTable.row($(this).parents('tr')).data();
            deleteNotice(deleteData.transid)
        });
    </script>
@endsection
