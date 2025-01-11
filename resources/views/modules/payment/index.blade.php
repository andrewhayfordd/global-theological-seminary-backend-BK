@extends('layout.app')
@section('n')

@section('page-content')

    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <h4>Payment</h4>
                    </ul>
            </div>
            {{-- <div class="col text-right">
                <div>
                    <a href="#" data-toggle="modal" data-target="#addBookModal" data-toggle="tooltip" data-placement="bottom"
                        title="Add staff" class="btn btn-sm btn-primary shadow-sm">Add Book</a>
                    <a href="#" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                        data-target="#addCategoryModal"><i class=""></i>Add Category</a>
                        <a href="#" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                        data-target="#borrowBookModal"><i class=""></i>Borrow Book</a> 
                        <a href="#" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                        data-target="#recommendBookModal"><i class=""></i>Recommend Book</a>
                </div>
            </div> --}}
        </div>
    </div>
    <!-- Table tabs -->
    <!-- Content Column -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="available-books-navlink" data-toggle="tab" href="#all-payments" role="tab"
                aria-controls="alllunch" aria-selected="false">All payments</a>
        </li>

        <li class="nav-item">
            <a class="nav-link " id="borrowed-books-navlink" data-toggle="tab" href="#all-debtors" role="tab"
                aria-controls="lunch" aria-selected="false">Debtors </a>
        </li>

        <li class="nav-item">
            <a class="nav-link " id="returned-books-navlink" data-toggle="tab" href="#full-payment" role="tab"
                aria-controls="return" aria-selected="false">Full Payment</a>
        </li>

        <li class="nav-item">
            <a class="nav-link " id="due-books-navlink" data-toggle="tab" href="#daily-payment" role="tab"
                aria-controls="due" aria-selected="false">Daily Fee Payment</a>
        </li>

        <li class="nav-item">
            <a class="nav-link " id="overdue-due-books-navlink" data-toggle="tab" href="#payment-history" role="tab"
                aria-controls="overdue" aria-selected="false">Payment History </a>
        </li>

        <li class="nav-item">
            <a class="nav-link " id="ledger-navlink" data-toggle="tab" href="#ledger-history" role="tab"
                aria-controls="ledger" aria-selected="false">Payment Ledger </a>
        </li>
    </ul>

    <div class="tab-content">
        {{-- All payments --}}
        <div role="tabpanel" class=" tab-pane active card shadow mb-4" id="all-payments">
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
                            {{-- <div class="col">
                                <div class="form-group">
                                    <label>Student <span class="text-danger">*</span></label>
                                    <select name="student" class="form-control select2" id="filter-bill-student" required>
                                        <option value="">--Select--</option>
                                        @foreach ($students as $item)
                                            <option value="{{ $item->student_no }}">{{ $item->lname }}
                                                {{ $item->mname }} {{ $item->fname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                        </div>
                        <p><span id="errMessage" style="color:red;"></span></p>
                    </div>
                </form>
            </div>
            <div class="card-header">
                {{-- <div>
                    <span>Total Amount Collected : <b> {{ $paymentTotal }} </b></span> <span>Total Balance :
                        <b>{{ $paymentBalance }}</b></span>
                </div> --}}
            </div>
            <div class="card-body">
                <div class="table-">
                    <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                        width='100%' id="payment-table">
                        <thead>
                            <tr>
                                {{-- <th>Semester</th> --}}
                                <th>Student</th>
                                <th>Program</th>
                                <th>Bill</th>
                                <th>Total Paid</th>
                                <th>Balance</th>
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
        <!--debtors table-->
        <div role="tabpanel" class="tab-pane mb-4" id="all-debtors">
            <div class="mt-3">
                <form id="debt-filter-form">
                    @csrf
                    <div class="col-6 col-md-12 col-sm">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Batch <span class="text-danger">*</span></label>
                                    <select name="batch" class="form-control m-b d-inline select2"
                                        id="filter-debt-batch" required>
                                        <option value="">--Select--</option>
                                        @foreach ($batch as $item)
                                            <option value="{{ $item->batch_code }}">{{ $item->batch_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Session <span class="text-danger">*</span></label>
                                    <select name="session" class="form-control m-b d-inline select2"
                                        id="filter-debt-sess" required>
                                        <option value="">--Select--</option>
                                        @foreach ($session as $item)
                                            <option value="{{ $item->session_code }}">{{ $item->session_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Program <span class="text-danger">*</span></label>
                                    <select name="batch" class="form-control m-b d-inline select2"
                                        id="filter-debt-prog" required>
                                        <option value="">--Select--</option>
                                        @foreach ($prog as $item)
                                            <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Branch <span class="text-danger">*</span></label>
                                    <select name="branch" class="form-control m-b d-inline select2"
                                        id="filter-debt-branch" required>
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
                                    <select name="sem" class="form-control m-b d-inline select2"
                                        id="filter-debt-semester" required>
                                        <option value="">--Select--</option>
                                        @foreach ($semester as $item)
                                            <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <p><span id="debtErrorMessages" style="color:red;"></span></p>
                    </div>
                </form>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="all-debtors-table">
                            <thead>
                                <tr>
                                    <th>Name Of Student</th>
                                    <th>Amount Paid</th>
                                    <th>Overall Balance</th>
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
        <!--full payment-->
        <div role="tabpanel" class=" tab-pane mb-4" id="full-payment">
            <div class="mt-3">
                <form id="debt-filter-form">
                    @csrf
                    <div class="col-6 col-md-12 col-sm">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Batch <span class="text-danger">*</span></label>
                                    <select name="batch" class="form-control m-b d-inline select2"
                                        id="filter-fullpay-batch" required>
                                        <option value="">--Select--</option>
                                        @foreach ($batch as $item)
                                            <option value="{{ $item->batch_code }}">{{ $item->batch_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Session <span class="text-danger">*</span></label>
                                    <select name="session" class="form-control m-b d-inline select2"
                                        id="filter-fullpay-sess" required>
                                        <option value="">--Select--</option>
                                        @foreach ($session as $item)
                                            <option value="{{ $item->session_code }}">{{ $item->session_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Program <span class="text-danger">*</span></label>
                                    <select name="batch" class="form-control m-b d-inline select2"
                                        id="filter-fullpay-prog" required>
                                        <option value="">--Select--</option>
                                        @foreach ($prog as $item)
                                            <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Branch <span class="text-danger">*</span></label>
                                    <select name="branch" class="form-control m-b d-inline select2"
                                        id="filter-fullpay-branch" required>
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
                                    <select name="sem" class="form-control m-b d-inline select2"
                                        id="filter-fullpay-semester" required>
                                        <option value="">--Select--</option>
                                        @foreach ($semester as $item)
                                            <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <p><span id="fullErrorMessages" style="color:red;"></span></p>
                    </div>
                </form>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="full-payment-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Amount Paid</th>
                                    <th>Overall Balance</th>
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
        {{-- Daily payment --}}
        <div role="tabpanel" class=" tab-pane  card shadow mb-4" id="daily-payment">
            <div class="card-header">
                <div class="mt-3">
                    <form id="his-filter-form">
                        @csrf
                        <div class="col-6 col-md-6 col-sm">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">Filter Branch <span class="text-danger">*</span></label>
                                        <select name="branch" class="form-control m-b d-inline select2"
                                            id="filter-daily-branch" required>
                                            <option value="">--Select--</option>
                                            @foreach ($branches as $item)
                                                <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-">
                    <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                        width='100%' id="daily-payment-table">
                        <thead>
                            <tr>
                                <th>Semester</th>
                                <th>Student</th>
                                <th>Bill</th>
                                <th>Amount Paid</th>
                                <th>Overall Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data is fetched here using ajax --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--payment history-->
        <div role="tabpanel" class=" tab-pane  card shadow mb-4" id="payment-history">
            <div class="mt-3">
                <form id="his-filter-form">
                    @csrf
                    <div class="col-6 col-md-12 col-sm">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Branch <span class="text-danger">*</span></label>
                                    <select name="branch" class="form-control m-b d-inline select2"
                                        id="filter-his-branch" required>
                                        <option value="">--Select--</option>
                                        @foreach ($branches as $item)
                                            <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Student <span class="text-danger">*</span></label>
                                    <select name="sem" class="form-control m-b d-inline select2"
                                        id="filter-his-student" required>
                                        <option value="">--Select--</option>
                                        @foreach ($students as $item)
                                            <option value="{{ $item->student_no }}">
                                                {{ $item->fname . ' ' . $item->mname . ' ' . $item->lname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Semester <span class="text-danger">*</span></label>
                                    <select name="sem" class="form-control m-b d-inline select2"
                                        id="filter-his-semester" required>
                                        <option value="">--Select--</option>
                                        <option value="all">--All--</option>
                                        @foreach ($semester as $item)
                                            <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <p>Overall Balance : GHS <span id="show-total" style="color:purple;"
                                class="font-weight-bold"></span></p>
                        <p><span id="hisErrorMessages" style="color:red;"></span></p>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-">
                    <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                        width='100%' id="payment-history-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Semester</th>
                                <th>Student</th>
                                <th>Trans. Channel</th>
                                <th>Bill</th>
                                <th>Amount Paid</th>
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
        <!--payment ledger-->
        <div role="tabpanel" class=" tab-pane  card shadow mb-4" id="ledger-history">
            <div class="mt-3">
                <form id="ledger-filter-form">
                    @csrf
                    <div class="col-6 col-md-12 col-sm">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">From <span class="text-danger">*</span></label>
                                    <input type="date" name="from" class="form-control form-control-sm"
                                        id="filter-from" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">To <span class="text-danger">*</span></label>
                                    <input type="date" name="to" class="form-control form-control-sm"
                                        id="filter-to" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Programme <span class="text-danger">*</span></label>
                                    <select name="sem" class="form-control m-b d-inline select2"
                                        id="filter-ledger-student" required>
                                        <option value="">--Select--</option>
                                        @foreach ($prog as $item)
                                            <option value="{{ $item->prog_code }}">
                                                {{ $item->prog_desc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Branch <span class="text-danger">*</span></label>
                                    <select name="branch" class="form-control m-b d-inline select2"
                                        id="filter-ledger-branch" required>
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
                                    <select name="sem" class="form-control m-b d-inline select2"
                                        id="filter-ledger-semester" required>
                                        <option value="">--Select--</option>
                                        <option value="all"> All </option>
                                        @foreach ($semester as $item)
                                            <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <p><span id="ledgerErrorMessages" style="color:red;"></span></p>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-">
                    <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                        width='100%' id="ledger-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Semester</th>
                                <th>Trans.</th>
                                <th>Student</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Balance</th>
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

    @include('modules.payment.modals.add_payment')
    @include('modules.payment.modals.edit_payment')
    @include('modules.payment.modals.payment_info')
    <script>
        // var progSelector = document.getElementById('filter-bill-student');
        var sems = document.getElementById("filter-bill-semester");
        var branchs = document.getElementById("filter-bill-branch");
        // var batchs = document.getElementById("filter-prog-bill-batch");
        var paymentTable;
        var erMsgs = null;

        paymentTable = $('#payment-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
             ajax: {
                 url: `${appUrl}/api/payment/all_payment/${school_code}`,
                 type: "POST",
             },
            processing: true,
            responsive: true,
            columns: [{
                    data: "student"
                },
                {
                    data: "program"
                },
                {
                    data: "bill"
                },
                {
                    data: "amtpaid"
                },
                {
                    data: "arrears"
                },
                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - payment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - payment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - payment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - payment List`,
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
                    text: '<i class="fa fa-money" aria-hidden="true"></i> Make Payment',
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-payment-modal").modal("show")
                    }
                },
            ]
        });

        $(sems).on('select2:select', function(e) {
            // e.preventDefault();
            if (branchs.value === '' || sems.value === '') {
                erMsgs = document.getElementById("errMessages").innerHTML = "Please all fields are required!";
                return false;

            }

            if (sems.value != '' || branchs.value != '') {
                setTimeout(function() {
                    document.getElementById("errMessages").innerHTML = '';
                }, 1000);

                paymentTable = $('#payment-table').DataTable({
                    destroy: true
                }).destroy();

                paymentTable = $('#payment-table').DataTable({
                    "lengthChange": false,
                    dom: 'Bfrtip',
                    ajax: {
                        url: `${appUrl}/api/payment/all_payment/${school_code}`,
                        type: "POST",
                        data: {
                            'semester': sems.value,
                            'branch': branchs.value
                        },
                    },
                    processing: true,
                    responsive: true,
                    columns: [{
                            data: "student"
                        },
                        {
                            data: "program"
                        },
                        {
                            data: "bill"
                        },
                        {
                            data: "amtpaid"
                        },
                        {
                            data: "arrears"
                        },
                        {
                            data: "action"
                        }
                    ],
                    buttons: [{
                            extend: 'print',
                            title: `${loggedInUserSchoolName} - payment List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'copy',
                            title: `${loggedInUserSchoolName} - payment List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'excel',
                            title: `${loggedInUserSchoolName} - payment List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'pdf',
                            title: `${loggedInUserSchoolName} - payment List`,
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
                            text: '<i class="fa fa-money" aria-hidden="true"></i> Make Payment',
                            attr: {
                                class: "ml-2 btn-primary btn btn-sm rounded"
                            },
                            action: function(e, dt, node, config) {
                                $("#add-payment-modal").modal("show")
                            }
                        },
                    ]
                });
            }
        });

        $("#payment-table").on("click", ".payment-delete-btn", function() {
            let data = paymentTable.row($(this).parents('tr')).data();

            Swal.fire({
                title: "Are you sure you want to delete payment?",
                text: "Or you can click cancel to abort!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Submit"

            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        text: "Deleting...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    $.ajax({
                        url: `${appUrl}/api/payment/delete`,
                        type: "POST",
                        data: {
                            'school_code': school_code,
                            'student_no': data.studentNo,
                            'semester': data.semester,
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
                            text: "Payment deleted successfully",
                            type: "success"
                        });
                        paymentTable.ajax.reload(false, null);

                    }).fail(() => {
                        alert('failed');
                    })
                }
            })
        });

        var debtSems = document.getElementById("filter-debt-semester");
        var debtBranch = document.getElementById("filter-debt-branch");
        var debtSession = document.getElementById("filter-debt-sess");
        var debtProg = document.getElementById("filter-debt-prog");
        var debtBatch = document.getElementById("filter-debt-batch");
        var debtErrorMsgs = null;
        var debtorsTable;

        //debtors table
        debtorsTable = $('#all-debtors-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/payment/fetchDebtors/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            columns: [{
                    data: "student"
                },
                {
                    data: "amount"
                },
                {
                    data: "balance"
                },


            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - payment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - payment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - payment List`,
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

        $(debtSems).on('select2:select', function(e) {
            // e.preventDefault();
            if (debtBranch.value === '' || debtSems.value === '' || debtSession.value === '' || debtProg.value ===
                '' || debtBatch.value === '') {
                debtErrorMsgs = document.getElementById("debtErrorMessages").innerHTML =
                    "Please all fields are required!";
                return false;
            }
            if (debtSems.value != '' || debtBranch.value != '' || debtSession.value != '' || debtProg.value !=
                '' || debtBatch.value != '') {
                setTimeout(function() {
                    document.getElementById("debtErrorMessages").innerHTML = '';
                }, 1000);

                debtorsTable = $('#all-debtors-table').DataTable({
                    destroy: true
                }).destroy();

                debtorsTable = $('#all-debtors-table').DataTable({
                    "lengthChange": false,
                    dom: 'Bfrtip',
                    ajax: {
                        url: `${appUrl}/api/payment/fetch_debtors`,
                        type: "POST",
                        data: {
                            'semester': debtSems.value,
                            'school_code': school_code,
                            'branch': debtBranch.value,
                            'session': debtSession.value,
                            'prog': debtProg.value,
                            'batch': debtBatch.value,
                        },
                    },
                    processing: true,
                    responsive: true,
                    columns: [{
                            data: "student"
                        },
                        {
                            data: "amount"
                        },
                        {
                            data: "balance"
                        },
                    ],
                    buttons: [{
                            extend: 'print',
                            title: `${loggedInUserSchoolName} - payment List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'copy',
                            title: `${loggedInUserSchoolName} - payment List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'excel',
                            title: `${loggedInUserSchoolName} - payment List`,
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
            }
        });

        //payment  history
        var historyStu = document.getElementById("filter-his-student");
        var historySems = document.getElementById("filter-his-semester");
        var historyBranch = document.getElementById("filter-his-branch");
        var historyErrorMsgs = null;
        var paymentHistoryTable;

        paymentHistoryTable = $('#payment-history-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/payment/fetchPaymentHistory/${school_code}`,
                type: "GET",
                // url: `${appUrl}/api/payment/fetch_payment_history`,
                //         type: "POST",
            },
            processing: true,
            responsive: true,
            columns: [{
                    data: "date"
                },
                {
                    data: "semester"
                },
                {
                    data: "student"
                },
                {
                    data: "cheque"
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
                    title: `${loggedInUserSchoolName} - payment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - payment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - payment List`,
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

                // {
                //     text: '<i class="fa fa-money" aria-hidden="true"></i> Make Payment',
                //     attr: {
                //         class: "ml-2 btn-primary btn btn-sm rounded"
                //     },
                //     // action: function(e, dt, node, config) {
                //     //     $("#add-payment-modal").modal("show")
                //     // }
                // },
            ]
        });

        $(historySems).on('select2:select', function(e) {
            // e.preventDefault();
            if (historyBranch.value === '' || historySems.value === '' || historyStu.value === '') {
                historyErrorMsgs = document.getElementById("hisErrorMessages").innerHTML =
                    "Please all fields are required!";
                return false;
            }
            if (historySems.value != '' || historyBranch.value != '' || historyStu.value != '') {
                setTimeout(function() {
                    document.getElementById("hisErrorMessages").innerHTML = '';
                }, 1000);

                paymentHistoryTable = $('#payment-history-table').DataTable({
                    destroy: true
                }).destroy();

                paymentHistoryTable = $('#payment-history-table').DataTable({
                    "lengthChange": false,
                    dom: 'Bfrtip',
                    ajax: {
                        url: `${appUrl}/api/payment/fetch_payment_history`,
                        type: "POST",
                        data: {
                            'student': historyStu.value,
                            'semester': historySems.value,
                            'school_code': school_code,
                            'branch': historyBranch.value
                        },
                    },
                    processing: true,
                    columns: [{
                            data: "date"
                        },
                        {
                            data: "semester"
                        },
                        {
                            data: "student"
                        },
                        {
                            data: "cheque"
                        },
                        {
                            data: "bill"
                        },
                        {
                            data: "amount"
                        },
                        // {
                        //     data: "balance"
                        // },
                        {
                            data: "action"
                        }
                    ],
                    buttons: [{
                            extend: 'print',
                            title: `${loggedInUserSchoolName} - payment List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            title: `${loggedInUserSchoolName} - payment List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            title: `${loggedInUserSchoolName} - payment List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'pdf',
                            title: `${loggedInUserSchoolName} - payment List`,
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

                document.getElementById("show-total").innerText = null;

                let formdata = new FormData();
                formdata.append("student_no", historyStu.value);
                formdata.append("school_code", school_code);

                fetch(`${appUrl}/api/payment/fetch_student_arrears`, {
                    method: "POST",
                    body: formdata,
                }).then(function(res) {
                    return res.json()
                }).then(function(data) {

                    if (!data.ok) {
                        document.getElementById("show-total").innerText = data.data;
                        return;
                    }
                    document.getElementById("show-total").innerText = data.data;

                });
            }
        });

        //Payment history delete button
        $("#payment-history-table").on("click", ".payment-history-delete-btn", function() {
            let data = paymentHistoryTable.row($(this).parents('tr')).data();

            Swal.fire({
                title: "Are you sure you want to delete payment?",
                text: "Or you can click cancel to abort!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Submit"

            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        text: "Deleting...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    $.ajax({
                        url: `${appUrl}/api/payment/delete_payment_history`,
                        type: "POST",
                        data: {
                            'school_code': school_code,
                            'student_no': data.studentNo,
                            'receipt': data.receipt,
                        },
                    }).done(function(response) {
                        if (!response.ok) {
                            Swal.fire({
                                text: data.msg,
                                type: "error"
                            });
                            return;
                        }
                        Swal.fire({
                            text: "Payment deleted successfully",
                            type: "success"
                        });

                        let formdata = new FormData();
                        formdata.append("student_no", data.studentNo);
                        formdata.append("school_code", school_code);

                        fetch(`${appUrl}/api/payment/fetch_student_arrears`, {
                            method: "POST",
                            body: formdata,
                        }).then(function(res) {
                            return res.json()
                        }).then(function(data) {

                            if (!data.ok) {
                                document.getElementById("show-total").innerText = data.data;
                                return;
                            }
                            document.getElementById("show-total").innerText = data.data;

                        });
                        paymentHistoryTable.ajax.reload(false, null);

                    }).fail(() => {
                        alert('failed');
                    })
                }
            })
        });

        //Pament info button
        $("#payment-history-table").on("click", ".payment-history-info-btn", function() {
            let data = paymentHistoryTable.row($(this).parents('tr')).data();
            $("#payment-details-modal").modal("show");

            $("#payment-details-studentno").html(data.studentNo);
            $("#payment-details-name").html(data.student);
            $("#payment-details-session").html(data.session);
            $("#payment-details-batch").html(data.batch);
            $("#payment-details-prog").html(data.prog);
            $("#payment-details-phone").html(data.phone);
        });

        //Daily payment
        var fullDailyBranch = document.getElementById("filter-daily-branch");
        var dailyPaymentTable;

        dailyPaymentTable = $('#daily-payment-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/payment/fetch_daily_payment/${school_code}`,
                type: "GET",
            },
            processing: true,
            // responsive: true,
            columns: [{
                    data: "semester"
                },

                {
                    data: "student"
                },

                {
                    data: "bill"
                },
                {
                    data: "amtpaid"
                },
                {
                    data: "arrears"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - payment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - payment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - payment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - payment List`,
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

        $(fullDailyBranch).on('select2:select', function(e) {

            dailyPaymentTable.ajax.url(
                    `${appUrl}/api/payment/filter_fetch_daily_payment/${school_code}/${fullDailyBranch.value}`)
                .load();
        });

        //Full payment
        var fullPaySems = document.getElementById("filter-fullpay-semester");
        var fullPayBranch = document.getElementById("filter-fullpay-branch");
        var fullSession = document.getElementById("filter-fullpay-sess");
        var fullProg = document.getElementById("filter-fullpay-prog");
        var fullBatch = document.getElementById("filter-fullpay-batch");
        var fullErrorMsgs = null;
        var fullPaymentTable;

        fullPaymentTable = $('#full-payment-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/payment/fetchFullPayment/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,

            columns: [

                {
                    data: "studentName"
                },

                {
                    data: "amountPaid"
                },
                {
                    data: "balance"
                }

            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - payment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - payment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - payment List`,
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

        $(fullPaySems).on('select2:select', function(e) {
            // e.preventDefault();
            if (fullPayBranch.value === '' || fullPaySems.value === '' || fullSession.value === '' || fullProg
                .value ===
                '' || fullBatch.value === '') {
                fullErrorMsgs = document.getElementById("fullErrorMessages").innerHTML =
                    "Please all fields are required!";
                return false;
            }
            if (fullPaySems.value != '' || fullPayBranch.value != '' || fullSession.value != '' || fullProg.value !=
                '' || fullBatch.value != '') {
                setTimeout(function() {
                    document.getElementById("fullErrorMessages").innerHTML = '';
                }, 1000);

                fullPaymentTable = $('#full-payment-table').DataTable({
                    destroy: true
                }).destroy();

                fullPaymentTable = $('#full-payment-table').DataTable({
                    "lengthChange": false,
                    dom: 'Bfrtip',
                    ajax: {
                        url: `${appUrl}/api/payment/fetch_full_payment`,
                        type: "POST",
                        data: {
                            'semester': fullPaySems.value,
                            'school_code': school_code,
                            'branch': fullPayBranch.value,
                            'session': fullSession.value,
                            'prog': fullProg.value,
                            'batch': fullBatch.value,
                        },
                    },
                    processing: true,
                    responsive: true,
                    columns: [{
                            data: "student"
                        },
                        {
                            data: "amount"
                        },
                        {
                            data: "balance"
                        }
                    ],
                    buttons: [{
                            extend: 'print',
                            title: `${loggedInUserSchoolName} - payment List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'copy',
                            title: `${loggedInUserSchoolName} - payment List`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'excel',
                            title: `${loggedInUserSchoolName} - payment List`,
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
            }
        });


        //payment  ledger
        var ledgerStu = document.getElementById("filter-ledger-student");
        var ledgerSems = document.getElementById("filter-ledger-semester");
        var ledgerBranch = document.getElementById("filter-ledger-branch");
        var from = document.getElementById("filter-from");
        var to = document.getElementById("filter-to");
        var ledgerErrorMsgs = null;
        var ledgerTable;

        ledgerTable = $('#ledger-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/payment/fetch_payment_ledger`,
                type: "POST",
             },
            processing: true,
            responsive: true,
            columns: [{
                    data: "date"
                },
                {
                    data: "semester"
                },
                {
                    data: "cheque"
                },
                {
                    data: "student"
                },
                {
                    data: "debit"
                },
                {
                    data: "credit"
                },
                {
                    data: "balance"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Transaction History`,
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

        $(ledgerSems).on('select2:select', function(e) {
            // e.preventDefault();
            if (ledgerStu.value === '' || ledgerSems.value === '' || from.value === '' || to.value === '' || ledgerBranch.value === '') {
                ledgerErrorMsgs = document.getElementById("ledgerErrorMessages").innerHTML =
                    "Please all fields are required!";
                return false;
            }
            if (ledgerStu.value != '' || ledgerSems.value != '' || from.value != '' || to.value != '' || ledgerBranch.value != '') {
                setTimeout(function() {
                    document.getElementById("ledgerErrorMessages").innerHTML = '';
                }, 1000);

                ledgerTable = $('#ledger-table').DataTable({
                    destroy: true
                }).destroy();

                ledgerTable = $('#ledger-table').DataTable({
                    "lengthChange": false,
                    dom: 'Bfrtip',
                    ajax: {
                        url: `${appUrl}/api/payment/fetch_payment_ledger`,
                        type: "POST",
                        data: {
                            'branch': ledgerBranch.value,
                            'prog': ledgerStu.value,
                            'semester': ledgerSems.value,
                            'school_code': school_code,
                            'from': from.value,
                            'to': to.value
                        },
                    },
                    processing: true,
                    columns: [{
                            data: "date"
                        },
                        {
                            data: "semester"
                        },
                        {
                            data: "cheque"
                        },
                        {
                            data: "student"
                        },
                        {
                            data: "debit"
                        },
                        {
                            data: "credit"
                        },
                        {
                            data: "balance"
                        }

                    ],
                    buttons: [{
                            extend: 'print',
                            title: `${loggedInUserSchoolName} - Transaction History`,
                            attr: {
                                class: "btn btn-sm btn-info rounded-right"
                            },
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,6]
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
    </script>
@endsection
