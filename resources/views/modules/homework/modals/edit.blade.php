<div class="modal fade" id="editHomeworkModal-{{ $homework->transid }}" tabindex="-1" aria-labelledby="editHomeworkModalLabel-{{ $homework->transid }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Homework</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('homework.update', $homework->transid) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="homework_title">Title</label>
                        <input type="text" name="homework_title" class="form-control" value="{{ $homework->homework_title }}" required>
                    </div>

                    <div class="form-group">
                        <label for="homework_details">Details</label>
                        <textarea name="homework_details" class="form-control" required>{{ $homework->homework_details }}</textarea>
                    </div>


                    <div class="form-group" id="course_select_{{ $homework->transid }}">
    <label for="course_code">Select Course (If applicable)</label>
    <select name="course_code" class="form-control">
        <option value="">Select Course</option>
        @foreach($courses as $course)
            <option value="{{ $course->course_code }}" {{ isset($homework->course_code) && $homework->course_code == $course->course_code ? 'selected' : '' }}>
                {{ $course->course_title }}
            </option>
        @endforeach
    </select>
</div>


                    <div class="form-group">
                        <label for="date_start">Start Date</label>
                        <input type="date" name="date_start" class="form-control" value="{{ $homework->date_start }}" required>
                    </div>

                    <div class="form-group">
                        <label for="date_end">End Date</label>
                        <input type="date" name="date_end" class="form-control" value="{{ $homework->date_end }}" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Homework</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <script>
    document.getElementById('recipient_type_{{ $homework->transid }}').addEventListener('change', function() {
        let courseSelect = document.getElementById('course_select_{{ $homework->transid }}');
        if (this.value === 'course_students') {
            courseSelect.style.display = 'block';
        } else {
            courseSelect.style.display = 'none';
        }
    });
</script> -->
