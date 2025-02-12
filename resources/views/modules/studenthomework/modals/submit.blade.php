<div class="modal fade" id="submitHomeworkModal{{ $homework->transid }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submit Homework</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('student.homework.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="homework_id" value="{{ $homework->transid }}">
                    <div class="form-group">
                        <label>Upload Your Work</label>
                        <input type="file" name="document" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
