<div class="modal fade" id="add-batch-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Batch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="add-batch-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <label for="">Month *</label>
                                  <select id="add-batch-month" class="form-control select2" name="month" required>
                                    <option value="">--Select--</option>
                                    <option value="JAN">January</option>
                                    <option value="FEB">February</option>
                                    <option value="MAR">March</option>
                                    <option value="APR">April</option>
                                    <option value="MAY">May</option>
                                    <option value="JUN">June</option>
                                    <option value="JUL">July</option>
                                    <option value="AUG">August</option>
                                    <option value="SEPT">September</option>
                                    <option value="OCT">October</option>
                                    <option value="NOV">November</option>
                                    <option value="DEC">December</option>
                                  </select>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col">
                                <label for="">Year *</label>
                                <select id="add-batch-year" class="form-control select2" name="year" required>
                                    <option value="">--Select--</option>
                                    <option value="21">2021</option>
                                    <option value="22">2022</option>
                                    <option value="23">2023</option>
                                    <option value="24">2024</option>
                                    <option value="25">2025</option>
                                    <option value="26">2026</option>
                                  </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button class="btn btn-primary" form="add-batch-form" type="submit">Save</button>
            </div>
        </div>
    </div>
</div>
<script>
      var addbatchForm = document.forms['add-batch-form']
        $('#add-batch-form').submit(function(e) {

            e.preventDefault()
            var formdata = new FormData(addbatchForm)
            formdata.append('school_code', `${school_code}`)

            Swal.fire({
                title: '',
                text: "Are you sure you want to add batch?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Submit'

            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        text: "Adding...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    fetch(`${appUrl}/api/batch/add`, {
                        method: "post",
                        body: formdata,
                    }).then(function(res) {
                        return res.json();
                    }).then(function(data) {
                        if (!data.ok) {
                            swal.fire({
                                text: data.msg,
                                type: "error"
                            });
                            return;
                        }
                        Swal.fire({
                            text: "Batch added successfully",
                            type: "success"
                        });
                        $("#add-batch-modal").modal("hide");
                        $("select").val(null).trigger('change');
                        batchTable.ajax.reload(false, null);
                        addbatchForm.reset();

                    }).catch(function(err) {
                        if (err) {

                            Swal.fire({
                                text: "adding batch failed",
                                type: "error"
                            });
                        }
                    })

                }
            })

        })
</script>