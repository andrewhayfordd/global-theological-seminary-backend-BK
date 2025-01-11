<div class="modal fade" id="add-supplier-member-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Supplier Member</h5>
                <button class="close" type="" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-supplier-member-form">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">First Name <span
                                        class="font-weight-bold text-danger">*</span></label>
                                <input type="text" required name="fname" id="add-supplier-member-fname"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Last Name <span
                                        class="font-weight-bold text-danger">*</span></label>
                                <input type="text" required name="lname" id="add-supplier-member-lname"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Phone <span class="font-weight-bold text-danger">*</span></label>
                                <input type="text" required name="phone" id="add-supplier-member-phone"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Supplier <span
                                        class="font-weight-bold text-danger">*</span></label>
                                <select name="supplier_code" required id="add-supplier-member-supplier"
                                    class="form-control form-control-sm">
                                    <option value="">--Select--</option>
                                    @foreach ($suppliers as $item)
                                        <option value="{{ $item->supplier_code }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Position</label>

                                <select name="position_code" id="add-supplier-member-position"
                                    class="form-control form-control-sm">
                                    <option value="">--Select--</option>
                                    @foreach ($positions as $item)
                                        <option value="{{ $item->pos_code }}">{{ $item->pos_desc }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm" form="add-supplier-member-form" type="submit"
                        name="submit">
                        Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#add-supplier-member-form").on('submit', function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        let addSupplierMemberForm = document.getElementById('add-supplier-member-form');
        var formdata = new FormData(addSupplierMemberForm);
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            // title: 'Do you want to edit this student assessment?',
            text: "Do you want to add member?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continue',
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    text: "please wait...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/supplier-member/create`, {
                    method: "POST",
                    body: formdata,
                    headers: {
                        "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
                    }
                }).then(function(res) {
                    return res.json();
                }).then(function(data) {
                    if (!data.ok) {
                        Swal.fire({
                            title: data.msg,
                            text: data.errors_all.join(' and '),
                            type: "error"
                        });
                        return;
                    }
                    Swal.fire({
                        text: data.msg,
                        type: "success"
                    });
                    $("#add-supplier-member-modal").modal('hide');
                    SupplierMemberTable.ajax.reload(false, null);
                    addSupplierMemberForm.reset();
                }).catch(function(err) {
                    if (err) {
                        console.log(err);
                        Swal.fire({
                            type: "error",
                            text: "Adding supplier member details failed, Try again later"
                        });
                    }
                })
            }
        })
    });
</script>
