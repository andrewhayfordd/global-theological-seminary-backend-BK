<div class="modal fade" id="edit-supplier-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Supplier Details</h5>
                <button class="close" type="" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-supplier-form">
                    @csrf
                    <input type="hidden" id="edit-supplier-transid" name="transid">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Name <span class="font-weight-bold text-danger">*</span></label>
                                <input type="text" required name="name" id="edit-supplier-name"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Phone <span class="font-weight-bold text-danger">*</span></label>
                                <input type="text" required name="phone" id="edit-supplier-phone"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="text" name="email" id="edit-supplier-email"
                                    class="form-control form-control-sm">
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Address</label>
                                <input type="text" name="address" id="edit-supplier-address"
                                    class="form-control form-control-sm">
                            </div>

                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm" form="edit-supplier-form" type="submit" name="submit">
                        Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#edit-supplier-form").on('submit',function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        let editSupplierForm = document.getElementById('edit-supplier-form');
        var formdata = new FormData(editSupplierForm);
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            // title: 'Do you want to edit this student assessment?',
            text: "Do you want to save changes?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Save',
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    text: "please wait...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/supplier/update`, {
                    method: "POST",
                    body: formdata,
                    headers: {
                        "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
                    }
                }).then(function(res) {
                    return res.json();
                }).then(function(data) {
                    if (!data.ok) {
                        Swal.fire({
                            title: data.msg ,
                            text:  data.errors_all.join(' and '),
                            type: "error"
                        });
                        return;
                    }
                    Swal.fire({
                        text: data.msg,
                        type: "success"
                    });
                    $("#edit-supplier-modal").modal('hide');
                    SupplierTable.ajax.reload(false, null);
                    editSupplierForm.reset();
                }).catch(function(err) {
                    if (err) {
                        console.log(err);
                        Swal.fire({
                            type: "error",
                            text: "Editing supplier details failed, Try again later"
                        });
                    }
                })
            }
        })
    });
</script>
