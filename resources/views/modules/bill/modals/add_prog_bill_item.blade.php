<div class="modal fade" id="add-bill-prog-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" style="min-width: 45%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Programme Bill Item</h5>
            </div>
            <div class="modal-body">
                <form action="" id="add-bill-prog-form">
                    <div class="row">
                        {{-- <div class="col-sm">
                            <div class="form-group">
                                <label>Student </label>
                                <select type="text" class="form-control select2" id="add-individual-student-bill"
                                    name="student">
                                    <option value="">--Select--</option>
                                    @forelse ($studentList as $item)
                                        <option value="{{ $item->student_no }}">{{ $item->lname }}
                                            {{ $item->mname }} {{ $item->fname }}</option>
                                    @empty
                                        <p>No data found</p>
                                    @endforelse
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Programme</label>
                                <select type="text" class="form-control select2" id="add-bill-prog-prog" name="program">
                                    <option value="">--Select--</option>
                                    @foreach ($programs as $item)
                                        <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Batch* </label>
                                <select type="text" class="form-control select2" name="batch" required
                                    id="add-prog-bill-batch">
                                    <option value="">--Select--</option>
                                    @forelse ($batches as $item)
                                        <option value="{{ $item->batch_code }}">{{ $item->batch_desc }}
                                        @empty
                                            <p>No data found</p>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Branch* </label>
                                <select type="text" class="form-control select2" id="add-bill-prog-item-branch"
                                    name="branch" required>
                                    <option value="">--Select--</option>
                                    @forelse ($branches as $item)
                                        <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}
                                        @empty
                                            <p>No data found</p>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Semester* </label>
                                <select type="text" class="form-control select2" id="add-bill-prog-item-sem"
                                    name="semester" required>
                                    <option value="">--Select--</option>
                                    @foreach ($semester as $item)
                                        <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Bill Items* </label>
                                <select type="text" class="form-control select2" id="add-bill-prog-item" name="item"
                                    required>
                                    <option value="">--Select--</option>
                                    @foreach ($billItem as $item)
                                        <option value="{{ $item->bill_code }}">{{ $item->bill_desc }} - ({{$item->amount}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="reset" class="btn btn-light">Reset</button>
                    <button class="btn btn-primary" form="add-bill-prog-form" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var addBillProgForm = document.getElementById("add-bill-prog-form")
    $(addBillProgForm).submit(function(e) {
        e.preventDefault();

        // After checking the required inputs are not empty and is valid the form is then submitted
        var formdata = new FormData(addBillProgForm)
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            title: 'Are you sure you want to add bill?',
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
                fetch(`${appUrl}/api/bill/add_prog_bill`, {
                    method: "POST",
                    body: formdata,
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
                        text: "Bills added  successfully",
                        type: "success"
                    });
                    $("#add-bill-prog-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    addBillProgForm.reset();

                }).catch(function(err) {
                    if (err) {
                        Swal.fire({
                            type: "error",
                            text: "Adding bill item failed!"
                        });
                    }
                })
            }
        })
    })

    // let programSelector = document.getElementById("add-bill-prog-item-prog");
    // let semSelector = document.getElementById("add-bill-prog-item-sem");
    // $("#add-bill-prog-item-prog").on("select2:select", function(e) {
    //     let progCode = e.params.data.id;
    //     $.ajax({
    //         url: `${appUrl}/api/bill/fetch_prog_semester/${school_code}/${progCode}`,
    //         type: 'GET',
    //     }).done(function(data) {
    //         if (!data.ok) {
    //             Swal.fire({
    //                 text: data.msg,
    //                 type: "info"
    //             });
    //             return;
    //         }
    //         semSelector.innerHTML = null;
    //         let semesters = data.data;
    //         let option = document.createElement("option");
    //         option.value = semesters.sem_code;
    //         option.innerText = semesters.sem_desc;
    //         semSelector.appendChild(option);
    //     });
    // });
</script>
