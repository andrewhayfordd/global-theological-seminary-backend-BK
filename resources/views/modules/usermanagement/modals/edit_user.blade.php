<div class="modal fade" id="edit-user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="edit-user-form">
                    @csrf
                    <div class="modal-body">

                        <div class="row">
                            <label for="">Email*</label>
                            <input type="text" class="form-control form-control-sm" name="userEmail" id="user-email"
                                required>
                        </div>
                        <div class="row">
                            <label for="">Phone*</label>
                            <input type="tel" class="form-control form-control-sm"
                                 name="userPhone" id="user-phone" required>
                        </div>
                        <div class="row">
                            <div class="col mt-3">
                                <div class="form-group">
                                    <label>User Type<span class="text-danger font-weight-bold">*</span></label>
                                    <select type="text" class="form-control form-control-sm select2"
                                        name="userType" id="user-type"    required>
                                          @foreach ($userType as $item)
                            <option value="{{ $item->usertype }}">{{ $item->usertype }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
            </div>

        </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
        <button type="reset" class="btn btn-light">Reset</button>
        <button class="btn btn-primary" form="edit-user-form" type="submit">Update</button>
    </div>
</div>
</div>
</div>
<script>
    
    $('#user-table').on('click', '.edit-btn', function () {
    var userdata = userTable.row($(this).parents('tr')).data();
    $('#user-email').val(userdata.Email);
    $('#user-phone').val(userdata.Phone);
    $("#user-type").val(userdata.userType).trigger("change");

    // Store the userId in a hidden input or directly in a variable
    $('#edit-user-form').data('userId', userdata.userId);

    $('#edit-user-modal').modal('show');
});

let editUserForm = document.forms['edit-user-form']
$("#edit-user-form").submit(function (e) {
    e.preventDefault();
    let formdata = new FormData(editUserForm);

    // Append the userId to the formdata
    formdata.append('userId', $('#edit-user-form').data('userId'));
    formdata.append('school_code', `${school_code}`);

    // Log data to the console for debugging
    console.log("Form data being sent: ", Object.fromEntries(formdata));

    swal.fire({
        title: "",
        text: "Are you sure you want to update this user?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Submit'
    }).then((result) => {
        if (result.value) {
            swal.fire({
                text: "Updating...",
                showConfirmButton: false,
                allowEscapeKey: false,
                allowOutsideClick: false
            });

            fetch(`${appUrl}/api/user/update`, {
                method: "post",
                body: formdata
            }).then(function (res) {
                return res.json();
            }).then(function (data) {
                if (!data.ok) {
                    swal.fire({
                        text: data.msg,
                        type: "error"
                    });
                    return;
                }

                swal.fire({
                    text: "User updated successfully",
                    type: "success"
                });

                $("#edit-user-modal").modal("hide");
                $("select").val(null).trigger('change');
                userTable.ajax.reload(false, null);
                editUserForm.reset();
            }).catch(function (err) {
                console.log(err);
                Swal.fire({
                    text: "Updating user failed",
                    type: "error"
                });
            });
        }
    });
});

</script>

