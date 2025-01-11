@extends('layout.app')
@section('page-name', 'Staff')
@section('page-content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Staff</li>
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
                aria-selected="true">Staff Details</a>
        </li>
        <li class="nav-item" role="presentation">
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
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h4 class="card-title">List of all Staff</h4>
                </div>
                <div class="card-body">
                    <div class="table-">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="staff-table">
                            <thead>
                                <tr>
                                    <th>Staff ID</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Department</th>
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

    @include('modules.staff.modals.add_staff')
    @include('modules.staff.modals.edit_staff')
    @include('modules.staff.modals.add_contact')
    @include('modules.staff.modals.add_qual')
    @include('modules.staff.modals.add_staff_contact')
    @include('modules.staff.modals.add_staff_qual')
    @include('modules.staff.modals.add_staff_emp')
    @include('modules.staff.modals.add_staff_account')
    <script>
        var staffTable = $('#staff-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/staff/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            // pageLength: 15,
            columns: [{
                    data: "staffno"
                },
                {
                    data: "name"
                },
                {
                    data: "gender"
                },
                {
                    data: "stafftype"
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
                    text: "Add Staff",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-staff-modal").modal("show")
                    }
                },
            ]
        });

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
            $('#edit-staff-first_name').val(updateStaffDtata.staffname)
            $('#edit-staff-gender').val(updateStaffDtata.gender)
            $("#edit-staff-last_name").val(updateStaffDtata.stafflastname)
            $("#edit-staff-email").val(updateStaffDtata.email)
            $("#edit-staff-phone").val(updateStaffDtata.phone)
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


        $("#staff-table").on("click", ".edit-btn", function() {
            var data = staffTable.row($(this).parents("tr")).data();
            // console.log(data);
            $("#edit-staff-modal").modal("show");
            document.getElementById("edit-staff-transid").value = data.id;
            document.getElementById("edit-staff-id").value = data.staffno;
            //document.getElementById("edit-student-title").value = data.title;
            $("#edit-staff-Staff-type").val(data.prog_code).trigger('change');
            document.getElementById("edit-staff-first_name").value = data.fname;
            document.getElementById("edit-staff-Middle_name").value = data.mname;
            document.getElementById("edit-steff-last_name").value = data.lname;
            $("#edit-staff-gender").val(data.gender).trigger('change');
            document.getElementById("edit-staff-dob").value = data.staffdob;
            document.getElementById("edit-staff-phone").value = data.phone;
            document.getElementById("edit-staff-email").value = data.email;
            $("#edit-staff-marital_status").val(data.staffmaritalstatus).trigger('change');
            document.getElementById("edit-staff-postal_address").value = data.postaladdress;
            document.getElementById("edit-staff-Residential-address").value = data.residentialaddress;
            
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
