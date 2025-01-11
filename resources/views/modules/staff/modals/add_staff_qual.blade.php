<div class="modal fade" id="add-staff-qual-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Staff Qualification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-staff-qual-form">
                    @csrf
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="">Staff *</label>
                                <select class="form-control select2" name="staff_id" id="add-staff-qual-name" required>
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
                                <label for="">Qualification *</label>
                                <select class="form-control select2" required name="qual">
                                    <option value="">--Select--</option>
                                    @foreach ($qual as $item)
                                        <option value="{{ $item->qual_code }}">{{ $item->qual_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="">Qualification Description </label>
                                <input type="text" class="form-control" name="qual_desc" id="add-staff-qual-name">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="">Institution *</label>
                            <input type="text" name="institution" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="">Completion Year *</label>
                            <input type="text" name="comp_year" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-light">Reset</button>
                <button type="submit" name="submit" form="add-staff-qual-form" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    let addStaffQualForm = document.forms['add-staff-qual-form']
    $('#add-staff-qual-form').submit(function(e) {
        e.preventDefault()
        let formdata = new FormData(addStaffQualForm);
        formdata.append('school_code', school_code);
        formdata.append('createuser', createuser);
        Swal.fire({
            title: '',
            text: 'Are you sure you want to add staff qualification?',
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
                fetch(`${appUrl}/api/staff/add_qual`, {
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
                        text: "Qualification added successfully",
                        type: "success"
                    });
                    $("#add-staff-qual-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    qualTable.ajax.reload(false, null);
                    addStaffQualForm.reset();
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
