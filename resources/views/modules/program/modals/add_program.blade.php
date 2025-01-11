<div class="modal fade" id="add-program-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Program</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-program-form">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Program Code <span class="text-danger font-weight-bold">*</span></label>
                                <input type="text" placeholder="" name="prog_code" id="add-prog-code"
                                    class="form-control " required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Program Description <span class="text-danger font-weight-bold">*</span></label>
                                <input name="prog_desc" id="add-prog-desc" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label id="crc">Program Type <span class="text-danger font-weight-bold">*</span></label>
                                <select name="prog_type" id="add-prog-type" class="form-control select2" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($type as $item)
                                        <option value="{{ $item->prog_type_code }}">{{ $item->prog_type_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Program Duration <span class="text-danger font-weight-bold">*</span></label>
                                <select type="text" class="form-control select2" id="add-prog-duration"
                                    name="prog_duration" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($duration as $item)
                                        <option value="{{ $item->dur_code }}">{{ $item->dur_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- card ends -->
            <div class="modal-footer">
                <button class="btn btn-sm btn-light" type="close" data-dismiss="modal">Cancel</button>
                <button class="btn btn-sm btn-light" type="reset" id="personal-details-form-reset-btn">Reset</button>
                <button class="btn btn-sm btn-primary" form="add-program-form" type="submit"
                    name="submit">Save</button>
            </div>
        </div> <!-- modal body ends -->
    </div> <!-- modal content ends -->
</div> <!-- modal dialog ends -->

<script>
    var programForm = document.getElementById("add-program-form")
    $(programForm).submit(function(e) {
        e.preventDefault();

        // After checking the required inputs are not empty and is valid the form is then submitted
        var formdata = new FormData(programForm)
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            title: 'Are you sure you want to add program?',
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
                fetch(`${appUrl}/api/program/add`, {
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
                        text: "Program added  successfully",
                        type: "success"
                    });
                    $("#add-program-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    programTable.ajax.reload(false, null);
                    programForm.reset();

                }).catch(function(err) {
                    if (err) {
                        Swal.fire({
                            text: "Adding prgram failed"
                        });
                    }
                })
            }
        })
    });
</script>
