<div class="modal fade" id="editTimetableModal" tabindex="-1" role="dialog" aria-labelledby="editTimetableModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTimetableModalLabel">Edit Timetable</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('timetables.update', 'edit') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_student_id">Select Student</label>
                        <select name="student_id" id="edit_student_id" class="form-control">
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->transid }}">{{ $student->fname }} {{ $student->lname }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_course_code">Select Course</label>
                        <select name="course_code" id="edit_course_code" class="form-control">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->course_code }}">{{ $course->course_title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_day">Day</label>
                        <input type="text" name="day" id="edit_day" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="edit_start_time">Start Time</label>
                        <input type="time" name="start_time" id="edit_start_time" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="edit_end_time">End Time</label>
                        <input type="time" name="end_time" id="edit_end_time" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="edit_location">Location</label>
                        <input type="text" name="location" id="edit_location" class="form-control" placeholder="Enter Location">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Timetable</button>
                </div>
            </form>
        </div>
    </div>
</div>
