<div class="modal fade" id="edit-ind-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="exampleModalLabel">Edit Bill Item</h5>
                <a href="#"><span class="close text-dark" data-dismiss="modal" aria-label="Close"
                        aria-hidden="false">×</span></a>
            </div>
            <div class="modal-body">
                <form id="edit-ind-form">
                    @csrf
                    <input type="text" name="item" id="ind-item" required hidden>
                    <input type="text" name="student" id="ind-student" required hidden>
                    <input type="text" name="semester" id="ind-semester" required hidden>
                    <input type="text" name="branch" id="ind-branch" required hidden>
                    <div class="form-group">
                        <label for="">Item Amount</label>
                        <input type="number" step="0.01" min="0" name="amount"
                            class="form-control form-control-sm" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-sm" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-light btn-sm" form="edit-ind-form" type="reset">Reset</button>
                <button class="btn btn-primary btn-sm" form="edit-ind-form" type="submit" name="submit"> <i
                        class=""></i> Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    var editIndForm = document.getElementById("edit-ind-form")
    $(editIndForm).submit(function(e) {
        e.preventDefault();

        // After checking the required inputs are not empty and is valid the form is then submitted
        var formdata = new FormData(editIndForm)
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            title: 'Are you sure you want to update bill item?',
            text: "Or click cancel to abort!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit'

        }).then((result) => {

            if (result.value) {
                Swal.fire({
                    text: "Processing...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/bill/update_individual_bill`, {
                    method: "POST",
                    body: formdata,
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
                        text: "Bill item updated successfully",
                        type: "success"
                    });
                    $("#edit-ind-modal").modal('hide');
                    // $("select").val(null).trigger('change');
                    studentBillTable.ajax.reload(false, null);
                    editIndForm.reset();
                    fetch(`${appUrl}/api/bill/fetch_student_total_bill`, {
                        method: "POST",
                        body: formdata,
                    }).then(function(res) {
                        return res.json()
                    }).then(function(data) {
                        // console.log(data);
                        if (!data.ok) {
                            Swal.fire({
                                text: data.msg,
                                type: "error"
                            });

                            return;
                        }
                        document.getElementById("text-total-bill").innerHTML = data.data
                            .total_bill;
                    });

                }).catch(function(err) {
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
