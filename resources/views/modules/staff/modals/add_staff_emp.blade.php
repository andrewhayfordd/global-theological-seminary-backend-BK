<div class="modal fade" id="add-emp-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Staff Employment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-emp-form">
                    @csrf
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="">Staff *</label>
                                <select class="form-control select2" name="staff_id" id="add-emp-name" required>
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
                                <label for="">Empolyment Type *</label>
                                <select class="form-control select2" required name="type">
                                    <option value="">--Select--</option>
                                    @foreach ($emp as $item)
                                        <option value="{{ $item->emptype_code }}">{{ $item->emptype_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="">Date Of Emplyment </label>
                                <input type="date" class="form-control" name="date" id="add-emp-name">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="">Position </label>
                            <input type="text" name="position" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="">Department</label>
                                <select class="form-control select2" name="dept">
                                    <option value="">--Select--</option>
                                    @foreach ($dept as $item)
                                        <option value="{{ $item->dept_code }}">{{ $item->dept_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-light">Reset</button>
                <button type="submit" name="submit" form="add-emp-form" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    let addStaffEmp = document.forms['add-emp-form']
    $('#add-emp-form').submit(function(e) {
        e.preventDefault()
        let formdata = new FormData(addStaffEmp);
        formdata.append('school_code', school_code);
        formdata.append('createuser', createuser);
        Swal.fire({
            title: '',
            text: 'Are you sure you want to add staff employment details?',
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
                fetch(`${appUrl}/api/staff/add_emp`, {
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
                        text: "Staff employment added successfully",
                        type: "success"
                    });
                    $("#add-emp-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    empTable.ajax.reload(false, null);
                    addStaffEmp.reset();
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
