@extends('layout.app')
@section('page-content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Courses</li>
                    </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="course-list-tab" data-toggle="tab" href="#course-list" role="tab"
                aria-controls="home" aria-selected="true">Course List</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="assign-course-tab" data-toggle="tab" href="#assign-course" role="tab"
                aria-controls="profile" aria-selected="false">Assign Course</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="program-students-tab" data-toggle="tab" href="#program-students" role="tab"
                aria-controls="profile" aria-selected="false">Program Students</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="course-students-tab" data-toggle="tab" href="#course-students" role="tab"
                aria-controls="profile" aria-selected="false">Course Students</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        {{-- course list --}}
        <div class="tab-pane fade show active" id="course-list" role="tabpanel" aria-labelledby="home-tab">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <form id="filter-form">
                        <div class="row">
                            <div class="col mt-2 ml-4">
                                <label for="">Programme*</label>
                                <select class="form-select select2" aria-label="Default select example" id="select-program">
                                    <option value="">--Select--</option>
                                    @foreach ($programs as $item)
                                        <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mt-2 ml-4">
                                <label for="">Semester*</label>
                                <select class="form-select select2" aria-label="Default select example"
                                    id="select-semester">
                                    <option value="">--Select--</option>
                                    @foreach ($semester as $item)
                                        <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mt-2 ml-4">
                                <label for="">Level*</label>
                                <select class="form-select select2" aria-label="Default select example" id="select-level">
                                    <option value="">--Select--</option>
                                    @foreach ($level as $item)
                                        <option value="{{ $item->level_code }}">{{ $item->level_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col  mt-2 ml-4" style="padding-top:30px;">
                                <button class="btn btn-md btn-outline-primary " name="submit" id="filter-courses"><i
                                        class="fa fa-filter"></i></button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="admincourses-table">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Title</th>
                                    <th>Semester</th>
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
        {{-- assign course --}}
        <div class="tab-pane fade" id="assign-course" role="tabpanel" aria-labelledby="profile-tab">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h4 class="card-title">All Assigned Courses</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="assigned-course-table">
                            <thead>
                                <tr>
                                    <th>Course ID</th>
                                    <th>Course Name</th>
                                    <th>Staff</th>
                                    <th>Semester</th>
                                    <th>Date Assigned</th>
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
        {{-- Program's students --}}
        <div class="tab-pane fade" id="program-students" role="tabpanel" aria-labelledby="progam-students-tab">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <form id="filter-form">
                        <div class="row">
                            <div class="col-6 mt-2 ml-4">
                                <label for="">Programme*</label>
                                <select class="form-select select2" aria-label="Default select example"
                                    id="filter-per-program-field">
                                    <option value="">--Select--</option>
                                    @foreach ($programs as $item)
                                        <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="program-student-table">
                            <thead>
                                <tr>
                                    <th>Program ID</th>
                                    <th>Prgram Name</th>
                                    <th>Student</th>
                                    <th>Academic Year</th>
                                    <th>Semester</th>

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
        {{-- Course's students --}}
        <div class="tab-pane fade" id="course-students" role="tabpanel" aria-labelledby="course-students-tab">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <form id="filter-form">
                        <div class="row">
                            <div class="col-6 mt-2 ml-4">
                                <label for="">Course*</label>
                                <select class="form-select select2" aria-label="Default select example"
                                    id="filter-per-course-field">
                                    <option value="">--Select--</option>
                                    @foreach ($courses as $item)
                                        <option value="{{ $item->course_code }}">{{ $item->course_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="course-student-table">
                            <thead>
                                <tr>
                                    <th>Course ID</th>
                                    <th>Course Name</th>
                                    <th>Student</th>
                                    <th>Academic Year</th>
                                    <th>Semester</th>

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


    @include('modules.admincourses.modals.assign_course')
    @include('modules.admincourses.modals.add_course')
    @include('modules.admincourses.modals.edit_course')
    @include('modules.admincourses.modals.coursesummary')
    @include('modules.admincourses.modals.register_student')
    @include('modules.admincourses.modals.edit_assign_course')
    <script>
        var ManagecoursesTable = $('#admincourses-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/course/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            columns: [{
                    data: "coursecode"
                },
                {
                    data: "coursetitle"
                },
                {
                    data: "semesterDesc"
                },
                {
                    data: "action"
                }
            ],
            buttons: [
                {
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Course List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Course List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Course List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Course List`,
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

                {
                    text: "Add course",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-course-modal").modal("show")
                    }
                },
                {
                    text: "Register student",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#register-student-modal").modal("show")
                    }
                }
            ]
        });

        //deleting course
        $("#admincourses-table").on("click", ".delete-btn", function() {
            var coursedata = ManagecoursesTable.row($(this).parents('tr')).data();
            swal.fire({
                title: " ",
                text: "Are you sure you want to delete this course?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete"
            }).then(function(result) {
                if (result.value) {
                    swal.fire({
                        text: "Deleting..",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    $.ajax({
                        url: `${appUrl}/api/course/delete/${coursedata.coursecode}/${school_code}`,
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
                            text: "Course deleted successfully",
                            type: "success"
                        });
                        ManagecoursesTable.ajax.reload(false, null);
                    }).fail(function() {
                        Swal.fire({
                            text: "Deleting course failed",
                            type: "error"
                        });
                    })

                }
            })

        })

        //edit course
        $('#admincourses-table').on("click", ".edit-btn", function() {
            $('#edit-course-modal').modal('show')
            var coursedata = ManagecoursesTable.row($(this).parents('tr')).data();
            $('#edit-course-transid').val(coursedata.transid);
            $('#course_title').val(coursedata.coursetitle);
            $('#course_code').val(coursedata.coursecode);
            $('#credit').val(coursedata.credit);
            $('#edit-course-semester').val(coursedata.semester).trigger("change");
            $('#edit-course-level').val(coursedata.level_code).trigger("change");

        })

        //updating form
        let editCourseForm = document.forms['edit-course-form']
        $("#edit-course-form").submit(function(e) {
            e.preventDefault()
            let formdata = new FormData(editCourseForm)
            formdata.append('school_code', `${school_code}`)
            swal.fire({
                title: "",
                text: "Are you sure you want to update this course?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Submit'
            }).then((result) => {
                if (result.value) {
                    swal.fire({
                        text: "Updating...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    })

                    fetch(`${appUrl}/api/course/update`, {
                        method: "post",
                        body: formdata
                    }).then(function(res) {
                        return res.json();
                    }).then(function(data) {

                        if (!data.ok) {
                            swal.fire({
                                text: data.msg,
                                type: "error"
                            })
                            return;
                        }


                        swal.fire({
                            text: "Course updated successfully",
                            type: "success"
                        });

                        $("#edit-course-modal").modal("hide");

                        $("select").val(null).trigger('change');
                        ManagecoursesTable.ajax.reload(false, null);
                        editCourseForm.reset();

                    }).catch(function(err) {
                        if (err) {
                            Swal.fire({
                                text: "updating course failed",
                                type: "error"
                            });
                        }
                    })
                }
            })
        })

        //showing list of all courses
        var courseStudent = $('#student-course-table').DataTable({
            dom: 'frtip',

            processing: true,
            responsive: true,
            columns: [{
                    data: "stuentname"
                },
                {
                    data: "semester"
                },

                {
                    data: "academicyear"
                }
            ],

        });

        $("#admincourses-table").on("click", ".btn-info", function() {
            $('#course-list-modal').modal('show');
            var data = ManagecoursesTable.row($(this).parents('tr')).data()
            let coursecode = data.coursecode;
            courseStudent.ajax.url(`${appUrl}/api/course/fetch_students/${school_code}/${coursecode}`)
                .load();

        })

        //registering students' courses
        let registerCoursesFrom = document.forms['register-course-form']
        $('#register-course-form').submit(function(e) {
            e.preventDefault()
            let FormData = new FormData()
            FormData.append('school_code', `${school_code}`)
            swal.fire({
                title: "",
                text: "Are you sure you want  register these courses?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Submit'
            }).then(function(result) {
                if (!result.value) {
                    swal.fire({
                        text: "Updating...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    })
                }
            })

        })

        //filtering students' courses

        var formdata = {}
        $('#filter-form').submit(function(e) {
            e.preventDefault()
            var selectprogram = $("#select-program").val()
            var selectsemester = $("#select-semester").val()
            var selectlevel = $("#select-level").val()

            formdata = {
                "school": `${school_code}`,
                "program": selectprogram,
                "semester": selectsemester,
                "level": selectlevel
            }
            //checking if the object is not null
            if (formdata["program"] !== "" || formdata["semester"] !== "" || formdata["level"] !== "") {
                formdata = JSON.stringify(formdata)
                ManagecoursesTable.ajax.url(`${appUrl}/api/course/filtercourses/${formdata}`).load()

            }
            $("select").val(null).trigger('change');


        })

        


        var assignedCourseTable = $('#assigned-course-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/course/fetch_assigned_courses/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            columns: [{
                    data: "course_code"
                },
                {
                    data: "course_desc"
                },
                {
                    data: "staff"
                },
                {
                    data: "semester"
                },
                {
                    data: "date_assigned"
                },
                {
                    data: "action"
                },

            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Assigned Course List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Assigned Course List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Assigned Course List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Assigned Course List`,
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

                {
                    text: "Assign course",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#assign-course-modal").modal("show")
                    }
                },

            ]
        });




        //edit assign course
        $('#assigned-course-table').on("click", ".assign-edit-btn", function() {
            $('#edit-assign-course-modal').modal('show')
            var assignedCoursedata = assignedCourseTable.row($(this).parents('tr')).data();
            $('#edit-assign-course-transid').val(assignedCoursedata.id);
            $('#course_code').val(assignedCoursedata.course_code);
            $('#staff').val(assignedCoursedata.staff);  // Ensure this matches your data
            $('#branch').val(assignedCoursedata.branch);

        })

        //updating assign course form
        let editassigncourse = document.forms['edit-assign-course-form']
        $("#edit-assign-course-form").submit(function(e) {
            e.preventDefault()
            
            let formdata = new FormData(editassigncourse)
            formdata.append('school_code', `${school_code}`)

            swal.fire({
                title: "",
                text: "Are you sure you want to update this course?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Submit'
            }).then((result) => {
                if (result.value) {
                    swal.fire({
                        text: "Updating...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    })

                    fetch(`${appUrl}/api/course/update_assigned_courses`, {
                        method: "post",
                        body: formdata
                    }).then(function(res) {
                        return res.json();
                    }).then(function(data) {

                        if (!data.ok) {
                            swal.fire({
                                text: data.msg,
                                type: "error"
                            })
                            return;
                        }


                        swal.fire({
                            text: "Course updated successfully",
                            type: "success"
                        });

                        $("#edit-assign-course-modal").modal("hide");

                        $("select").val(null).trigger('change');
                        assignedCourseTable.ajax.reload(false, null);
                        editassigncourse.reset();

                    }).catch(function(err) {
                        if (err) {
                            Swal.fire({
                                text: "updating course failed",
                                type: "error"
                            });
                        }
                    })
                }
            })
        })






        // Event delegation for dynamically loaded table rows
// $('#assigned-course-table').on("click", ".assign-edit-btn", function() {
//     console.log("edit");
//     // Ensure DataTable returns the correct row data
//     var assignedCoursedata = assignedCourseTable.row($(this).closest('tr')).data();

//     // Check if data is retrieved successfully
//     if (!assignedCoursedata) {
//         console.error("Assigned course data not found");
//         return;
//     }

//     // Populate modal fields with the retrieved data
//     $('#edit-assign-course-transid').val(assignedCoursedata.id);
//     $('#course_code').val(assignedCoursedata.course_code);
//     $('#staff').val(assignedCoursedata.staff);  // Ensure this matches your data
//     $('#branch').val(assignedCoursedata.branch);

//     // Show the modal
//     $('#edit-assign-course-modal').modal('show');
// });

// // Submitting the form and updating the course
// $(document).ready(function () {
//     const appUrl = window.location.origin;

//     $("#edit-assign-course-form").submit(function (e) {
//         e.preventDefault();

//         let formdata = new FormData(this);
//         formdata.append('_token', $('meta[name="csrf-token"]').attr('content'));

//         Swal.fire({
//             title: "",
//             text: "Are you sure you want to update this course?",
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonColor: '#3085d6',
//             confirmButtonText: 'Submit'
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 Swal.fire({
//                     text: "Updating...",
//                     showConfirmButton: false,
//                     allowEscapeKey: false,
//                     allowOutsideClick: false
//                 });

//                 console.log("Sending request...");

//                 fetch(`${appUrl}/api/course/update_assigned_courses`, {
//                     method: "POST",
//                     body: formdata
//                 })
//                     .then(res => {
//                         console.log("Response received", res);
//                         return res.json();
//                     })
//                     .then(data => {
//                         console.log("Data received", data);

//                         if (!data.ok) {
//                             Swal.fire({
//                                 text: data.msg,
//                                 icon: "error"
//                             });
//                             return;
//                         }

//                         Swal.fire({
//                             text: "Course updated successfully",
//                             icon: "success"
//                         });

//                         $("#edit-assign-course-modal").modal("hide");
//                         $("#edit-assign-course-form")[0].reset();
//                         $("select").val(null).trigger('change');
//                         assignedCourseTable.ajax.reload(null, false);
//                     })
//                     .catch(err => {
//                         console.error("Error:", err);
//                         Swal.fire({
//                             text: "Updating course failed",
//                             icon: "error"
//                         });
//                     });
//             }
//         });
//     });
// });


       


        //deleting assigned Course
        $('#assigned-course-table').on('click', '.assign-delete-btn', function() {
    let assignedCoursedata = assignedCourseTable.row($(this).parents('tr')).data();
    // console.log(assignedCoursedata); 
    swal.fire({
        title: '',
        text: 'Are you sure you want to delete this assigned course?',
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
                url: `${appUrl}/api/course/assigned_courses_delete/${assignedCoursedata.id}`,
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
                    text: "Assigned course deleted successfully",
                    type: "success"
                });
                assignedCourseTable.ajax.reload(false, null);
            }).fail(() => {
                alert('Processing failed!');
            });
        }
    })
});


        //all students per program

        var StudentsProgramTable = $('#program-student-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/course/program_students/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            columns: [{
                    data: "programcode"
                },
                {
                    data: "programtitle"
                },
                {
                    data: "student"
                },

                {
                    data: "acyear"
                },
                {
                    data: "semester"
                },
                // {
                //     data: "action"
                // }
            ],

            buttons: [

                {
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Course List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Course List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Course List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Course List`,
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
        //course students
        var StudentCourseTable = $('#course-student-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/course/course_students/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            columns: [{
                    data: "coursecode"
                },
                {
                    data: "coursedesc"
                },
                {
                    data: "student"
                },
                {
                    data: "acyear"
                },
                {
                    data: "semester"
                },

            ],

            buttons: [

                {
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Assigned Course List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Assigned Course List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Assigned Course List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Assigned Course List`,
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
        //filter per course
        $('#filter-per-course-field').on('select2:select', function(e) {
            var selectedCourse = document.getElementById('filter-per-course-field').value;
            var formdata = {
                "school": `${school_code}`,
                "course": selectedCourse,

            }
            //checking if the object is not null
            if (formdata["course"] !== "") {
                formdata = JSON.stringify(formdata)
                StudentCourseTable.ajax.url(`${appUrl}/api/course/filterStudentPerCourse/${formdata}`).load()

            }
            $("select").val(null).trigger('change');

        });
        //filter per program

        $('#filter-per-program-field').on('select2:select', function(e) {
            var selectedProgram = document.getElementById('filter-per-program-field').value;
            var programata = {
                "school": `${school_code}`,
                "program": selectedProgram,

            }
            // //checking if the object is not null
            if (programata["program"] !== "") {
                programata = JSON.stringify(programata)
                StudentsProgramTable.ajax.url(`${appUrl}/api/course/filterStudentPerProgram/${programata }`).load()

            }
            $("select").val(null).trigger('change');

        });
    </script>
@endsection
