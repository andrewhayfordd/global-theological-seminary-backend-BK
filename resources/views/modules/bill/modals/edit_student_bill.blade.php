<div class="modal fade" id="edit-student-bill-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Student Bill Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="edit-student-bill-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row ">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Academic year* </label>
                                    <input name="acyear" id="edit_acyear" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Student* </label>
                                    <select type="text" class="form-control select2" id="edit_selected_student"
                                        name="selected_student" required>
                                        @forelse ($studentList as $item)
                                            <option value="{{ $item->student_no }}">{{ $item->lname }}
                                                {{ $item->mname }} {{ $item->fname }}</option>
                                        @empty
                                            <p>No data found</p>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Bill Item* </label>
                                    <select type="text" class="form-control select2" id="edit_selected_item"
                                        name="selected_item" required>
                                        @forelse ($billitem as $item)
                                            <option value="{{ $item->bill_code }}">{{ $item->bill_desc }}</option>
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
                                    <label>Bill Item Amount* </label>
                                    <input name="bill_item_amount" id="edit_bill_amount" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-light">Reset</button>
                <button class="btn btn-primary" form="edit-student-bill-form" type="submit">Submit</button>
            </div>
        </div>
    </div>
</div>


<script>
    let transId = ''
    let studentId = ''
    $("#student-bill-table").on("click", ".edit-btn", function() {
        let data = studentBillTable.row($(this).parents('tr')).data()
        $("#edit_acyear").val(data.acYear)
        $("#edit_acterm").val(data.acTerm).trigger("change")
        $("#selected_item").val(data.acTerm).trigger("change")
        $("#edit_bill_amount").val(data.studentAmount)
        $("#edit_selected_student").val(data.studentName).trigger("change")
        transId = data.billtransid
        studentId = data.studentCode
        $("#edit-student-bill-modal").modal("show")

    });
    let editFormData = document.forms['edit-student-bill-form']
    $(editFormData).submit(function(e) {
        e.preventDefault();

        //Appending data to form
        var formdata = new FormData(editFormData)
        formdata.append("school_code", school_code)
        formdata.append('transid', transId)
        formdata.append('studentcode', studentId)

        Swal.fire({
            title: "Are you sure you want to update this bill?",
            text: "Or you can click cancel to abort!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Update"

        }).then(function(result) {
            if (result.value) {
                Swal.fire({
                    text: "Updating please wait...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/bill/upateStudentBill`, {
                    method: "post",
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
                        text: "Student bill updated successfully!",
                        type: "success"
                    });
                    $("#edit-student-bill-modal").modal("hide")
                    $("select").val(null).trigger('change');
                    studentBillTable.ajax.reload(false, null)
                    editFormData.reset();

                }).catch(function(err) {
                    if (err) {
                       
                        Swal.fire({
                            text: "Updating student bill failed!"
                        });
                    }
                })
            }

        })

    })
   
</script>
