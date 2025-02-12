@extends('layouts.app')
@section('page-name', 'Assessment')
@section('content')

<div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Assessment</li>
                    </ul>
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


@include('modules.students.modals.add_student_assess')
@include('modules.students.modals.edit_student_assess')

<script>

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
   
</script>
@endsection