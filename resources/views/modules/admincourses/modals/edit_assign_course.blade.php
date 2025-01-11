<div class="modal fade" id="edit-assign-course-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Assign Course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="edit-assign-course-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                    <input type="text" name="transid" id="edit-assign-course-transid" hidden required>
                        <div class="col">
                            <label>Course<span class="text-danger">*</span></label>
                            <select class="form-control select2" name="course_code" id="course_code">
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
                            <select class="form-control select2" name="staff" id="staff">
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
                            <select class="form-control select2" name="branch" id="branch">
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
                <button type="submit" name="submit" form="edit-assign-course-form" class="btn btn-primary">Submit
                </button>
            </div>
        </div>
    </div>
</div>
