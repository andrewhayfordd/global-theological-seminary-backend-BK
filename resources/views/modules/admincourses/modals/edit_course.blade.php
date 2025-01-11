<div class="modal fade" id="edit-course-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Course </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="edit-course-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <input type="text" name="transid" id="edit-course-transid" hidden required>
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Course Title *</label>
                                <input type="text" class="form-control" name="course_title" required
                                    id="course_title">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Course Code *</label>
                                <input type="text" class="form-control" name="course_code" required id="course_code">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Course Description </label>
                                <input type="text" class="form-control" name="course_desc" id="course_desc">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="email">Credit *</label>
                            <input type="number" name="credit" class="form-control" id="credit">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col semester">
                            <label for="semester">Semester *</label>
                            <select class="form-select form-control select2" aria-label="Default select example" name="semester"
                                id="edit-course-semester">
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
                            <select class="form-select select2" aria-label="Default select example" id="edit-course-level"
                                name="level">
                                <option value="">--Select--</option>
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
                <button type="submit" name="submit" form="edit-course-form" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>


 