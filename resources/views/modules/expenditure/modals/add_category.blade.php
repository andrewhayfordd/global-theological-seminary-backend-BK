<div class="modal fade" id="add-category-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" style="min-width: 45%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
            </div>
            <div class="modal-body">
                <form action="" id="add-category-form">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control select2" name="category" required>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-primary" form="add-category-form" type="submit">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="reset" form="add-category-form" class="btn btn-light">Reset</button>
                </div>
            </div>
        </div>
    </div>
</div>
