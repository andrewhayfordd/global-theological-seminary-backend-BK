<div class="modal fade" id="add-con-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Staff Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-con-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="">Staff *</label>
                                <select class="form-control select2" name="staff_id" id="add-con-name" required>
                                    <option value="">--Select--</option>
                                    @foreach ($staff as $item)
                                        <option value="{{ $item->staffno }}">{{ $item->fname }} {{ $item->lname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="">
                                <label for="">Relation *</label>
                                <select class="form-control select2" required name="relation">
                                    <option value="">--Select--</option>
                                    @foreach ($rel as $item)
                                        <option value="{{ $item->rel_code }}">{{ $item->rel_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="">
                                <label for="">Name *</label>
                                <input type="text" class="form-control" name="name" id="add-con-name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="email">Email </label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="col">
                            <label for="email">Phone Number</label>
                            <input type="tel" name="phone" id="phone" class="form-control">

                        </div>
                        <div class="col">
                            <label for="dob">Date of birth</label>
                            <input type="date" name="dob" id="dob" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="email">Residential Address</label>
                            <input type="text" name="res_address" id="email" class="form-control">
                        </div>
                        <div class="col">
                            <label for="email">land Mark</label>
                            <input type="text" name="land_mark" id="phone" class="form-control">

                        </div>
                        <div class="col">
                            <label for="dob">GPS Code</label>
                            <input type="text" name="gps" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="email">Guarantor's Form</label>
                            <input type="file" name="image" id="file" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-light">Reset</button>
                <button type="submit" name="submit" form="add-con-form" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    let addConForm = document.forms['add-con-form']
    $('#add-con-form').submit(function(e) {
        e.preventDefault()
        let formdata = new FormData(addConForm);
        formdata.append('school_code', school_code);
        formdata.append('createuser', createuser)
        Swal.fire({
            title: '',
            text: 'Are you sure you want to add staff contact?',
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
                fetch(`${appUrl}/api/staff/add_contact`, {
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
                        text: "Contact added successfully",
                        type: "success"
                    });
                    $("#add-con-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    staffTable.ajax.reload(false, null);
                    contactTable.ajax.reload(false, null);
                    addConForm.reset();
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
