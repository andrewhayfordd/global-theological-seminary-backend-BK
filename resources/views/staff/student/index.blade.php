@extends('layout.app')
@section('page-name', 'Students')
@section('page-content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Students</li>
                    </ul>
            </div>
            {{-- <div class="col text-right">
                <div>
                    <a href="#" data-toggle="modal" data-target="#addStaffModal" data-toggle="tooltip" data-placement="bottom"
                        title="Add staff" class="btn btn-sm btn-primary shadow-sm">Add Staff Qualif</a>
                    <a href="#" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                        data-target="#AssignTeacherModal"><i class=""></i> Assign Teacher</a>
                </div>
            </div> --}}
        </div>
    </div>
    <!-- /Page Header -->

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">Student Details</a>
        </li>
        {{-- <li class="nav-item" role="presentation">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                aria-selected="false">Contacts</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact"
                aria-selected="false">Qualifications</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="emp-tab" data-toggle="tab" href="#emp" role="tab" aria-controls="emp"
                aria-selected="false">Employment Details</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="acc-tab" data-toggle="tab" href="#acc" role="tab" aria-controls="emp"
                aria-selected="false">Account Details</a>
        </li> --}}
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h4 class="card-title">List of all students</h4>
                </div>
                <div class="card-body">
                    <div class="table-">
                        <div class="mb-5">
                            <form id="course-filter-form">
                                @csrf
                                <div class="row">
                                    <div class="col">
                                        <label for="">Courses</label>
                                        <select name="filter-course" id="course-filter" class="form-control select2">
                                            <option value="">--Select Option--</option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->course_code }}">{{ $course->course_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="input-group-prepend px-0 pt-0 mt-4">
                                        <button type="submit" class="btn btn-dark btn-sm" name='submit'><i
                                                class="fas fa-filter ml-1"></i>Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="staff-student-table">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Course</th>
                                    {{-- <th>Action</th> --}}
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
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="card shadow mb-4">
                {{-- <div class="card-header">
                    <h4 class="card-title">List of all Staff</h4>
                </div> --}}
                <div class="card-body">
                    <div class="table-">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="contact-table">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Name</th>
                                    <th>Relationship</th>
                                    <th>Phone</th>
                                    <th>Email</th>
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
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="card shadow mb-4">
                {{-- <div class="card-header">
                    <h4 class="card-title">List of all Staff</h4>
                </div> --}}
                <div class="card-body">
                    <div class="table-">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="qual-table">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Qualification</th>
                                    <th>Institution</th>
                                    <th>Completion Year</th>
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
        <div class="tab-pane fade" id="emp" role="tabpanel" aria-labelledby="emp-tab">
            <div class="card shadow mb-4">
                {{-- <div class="card-header">
                    <h4 class="card-title">List of all Staff</h4>
                </div> --}}
                <div class="card-body">
                    <div class="table-">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="emp-table">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Date Employed</th>
                                    <th>Position</th>
                                    <th>Type of Employment</th>
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
        <div class="tab-pane fade" id="acc" role="tabpanel" aria-labelledby="acc-tab">
            <div class="card shadow mb-4">
                {{-- <div class="card-header">
                    <h4 class="card-title">List of all Staff</h4>
                </div> --}}
                <div class="card-body">
                    <div class="table-">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="acc-table">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Bank</th>
                                    <th>Account No.</th>
                                    <th>Type of Account</th>
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


    <script>
        var staffStudentTable = $('#staff-student-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            // ajax: {
            //     url: `${appUrl}/api/staff_dashboard/students/{{ $staff_no }}`,
            //     type: "GET",
            // },
            processing: true,
            responsive: true,
            // pageLength: 15,
            columns: [{
                    data: "studentCode"
                },
                {
                    data: "name"
                },
                {
                    data: "gender"
                },
                {
                    data: "courseName"
                },


            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Staff List`,
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

        const courseFilterForm = document.getElementById('course-filter-form');

        $(courseFilterForm).submit(function(e) {
            e.preventDefault();

            let course = document.getElementById("course-filter").value;

            staffStudentTable.ajax.url(
                `${appUrl}/api/staff_dashboard/students/${course}`).load();

        })

        //viewing staff info
        $('#staff-table').on('click', '.info-btn', function() {
            alert('Not yet completed')
        })

        $('#staff-table').on('click', '.edit-btn', function() {
            $('#edit-staff-modal').modal('show')
        })

        $('#staff-table').on('click', '.contact-btn', function() {
            let data = staffTable.row($(this).parents('tr')).data()
            $('#add-contact-id').val(data.staffno)

        })

        $('#staff-table').on('click', '.qual-btn', function() {
            let data = staffTable.row($(this).parents('tr')).data()
            $('#add-qual-id').val(data.staffno)

        })


        //deleting staff members 
        $('#staff-table').on('click', '.delete-btn', function() {
            var staffdata = staffTable.row($(this).parents('tr')).data()
            swal.fire({
                title: '',
                text: 'Are you sure you want to delete staff?',
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
                        url: `${appUrl}/api/staff/delete/${staffdata.id}`,
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
                            text: "Staff member deleted successfully",
                            type: "success"
                        });
                        staffTable.ajax.reload(false, null);
                    }).fail(() => {
                        alert('Processing failed!')
                    })
                }
            })
        })
        //updating staffs
        $('#staff-table').on('click', '.edit-btn', function() {
            var updateStaffDtata = staffTable.row($(this).parents('tr')).data()
            $('#edit-staff-modal').modal('show')
            $('#first_name').val(updateStaffDtata.staffname)
            $('#gender').val(updateStaffDtata.gender)
            $("#last_name").val(updateStaffDtata.stafflastname)
            $("#email").val(updateStaffDtata.email)
            $("#phone").val(updateStaffDtata.phone)
        });

        var qualTable = $('#qual-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/staff/fetch_qual/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            // pageLength: 15,
            columns: [{
                    data: "staff"
                },
                {
                    data: "qual"
                },
                {
                    data: "inst"
                },
                {
                    data: "comp"
                },

                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Staff List`,
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
                    text: "Add Staff Qualification",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-staff-qual-modal").modal("show")
                    }
                },
            ]
        });
        $('#qual-table').on('click', '.qual-delete-btn', function() {
            let staffdata = qualTable.row($(this).parents('tr')).data()
            swal.fire({
                title: '',
                text: 'Are you sure you want to delete staff qualification?',
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
                        url: `${appUrl}/api/staff/qual_delete/${staffdata.id}`,
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
                            text: "Staff qualification deleted successfully",
                            type: "success"
                        });
                        qualTable.ajax.reload(false, null);
                    }).fail(() => {
                        alert('Processing failed!')
                    })
                }
            })
        })

        var contactTable = $('#contact-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/staff/fetch_contact/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            // pageLength: 15,
            columns: [{
                    data: "staff"
                },
                {
                    data: "name"
                },
                {
                    data: "rel"
                },
                {
                    data: "phone"
                },
                {
                    data: "email"
                },
                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Staff List`,
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
                    text: "Add Staff Contact",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-con-modal").modal("show")
                    }
                },
            ]
        });
        $('#contact-table').on('click', '.con-delete-btn', function() {
            let staffdata = contactTable.row($(this).parents('tr')).data()
            swal.fire({
                title: '',
                text: 'Are you sure you want to delete staff contact?',
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
                        url: `${appUrl}/api/staff/contact_delete/${staffdata.id}`,
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
                            text: "Staff contact deleted successfully",
                            type: "success"
                        });
                        contactTable.ajax.reload(false, null);
                    }).fail(() => {
                        alert('Processing failed!')
                    })
                }
            })
        })

        var empTable = $('#emp-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/staff/fetch_employment/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            // pageLength: 15,
            columns: [{
                    data: "staff"
                },
                {
                    data: "date"
                },
                {
                    data: "position"
                },
                {
                    data: "type"
                },
                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Staff List`,
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
                    text: "Add Staff Employment",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-emp-modal").modal("show")
                    }
                },
            ]
        });
        $('#emp-table').on('click', '.emp-delete-btn', function() {
            let staffdata = empTable.row($(this).parents('tr')).data()
            swal.fire({
                title: '',
                text: 'Are you sure you want to delete staff employment details?',
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
                        url: `${appUrl}/api/staff/emp_delete/${staffdata.id}`,
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
                            text: "Staff deleted successfully",
                            type: "success"
                        });
                        empTable.ajax.reload(false, null);
                    }).fail(() => {
                        alert('Processing failed!')
                    })
                }
            })
        })

        var accTable = $('#acc-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/staff/fetch_account/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            columns: [{
                    data: "staff"
                },
                {
                    data: "bank"
                },
                {
                    data: "accountNo"
                },
                {
                    data: "account"
                },
                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Staff List`,
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
                    text: "Add Staff Account Details",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-acc-modal").modal("show")
                    }
                },
            ]
        });
        $('#acc-table').on('click', '.acc-delete-btn', function() {
            let staffdata = accTable.row($(this).parents('tr')).data()
            swal.fire({
                title: '',
                text: 'Are you sure you want to delete staff account details?',
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
                        url: `${appUrl}/api/staff/acc_delete/${staffdata.id}`,
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
                            text: "Staff account details deleted successfully",
                            type: "success"
                        });
                        accTable.ajax.reload(false, null);
                    }).fail(() => {
                        alert('Processing failed!')
                    })
                }
            })
        })
    </script>
@endsection
