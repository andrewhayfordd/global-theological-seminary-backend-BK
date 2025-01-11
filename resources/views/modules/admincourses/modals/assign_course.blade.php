<div class="modal fade" id="assign-course-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Assign Course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="assign-course-form">
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
                    <div class="row mt-3">
                        <div class="col">
                            <label>Staff<span class="text-danger">*</span></label>
                            <select class="form-control select2" name="staff">
                                <option value="">--Select--</option>
                                @foreach ($staff as $item)
                                    <option value={{ $item->staffno }}>{{ $item->title_desc }}{{ '.' }}
                                        {{ $item->fname }} {{ $item->mname }} {{ $item->lname }}</option>
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
                <button type="submit" name="submit" form="assign-course-form" class="btn btn-primary">Submit
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    //adding courses
    let assignCourseForm = document.forms['assign-course-form']
    $('#assign-course-form').submit(function(e) {
        e.preventDefault();
        var formdata = new FormData(assignCourseForm)
        formdata.append('school_code', `${school_code}`)
        formdata.append('createuser', `${createuser}`)
        swal.fire({
            title: "",
            text: "Are you sure you want to assign course to staff?",
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

                fetch(`${appUrl}/api/course/assign_course`, {
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
                        text: "Course assigned successfully",
                        type: "success"
                    });
                    $("#assign-course-modal").modal("hide");

                    assignedCourseTable.ajax.reload(false, null);
                    assignCourseForm.reset();

                }).catch(function(err) {
                    if (err) {

                        Swal.fire({
                            text: "assigning course failed",
                            type: "error"
                        });
                    }
                })
            }
        })
    })
</script>