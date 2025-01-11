<div class="modal fade" id="edit-bill-amount-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Update Bill Amount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {{-- Add bill item amount --}}

                <form action="" id="edit-bill-item-amount-form">
                    <input type="hidden" name="edit_bill_item_amount__transid" id="transid">
                    <input type="hidden" name="edit_bill_item_amount__branch" id="edit_bill_item_amount__branch">
                    <input type="hidden" name="edit_bill_item_amount__batch" id="edit_bill_item_amount__batch">
                    <input type="hidden" name="edit_bill_item_amount__semester" id="edit_bill_item_amount__semester">
                    <input type="hidden" name="edit_bill_item_amount__level" id="edit_bill_item_amount__level">
                    <input type="hidden" name="edit_bill_item_amount__program" id="edit_bill_item_amount__program">
                    <input type="hidden" name="edit_bill_item_amount__session" id="edit_bill_item_amount__session">
                    <input type="hidden" name="edit_bill_item_amount__item_name" id="edit_bill_item_amount__item_name">
                   
                    <div class="row">
                        <div class="col-sm">
                            <label for="">Amount*</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="edit_bill_amount"
                                    id="edit_bill_amount">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="reset" class="btn btn-light">Reset</button>
                    <button class="btn btn-primary" form="edit-bill-item-amount-form" type="submit">Submit</button>
                </div>


            </div>

        </div>
    </div>
</div>
<script>
    //Updating bill amount table
    $('#student-bill-setup-table').on("click", ".edit-btn", function() {
        var data = billItemAmountTable.row($(this).parents('tr')).data()
        $('#edit_bill_amount').val(data.billAmount)
        $("#edit_bill_item_amount__item_name").val(data.billCode)
        $('#edit_bill_item_amount__semester').val(data.billSemester)
        $('#edit_bill_item_amount__session').val(data.billSession)
        $('#edit_bill_item_amount__batch').val(data.billBatch)
        $('#edit_bill_item_amount__level').val(data.billLevel)
        $('#edit_bill_item_amount__program').val(data.billProgram)
        $('#edit_bill_item_amount__branch').val(data.billBranch)
        $('#transid').val(data.billTransid)
        $('#edit-bill-amount-modal').modal('show')
      
    })
    //submitting the form to update bill amount
    let editBillItemAmountForm = document.forms['edit-bill-item-amount-form'];
    $(editBillItemAmountForm).submit(function(e) {
        e.preventDefault();
        var formdata = new FormData(editBillItemAmountForm);
        formdata.append("school_code", `${school_code}`)
        Swal.fire({
            title: '',
            text: "Are you sure you want to update this bill ?",
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
                fetch(`${appUrl}/api/bill/updateBillItemAmount`, {
                    method: "POST",
                    body: formdata
                }).then(function(res) {
                    return res.json();
                }).then(function(data) {
                    if (!data.ok) {
                        Swal.fire({
                            text: data.msg,
                            type: "error"
                        });
                        
                        return;
                    }
                    Swal.fire({
                        text: "Bill updated  successfully",
                        type: "success"
                    });
                    $("#edit-bill-amount-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    billItemAmountTable.ajax.reload(false, null);
                    editBillItemAmountForm.reset();

                }).catch(function(err) {
                    if (err) {
                        console.log(err);
                        Swal.fire({
                            text: "updating bill item failed",
                            type: "error"
                        });
                    }
                })
            }
        })
    });
</script>
