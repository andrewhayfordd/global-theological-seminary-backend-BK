<div class="modal fade" id="add-bill-amount-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" style="min-width: 65%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Programme Bill</h5>
            </div>
            <div class="modal-body">
                <form action="" id="program-bill-form">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Programme* </label>
                                <select type="text" class="form-control select2" id="add-bill-item-prog"
                                    name="program" required>
                                    <option value="">--Select--</option>
                                    @foreach ($programs as $item)
                                        <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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

                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Branch* </label>
                                <select type="text" class="form-control select2" id="add-bill-item-branch"
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
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Semester* </label>
                                <select type="text" class="form-control select2" id="add-bill-item-sem"
                                    name="semester" required>
                                    <option value="">--Select--</option>
                                    @foreach ($semester as $item)
                                        <option value="{{ $item->sem_code }}">{{ $item->sem_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <p><span id="info-bill-prog" class="text-info"></span> </p>
                    </div>
                    <div id="require-prog-bill-items">

                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="reset" class="btn btn-light">Reset</button>
                    <button class="btn btn-primary" form="program-bill-form" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var programBillForm = document.getElementById("program-bill-form")
    $(programBillForm).submit(function(e) {
        e.preventDefault();

        let billItems = Array.from(document.getElementsByClassName('bill-item'));

        // We wil store the bill items in this object and send them to PHP
        // as an array
        let itemsToSend = {};

        billItems.forEach(item => {
            itemsToSend[item.name] = item.value;
        });

        // After checking the required inputs are not empty and is valid the form is then submitted
        var formdata = new FormData(programBillForm)
        formdata.append('billItems', JSON.stringify(itemsToSend));
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            title: 'Are you sure you want to add bill to this programme?',
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
                fetch(`${appUrl}/api/bill/add_programme_bill`, {
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
                    $("#add-student-bill-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    programBillForm.reset();

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


    var infoBillLoader = document.getElementById("info-bill-prog");
    $("#add-bill-item-prog").on("select2:select", function(e) {
        let batchNo = e.params.data.id;
        infoBillLoader.innerText = null;
        infoBillLoader.innerText = "Loading bill items...";
        $.ajax({
            url: `${appUrl}/api/bill/fetch_prog_bill_items/${school_code}/${batchNo}`,
            type: 'GET',
        }).done(function(data) {
            let columns = [];

            let indiBillItemsHolder = document.getElementById('require-prog-bill-items');
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

            setTimeout(() => {
                infoBillLoader.innerText = null;
            }, 1000);
        });
    });

    // let programSelector = document.getElementById("add-bill-item-prog");
    // let semSelector = document.getElementById("add-bill-item-sem");
    // $("#add-bill-item-prog").on("select2:select", function(e) {
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
