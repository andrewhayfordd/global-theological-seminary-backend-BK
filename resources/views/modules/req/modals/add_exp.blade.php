<div class="modal fade" id="add-req-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white" id="exampleModalLabel">Add Request</h5>
                <button class="close text-white" type="" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- modal body starts -->
                <form id="add-req-form">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <label for="">Branch <span class="text-danger">*</span></label>
                            <select name="branch" id="stu" class="form-control form-control-sm select2" required>
                                <option value="">--Select--</option>
                                @foreach ($branches as $item)
                                    <option value="{{ $item->branch_code }}" selected>
                                        {{ $item->branch_desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="">Semester <span class="text-danger">*</span></label>
                            <select name="semester" id="exp-acterm" class="form-control form-control-sm select2"
                                required>
                                <option value="">--Select--</option>
                                @foreach ($semester as $item)
                                    <option value="{{ $item->sem_code }}" selected>
                                        {{ $item->sem_desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col mt-2">
                            <label for="userBooks">Requestor <span class="text-danger">*</span></label>
                            <input name="req" class="form-control form-control-sm" required>
                        </div>
                        <div class="col mt-2">
                            <label for="userBooks">Requested Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="payment-type" class="form-control form-control-sm"
                                required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <label for="userBooks">Requested Quantity <span class="text-danger">*</span></label>
                            <input type="text" name="quantity" id="payment-type" class="form-control form-control-sm"
                                required>
                        </div>
                        <div class="col">
                            <label for="section">Item<span class="text-danger">*</span></label>
                            <select name="item" class="form-control select2" id="">
                                <option value="">--Select--</option>
                                @foreach ($items as $item)
                                    <option value="{{$item->item_code}}">{{$item->item_desc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-light" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-sm btn-light" form="add-req-form" type="reset">Reset</button>
                <button class="btn btn-sm btn-primary" form="add-req-form" type="submit">Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    const requestForm = document.forms['add-req-form'];

    $(requestForm).submit(function(e) {
        e.preventDefault();

        // After checking the required inputs are not empty and is valid the form is then submitted
        var formdata = new FormData(requestForm)
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            title: 'Are you sure you want to add request?',
            text: "Or click cancel to abort!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit'

        }).then((result) => {

            if (result.value) {
                Swal.fire({
                    text: "Processing",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/requisition/add`, {
                    method: "POST",
                    body: formdata,
                    headers: {
                        "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
                    }
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
                        text: "Added successfully",
                        type: "success"
                    });
                    $("#add-req-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    requisitionTable.ajax.reload(null, false);
                    requestForm.reset();

                }).catch(function(err) {
                    if (err) {
                        Swal.fire({
                            type: "error",
                            text: "Processing failed",
                        });
                    }
                })
            }
        })
    });

    /**
     *This is to populate required inputs for payment type
     */
    let requiredInputs = {
        "cheque": `<div class='row'>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Cheque Bank</label>
                                <div class="input-group">
                                    <input type="text" name="cheque_bank" class="form-control form-control-sm" placeholder="Enter Bank name" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Cheque No.</label>
                                <div class="input-group">
                                    <input type="text" name="cheque_no" class="form-control form-control-sm" placeholder="Enter Cheque No." required>
                                </div>
                            </div>
                        </div>
    </div>`,

        "momo": `<div class='row'>
        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Mobile money Name</label>
                                <div class="input-group">
                                    <input type="text" name="momoName" class="form-control form-control-sm" placeholder="Enter mobile money name">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Mobile money number</label>
                                <div class="input-group">
                                    <input type="text" name="momoNo" class="form-control form-control-sm" placeholder="Enter mobile money No.">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Transaction ID</label>
                                <div class="input-group">
                                    <input type="text" name="transid" class="form-control form-control-sm" placeholder="Enter transaction ID No.">
                                </div>
                            </div>
                        </div>

        </div>`,
        "bank": `<div class='row'>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Bank Name</label>
                                <div class="input-group">
                                    <input type="text" name="bank_name" class="form-control form-control-sm" placeholder="Enter name of bank">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Branch</label>
                                <div class="input-group">
                                    <input type="text" name="branch" class="form-control form-control-sm" placeholder="Enter bank's branch">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Account Number</label>
                                <div class="input-group">
                                    <input type="text" name="accno" class="form-control form-control-sm" placeholder="Enter account number">
                                </div>
                            </div>
                        </div>

        </div>
            <div class='row'>
                <div class="col-sm-6">
                            <div class="form-group">
                                <label>Payer Name</label>
                                <div class="input-group">
                                    <input type="text" name="payer_name" class="form-control form-control-sm" placeholder="Enter payer's name">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Account Holder</label>
                                <div class="input-group">
                                    <input type="text" name="acc_holder" class="form-control form-control-sm" placeholder="Enter account holder's name">
                                </div>
                            </div>
                        </div>
            </div>
        
        `
    };

    function showRequiredInputs(whichInputs, output) {
        let out = document.getElementById(output);
        out.innerHTML = null;

        switch (whichInputs) {
            case 'cheque':
                out.innerHTML = requiredInputs.cheque;
                break;
            case 'bank':
                out.innerHTML = requiredInputs.bank;
                break;
            case 'momo':
                out.innerHTML = requiredInputs.momo
        }
    }

    const paymentType = document.getElementById('payment-type');

    $(paymentType).on("select2:select", function(e) {

        let selectedPaymentType = e.params.data.id;
        showRequiredInputs(selectedPaymentType, "required-inputs-output");
    });
</script>
