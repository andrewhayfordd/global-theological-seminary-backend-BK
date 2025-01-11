<div class="modal fade" id="add-department-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="add-department-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                          <label for="">Department Name *</label>
                            <input type="text" id="add-department" class="form-control form-control-sm"
                                placeholder="Eg. Finance" name="departmentname" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-light">Reset</button>
                <button class="btn btn-primary" form="add-department-form" type="submit">Save</button>
            </div>
        </div>
    </div>
</div>
