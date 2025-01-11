<div class="modal fade" id="add-acc-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Staff Account Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-acc-form">
                    @csrf
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="">Staff *</label>
                                <select class="form-control select2" name="staff_id" id="add-acc-name" required>
                                    <option value="">--Select--</option>
                                    @foreach ($staff as $item)
                                        <option value="{{ $item->staffno }}">{{ $item->fname }} {{ $item->lname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="">Bank</label>
                                <select class="form-control select2" name="bank">
                                    <option value="">--Select--</option>
                                    @foreach ($bank as $item)
                                        <option value="{{ $item->bank_code }}">{{ $item->bank_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="">Account Type *</label>
                                <select class="form-control select2" required name="type">
                                    <option value="">--Select--</option>
                                    @foreach ($acc as $item)
                                        <option value="{{ $item->account_code }}">{{ $item->account_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="">Account Number </label>
                                <input type="text" class="form-control" name="account_no" id="add-acc-name">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="">Branch </label>
                            <input type="text" name="branch" class="form-control">
                        </div>
                    </div>
                   
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-light">Reset</button>
                <button type="submit" name="submit" form="add-acc-form" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    let addStaffAcc = document.forms['add-acc-form']
    $('#add-acc-form').submit(function(e) {
        e.preventDefault()
        let formdata = new FormData(addStaffAcc);
        formdata.append('school_code', school_code);
        formdata.append('createuser', createuser);
        Swal.fire({
            title: '',
            text: 'Are you sure you want to add staff account details?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: 'Submit'
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    text: "Processing...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/staff/add_acc`, {
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
                        text: "Staff account details added successfully",
                        type: "success"
                    });
                    $("#add-acc-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    accTable.ajax.reload(false, null);
                    addStaffAcc.reset();
                }).catch(function(err) {
                    if (err) {
                        Swal.fire({
                            text: "Adding contact failed",
                            type: "error"
                        });
                    }
                })
            }
        })
    })
</script>
