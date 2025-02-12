<!-- Notice Creation Modal -->
<div class="modal fade" id="createNoticeModal" tabindex="-1" aria-labelledby="createNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Notice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('notice.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="notice_title">Title</label>
                        <input type="text" name="notice_title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="notice_details">Details</label>
                        <textarea name="notice_details" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="recipient_type">Send Notice To</label>
                        <select name="recipient_type" id="recipient_type" class="form-control" required>
                            <option value="students">All Students</option>
                            <option value="staff">All Staff</option>
                            <option value="course_students">Students in a Course</option>
                            <option value="all">Everyone (Students & Staff)</option>
                        </select>
                    </div>

                    <div class="form-group" id="course_select" style="display: none;">
                        <label for="course_code">Select Course (If applicable)</label>
                        <select name="course_code" class="form-control">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->course_code }}">{{ $course->course_title }}</option>
                            @endforeach
                        </select>
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
                    <button type="submit" class="btn btn-primary">Create Notice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('recipient_type').addEventListener('change', function() {
        let courseSelect = document.getElementById('course_select');
        if (this.value === 'course_students') {
            courseSelect.style.display = 'block';
        } else {
            courseSelect.style.display = 'none';
        }
    });
</script>
