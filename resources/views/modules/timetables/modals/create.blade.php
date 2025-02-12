<div class="modal fade" id="createTimetableModal" tabindex="-1" role="dialog" aria-labelledby="createTimetableModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTimetableModalLabel">Create Timetable</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('timetables.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                <div class="form-group">
                   <label for="student_id">Select Student</label>
                   <select name="student_id" class="form-control">
                   <option value="">Select Student</option>
                      @foreach($students as $student)
                    <option value="{{ $student->transid }}">{{ $student->fname }} {{ $student->lname }}</option>
                      @endforeach
                   </select>
                </div>

                    <div class="form-group">
                        <label for="course_code">Select Course</label>
                        <select name="course_code" id="course_code" class="form-control">
                            <option value="">Select Course</option>
                            @foreach($students as $student)
                            <option value="{{ $student->transid }}">{{ $student->fname }} {{ $student->lname }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="day">Day</label>
                        <input type="text" name="day" id="day" class="form-control" placeholder="Enter Day (e.g., Monday)">
                    </div>

                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" class="form-control" placeholder="Enter Location">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Timetable</button>
                </div>
            </form>
        </div>
    </div>
</div>
