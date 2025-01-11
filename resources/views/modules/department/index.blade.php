@extends('layout.app')
@section('page-content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Department</li>
                    </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="card-header py-3">
                <ul class="list-group list-group-horizontal-sm" id="info-panel">
                    <li class="list-group-item">
                        <div class="m-0 p-0 font-weight-bold text-primary">
                            Total Staff Academics:
                            <span class="text-secondary" id="info-panel-total-students">{{ $totalAcadmics }}</span>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="m-0 p-0 font-weight-bold text-primary">
                            Total Staff Non Academics:
                            <span class="text-secondary" id="info-panel-total-students">{{ $totalNonAcadmics }}</span>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="m-0 p-0 font-weight-bold text-primary">
                            Total Males Academics:
                            <span class="text-secondary" id="info-panel-total-students">{{ $maleAcademics }}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="m-0 p-0 font-weight-bold text-primary">
                            Total Females Academics:
                            <span class="text-secondary" id="info-panel-total-females">{{ $femaleAcademics }}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="m-0 p-0 font-weight-bold text-primary">
                            Total Males Non-Academics:
                            <span class="text-secondary" id="info-panel-total-males">{{ $maleNonAcademics }}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="m-0 p-0 font-weight-bold text-primary">
                            Total Females Non-Academics:
                            <span class="text-secondary" id="info-panel-inactive-students">{{ $femaleNonAcademics }}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="table-">
                <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                    width='100%' id="department-table">
                    <thead>
                        <tr>
                            <th>Department Code</th>
                            <th>Department Description</th>
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
    @include('modules.department.modals.add_department')
    @include('modules.department.modals.edit_department')
    @include('modules.department.modals.department_list')
    <script>
        var departmentTable = $('#department-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/department/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            // pageLength: 15,
            columns: [{
                    data: "departmentcode"
                },
                {
                    data: "departmentname"
                },

                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Department List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Department List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Department List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Department List`,
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
                    text: "Add department",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-department-modal").modal("show")
                    }
                },
            ]
        });
        //storing data
        var addDepartmentForm = document.forms['add-department-form']
        $('#add-department-form').submit(function(e) {

            e.preventDefault()
            var formdata = new FormData(addDepartmentForm)
            formdata.append('school_code', `${school_code}`)

            Swal.fire({
                title: '',
                text: "Are you sure you want to add deparment?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Submit'

            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        text: "Adding...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    fetch(`${appUrl}/api/department/add`, {
                        method: "post",
                        body: formdata,
                    }).then(function(res) {
                        return res.json();
                    }).then(function(data) {
                        if (!data.ok) {
                            swal.fire({
                                text: data.msg,
                                type: "error"
                            });
                            return;
                        }
                        Swal.fire({
                            text: "Department added successfully",
                            type: "success"
                        });
                        $("#add-department-modal").modal("hide");
                        $("select").val(null).trigger('change');
                        departmentTable.ajax.reload(false, null);
                        addDepartmentForm.reset();

                    }).catch(function(err) {
                        if (err) {

                            Swal.fire({
                                text: "adding department failed",
                                type: "error"
                            });
                        }
                    })

                }
            })

        })
        //deleting department
        $('#department-table').on("click", ".delete-btn", function() {
            var data = departmentTable.row($(this).parents('tr')).data();
            Swal.fire({
                title: "Are  you sure you wat to delete this department?",
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
                        url: `${appUrl}/api/department/delete/${data.departmentcode}`,
                        type: "post"
                    }).done(function(data) {
                        if (!data.ok) {
                            Swal.fire({
                                text: data.msg,
                                type: "error"
                            });
                            return;
                        }
                        Swal.fire({
                            text: "Department deleted successfully",
                            type: "success"
                        });
                        departmentTable.ajax.reload(false, null);
                    }).fail(function() {
                        console.log('processing failed');
                    })
                }
            })

        });
        //updating detpartment

        $('#department-table').on("click", ".edit-btn", function() {
            $('#edit-department-modal').modal('show')

            var data = departmentTable.row($(this).parents('tr')).data()
            $('#edit-department-name').val(data.departmentname)
            $('#edit-department-code').val(data.departmentcode)


        })
        let editdepartmentForm = document.forms['edit-department-form'];
        $("#edit-department-form").submit(function(e) {
            e.preventDefault();

            var formdata = new FormData(editdepartmentForm);

            Swal.fire({
                title: '',
                text: "Are you sure you want to update department?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Submit'

            }).then((result) => {

                if (result.value) {
                    Swal.fire({
                        text: "updating...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    fetch(`${appUrl}/api/department/update`, {
                        method: "POST",
                        body: formdata
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
                            text: "department updated  successfully",
                            type: "success"
                        });
                        $("#edit-department-modal").modal('hide');
                        $("select").val(null).trigger('change');
                        departmentTable.ajax.reload(false, null);
                        editdepartmentForm.reset();

                    }).catch(function(err) {
                        if (err) {
                            console.log(err);
                            Swal.fire({
                                text: "updating department failed",
                                type: "error"
                            });
                        }
                    })
                }
            })
        });

        var departmentListTable = $('#department-list-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            processing: true,
            responsive: true,
            columns: [{
                    data: "picture"
                },
                {
                    data: "staff"
                },
                {
                    data: "dept"
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
        $('#department-table').on("click", ".dept-btn", function() {
            var data = departmentTable.row($(this).parents('tr')).data();
            $("#dept-list-modal").modal("show")
            departmentListTable.ajax.url(`${appUrl}/api/department/department_list/${school_code}/${data.departmentcode}`).load();
        });

    </script>
@endsection
