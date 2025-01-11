<div class="modal fade" id="edit-cat-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white" id="exampleModalLabel">Edit Item</h5>
                <a href="#"><span class="close text-white" data-dismiss="modal" aria-label="Close"
                        aria-hidden="false">×</span></a>
            </div>
            <div class="modal-body">
                <form id="edit-cat-form">
                    @csrf
                    <div class="form-group">
                        <input type="text" id="edit-cat-id" name="code" required hidden>
                        <label for="">Category Name</label>
                        <input type="text" name="desc" id="edit-cat-exp" placeholder=""
                            class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-sm rounded" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-light btn-sm rounded" form="edit-cat-form" type="reset">Reset</button>
                <button class="btn btn-primary btn-sm rounded" form="edit-cat-form" type="submit" name="submit"> <i
                        class=""></i> Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    var editCatForm = document.getElementById("edit-cat-form")
    $(editCatForm).submit(function (e) {
        e.preventDefault();

        // After checking the required inputs are not empty and is valid the form is then submitted
        var formdata = new FormData(editCatForm)
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            title: 'Are you sure you want to update item?',
            text: "Or click cancel to abort!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit'

        }).then((result) => {

            if (result.value) {
                Swal.fire({
                    text: "Updaing please wait...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/requisition/update_category`, {
                    method: "POST",
                    body: formdata,
                    headers: {
                        "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
                    }
                }).then(function (res) {
                    return res.json()
                }).then(function (data) {
                    if (!data.ok) {
                        Swal.fire({
                            text: data.msg,
                            type: "error"
                        });
                        return;
                    }
                    Swal.fire({
                        text: "Item updated  successfully",
                        type: "success"
                    });
                    $("#edit-cat-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    requisitionCatTable.ajax.reload(null, false);
                    editCatForm.reset();

                }).catch(function (err) {
                    if (err) {
                        Swal.fire({
                            text: "Updating failed"
                        });
                    }
                })
            }
        })
    });

</script>
