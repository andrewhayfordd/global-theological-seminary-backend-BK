@extends('layout.app')
@section('page-name', 'Student')
@section('page-content')

    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Student</li>
                    </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="allPaymentModal-tab" data-toggle="tab" href="#allPaymentModal" role="tab"
                aria-controls="allPayment" aria-selected="false">All Students</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " id="PaymentModal-tab" data-toggle="tab" href="#PaymentModal" role="tab"
                aria-controls="allPayment" aria-selected="false">Inactive Students</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " id="fPaymentModal-tab" data-toggle="tab" href="#fPaymentModal" role="tab"
                aria-controls="allPayment" aria-selected="false">Assessment</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " id="transcript-tab" data-toggle="tab" href="#transcrpit" role="tab"
                aria-controls="allPayment" aria-selected="false">Transcript</a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link " id="dailyPaymentModal-tab" data-toggle="tab" href="#dailyPaymentModal" role="tab"
                aria-controls="dailyPayment" aria-selected="false">Daily Fee Payment</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="gradeBill-tab" data-toggle="tab" href="#gradeBill" role="tab"
                aria-controls="gradeBill" aria-selected="false">Payment History</a>
        </li> --}}
    </ul>
    <div class="tab-content" id="myTabContent">
        {{-- All students --}}
        <div class="tab-pane fade show active mt-3" id="allPaymentModal" role="tabpanel"
            aria-labelledby="allPaymentModal-tab">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="card-header py-3">

                        {{-- Student breakdown start --}}
                        <ul class="list-group list-group-horizontal-sm" id="info-panel">
                            <li class="list-group-item">
                                <div class="m-0 p-0 font-weight-bold text-primary">
                                    Total Students:
                                    <span class="text-secondary" id="info-panel-total-students">loading...</span>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="m-0 p-0 font-weight-bold text-primary">
                                    Total Females:
                                    <span class="text-secondary" id="info-panel-total-females">loading...</span>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="m-0 p-0 font-weight-bold text-primary">
                                    Total Males:
                                    <span class="text-secondary" id="info-panel-total-males">loading...</span>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="m-0 p-0 font-weight-bold text-primary">
                                    Total Inactive Students:
                                    <span class="text-secondary" id="info-panel-inactive-students">loading...</span>
                                </div>
                            </li>
                        </ul>
                        {{-- Student breakdown ends --}}

                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="mt-2 mb-4">
                            <form id="filter-student">
                                <div class="row">
                                    <div class="col mt-2 ml-4">
                                        <label for="">Batch</label>
                                        <select class="form-select select2" aria-label="Default select example"
                                            id="select-batch">
                                            <option value="">--Select--</option>
                                            @foreach ($batch as $item)
                                                <option value="{{ $item->batch_code }}">{{ $item->batch_desc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col mt-2 ml-4">
                                        <label for="">Programme</label>
                                        <select class="form-select select2" aria-label="Default select example"
                                            id="select-program">
                                            <option value="">--Select--</option>
                                            @foreach ($prog as $item)
                                                <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col mt-2 ml-4">
                                        <label for="">Session</label>
                                        <select class="form-select select2" aria-label="Default select example"
                                            id="select-session">
                                            <option value="">--Select--</option>
                                            @foreach ($sess as $item)
                                                <option value="{{ $item->session_code }}">{{ $item->session_desc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col mt-2 ml-4">
                                        <label for="">Branch</label>
                                        <select class="form-select select2" aria-label="Default select example"
                                            id="select-branch">
                                            <option value="">--Select--</option>
                                            @foreach ($branch as $item)
                                                <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col" style="padding-top: 39px;">
                                        <button class="btn btn-md btn-outline-primary" type="submit"
                                            form="filter-student"><i class="fa fa-filter"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                                width='100%' id="student-table">
                                <thead>
                                    <tr>
                                        <th class="all">Student ID</th>
                                        <th class="all">Name</th>
                                        <th class="all">Gender</th>
                                        <th class="all">Programme</th>
                                        <th class="all">Action</th>

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

        {{-- Inactive --}}
        <div class="tab-pane fade show mt-3" id="PaymentModal" role="tabpanel" aria-labelledby="PaymentModal-tab">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                                width='100%' id="inactive-table">
                                <thead>
                                    <tr>
                                        <th class="all">Student ID</th>
                                        <th class="all">Name</th>
                                        <th class="all">Gender</th>
                                        <th class="all">Programme</th>
                                        <th class="all">Action</th>

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

        <div class="tab-pane fade show mt-3" id="fPaymentModal" role="tabpanel" aria-labelledby="fPaymentModal-tab">

            <form id="filter-assessment-form">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Branch</label>
                            <select name="branch" class="form-control m-b d-inline select2" id="student-filter-branch">
                                <option value="">--Select--</option>
                                @foreach ($branch as $item)
                                    <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Semester </label>
                            <select name="sem" class="form-control m-b d-inline select2" id="student-filter-sem">
                                <option value="">--Select--</option>
                                @foreach ($semester as $item)
                                    <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Batch</label>
                            <select name="batch" class="form-control m-b d-inline select2" id="student-filter-batch">
                                <option value="">--Select--</option>
                                @foreach ($batch as $item)
                                    <option value="{{ $item->batch_code }}">{{ $item->batch_desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <label for="">Programme</label>
                        <select name="prog" id="student-filter-prog" class="form-control select2">
                            <option value="">--Select--</option>
                            @foreach ($prog as $item)
                                <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col d-flex align-items-center">
                        <div>
                            <button class="btn btn-md btn-outline-primary" type="submit" form="filter-assessment-form"><i
                                class="fa fa-filter"></i></button>
                        </div>
                    </div>
                </div>
                <p><span id="errMessage" style="color:red;"></span></p>
            </form>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table width="100%"
                            class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            id="assessment-table">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Test Score(40)</th>
                                    <th>Exam Score(60)</th>
                                    <th>Total Score</th>
                                    <th>More Info</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data is fetched using ajax --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade show mt-3" id="transcrpit" role="tabpanel" aria-labelledby="transcript-tab">

            <form id="filter-assessment-form">
                <div class="row">
                    {{-- <div class="col">
                        <div class="form-group">
                            <label for="">Transcripts</label>
                            <select name="branch" class="form-control m-b d-inline select2" id="student-filter-branch">
                                <option value="">--Select--</option>
                                @foreach ($branch as $item)
                                    <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col d-flex align-items-center">
                        <div>
                            <button class="btn btn-md btn-outline-primary" type="submit" form="filter-assessment-form"><i
                                class="fa fa-filter"></i></button>
                        </div>
                    </div> --}}
                </div>
                <p><span id="errMessage" style="color:red;"></span></p>
            </form>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table width="100%"
                            class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            id="transcript-table">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data is fetched using ajax --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade show mt-3" id="dailyPaymentModal" role="tabpanel"
            aria-labelledby="dailyPaymentModal-tab">
        </div>

        <div class="tab-pane fade show mt-3" id="gradeBill" role="tabpanel" aria-labelledby="gradeBill-tab">
        </div>
    </div>


    @include('modules.students.modals.add_student')
    @include('modules.students.modals.edit_student')
    @include('modules.students.modals.info_student')
    @include('modules.students.modals.add_student_assess')
    @include('modules.students.modals.edit_student_assess')

    <script>
        //getting the student course
        let arrCourses = @json($course);
        let studentProg = document.getElementById('student-prog') ;
        $('#student-prog').on("change", function (e) { 
            let id = e.target.value
            if (id) {
                let prog = document.getElementById(id).dataset.prog
            let filterArrCourse = arrCourses.filter(function(course){
                return course.prog == prog;
            })
            let newArrCourse = filterArrCourse.map(function(course){
                return `<option value="${course.course_code}"> ${course.course_title }</option>`;
            })
            let html = `<option value="">--Select--</option>` + newArrCourse.join(' ')
            $("#courses").html(html);
            }
            else{
                $("#courses").html(`<option value="">--Select--</option>`);
            }
         })

        //  end
      var trasnTable = $('#transcript-table').DataTable({
            dom: 'frtip',
            ajax: {
                url: `${appUrl}/api/student/${school_code}`,
                type: "GET",


            },
            processing: true,
            responsive: true,
            // pageLength: 15,
            columns: [{
                    data: "student_no"
                },
                {
                    data: "name"
                },
                {
                    data: "student_no",
                    'render': function(data, type, full, meta) {
                        var html = '';
                            html += `<a href="{{ url('student/transcript/download/${data}') }}" target="_blank"><button type='button'
                                                    rel='tooltip' class='btn m-2 btn-success btn-sm edit-btn'>
                                                       <i class='fas fa-print'></i>
                                                    </button></a>`;
                        
                        return html;
                    },
                    className: "text-center",
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Student List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, ]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Student List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Student List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Student List`,
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

                
            ]
        });

        var studentTable = $('#student-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/student/${school_code}`,
                type: "GET",


            },
            processing: true,
            responsive: true,
            // pageLength: 15,
            columns: [{
                    data: "student_no"
                },
                {
                    data: "name"
                },
                {
                    data: "gender"
                },
                {
                    data: "prog"
                },
                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Student List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Student List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Student List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Student List`,
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
                    text: "Admission Form",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-student-modal").modal("show")
                    }
                },
            ]
        });

        var inactiveTable = $('#inactive-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/student/${school_code}/inactive`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            // pageLength: 15,
            columns: [{
                    data: "student_no"
                },
                {
                    data: "name"
                },
                {
                    data: "gender"
                },
                {
                    data: "prog"
                },
                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Student List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Student List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Student List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Student List`,
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

        $("#student-table").on("click", ".student-update-btn", function() {
            var data = studentTable.row($(this).parents("tr")).data();
            // console.log(data);
            $("#edit-student-modal").modal("show");
            document.getElementById("edit-student-transid").value = data.id;
            document.getElementById("edit-student-id").value = data.student_no;
            // document.getElementById("edit-student-title").value = data.title;
            $("#edit-student-programs").val(data.prog_code).trigger('change');
            $("#edit-student-session").val(data.session).trigger('change');
            $("#edit-student-batch").val(data.batch).trigger('change');
            $("#edit-student-current-level").val(data.current_level).trigger('change');
            document.getElementById("edit-student-fname").value = data.fname;
            document.getElementById("edit-student-mname").value = data.mname;
            document.getElementById("edit-student-lname").value = data.lname;
            $("#edit-student-gender").val(data.gender).trigger('change');
            document.getElementById("edit-student-dob").value = data.dob;
            document.getElementById("edit-student-phone").value = data.phone;
            document.getElementById("edit-student-email").value = data.email;
            $("#edit-student-marital-status").val(data.marital_status).trigger('change');
            document.getElementById("edit-student-postal_address").value = data.postal_address;
            document.getElementById("edit-student-residential_address").value = data.residential;
            $("#edit-student-branch").val(data.branch).trigger('change');
            document.getElementById("edit-student-profession").value = data.profession;
            $("#edit-student-employment_status").val(data.employment_status).trigger('change');
            document.getElementById("edit-student-employer").value = data.employer;
            document.getElementById("edit-student-employer_contact").value = data.employer_contact;
            //document.getElementById("edit-student-gpost").value = data.gpost;
            document.getElementById("edit-student-english_language_grade").value = data.eng_lang_grade;
        });

        $("#inactive-table").on("click", ".student-update-btn", function() {
            var data = inactiveTable.row($(this).parents("tr")).data();
            // console.log(data);
            $("#edit-student-modal").modal("show");
            document.getElementById("edit-student-transid").value = data.id;
            document.getElementById("edit-student-id").value = data.student_no;
            //document.getElementById("edit-student-title").value = data.title;
            $("#edit-student-programs").val(data.prog_code).trigger('change');
            $("#edit-student-session").val(data.session).trigger('change');
            $("#edit-student-batch").val(data.batch).trigger('change');
            $("#edit-student-current-level").val(data.current_level).trigger('change');
            document.getElementById("edit-student-fname").value = data.fname;
            document.getElementById("edit-student-mname").value = data.mname;
            document.getElementById("edit-student-lname").value = data.lname;
            $("#edit-student-gender").val(data.gender).trigger('change');
            document.getElementById("edit-student-dob").value = data.dob;
            document.getElementById("edit-student-phone").value = data.phone;
            document.getElementById("edit-student-email").value = data.email;
            $("#edit-student-marital-status").val(data.marital_status).trigger('change');
            document.getElementById("edit-student-postal_address").value = data.postal_address;
            document.getElementById("edit-student-residential_address").value = data.residential;
            $("#edit-student-branch").val(data.branch).trigger('change');
            document.getElementById("edit-student-profession").value = data.profession;
            $("#edit-student-employment_status").val(data.employment_status).trigger('change');
            document.getElementById("edit-student-employer").value = data.employer;
            document.getElementById("edit-student-employer_contact").value = data.employer_contact;
            // document.getElementById("edit-student-gpost").value = data.gpost;
            document.getElementById("edit-student-english_language_grade").value = data.eng_lang_grade;
        });

        $('#student-table').on('click', '.delete-btn', function() {
            let data = studentTable.row($(this).parents('tr')).data()
            swal.fire({
                title: '',
                text: 'Are you sure you want to delete student?',
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
                        url: `${appUrl}/api/student/delete/${data.id}`,
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
                        studentTable.ajax.reload(false, null);
                    }).fail(() => {
                        alert('Processing failed!')
                    })
                }
            })
        })

        $('#inactive-table').on('click', '.restore-btn', function() {
            let data = inactiveTable.row($(this).parents('tr')).data()
            swal.fire({
                title: '',
                text: 'Are you sure you want to restore student?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: 'Restore'
            }).then((result) => {
                if (result.value) {
                    swal.fire({
                        text: 'Processing...',
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    $.ajax({
                        url: `${appUrl}/api/student/restore/${data.id}`,
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
                            text: "Student restored successfully",
                            type: "success"
                        });
                        studentTable.ajax.reload(false, null);
                        inactiveTable.ajax.reload(false, null);
                    }).fail(() => {
                        alert('Processing failed!')
                    })
                }
            })
        })

        $("#student-table").on("click", ".info-btn", function() {
            let data = studentTable.row($(this).parents('tr')).data();
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

        $("#inactive-table").on("click", ".info-btn", function() {
            let data = inactiveTable.row($(this).parents('tr')).data();
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

        (function() {
            document.addEventListener("DOMContentLoaded", function() {
                fetch(`${appUrl}/api/student/${school_code}/student_stats`, {
                        method: "GET",
                    })
                    .then(res => res.json())
                    .then(payload => {
                        if (!payload.ok) {
                            $("#info-panel-total-students").text("N/A");
                            $("#info-panel-total-females").text("N/A");
                            $("#info-panel-total-males").text("N/A");
                            $("#info-panel-inactive-students").text("N/A");
                            return;
                        }

                        $("#info-panel-total-students").text(payload.data.totalStudents);
                        $("#info-panel-total-females").text(payload.data.totalFemales);
                        $("#info-panel-total-males").text(payload.data.totalMales);
                        $("#info-panel-inactive-students").text(payload.data.inactiveStudents);

                    }).catch(err => {
                        if (err) {
                            console.error(err);
                        }
                    });
            });
        })();
        //filtering students
        var formdata = {}
        $('#filter-student').submit(function(e) {
            e.preventDefault()
            var selectprogram = $("#select-program").val()
            var selectbatch = $("#select-batch").val()
            var selectsession = $("#select-session").val()
            var selectbranch = $("#select-branch").val()
            formdata = {
                "school": `${school_code}`,
                "program": selectprogram,
                "batch": selectbatch,
                "session": selectsession,
                "branch": selectbranch
            }
            //checking if the object is not null
            if (formdata["program"] !== "" || formdata["batch"] !== "" || formdata["session"] !== "" || formdata[
                    "branch"] !== "") {
                formdata = JSON.stringify(formdata)
                studentTable.ajax.url(`${appUrl}/api/student/filterstudent/${formdata}`).load()

            }
            $("select").val(null).trigger('change');


        })

        //filter assessment table
        var prog = document.getElementById('student-prog');
        var sem = document.getElementById("student-sem");
        var batch = document.getElementById("student-batch");
        var branch = document.getElementById("student-branch");
        var erMsg = null;
        var assessTable = $('#assessment-table').DataTable({
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/assessment/all/${school_code}`,
                type: "GET",
            },
            processing: true,
            columns: [{
                    data: "student_id"
                },
                {
                    data: "student_name"
                },
                {
                    data: "pure_test",
                },
                {
                    data: "pure_exam"
                },
                {
                    data: "total_score"
                },
                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Assessment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Assessment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Assessment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Assessment List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3]
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
                    text: "Add Student Assessment",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-assess-modal").modal("show")
                    }
                },
            ]
        });
        //record assessement
        $("#add-student-assess-form-admin").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.

            let addStuAssessForm = document.getElementById('add-student-assess-form-admin');

            var formdata = new FormData(addStuAssessForm)
            formdata.append("createuser", createuser);
            formdata.append("school_code", school_code);
            Swal.fire({
                title: 'Do you want to add this student assessment?',
                text: "Or click cancel to abort!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Add'

            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        text: "Adding assessment please wait...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    fetch(`${appUrl}/api/assessment/store`, {
                        method: "POST",
                        body: formdata,
                        headers: {
                            "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
                        }
                    }).then(function(res) {
                        return res.json();
                    }).then(function(data) {
                        if (!data.ok) {
                            Swal.fire({
                                text: data.msg,
                                type: "error"
                            });
                            return;
                        }
                        Swal.fire({
                            text: "Assessment added  successfully",
                            type: "success"
                        });
                        $("#add-assess-modal").modal('hide');
                        $("select").val(null).trigger('change');
                        assessTable.ajax.reload(false, null);
                        addStuAssessForm.reset();

                    }).catch(function(err) {
                        if (err) {
                            Swal.fire({
                                type: "error",
                                text: "adding assessment failed"
                            });
                        }
                        console.log(err)
                    })
                }
            })
        });

        // edit assessment details for a student
        $('#assessment-table').on('click', '.edit-btn', function() {
            let data = assessTable.row($(this).parents('tr')).data();
            $("#edit-assess-modal").modal('show');
            $('#edit-ass-student').val(data.student_id).trigger('change');
            $('#edit-ass-course').val(data.course_id).trigger('change');
            $('#edit-ass-branch').val(data.branch_id).trigger('change');
            $('#edit-ass-semester').val(data.sem_id).trigger('change');
            $('#edit-ass-test-score').val(data.test_score);
            $('#edit-ass-exam-score').val(data.exam_score);
            $('#edit-ass-code').val(data.asessment_id);
            $('#edit-ass-language-grade').val(data.english_language_grade)
        })

        //start update
        $("#edit-student-assess-form-admin").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            let editStuAssessForm = document.getElementById('edit-student-assess-form-admin');

            var formdata = new FormData(editStuAssessForm);
            formdata.append("createuser", createuser);
            formdata.append("school_code", school_code);
            Swal.fire({
                title: 'Do you want to edit this student assessment?',
                text: "Or click cancel to abort!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Add'

            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        text: "Editing assessment please wait...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    fetch(`${appUrl}/api/assessment/update`, {
                        method: "POST",
                        body: formdata,
                        headers: {
                            "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
                        }
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
                        Swal.fire({
                            text: "Assessment edited  successfully",
                            type: "success"
                        });
                        $("#edit-assess-modal").modal('hide');
                        assessTable.ajax.reload(false, null);
                        editStuAssessForm.reset();
                    }).catch(function(err) {
                        if (err) {
                            console.log(err);
                            Swal.fire({
                                type: "error",
                                text: "editing assessment failed"
                            });
                        }
                    })
                }
            })
        });


        //delete assessment for a student
        $('#assessment-table').on('click', '.delete-btn', function() {
            let data = assessTable.row($(this).parents('tr')).data();
            swal.fire({
                title: '',
                text: 'Are you sure you want to delete student assessment?',
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
                        url: `${appUrl}/api/assessment/delete/${data.asessment_id}`,
                        type: 'post'
                    }).done(function(data) {
                        if (!data.ok) {
                            Swal.fire({
                                text: data.msg + "\n Working my guy",
                                type: "error"
                            });
                            return;
                        }
                        Swal.fire({
                            text: data.msg,
                            type: "success"
                        });
                        assessTable.ajax.reload(false, null);
                    }).fail(() => {
                        Swal.fire({
                            text: "Oops! Processing failed",
                            type: "error"
                        });
                    })
                }
            })
        })

        //filtering operation
        $("#filter-assessment-form").on("submit", function(e) {
            e.preventDefault();
            let branch = $("#student-filter-branch").val();
            let semester = $("#student-filter-sem").val();
            let batch = $("#student-filter-batch").val();
            let prog = $("#student-filter-prog").val();
            data = {
                branch,
                semester,
                batch,
                prog
            }
            let queryString = new URLSearchParams(data).toString();
            assessTable.ajax.url(`${appUrl}/api/assessment/all?${queryString}`).load()
        });



        // $(prog).on("select2:select", function(e) {
        //     if (prog.value === '' || batch.value === '' || branch.value === '' || sem.value === '') {
        //         erMsg = document.getElementById("errMessage").innerHTML = "Please all fields are required!";
        //         return false;

        //     } else if (prog.value != '' || batch.value != '' || branch.value != '' || sem.value === '') {
        //         setTimeout(function() {
        //             document.getElementById("errMessage").innerHTML = '';
        //         }, 1000);
        //     } else {
        //         formdata.append('semester', sem.value);
        //         formdata.append('branch', branch.value);
        //         formdata.append('student', studentSelector.value);
        //     }

        //     $('#assessment-table').DataTable({
        //         destroy: true
        //     }).destroy();

        //     assessTable = $('#assessment-table').DataTable({
        //         dom: 'Bfrtip',
        //         ajax: {
        //             url: `${appUrl}/api/assessment/filter_fetch_terminal_report/${school_code}`,
        //             type: "POST",
        //             data: {
        //                 'prog': prog.value,
        //                 'semester': sem.value,
        //                 'branch': branch.value,
        //                 'batch': batch.value,
        //             },
        //         },
        //         processing: true,
        //         columns: [{
        //                 data: "student"
        //             },
        //             {
        //                 data: "class"
        //             },
        //             {
        //                 data: "action"
        //             },
        //         ],
        //         buttons: [{
        //                 extend: 'print',
        //                 title: `${loggedInUserSchoolName} - Assessment List`,
        //                 attr: {
        //                     class: "btn btn-sm btn-info rounded-right"
        //                 },
        //                 exportOptions: {
        //                     columns: [0, 1, 2, 3]
        //                 }
        //             },
        //             {
        //                 extend: 'copy',
        //                 title: `${loggedInUserSchoolName} - Assessment List`,
        //                 attr: {
        //                     class: "btn btn-sm btn-info rounded-right"
        //                 },
        //                 exportOptions: {
        //                     columns: [0, 1, 2, 3]
        //                 }
        //             },
        //             {
        //                 extend: 'excel',
        //                 title: `${loggedInUserSchoolName} - Assessment List`,
        //                 attr: {
        //                     class: "btn btn-sm btn-info rounded-right"
        //                 },
        //                 exportOptions: {
        //                     columns: [0, 1, 2, 3]
        //                 }
        //             },
        //             {
        //                 extend: 'pdf',
        //                 title: `${loggedInUserSchoolName} - Assessment List`,
        //                 attr: {
        //                     class: "btn btn-sm btn-info rounded-right"
        //                 },
        //                 exportOptions: {
        //                     columns: [0, 1, 2, 3]
        //                 }
        //             },
        //             {
        //                 text: "Refresh",
        //                 attr: {
        //                     class: "ml-2 btn-secondary btn btn-sm rounded"
        //                 },
        //                 action: function(e, dt, node, config) {
        //                     dt.ajax.reload(false, null);
        //                 }
        //             },
        //         ]
        //     });
        // })
    </script>
@endsection
