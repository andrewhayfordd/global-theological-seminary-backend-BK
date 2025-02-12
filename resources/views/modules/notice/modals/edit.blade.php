<!-- Edit Notice Modal -->
<div class="modal fade" id="editNoticeModal-{{ $notice->transid }}" tabindex="-1" aria-labelledby="editNoticeModalLabel-{{ $notice->transid }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Notice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('notice.update', $notice->transid) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="notice_title">Title</label>
                        <input type="text" name="notice_title" class="form-control" value="{{ $notice->notice_title }}" required>
                    </div>

                    <div class="form-group">
                        <label for="notice_details">Details</label>
                        <textarea name="notice_details" class="form-control" required>{{ $notice->notice_details }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="recipient_type">Send Notice To</label>
                        <select name="recipient_type" id="recipient_type_{{ $notice->transid }}" class="form-control" required>
                            <option value="students" {{ $notice->notice_recipient == 'students' ? 'selected' : '' }}>All Students</option>
                            <option value="staff" {{ $notice->notice_recipient == 'staff' ? 'selected' : '' }}>All Staff</option>
                            <option value="course_students" {{ $notice->notice_recipient == 'course_students' ? 'selected' : '' }}>Students in a Course</option>
                            <option value="all" {{ $notice->notice_recipient == 'all' ? 'selected' : '' }}>Everyone (Students & Staff)</option>
                        </select>
                    </div>

                    <div class="form-group" id="course_select_{{ $notice->transid }}" style="display: {{ $notice->notice_recipient == 'course_students' ? 'block' : 'none' }};">
    <label for="course_code">Select Course (If applicable)</label>
    <select name="course_code" class="form-control">
        <option value="">Select Course</option>
        @foreach($courses as $course)
            <option value="{{ $course->course_code }}" {{ isset($notice->course_code) && $notice->course_code == $course->course_code ? 'selected' : '' }}>
                {{ $course->course_title }}
            </option>
        @endforeach
    </select>
</div>


                    <div class="form-group">
                        <label for="date_start">Start Date</label>
                        <input type="date" name="date_start" class="form-control" value="{{ $notice->date_start }}" required>
                    </div>

                    <div class="form-group">
                        <label for="date_end">End Date</label>
                        <input type="date" name="date_end" class="form-control" value="{{ $notice->date_end }}" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Notice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('recipient_type_{{ $notice->transid }}').addEventListener('change', function() {
        let courseSelect = document.getElementById('course_select_{{ $notice->transid }}');
        if (this.value === 'course_students') {
            courseSelect.style.display = 'block';
        } else {
            courseSelect.style.display = 'none';
        }
    });
</script>
