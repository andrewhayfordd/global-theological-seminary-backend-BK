<div class="modal fade" id="add-course-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="add-course-form">
                    @csrf
                    <div class="row mt-3">
                        <div class="col">
                            <label for="email">Programme *</label>
                            <select class="form-control select2" name="program">
                                <option value="">--Select--</option>
                                @foreach ($programs as $item)
                                    <option value={{ $item->prog_code }}>{{ $item->prog_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Course Title *</label>
                                <input type="text" class="form-control" name="course_title" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Course Code *</label>
                                <input type="text" class="form-control" name="course_code" id="add-department-name"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Course Description *</label>
                                <input type="text" class="form-control" name="course_desc" id="add-department-name"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="email">Credit Hours *</label>
                            <input type="number" min="1" step="0.2" required name="credit" id="email"
                                class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="email">Semester *</label>
                            <select class="form-control select2" name="semester" required>
                                <option value="">--Select--</option>
                                @foreach ($semester as $item)
                                    <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="email">Level *</label>
                            <select class="form-select select2" aria-label="Default select example" id="select-level"
                                name="level">
                                <option value="">--Select level--</option>
                                @foreach ($level as $item)
                                    <option value="{{ $item->level_code }}">{{ $item->level_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" form="add-course-form" class="btn btn-primary">Submit
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    //adding courses
    let addCourseForm = document.forms['add-course-form']
    $('#add-course-form').submit(function(e) {
        e.preventDefault();
        var formdata = new FormData(addCourseForm)
        formdata.append('school_code', `${school_code}`)
        formdata.append('createuser', `${createuser}`)
        swal.fire({
            title: "",
            text: "Are you sure you want to add this course?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Submit'
        }).then((result) => {
            if (result.value) {
                swal.fire({
                    text: "Adding...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                })

                fetch(`${appUrl}/api/course/add`, {
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
                        text: "Course added successfully",
                        type: "success"
                    });
                    $("#add-course-modal").modal("hide");

                    ManagecoursesTable.ajax.reload(false, null);
                    addCourseForm.reset();

                }).catch(function(err) {
                    if (err) {

                        Swal.fire({
                            text: "adding course failed",
                            type: "error"
                        });
                    }
                })
            }
        })
    })
</script>
