<div class="modal fade" id="edit-staff-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="edit-staff-form" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="id" id="edit-staff-transid" hidden required>
                    <!-- <input type="text" name="staffno" id="edit-staff" hidden required> -->

                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">First name</label>
                                <input type="text" class="form-control"  name="first_name" id="edit-staff-first_name">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Last name</label>
                                <input type="text" class="form-control"  name="last_name" id="edit-staff-last_name" >
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Middle name</label>
                                <input type="text" class="form-control"  name="Middle_name" id="edit-staff-Middle_name">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col">
                            <label for="email">Email</label>
                        <input type="email" name="email" id="edit-staff-email" class="form-control">
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="email">Phone number</label>
                        <input type="tel" name="phone_number" id="edit-staff-phone" class="form-control">
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="dob">Postal address</label>
                        <input type="text"  id="edit-staff-postal_address" class="form-control" name="postal_address">
                            
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col">
                            <label for="Residential-address">Residential address</label>
                        <input type="text"  id="edit-staff-Residential-address" class="form-control" name="residential_address">
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="Staff-type">Staff type</label>
                            <select id="edit-staff-Staff-type" class="form-control" name="staff_type">
                                <option value="">--Select--</option>
                                <option value="AC">Academic</option>
                                <option value="NAC">Non-Academic</option>
                            </select>
                        <!-- <input type="text"  id="Staff-type" class="form-control" name="staff_type"> -->
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="Profile-picture">Profile picture</label>
                        <input type="file"  id="Profile_picture" class="form-control" name="profile_pic">
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                               <label for="">Marital status</label>
                                <select class="form-select form-control" aria-label="Default select example" name="marital_status" id="edit-staff-marital_status">
                                    <option selected value="Married">Married</option>
                                    <option value="Single">Single</option>
                                   
                                  </select>
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                               <label for="">Gender</label>
                                <select class="form-select form-control" aria-label="Default select example" name="gender" id="edit-staff-gender">
                                    <option selected value="M">Male</option>
                                    <option value="F">Female</option>
                                   
                                  </select>
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="dob">Date of birth</label>
                        <input type="date" name="dob" id="edit-staff-dob" class="form-control" >
                            
                        </div>
                    </div>
                   
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" form="edit-staff-form" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>



<script>
    
    var updatestaffForm = document.getElementById("edit-staff-form")
    $(updatestaffForm).submit(function(e) {
        e.preventDefault();

        // After checking the required inputs are not empty and is valid the form is then submitted
        var formdata = new FormData(updatestaffForm)
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            title: 'Are you sure you want to update Staff?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit'

        }).then((result) => {

            if (result.value) {
                Swal.fire({
                    text: "Processing...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/staff/edit`, {
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
                        text: "Staff updated  successfully",
                        type: "success"
                    });
                    $("#edit-staff-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    staffTable.ajax.reload(false, null);
                    updatestaffForm.reset();

                }).catch(function(err) {
                    if (err) {
                        Swal.fire({
                            text: "Adding staff failed"
                        });
                    }
                })
            }
        })
    });
</script>
