<div class="modal fade" id="add-bill-item-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Bill Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="tab-pane  show active fade" id="add-bill-item" role="tabpanel"
                    aria-labelledby="bill-item-tab">
                    <form action="" method="POST" id="add-bill-item-form">
                        @csrf

                        <div class="row mt-2">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Program* </label>
                                    <select type="text" class="form-control select2" id="add-bill-item-progs"
                                        name="program" required>
                                        <option value="">--Select--</option>
                                        @forelse ($programs as $item)
                                            <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}

                                            @empty
                                                <p>No data found</p>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-sm">
                                <div class="form-group">
                                    <label>Batch* </label>
                                    <select type="text" class="form-control select2" id="add-bill-batch"
                                        name="batch" required>
                                        @forelse ($batches as $item)
                                            <option value="{{ $item->batch_code }}">{{ $item->batch_desc }}

                                            @empty
                                                <p>No data found</p>
                                        @endforelse
                                    </select>
                                </div>
                            </div> --}}
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Branch* </label>
                                    <select type="text" class="form-control select2" id="add-bill-branch"
                                        name="branch" required>
                                        <option value="">--Select--</option>
                                        @forelse ($branches as $item)
                                            <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}

                                            @empty
                                                <p>No data found</p>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Semester* </label>
                                    <select type="text" class="form-control select2" id="add-bill-sem"
                                        name="semester" required>
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
                                    <input type="text" id="add-bill-desc" class="form-control form-control-sm"
                                        placeholder="Eg. Tuition Fee" name="desc" required>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="">Bill Amount*</label>
                                    <input type="number" id="add-bill-desc" class="form-control form-control-sm"
                                        name="amount" required>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="reset" class="btn btn-light">Reset</button>
                        <button class="btn btn-primary" form="add-bill-item-form" type="submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //adding bill items
    var billItemForm = document.getElementById("add-bill-item-form")
    $(billItemForm).submit(function(e) {
        e.preventDefault();

        // After checking the required inputs are not empty and is valid the form is then submitted
        var formdata = new FormData(billItemForm)
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            title: 'Are you sure you want to add bill item?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit'

        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    text: "Adding please wait...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/bill/add_bill_item`, {
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
                        text: "Bill item added  successfully",
                        type: "success"
                    });
                    $("#add-bill-item-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    billTable.ajax.reload(false, null);
                    billItemForm.reset();
                }).catch(function(err) {
                    if (err) {
                        Swal.fire({
                            text: "Adding bill item failed!"
                        });
                    }
                })
            }
        })
    })
    //deleting bill items
    $("#bill-item-table").on("click", ".delete-btn", function() {
        let data = billTable.row($(this).parents('tr')).data();

        Swal.fire({
            title: "Are you sure you want to delete this bill?",
            text: "Or you can click cancel to abort!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete"

        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    text: "Deleting please wait...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                $.ajax({
                    url: `${appUrl}/api/bill/destroyBillItem/${data.billCode}/${school_code}`,
                    type: "POST",
                }).done(function(data) {
                    if (!data.ok) {
                        Swal.fire({
                            text: data.msg,
                            type: "error"
                        });
                        return;
                    }
                    Swal.fire({
                        text: "Bill deleted successfully",
                        type: "success"
                    });
                    billTable.ajax.reload(false, null);


                }).fail((xhr, status, error) => {

                    alert('Processing failed');
                })
            }
        })
    });
    //adding bill items amounts
    // var billItemAmountForm = document.getElementById("add-bill-item-amount-form")
    // $(billItemAmountForm).submit(function(e) {
    //     e.preventDefault();
    //     // After checking the required inputs are not empty and is valid the form is then submitted
    //     var formdata = new FormData(billItemAmountForm)
    //     formdata.append("createuser", createuser);
    //     formdata.append("school_code", school_code);
    //     formdata.append("acyear", acyear)

    //     Swal.fire({
    //         title: 'Are you sure you want to add this amount to the bill item?',
    //         type: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Submit'

    //     }).then((result) => {

    //         if (result.value) {
    //             Swal.fire({
    //                 text: "Adding please wait...",
    //                 showConfirmButton: false,
    //                 allowEscapeKey: false,
    //                 allowOutsideClick: false
    //             });
    //             fetch(`${appUrl}/api/bill/addBillItemAmount`, {
    //                 method: "POST",
    //                 body: formdata,
    //             }).then(function(res) {
    //                 return res.json()
    //             }).then(function(data) {
    //                 if (!data.ok) {
    //                     Swal.fire({
    //                         text: data.msg,
    //                         type: "error"
    //                     });
    //                     return;
    //                 }
    //                 Swal.fire({
    //                     text: "Adding Amount to Bill Item was successful",
    //                     type: "success"
    //                 });

    //                 $("select").val(null).trigger('change');
    //                 studentBillTable.ajax.reload(false, null);
    //                 billItemAmountTable.ajax.reload(false, null)
    //                 billItemAmountForm.reset();
    //                 $("#add-bill-amount-modal").modal('hide');
    //             }).catch(function(err) {
    //                 if (err) {
    //                     console.log(err);
    //                     Swal.fire({
    //                         text: "Adding Amount to bill item failed!"
    //                     });
    //                 }
    //             })
    //         }
    //     })
    // });

</script>
