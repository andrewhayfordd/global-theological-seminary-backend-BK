@extends('layout.app')
@section('page-name', 'Expenditure')
@section('page-content')

    <style>
        Chrome,
        Safari,
        Edge,
        Opera input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h3 class="page-title">@yield('page-name')
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Expenditure</li>
                </ul>
            </h3>
            <div class="">
                <a href="#" data-toggle="modal" data-target="#add-expense-modal"
                    class="btn btn-sm btn-primary shadow-sm mx-0"><i class=""></i>Add Expense</a>
                <a href="#" data-toggle="modal" data-target="#add-category-modal"
                    class="btn btn-sm btn-info shadow-sm mx-0"><i class=""></i>Add Expenditure Category</a>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="expense-tab" data-toggle="tab" href="#expenses-container" role="tab"
                aria-controls="profile" aria-selected="false">Expenses</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="category-tab" data-toggle="tab" href="#categories-container" role="tab"
                aria-controls="profile" aria-selected="false">Expenditure Categories</a>
        </li>
    </ul>
    <div class="tab-content" id="bills">
        <div class="tab-pane fade show active" id="expenses-container" role="tabpanel" aria-labelledby="student-bill-tab">
            <div class="mt-3">
                <form id="filter-form">
                    @csrf
                    <div class="col-6 col-md-12 col-sm">
                        <div class="row">
                            <div class="text-end col-12 mb-2">
                                <button class="btn btn-md btn-outline-primary" type="submit" form="filter-form"><i
                                    class="fa fa-filter"></i></button>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Batch</label>
                                    <select name="acyear" class="form-control m-b d-inline select2" id="filter-batch"
                                        >
                                        <option value="">--Select--</option>
                                        @forelse($batches as $item)
                                        <option value="{{ $item->code }}">{{ $item->label }}</option>
                                    @empty
                                        <option value="">No data found</option>
                                    @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Branch </label>
                                    <select name="branch" class="form-control m-b d-inline select2"
                                        id="filter-branch">
                                        <option value="">--Select--</option>
                                        @forelse ($branches as $item)
                                        <option value="{{ $item->code }}">{{ $item->label }}</option>
                                    @empty
                                        <option value="">No data found</option>
                                    @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Semester</label>
                                    <select name="acterm" class="form-control select2" id="filter-semester">
                                        <option value="">--Select--</option>
                                        @forelse ($semesters as $item)
                                        <option value="{{ $item->code }}">{{ $item->label }}</option>
                                    @empty
                                        <option value="">No data found</option>
                                    @endforelse
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-group">
                                    <label>Transaction Type</label>
                                    <select name="trans_type" class="form-control select2" id="filter-type">
                                        <option value="" selected>--Select--</option>
                                        <option value="bank">Bank</option>
                                        <option value="cash">Cash</option>
                                        <option value="cheque">Cheque</option>     
                                      </select>
                                </div>
                            </div>

                            
        
                        </div>
                        <p><span id="errMessage" style="color:red;"></span></p>
                    </div>
                </form>
            </div>
            <div class="card shadow mb-4">

                <div class="card-body">
                    <div class="table-">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="expenses-table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Trans. Type</th>
                                    <th>Branch</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="categories-container" role="tabpanel" aria-labelledby="tudent-bill-setup">
            {{-- <div class="mt-3">
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
                                       
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Branch <span class="text-danger">*</span></label>
                                    <select name="acyear" class="form-control m-b d-inline select2"
                                        id="filter-prog-bill-branch" required>
                                        <option value="">--Select--</option>
                                       
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Semester <span class="text-danger">*</span></label>
                                    <select name="acterm" class="form-control m-b d-inline select2"
                                        id="filter-prog-bill-semester" required>
                                        <option value="">--Select--</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Programme <span class="text-danger">*</span></label>
                                    <select name="student" class="form-control select2" id="filter-prog-bill-student"
                                        required>
                                        <option value="">--Select--</option>
                                       
                                    </select>
                                </div>
                            </div>
                        </div>
                        <p><span id="errMessages" style="color:red;"></span></p>
                    </div>
                </form>
            </div> --}}
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="categories-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modules.expenditure.modals.add_expense')
    @include('modules.expenditure.modals.edit_expense')

    @include('modules.expenditure.modals.add_category')
    @include('modules.expenditure.modals.edit_category')
    @push('scripts')
        <script>
            //General 
            //Event on transaction type
            $(".transaction-type").on("change", function(e) {
                let value = e.target.value;
                let htmlString = '';
                switch (value) {
                    case "bank":
                        htmlString = ` <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <input type="text" class="form-control" name="bank_name" id="bank_name"  required > 
                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Branch</label>
                                    <input type="text" class="form-control" name="bank_branch" id="bank_branch"  required > 
                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <input type="text" class="form-control" name="bank_account_number" id="bank_account_number"  required > 
                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payer's Name</label>
                                    <input type="text" class="form-control" name="payer_name" id="payer_name"  required > 
                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Holder</label>
                                    <input type="text" class="form-control" name="account_holder" id="account_holder"  required > 
                                    
                                </div>
                            </div>

                        </div>`
                        $(".transaction-container").html(htmlString);
                        break;
                    case "cheque":
                        htmlString = `<div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cheque Bank </label>
                                    <input type="text" class="form-control" name="cheque_bank" id="cheque_bank" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label id="you-this">Cheque Number</label>
                                    <input type="text" class="form-control" name="cheque_no" id="cheque_no" required>
                                </div>
                            </div>
                        </div>`;
                        $(".transaction-container").html(htmlString);
                        break;

                    default:
                        $(".transaction-container").html(htmlString);
                        break;
                }
            });
            //end General

            // Expense section
            // creating an instance for expenses-table
            var expenseTable = $('#expenses-table').DataTable({
                "lengthChange": false,
                dom: 'Bfrtip',
                ajax: {
                    url: `${appUrl}/api/expenditure/fetch/${school_code}`,
                    type: "GET",
                },
                processing: true,
                responsive: true,
                columns: [{
                        data: "item"
                    },
                    {
                        data: "amount"
                    },
                    {
                        data: "trans_type"
                    },

                    {
                        data: "branch_desc"
                    },
                    {
                        data: "date"
                    },
                    {

                        data: 'action'
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

            //add an expense
            let expenseForm = document.getElementById('add-expense-form');
            expenseForm.addEventListener('submit', (e) => {

                e.preventDefault();
                let formData = new FormData(expenseForm);
                formData.append("createuser", createuser);
                formData.append("school_code", school_code);
                Swal.fire({
                    text: "Please wait...",
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                })

                //making request to store the details
                fetch(`${appUrl}/api/expenditure/add`, {
                        method: "POST",
                        body: formData,
                    })
                    .then(res => {
                        return res.json()
                    }).then((data) => {

                        //if it couldnt store the data
                        if (!data.ok) {
                            Swal.fire({
                                html: data.msg,
                                type: 'error',
                                timer: 3000,
                            });
                            return;
                        }
                        // ends here 

                        Swal.fire({
                            html: data.msg,
                            type: 'success',
                            timer: 3000,
                        });
                        $('#add-expense-modal').modal("hide");

                        expenseTable.ajax.reload(false, null);
                        expenseForm.reset();
                        $('select').val("").trigger("change");

                    }).catch(err => {

                        Swal.fire({
                            text: "Oops! Something went wrong!",
                            type: 'error'
                        });
                        console.error(err);

                    })

            })

            //Delete an expense
            $('#expenses-table').on("click", ".exp-delete-btn", function(e) {
                Swal.fire({
                    text: "Do you want to delete this expense?",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    type: "warning",
                }).then((result) => {
                    //Cancel the delete process
                    if (!result.value) {
                        return;
                    }
                    //end of cancel process

                    //Get data from the table 
                    let data = expenseTable.row($(this).parents('tr')).data();
                    Swal.fire({
                        text: "Please wait...",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                    })
                    fetch(`${appUrl}/api/expenditure/delete/${data.id}/${school_code}`, {
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            method: "POST",
                        })
                        .then(res => {
                            return res.json()
                        }).then((data) => {

                            //if it couldnt store the data
                            if (!data.ok) {
                                Swal.fire({
                                    html: data.msg,
                                    type: 'error',
                                    timer: 3000,
                                });
                                return;
                            }
                            // ends here

                            expenseTable.ajax.reload(false, null);
                            Swal.fire({
                                html: data.msg,
                                type: 'success',
                                timer: 3000,
                            });
                            $('#add-expense-modal').modal("hide");

                            expenseForm.reset();
                            $('select').val("").trigger("change");

                        }).catch(err => {

                            Swal.fire({
                                text: "Oops! Something went wrong!",
                                type: 'error'
                            });
                            console.error(err);

                        })

                })
            });
            //end delete expenses

            //update expense
            $('#expenses-table').on("click", ".exp-edit-btn", function() {
                let data = expenseTable.row($(this).parents('tr')).data();
                $('#edit-expense-modal').modal("show");
                $("#edit-transaction-type").val(data.trans_type).trigger("change");
                $("#edit-amount").val(data.raw);
                $("#edit-acyear").val(data.rawYear).trigger("change");
                $("#edit-branch").val(data.branch).trigger("change");
                $("#edit-acterm").val(data.acterm).trigger("change");
                $("#edit-exp_type").val(data.exp_type).trigger("change");
                $("#edit-branch").val(data.branch).trigger("change");
                $("#edit-note").val(data.notes)
                $("#cheque_bank").val(data.cheque_bank)
                $("#exp_id").val(data.id)

                switch (data.trans_type) {
                    case "bank":
                        htmlString = ` <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <input type="text" class="form-control" name="bank_name" value="${data.bank}"   required > 
                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Branch</label>
                                    <input type="text" class="form-control" name="bank_branch" id="bank_branch" value="${data.bank_branch}"  required > 
                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <input type="text" class="form-control" name="bank_account_number" id="bank_account_number" value="${data.account_no}" required > 
                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payer's Name</label>
                                    <input type="text" class="form-control" name="payer_name" id="payer_name" value="${data.payer}"  required > 
                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Holder</label>
                                    <input type="text" class="form-control" name="account_holder" id="account_holder" value="${data.holder}"  required > 
                                    
                                </div>
                            </div>

                        </div>`
                        $(".transaction-container").html(htmlString);
                        break;
                    case "cheque":
                        htmlString = `<div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cheque Bank </label>
                                    <input type="text" class="form-control" name="cheque_bank" id="cheque_bank" value="${data.cheque_bank}"  required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label id="you-this">Cheque Number</label>
                                    <input type="text" class="form-control" name="cheque_no" id="cheque_no" value="${data.cheque_no}" required>
                                </div>
                            </div>
                        </div>`;
                        $(".transaction-container").html(htmlString);
                        break;

                    default:
                        $(".transaction-container").html(htmlString);
                        break;
                }



            });

            let expenseEditForm = document.getElementById("edit-expense-form")
            $("#edit-expense-form").on("submit", function(e) {
                e.preventDefault();
                let dataForm = new FormData(expenseEditForm);
                dataForm.append('createuser', createuser);
                dataForm.append('school_code', school_code)

                Swal.fire({
                    text: "Do you want save changes?",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    type: "warning",
                }).then((result) => {
                    //Cancel the update process
                    if (!result.value) {
                        return;
                    }

                    Swal.fire({
                        text: "Please wait...",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                    })
                    fetch(`${appUrl}/api/expenditure/update/`, {
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            method: "POST",
                            body: dataForm,
                        })
                        .then(res => {
                            return res.json()
                        }).then((data) => {

                            //if it couldnt store the data
                            if (!data.ok) {
                                Swal.fire({
                                    html: data.msg,
                                    type: 'error',
                                    timer: 3000,
                                });
                                return;
                            }
                            // ends here
                            $('#edit-expense-modal').modal("hide");
                            expenseTable.ajax.reload(false, null);
                            Swal.fire({
                                html: data.msg,
                                type: 'success',
                                timer: 3000,
                            });

                            expenseEditForm.reset();
                            $('select').val("").trigger("change");

                        }).catch(err => {

                            Swal.fire({
                                text: "Oops! Something went wrong!",
                                type: 'error'
                            });
                            console.error(err);

                        })

                })

            });


            //Performing filtering on expenses

            $("#filter-form").on("submit", function(e){
                e.preventDefault();
                let filterForm = document.getElementById("filter-form");
                let filterData = new FormData(filterForm)
                let searchQuery = new URLSearchParams();
                searchQuery.append("acyear",filterData.get('acyear'))
                searchQuery.append("branch",filterData.get('branch'))
                searchQuery.append("acterm",filterData.get('acterm'))
                searchQuery.append("trans_type",filterData.get('trans_type'))

                //start with the fecthing request
                expenseTable.ajax.url(`${appUrl}/api/expenditure/fetch/${school_code}?${searchQuery.toString()}`).load()
                

            })


            // end Expense section


            // Categories  Starts here

            //creating an instance for the category table
            let categoryTable = $('#categories-table').DataTable({
                // "lengthChange": false,
                dom: 'Bfrtip',
                ajax: {
                    url: `${appUrl}/api/expenditure/fetch_category/${school_code}`,
                    type: "GET",
                },
                processing: true,
                responsive: true,
                columns: [{
                        data: "code"
                    },
                    {
                        data: "name"
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

            //creating a category
            let categoryForm = document.getElementById('add-category-form');
            $('#add-category-form').on("submit", function(e) {
                e.preventDefault();
                let dataForm = new FormData(categoryForm);
                Swal.fire({
                    text: "Please wait...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });

                dataForm.append('createuser', createuser);
                dataForm.append('school_code', school_code);

                fetch(`${appUrl}/api/expenditure/add_category`, {
                        body: dataForm,
                        method: "POST"
                    })
                    .then((result) => {
                        return result.json();
                    })
                    .then((data) => {
                        //if there is an error
                        if (!data.ok) {
                            Swal.fire({
                                html: data.msg,
                                type: 'error',
                                timer: 3000,
                            });
                            return;
                        }
                        // ends here 

                        Swal.fire({
                            html: data.msg,
                            type: 'success',
                            timer: 3000,
                        });
                        categoryTable.ajax.reload(false, null);
                        // categoryForm.modal('hide');
                        categoryForm.reset();
                        $('#add-category-modal').modal("hide")



                    }).catch((err) => {
                        Swal.fire({
                            text: "Oops! Something went wrong!",
                            type: 'error'
                        });
                        console.error(err);
                    });


                //end request

            });

            //end creating the category

            //update the category
            //passing data into the form
            $('#categories-table').on('click', '.cat-update-btn', function() {
                let data = categoryTable.row($(this).parents('tr')).data();

                //show modal
                $('#edit-category-modal').modal('show')
                //end modal show
                $('#edit-category-name').val(data.name);
                $('#edit-category-code').val(data.code);

            });

            let editcategoryForm = document.getElementById('edit-category-form');
            //submitting on the update form
            $('#edit-category-form').on('submit', function(e) {
                e.preventDefault();

                let dataForm = new FormData(editcategoryForm);
                dataForm.append('createuser', createuser);
                dataForm.append('school_code', school_code)

                Swal.fire({
                    text: "Please wait...",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                });

                //update request starts here
                fetch(`${appUrl}/api/expenditure/update/category`, {
                        method: "POST",
                        body: dataForm,
                    })
                    .then(res => {
                        return res.json();
                    })
                    .then((data) => {

                        //if there is an error
                        if (!data.ok) {
                            Swal.fire({
                                html: data.msg,
                                type: 'error',
                                timer: 3000,
                            });
                            return;
                        }
                        // ends here 

                        Swal.fire({
                            html: data.msg,
                            type: 'success',
                            timer: 3000,
                        });
                        categoryTable.ajax.reload(false, null);
                        // categoryForm.modal('hide');
                        editcategoryForm.reset();
                        $('#edit-category-modal').modal("hide");

                    })
                    .catch((err) => {
                        Swal.fire({
                            text: "Oops! Something went wrong!",
                            type: 'error'
                        });
                        console.error(err);
                    });
                // update request ends here

            });

            //end the edit category

            //delete the category
            $('#categories-table').on('click', '.cat-delete-btn', function() {
                let data = categoryTable.row($(this).parents('tr')).data();
                Swal.fire({
                    text: "Are you sure want to delete category",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Delete",
                    confirmButtonColor: "#DD6B55",
                }).then((result) => {
                    //if the delete button is pressed
                    if (result.value) {
                        Swal.fire({
                            text: "Please wait...",
                            showConfirmButton: false,
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                        });
                        //Make request to delete the category
                        fetch(`${appUrl}/api/expenditure/category/delete/${data.code}`, {
                            method: "Post",
                        }).then((result) => {
                            return result.json();
                        }).then((data) => {
                            //if there is an error
                            if (!data.ok) {
                                Swal.fire({
                                    html: data.msg,
                                    type: 'error',
                                    timer: 3000,
                                });
                                return;
                            }
                            // ends here 
                            categoryTable.ajax.reload(false, null);
                            Swal.fire({
                                html: data.msg,
                                type: 'success',
                                timer: 3000,
                            });

                        }).catch((err) => {
                            Swal.fire({
                                text: "Oops! Something went wrong!",
                                type: 'error'
                            });
                            console.error(err);
                        });
                        //delete request ends here
                    }
                })

            });
            //end delete
        </script>
    @endpush


@endsection
