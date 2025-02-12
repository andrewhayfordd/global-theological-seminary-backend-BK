<div class="modal fade" id="createHomeworkModal" tabindex="-1" aria-labelledby="createHomeworkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Assignment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('homework.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="homework_title">Title</label>
                        <input type="text" name="homework_title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="homework_details">Details</label>
                        <textarea name="homework_details" class="form-control" required></textarea>
                    </div>
                    
                    <div class="form-group" hidden>
                        <label for="recipient_type"></label>
                        <input name="recipient_type" value="course_students" id="recipient_type" class="form-control" required>
                    </div>

                    <div class="form-group" id="course_select">
                        <label for="course_code">Select Course</label>
                        <select name="course_code" class="form-control">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->course_code }}">{{ $course->course_code }}-{{ $course->course_title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                    </div>

                    <div class="form-group">
                        <label for="date_start">Start Date</label>
                        <input type="date" name="date_start" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="date_end">End Date</label>
                        <input type="date" name="date_end" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>