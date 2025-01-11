<div class="modal fade" id="add-staff-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Staff </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="add-staff-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">First name</label>
                                <input type="text" class="form-control" name="fname" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Middle name</label>
                                <input type="text" class="form-control" name="mname" id="add-department-name">
                            </div>
                        </div>
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Last name</label>
                                <input type="text" class="form-control" name="lname" id="add-department-name"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="">Gender</label>
                            <select class="form-control select2" name="gender" required>
                                <option selected value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="add-staff-email" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="email">Phone number</label>
                            <input type="tel" name="phone" id="add-staff-phone" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="Staff-type">Staff type</label>
                            <select id="add-staff-type" class="form-control" name="staff_type">
                                <option value="">--Select--</option>
                                <option value="AC">Academic</option>
                                <option value="NAC">Non-Academic</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="dob">Date of birth</label>
                            <input type="date" name="dob" id="add-staff-dob" class="form-control">
                        </div>
                        <div class="col">
                            <label for="dob">Postal address</label>
                            <input type="text" id="add-staff-post" class="form-control" name="postAddress">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="Residential-address">Residential address</label>
                            <input type="text" id="add-staff-address" class="form-control" name="resAddress">
                        </div>
                        <div class="col">
                            <label for="">Marital status</label>
                            <select class="form-select form-control" id="add-staff-status" name="marital_status">
                                <option selected value="Married">Married</option>
                                <option value="Single">Single</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="Profile-picture">Profile picture</label>
                            <input type="file" class="form-control" name="image">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" form="add-staff-form" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    //adding staffs
    let addStaffForm = document.forms['add-staff-form']
    $('#add-staff-form').submit(function(e) {
        e.preventDefault()
        let formdata = new FormData(addStaffForm);
        formdata.append('school_code', school_code);
        formdata.append('createuser', createuser)
        Swal.fire({
            title: '',
            text: 'Are you sure you want to add staff?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: 'Submit'
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    text: "Adding...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/staff/add`, {
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
                        text: "Staff added successfully",
                        type: "success"
                    });
                    $("#add-staff-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    staffTable.ajax.reload(false, null);
                    addStaffForm.reset();
                }).catch(function(err) {
                    if (err) {
                        Swal.fire({
                            text: "Adding staff failed",
                            type: "error"
                        });
                    }
                })
            }
        })
    })
</script>
