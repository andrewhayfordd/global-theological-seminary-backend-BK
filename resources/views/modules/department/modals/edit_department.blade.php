<div class="modal fade" id="edit-department-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Department </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="edit-department-form">
                    @csrf
                    <div class="row mt-2">
                        <div class="col">
                            <input type="text" name="id" id="edit-department-code" hidden required>
                            <label for="">Department Name</label>
                            <input type="text" name="departmentupdatedname" class="form-control form-control-sm"
                                id="edit-department-name" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="reset" form="edit-department-form" class="btn btn-secondary"
                    data-dismiss="modal">Reset</button>
                <button type="submit" name="submit" form="edit-department-form" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>
