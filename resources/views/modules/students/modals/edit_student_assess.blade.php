<div class="modal fade" id="edit-assess-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Student Assessment</h5>
                <button class="close" type="" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-student-assess-form-admin">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <input type="hidden" name="assessment_id" id="edit-ass-code">
                                <label for="">Student <span
                                        class="font-weight-bold text-danger">*</span></label>
                                <select name="student" class="form-control select2" id="edit-ass-student">
                                    <option value="">--Select--</option>
                                    @foreach ($student as $item)
                                        <option value="{{ $item->student_no }}">{{ $item->student_no }} - {{ $item->fname }} {{ $item->mname }}
                                            {{ $item->lname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Course <span class="font-weight-bold text-danger">*</span></label>
                                <select name="course" class="form-control select2" id="edit-ass-course">
                                    <option value="">--Select--</option>
                                    @foreach ($course as $item)
                                        <option value="{{ $item->course_code }}">{{ $item->course_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Branch<span class="font-weight-bold text-danger">*</span></label>
                                <select name="branch" class="form-control select2" id="edit-ass-branch" required>
                                    <option value="">--Select--</option>
                                    @foreach ($branch as $item)
                                        <option value="{{$item->branch_code}}">{{$item->branch_desc}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Semester <span
                                        class="font-weight-bold text-danger">*</span></label>
                                <select name="semester" class="form-control select2" id="edit-ass-semester" required>
                                    <option value="">--Select--</option>
                                    @foreach ($semester as $item)
                                        <option value="{{$item->sem_code}}">{{$item->sem_desc}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Test Score (100%)</label>
                                <input type="number" min="0"max="100" id="edit-ass-test-score" step="0.01" name="test"
                                    class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Exam Score (100%)</label>
                                <input type="number" name="exam" id="edit-ass-exam-score" min="0" max="100" step="0.01"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" form="edit-student-assess-form-admin"
                        type="reset">Reset</button>
                    <button class="btn btn-primary btn-sm" form="edit-student-assess-form-admin" type="submit"
                        name="submit">
                        Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
