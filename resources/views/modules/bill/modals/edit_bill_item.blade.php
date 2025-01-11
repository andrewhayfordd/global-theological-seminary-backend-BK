<div class="modal fade" id="edit-bill-item-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Bill Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="edit-bill-item-form">
                    @csrf
                    <input type="text" name="transid" id="bill-item-transid" required hidden>
                    <div class="row mt-2">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Programme* </label>
                                <select type="text" class="form-control select2" id="edit-bill-item-progs"
                                    name="program" required>
                                    <option value="">--Select--</option>
                                    @foreach ($programs as $item)
                                        <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Batch* </label>
                                <select type="text" class="form-control select2" id="edit-bill-batch" name="batch"
                                    required>
                                    <option value="">--Select--</option>
                                    @foreach ($batches as $item)
                                        <option value="{{ $item->batch_code }}">{{ $item->batch_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Branch* </label>
                                <select type="text" class="form-control select2" id="edit-bill-branch" name="branch"
                                    required>
                                    <option value="">--Select--</option>
                                    @foreach ($branches as $item)
                                        <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Semester* </label>
                                <select type="text" class="form-control select2" id="edit-bill-sem" name="semester"
                                    required>
                                    <option value="">--Select--</option>
                                    @foreach ($semester as $item)
                                        <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Bill Description*</label>
                                <input type="text" id="edit-bill-desc" class="form-control form-control-sm"
                                    placeholder="Eg. Tuition Fee" name="bill_desc" required>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Bill Amount*</label>
                                <input type="number" id="edit-bill-amount" class="form-control form-control-sm"
                                    name="amount" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-light">Reset</button>
                <button class="btn btn-primary" form="edit-bill-item-form" type="submit">Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    //updating detpartment

    $('#bill-item-table').on("click", ".edit-btn", function() {
        var data = billTable.row($(this).parents('tr')).data()
        $('#edit-bill-item-modal').modal('show')
        $('#bill-item-transid').val(data.transid)
        $('#edit-bill-desc').val(data.billDesc)
        $('#edit-bill-amount').val(data.amount)
        $('#edit-bill-sem').val(data.sem).trigger("change")
        $('#edit-bill-branch').val(data.branch).trigger("change")
        $('#edit-bill-batch').val(data.batch).trigger("change")
        $('#edit-bill-item-progs').val(data.prog).trigger("change")
    });

    let editBillItemForm = document.forms['edit-bill-item-form'];
    $(editBillItemForm).submit(function(e) {
        e.preventDefault();
        var formdata = new FormData(editBillItemForm);
        formdata.append("school_code", `${school_code}`)
        formdata.append("createuser", `${createuser}`)
        Swal.fire({
            title: '',
            text: "Are you sure you want to update bill item?",
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
                fetch(`${appUrl}/api/bill/update_bill_item`, {
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
                    $("#edit-bill-item-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    billTable.ajax.reload(false, null);
                    editBillItemForm.reset();

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

    // let editSemSelector = document.getElementById("edit-bill-sem");
    // $("#edit-bill-item-progs").on("select2:select", function(e) {
    //     let progCode = e.params.data.id;
    //     $.ajax({
    //         url: `${appUrl}/api/bill/fetch_prog_semester/${school_code}/${progCode}`,
    //         type: 'GET',
    //     }).done(function(data) {
    //         editSemSelector.innerHTML = null;
    //         if (!data.ok) {
    //             Swal.fire({
    //                 text: data.msg,
    //                 type: "info"
    //             });
    //             return;
    //         }
    //         let semesters = data.data;
    //         let option = document.createElement("option");
    //         option.value = semesters.sem_code;
    //         option.innerText = semesters.sem_desc;
    //         editSemSelector.appendChild(option);
    //     });
    // });
</script>
