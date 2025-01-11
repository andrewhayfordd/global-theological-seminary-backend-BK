<div class="modal fade" id="add-user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width: 55%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="add-user-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <label for="">FirstName*</label>
                                <input type="text" class="form-control form-control-sm" name="userFname" required>
                            </div>
                            <div class="col">
                                <label for="">LastName*</label>
                                <input type="text" class="form-control form-control-sm" name="userLname" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <label for="">Email*</label>
                                <input type="text" class="form-control form-control-sm" name="userEmail" required>
                            </div>
                            <div class="col">
                                <label for="">Phone*</label>
                                <input type="tel" class="form-control form-control-sm" name="userPhone" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div class="form-group">
                                    <label>User Type<span class="text-danger font-weight-bold">*</span></label>
                                    <select type="text" class="form-control form-control-sm select2" name="userType"
                                        required>
                                        <option value="">--Select--</option>
                                        <option value="ADM">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Branch* </label>
                                    <select type="text" class="form-control select2" id="add-prog-duration"
                                        name="user_branch" required>
                                        @forelse ($branches as $item)
                                            <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}
                                            @empty
                                                <p>No data found</p>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row ">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Department* </label>
                                    <select type="text" class="form-control select2" id="add-prog-duration"
                                        name="user_department" required>
                                        @forelse ($departments as $item)
                                            <option value="{{ $item->dept_code }}">{{ $item->dept_desc }}
                                            @empty
                                                <p>No data found</p>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Program</label>
                                    <select type="text" class="form-control select2" id="add-prog-duration"
                                        name="user_program" required>
                                        @forelse ($programs as $item)
                                            <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}
                                            @empty
                                                <p>No data found</p>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div> --}}

                       
                        <div class="row mt-2">
                            <label for="">Upload profile picture</label>
                            <input type="file" class="form-control form-control-sm" name="userPic">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-light">Reset</button>
                <button class="btn btn-primary" form="add-user-form" type="submit">Save</button>
            </div>
        </div>
    </div>
</div>
<script>
    //Adding user
    let userForm = document.getElementById('add-user-form')

    $('#add-user-form').submit(function(e) {
        e.preventDefault()
        var formdata = new FormData(userForm)
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            title: 'Are you sure you want to add user?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Add'

        }).then(function(result) {
            if (result.value) {
                Swal.fire({
                    text: "Adding user please wait...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/user/add`, {
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
                        text: "User added  successfully",
                        type: "success"
                    });
                    $("#add-user-modal").modal('hide');
                    userTable.ajax.reload(false, null);
                    userForm.reset();
                }).catch(function(err) {
                    if (err) {

                        Swal.fire({
                            text: "Adding user failed"
                        });
                    }
                })
            }

        })
    })
</script> 
