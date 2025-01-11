<div class="modal fade" id="add-qual-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Staff Qualification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-qual-form">
                    @csrf
                    <input type="text" name="staff_id" id="add-qual-id" hidden required>
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
                                <input type="text" class="form-control" name="qual_desc" id="add-qual-name">
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
                <button type="submit" name="submit" form="add-qual-form" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    let addQualForm = document.forms['add-qual-form']
    $('#add-qual-form').submit(function(e) {
        e.preventDefault()
        let formdata = new FormData(addQualForm);
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
                    $("#add-qual-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    staffTable.ajax.reload(false, null);
                    addQualForm.reset();
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
