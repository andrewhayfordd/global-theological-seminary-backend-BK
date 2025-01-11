<div class="modal fade" id="individual-bill-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" style="min-width: 60%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Individual Bill</h5>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="add-student-bill-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row ">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Student* </label>
                                    <select type="text" class="form-control select2" id="individual-student-bill"
                                        name="student_no" required>
                                        <option value="">--Select--</option>
                                        @forelse ($studentList as $item)
                                            <option value="{{ $item->student_no }}">{{ $item->lname }}
                                                {{ $item->mname }} {{ $item->fname }}</option>
                                        @empty
                                            <p>No data found</p>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Branch* </label>
                                    <select type="text" class="form-control select2" id="add-branch-bill"
                                        name="branch" required>
                                        @forelse ($branches as $item)
                                            <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}

                                            @empty
                                                <p>No data found</p>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Semester* </label>
                                    <select type="text" class="form-control select2" id="add-ind-sem-bill"
                                        name="semester" required>
                                        <option value="">--Select--</option>
                                        @foreach ($semester as $item)
                                            <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="require-individual-bill-items">


                        </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-light">Reset</button>
                <button class="btn btn-primary" form="add-student-bill-form" type="submit">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    var StudenBillForm = document.getElementById("add-student-bill-form")
    $(StudenBillForm).submit(function(e) {
        e.preventDefault();

        let billItems = Array.from(document.getElementsByClassName('bill-item'));

        // We wil store the bill items in this object and send them to PHP
        // as an array
        let itemsToSend = {};

        billItems.forEach(item => {
            itemsToSend[item.name] = item.value;
        });

        // After checking the required inputs are not empty and is valid the form is then submitted
        var formdata = new FormData(StudenBillForm)
        formdata.append('billItems', JSON.stringify(itemsToSend));
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            title: 'Are you sure you want to add bill to student?',
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
                fetch(`${appUrl}/api/bill/addStudentBill`, {
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
                        text: "Bill added  successfully",
                        type: "success"
                    });
                    $("#add-student-bill-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    studentBillTable.ajax.reload(false, null);
                    StudenBillForm.reset();

                }).catch(function(err) {
                    if (err) {
                        console.log(err);
                        Swal.fire({
                            text: "Adding bill item failed!"
                        });
                    }
                })
            }
        })
    })
    $("#acyear").val(new Date().getFullYear());

    //Deleting bills
    $("#student-bill-table").on("click", ".delete-btn", function() {
        let data = studentBillTable.row($(this).parents('tr')).data();

        Swal.fire({
            title: "Are you sure you want to delete this bill?",
            text: "Or you can click cancel to abort!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete"

        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    text: "Deleting please wait...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                $.ajax({
                    url: `${appUrl}/api/bill/destroyStudentBill/${data.billCode}/${data.studentCode}/${school_code}`,
                    type: "POST",
                }).done(function(data) {
                    if (!data.ok) {
                        Swal.fire({
                            text: data.msg,
                            type: "error"
                        });
                        return;
                    }
                    Swal.fire({
                        text: "Student bill deleted successfully",
                        type: "success"
                    });
                    studentBillTable.ajax.reload(false, null);

                }).fail(() => {
                    alert('Processing failed');
                })
            }
        })
    });

    let semSelectors = document.getElementById("add-ind-sem-bill");
    $("#individual-student-bill").on("select2:select", function(e) {
        let studentNo = e.params.data.id;

        $.ajax({
            url: `${appUrl}/api/bill/fetch_individual_bill_items/${school_code}/${studentNo}`,
            type: 'GET',
        }).done(function(data) {
            let columns = [];

            let indiBillItemsHolder = document.getElementById('require-individual-bill-items');
            indiBillItemsHolder.innerHTML = null;
            let row = document.createElement('div');
            row.classList.add('row');

            data.data.forEach(billItem => {

                let col = document.createElement('div');
                col.classList.add('col-sm-4');

                let formGroup = document.createElement('div');
                formGroup.classList.add('form-group');

                let label = document.createElement('label');
                label.innerText = billItem.bill_desc.toUpperCase();

                let input = document.createElement('input');
                input.setAttribute("required", "true");
                input.setAttribute("readonly", "true");
                input.name = billItem.bill_code;
                input.classList.add('form-control', 'bill-item', 'form-control-sm');
                input.type = "text";
                input.value = billItem.amount;

                formGroup.appendChild(label);
                formGroup.appendChild(input);

                col.appendChild(formGroup)
                row.appendChild(col);
                indiBillItemsHolder.appendChild(row);

            });

        });

        // $.ajax({
        //     url: `${appUrl}/api/bill/fetch_ind_prog_semester/${school_code}/${studentNo}`,
        //     type: 'GET',
        // }).done(function(data) {

        //     if (!data.ok) {
        //         Swal.fire({
        //             text: data.msg,
        //             type: "info"
        //         });
        //         return;
        //     }
        //     semSelectors.innerHTML = null;
        //     let sems = data.data;
        //     let option = document.createElement("option");
        //     option.value = sems.sem_code;
        //     option.innerText = sems.sem_desc;
        //     semSelectors.appendChild(option);
        // });

    });
</script>
