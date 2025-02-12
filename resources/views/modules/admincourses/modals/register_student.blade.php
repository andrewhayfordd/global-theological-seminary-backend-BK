<div class="modal fade" id="register-student-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Register Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="register-course">
                    @csrf

                    <div class="row">
                        <div class="col">
                            <p class="text-danger">All fields are required</p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <label>Student<span class="text-danger">*</span></label>
                            <select class="form-select form-control select2 " aria-label="Default select example"
                                name="student_no" id="select-student">
                                <option value="">--Select student--</option>
                                @foreach ($student as $item)
                                    <option value={{ $item->student_no }}>{{ $item->fname }} {{ $item->mname }}
                                        {{ $item->lname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @csrf
                    <div class="row mt-3">
                        <div class="col">
                            <label>Course<span class="text-danger">*</span></label>
                            <select class="form-control select2" name="course_code">
                                <option value="">--Select--</option>
                                @foreach ($courses as $item)
                                    <option value={{ $item->course_code }}>{{ $item->course_title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @csrf
                    <div class="row mt-3">
                        <div class="col">
                            <label>Semester<span class="text-danger">*</span></label>
                            <select class="form-control select2" name="semester">
                                <option value="">--Select--</option>
                                @foreach ($semester as $item)
                                    <option value={{ $item->sem_code }}>{{ $item->sem_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label>Branch<span class="text-danger">*</span></label>
                            <select class="form-control select2" name="branch">
                                @foreach ($branches as $item)
                                    <option value={{ $item->branch_code }}>{{ $item->branch_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" form="register-course" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    //adding courses
    let registerCourseForm = document.forms['register-course']
    $('#register-course').submit(function(e) {
        e.preventDefault();
        var formdata = new FormData(registerCourseForm)
        formdata.append('school_code', `${school_code}`)
        formdata.append('createuser', `${createuser}`)
        swal.fire({
            title: "",
            text: "Are you sure you want  register these course?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Submit'
        }).then((result) => {
            if (result.value) {
                swal.fire({
                    text: "Processing...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                })

                fetch(`${appUrl}/api/course/register_course`, {
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
                        text: "Course registration successfully",
                        type: "success"
                    });
                    $("#register-student-modal").modal("hide");

                    assignedCourseTable.ajax.reload(false, null);
                    assignCourseForm.reset();

                }).catch(function(err) {
                    if (err) {
                        print(error.text);
                        Swal.fire({
                            text: "Course registration failed",
                            type: "error"
                        });
                    }
                })
            }
        })
    })
</script>
