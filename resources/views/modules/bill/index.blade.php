@extends('layout.app')
@section('page-name', 'Bill')
@section('page-content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h3 class="page-title">@yield('page-name')
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Bill</li>
                </ul>
            </h3>
            <div class="">
                <a href="#" data-toggle="modal" data-target="#add-bill-prog-modal"
                    class="btn btn-sm btn-info shadow-sm mx-0"><i class=""></i>Add Programme Bill Item</a>
                <a href="#" data-toggle="modal" data-target="#add-bill-modal"
                    class="btn btn-sm btn-info shadow-sm mx-0"><i class=""></i>Add Student Bill Item</a>
                <a href="#" data-toggle="modal" data-target="#add-bill-amount-modal"
                    class="btn btn-sm btn-primary shadow-sm mx-0"><i class=""></i>Add Programme Bill</a>
                <a href="#" data-toggle="modal" data-target="#individual-bill-modal"
                    class="btn btn-sm btn-primary shadow-sm mx-0"><i class=""></i>Add Individual Bill</a>
            </div>
        </div>

    </div>
    <!-- /Page Header -->

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="bill-items-tab" data-toggle="tab" href="#add-bills" role="tab"
                aria-controls="home" aria-selected="true">Bill Items</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="student-bill-tab" data-toggle="tab" href="#add-student-bills" role="tab"
                aria-controls="profile" aria-selected="false">Individual Bill items</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="student-bill-setup-tab" data-toggle="tab" href="#student-bill-setup" role="tab"
                aria-controls="profile" aria-selected="false">Programme Bills</a>
        </li>
    </ul>
    <div class="tab-content" id="bills">
        <div class="tab-pane fade show active" id="add-bills" role="tabpanel" aria-labelledby="home-tab">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col mt-2 ">
                            {{-- <form id="filter-bill">
                                <div class="row mb-3">
                                    <div class="col mt-2 ml-4">

                                        <select class="form-select select2" aria-label="Default select example"
                                            id="select-batch">
                                            <option value="">--Select Batch--</option>
                                            @foreach ($batches as $item)
                                                <option value="{{ $item->batch_code }}">{{ $item->batch_desc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col mt-2 ml-4">

                                        <select class="form-select select2" aria-label="Default select example"
                                            id="select-program">
                                            <option value="">--Select Program--</option>
                                            @foreach ($programs as $item)
                                                <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col mt-2 ml-4">

                                        <select class="form-select select2" aria-label="Default select example"
                                            id="select-branch">
                                            <option value="">--Select Branch--</option>
                                            @foreach ($branches as $item)
                                                <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col" style="padding-top: 5px;">
                                        <button class="btn btn-md btn-outline-primary" type="submit" form="filter-bill"><i
                                                class="fa fa-filter"></i></button>
                                    </div>
                                </div>

                            </form> --}}
                        </div>

                    </div>
                    <div class="table-">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="bill-item-table">
                            <thead>
                                <tr>
                                    <th>Bill Code</th>
                                    <th>Bill Items</th>
                                    <th>Bill Amount</th>
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
        </div>
        <div class="tab-pane fade" id="add-student-bills" role="tabpanel" aria-labelledby="student-bill-tab">
            <div class="mt-3">
                <form id="student-form">
                    @csrf
                    <div class="col-6 col-md-12 col-sm">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Branch <span class="text-danger">*</span></label>
                                    <select name="acyear" class="form-control m-b d-inline select2" id="filter-bill-branch"
                                        required>
                                        <option value="">--Select--</option>
                                        @foreach ($branches as $item)
                                            <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Semester <span class="text-danger">*</span></label>
                                    <select name="acterm" class="form-control m-b d-inline select2"
                                        id="filter-bill-semester" required>
                                        <option value="">--Select--</option>
                                        @foreach ($semester as $item)
                                            <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Student <span class="text-danger">*</span></label>
                                    <select name="student" class="form-control select2" id="filter-bill-student"
                                        required>
                                        <option value="">--Select--</option>
                                        @foreach ($studentList as $item)
                                            <option value="{{ $item->student_no }}">{{ $item->lname }}
                                                {{ $item->mname }} {{ $item->fname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <p><span id="errMessage" style="color:red;"></span></p>
                    </div>
                </form>
            </div>
            <div class="card shadow mb-4">
                <div class="row">
                    <div class="text-end text-capitalize  text-dark pt-2">
                        <span>Total: GHS</span>
                        <span id="text-total-bill" class="mr-3">0</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="student-bill-table">
                            <thead>
                                <tr>
                                    <th>Programme</th>
                                    <th>Student ID</th>
                                    <th>Bill Item</th>
                                    <th>Bill Amount</th>
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
        </div>
        <div class="tab-pane fade" id="student-bill-setup" role="tabpanel" aria-labelledby="student-bill-tab">
            <div class="mt-3">
                <form id="student-form">
                    @csrf
                    <div class="col-6 col-md-12 col-sm">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Batch <span class="text-danger">*</span></label>
                                    <select name="acyear" class="form-control m-b d-inline select2"
                                        id="filter-prog-bill-batch" required>
                                        <option value="">--Select--</option>
                                        @foreach ($batches as $item)
                                            <option value="{{ $item->batch_code }}">{{ $item->batch_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Branch <span class="text-danger">*</span></label>
                                    <select name="acyear" class="form-control m-b d-inline select2"
                                        id="filter-prog-bill-branch" required>
                                        <option value="">--Select--</option>
                                        @foreach ($branches as $item)
                                            <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Semester <span class="text-danger">*</span></label>
                                    <select name="acterm" class="form-control m-b d-inline select2"
                                        id="filter-prog-bill-semester" required>
                                        <option value="">--Select--</option>
                                        @foreach ($semester as $item)
                                            <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Programme <span class="text-danger">*</span></label>
                                    <select name="student" class="form-control select2" id="filter-prog-bill-student"
                                        required>
                                        <option value="">--Select--</option>
                                        @foreach ($programs as $item)
                                            <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <p><span id="errMessages" style="color:red;"></span></p>
                    </div>
                </form>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="prog-bill-table">
                            <thead>
                                <tr>
                                    <th>Student Number</th>
                                    <th>Student Name</th>
                                    <th>Total Bill</th>
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
        </div>

    </div>
    @include('modules.bill.modals.discount_ind_bill')
    @include('modules.bill.modals.edit_ind_bill')
    @include('modules.bill.modals.add_bill')
    @include('modules.bill.modals.add_bill_amount')
    @include('modules.bill.modals.edit_bill_amount')
    @include('modules.bill.modals.edit_student_bill')
    @include('modules.bill.modals.add_bill_item')
    @include('modules.bill.modals.edit_bill_item')
    @include('modules.bill.modals.add_prog_bill_item')
    @include('modules.bill.modals.add_student_bill')


    <script>
        var billTable = $('#bill-item-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/bill/fetch_bill_item/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            columns: [{
                    data: "billCode"
                },
                {
                    data: "billDesc"
                },
                {
                    data: "amount"
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
                    text: "Add Bill Item",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-bill-item-modal").modal("show")
                    }
                },
            ]
        });


        var studentSelector = document.getElementById('filter-bill-student');
        var sem = document.getElementById("filter-bill-semester");
        var branch = document.getElementById("filter-bill-branch");
        var studentBillTable;
        var erMsg = null;
        //Add student bill item
        studentBillTable = $('#student-bill-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/bill/fetch_student_bill/${school_code}`,
                type: "POST",
            },
            processing: true,
            responsive: true,
            columns: [{
                    data: "program"
                },
                {
                    data: "student_no"
                },
                {
                    data: "bill"
                },
                {
                    data: "amount"
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
            ]
        });

        $(studentSelector).on('select2:select', function(e) {
            // e.preventDefault();
            if (branch.value === '' || sem.value === '' || studentSelector.value === '') {
                erMsg = document.getElementById("errMessage").innerHTML = "Please all fields are required!";
                return false;

            }

            if (sem.value != '' || branch.value != '' || studentSelector.value != '') {
                setTimeout(function() {
                    document.getElementById("errMessage").innerHTML = '';
                }, 1000);

                studentBillTable = $('#student-bill-table').DataTable({
                    destroy: true
                }).destroy();

                studentBillTable = $('#student-bill-table').DataTable({
                    "lengthChange": false,
                    dom: 'Bfrtip',
                    ajax: {
                        url: `${appUrl}/api/bill/fetch_student_bill/${school_code}`,
                        type: "POST",
                        data: {
                        'student': studentSelector.value,
                        'semester': sem.value,
                        'branch': branch.value
                    },
                    error: function (xhr) {
                    console.error("Error fetching data:", xhr.responseText);
                    Swal.fire({
                    text: "Failed to load data. Please try again.",
                    icon: "error",
                      });
                    }
                    },
                    processing: true,
                    columns: [{
                            data: "program"
                        },
                        {
                            data: "student_no"
                        },
                        {
                            data: "bill"
                        },
                        {
                            data: "amount"
                        },
                        {
                            data: "action"
                        }

                    ],
                    buttons: [{
                            extend: 'print',
                            title: `${loggedInUserSchoolName} - Bill List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, ]
                            }
                        },
                        {
                            extend: 'copy',
                            title: `${loggedInUserSchoolName} - Bill List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, ]
                            }
                        },
                        {
                            extend: 'excel',
                            title: `${loggedInUserSchoolName} - Bill List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, ]
                            }
                        },
                        {
                            extend: 'pdf',
                            title: `${loggedInUserSchoolName} - Bill List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, ]
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

                let formdata = new FormData();
                formdata.append('semester', sem.value);
                formdata.append('branch', branch.value);
                formdata.append('student', studentSelector.value);
                formdata.append('school_code', school_code);

                fetch(`${appUrl}/api/bill/fetch_student_total_bill`, {
                    method: "POST",
                    body: formdata,
                }).then(function(res) {
                    return res.json()
                }).then(function(data) {
                    if (!data.ok) {
                        Swal.fire({
                            text: data.msg,
                            type: "error"
                        });
                        return;
                    }
                    document.getElementById("text-total-bill").innerHTML = data.data.total_bill;
                });
            }
        });


        var progSelector = document.getElementById('filter-prog-bill-student');
        var sems = document.getElementById("filter-prog-bill-semester");
        var branchs = document.getElementById("filter-prog-bill-branch");
        var batchs = document.getElementById("filter-prog-bill-batch");
        var programBillTable;
        var erMsgs = null;
        //bill amount items
        var programBillTable = $('#prog-bill-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/bill/fetch_program_bill/${school_code}`,
                type: "POST",
            },
            processing: true,
            responsive: true,
            columns: [{
                    data: "billCode"
                },
                {
                    data: "billDesc"
                },
                {
                    data: "billAmount"
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
            ]
        });

        $(progSelector).on('select2:select', function(e) {
            // e.preventDefault();
            if (branchs.value === '' || sems.value === '' || progSelector.value === '' || batchs.value === '') {
                erMsgs = document.getElementById("errMessages").innerHTML = "Please all fields are required!";
                return false;

            }

            if (sems.value != '' || branchs.value != '' || progSelector.value != '' || batchs.value === '') {
                setTimeout(function() {
                    document.getElementById("errMessages").innerHTML = '';
                }, 1000);

                programBillTable = $('#prog-bill-table').DataTable({
                    destroy: true
                }).destroy();

                programBillTable = $('#prog-bill-table').DataTable({
                    "lengthChange": false,
                    dom: 'Bfrtip',
                    ajax: {
                        url: `${appUrl}/api/bill/fetch_program_bill/${school_code}`,
                        type: "POST",
                        data: {
                            'program': progSelector.value,
                            'semester': sems.value,
                            'batch': batchs.value,
                            'branch': branchs.value
                        },
                    },
                    processing: true,
                    columns: [{
                            data: "student_no"
                        },
                        {
                            data: "student_name"
                        },
                        {
                            data: "amount"
                        },
                        {
                            data: "action"
                        }

                    ],
                    buttons: [{
                            extend: 'print',
                            title: `${loggedInUserSchoolName} - Bill List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, ]
                            }
                        },
                        {
                            extend: 'copy',
                            title: `${loggedInUserSchoolName} - Bill List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, ]
                            }
                        },
                        {
                            extend: 'excel',
                            title: `${loggedInUserSchoolName} - Bill List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, ]
                            }
                        },
                        {
                            extend: 'pdf',
                            title: `${loggedInUserSchoolName} - Bill List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, ]
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
            }
        });

        //Deleting elements from tblbill_amt
        $("#student-bill-setup-table").on("click", ".delete-btn", function() {
            let data = billItemAmountTable.row($(this).parents('tr')).data();

            Swal.fire({
                title: "Are you sure you want to delete this bill?",
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
                        url: `${appUrl}/api/payment/destroyBillItemAmount`,
                        type: "POST",
                        data: {
                            "schoolCode": `${school_code}`,
                            "billCode": `${data.billCode}`,
                            "billsemester": `${data.billSemester}`,
                            "billSession": `${data.billSession}`,
                            "billBatch": `${data.billBatch}`,
                            "billLevel": `${data.billLevel}`,
                            "billProgram": `${data.billProgram}`,
                            "billBranch": `${data.billBranch}`,
                            "billTransId": `${data.billTransid}`
                        }
                    }).done(function(data) {
                        if (!data.ok) {
                            Swal.fire({
                                text: data.msg,
                                type: "error"
                            });
                            return;
                        }
                        Swal.fire({
                            text: "Bill deleted successfully",
                            type: "success"
                        });
                        billItemAmountTable.ajax.reload(false, null);

                    }).fail((xhr, status, error) => {
                        console.log(error);
                        alert('Processing failed');
                    })
                }
            })
        });

        //Discount individual bill
        $("#student-bill-table").on("click", ".discount-btn", function() {
            let data = studentBillTable.row($(this).parents('tr')).data();
            // console.log(data);
            $('#discount-ind-modal').modal('show');
            $('#dis-ind-item').val(data.itemcode);
            $('#dis-ind-student').val(data.student_no);
            $('#dis-ind-semester').val(data.semester);
            $('#dis-ind-branch').val(data.branch);
            $('#dis-ind-amount').val(data.amount);
        });

        //Delete individual bill
        $("#student-bill-table").on("click", ".ind-delete-btn", function() {
            let data = studentBillTable.row($(this).parents('tr')).data();
           
            Swal.fire({
                title: "Are you sure you want to delete this bill item for this student?",
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
                        url: `${appUrl}/api/bill/delete_student_bill_item`,
                        type: "POST",
                        data: {
                            "school_code": `${school_code}`,
                            "student": `${data.student_no}`,
                            "semester": `${data.semester}`,
                            "item": `${data.itemcode}`,
                        },
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
                        studentBillTable.ajax.reload(false, null);

                    }).fail(() => {
                        alert('Processing failed');
                    })
                }
            })
        });

        //Edit individual bill
        $("#student-bill-table").on("click", ".ind-edit-btn", function() {
            let data = studentBillTable.row($(this).parents('tr')).data();
            $('#edit-ind-modal').modal('show');
            $('#ind-item').val(data.itemcode);
            $('#ind-student').val(data.student_no);
            $('#ind-semester').val(data.semester);
            $('#ind-branch').val(data.branch);
        });
    </script>
@endsection