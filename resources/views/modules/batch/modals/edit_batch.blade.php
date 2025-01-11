<div class="modal fade" id="edit-batch-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Batch </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="edit-batch-form">
                    @csrf
                    <div class="row mt-2">
                        <div class="col">
                            <input type="text" name="id" id="edit-batch-code" hidden required>
                            <label for="">Description</label>
                            <input type="text" name="desc" class="form-control form-control-sm"
                                id="edit-batch-name" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="reset" form="edit-batch-form" class="btn btn-secondary"
                    data-dismiss="modal">Reset</button>
                <button type="submit" name="submit" form="edit-batch-form" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    let editbatchForm = document.forms['edit-batch-form'];
        $("#edit-batch-form").submit(function(e) {
            e.preventDefault();

            var formdata = new FormData(editbatchForm);

            Swal.fire({
                title: '',
                text: "Are you sure you want to update batch?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Submit'

            }).then((result) => {

                if (result.value) {
                    Swal.fire({
                        text: "updating...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    fetch(`${appUrl}/api/batch/update`, {
                        method: "POST",
                        body: formdata
                    }).then(function(res) {
                        return res.json()
                    }).then(function(data) {
                        if (!data.ok) {
                            Swal.fire({
                                text: data.msg,
                                type: "error"
                            });
                            return;
                        }
                        Swal.fire({
                            text: "Batch updated  successfully",
                            type: "success"
                        });
                        $("#edit-batch-modal").modal('hide');
                        $("select").val(null).trigger('change');
                        batchTable.ajax.reload(false, null);
                        editbatchForm.reset();

                    }).catch(function(err) {
                        if (err) {
                            console.log(err);
                            Swal.fire({
                                text: "updating batch failed",
                                type: "error"
                            });
                        }
                    })
                }
            })
        });
</script>
