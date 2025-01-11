<div class="modal fade" id="add-payment-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Record Payment</h5>
                <button class="close text-white" type="" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <!-- modal body starts -->
                <div class="card shadow-none">
                    <!-- card start -->
                    <div class="card-header">
                        <ul class="nav nav-pills nav-justified" id="pills-tab" role="tablist">
                            {{-- <li class="nav-item">
                                <a class="nav-link active" id="pills-personal-details-tab" data-toggle="pill"
                                    href="#pills-personal-details" role="tab" aria-controls="pills-personal-details"
                                    aria-selected="true">Offline Payment</a>
                            </li> --}}
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="pills-academic-details-tab" href="#pills-academic-details"
                                    role="tab" aria-controls="pills-academic-details" aria-selected="false">Online
                                    Payment</a>
                            </li> --}}
                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- card body starts -->
                        <div class="tab-content" id="pills-tabContent">
                            {{-- OFFLINE PAYMENT TAB --}}
                            <div class="tab-pane fade show active" id="pills-personal-details" role="tabpanel"
                                aria-labelledby="pills-personal-details-tab">
                                <form id="add-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12" id="add-payment-feed"></div>
                                    </div>
                                    <div class="row">
                                        <p class="font-weight-bold ">
                                            Student Program : <span class="text-primary" id="stu-prog-desc"></span>
                                        </p>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="">Student <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select name="student" id="stu"
                                                    class="bill-selectors form-control form-control-sm select2"
                                                    required>
                                                    <option value="">--Select--</option>
                                                    @foreach ($students as $item)
                                                        <option value="{{ $item->student_no }}">
                                                            {{ $item->fname . ' ' . $item->mname . ' ' . $item->lname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="">Bill Semester <span
                                                    class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select disabled name="bill_semester" id="add-payment-bill-semester"
                                                    class="bill-selectors form-control form-control-sm select2"
                                                    required>
                                                    <option value="">--Select--</option>
                                                    @foreach ($semester as $item)
                                                        <option value="{{ $item->sem_code }}">
                                                            {{ $item->sem_desc }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col">
                                            <div class="d-flex justify-content-between">
                                                <label for="">Student Number <span
                                                        class="text-danger">*</span></label>
                                                <span id="loader-student" class="font-weight-bold text-secondary"
                                                    style="display: none">Loading...</span>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="student_no"
                                                    class="form-control form-control-sm" readonly>
                                            </div>
                                        </div> --}}
                                    </div>

                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="d-flex justify-content-between">
                                                <label for="userBooks">Semester Total Bill <span
                                                        class="text-danger">*</span></label> <span id="loader-bill"
                                                    class="font-weight-bold text-secondary"
                                                    style="display: none">Loading...</span>
                                            </div>
                                            <input name="bill" type="number" class="form-control form-control-sm "
                                                id="add-payment-bill" required readonly>
                                        </div>
                                        <div class="col">
                                            <div class="">
                                                <label for="userBooks">Payment Type <span
                                                        class="text-danger">*</span></label>
                                                <select name="payment_type" id="payment-type"
                                                    class="form-control form-control-sm select2" required>
                                                    <option value="">--Select--</option>
                                                    <option value="cheque">Cheque</option>
                                                    {{-- <option value="cash">Cash</option> --}}
                                                    <option value="momo">Mobile Money</option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                    <div id="required-inputs-output">

                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm">
                                            <div class="d-flex justify-content-between">
                                                <label for="section">Semester Balance <span
                                                        class="text-danger">*</span></label>
                                                <span id="loader-balance" class="font-weight-bold text-secondary"
                                                    style="display: none">Loading...</span>
                                            </div>
                                            <input name="balance" required type="number"
                                                class="form-control form-control-sm" readonly>
                                        </div>
                                        <div class="col-sm">
                                            <label for="">Semester Paid <span
                                                    class="text-danger">*</span></label><span id="loader-total-paid"
                                                class="font-weight-bold text-secondary"
                                                style="display: none">Loading...</span>
                                            <input type="text" class="form-control form-control-sm" name="totalPaid"
                                                required readonly>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm">
                                            <div class="d-flex justify-content-between">
                                                <label for="section">Overall Balance <span
                                                        class="text-danger">*</span></label>
                                            </div>
                                            <input name="arrears" required type="number"
                                                class="form-control form-control-sm" readonly>
                                        </div>
                                        <div class="col-sm">
                                            <label for="">Overall Paid <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="overallPaid" required readonly>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="section">Amount To Pay <span
                                                        class="text-danger">*</span></label>
                                                <input name="amtpaid" step="0.01" type="number"
                                                    class="form-control forn-control-sm" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="">Payment Semester <span
                                                    class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select name="semester" id="add-payment-semester"
                                                    class="form-control form-control-sm select2" required>
                                                    <option value="">--Select--</option>
                                                    @foreach ($semester as $item)
                                                        <option value="{{ $item->sem_code }}">
                                                            {{ $item->sem_desc }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="section">Payment Description</label>
                                                <textarea name="payment_desc" cols="3" rows="4" class="form-control forn-control-sm"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="section">Payment Date <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" name="payment_date"
                                                    class="form-control forn-control-sm" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-sm btn-primary" form="add-payment-form" type="submit"
                                            name="submit"> <i class=""></i> Pay</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- card ends -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-light" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-sm btn-light" form="add-payment-form" type="reset">Reset</button>
            </div>
        </div>
    </div>
</div>
<script>
    const paymentForm = document.forms['add-payment-form'];
    const loaderBill = document.getElementById("loader-bill");
    const loaderBalance = document.getElementById("loader-balance");
    const loaderTotalPaid = document.getElementById("loader-total-paid");
    const loaderStudent = document.getElementById("loader-student");

    $(paymentForm).submit(function(e) {
        e.preventDefault();
        var studentName = e.target.student.options[e.target.student.selectedIndex].text;

        if (paymentForm.bill_semester.value !== paymentForm.semester.value) {
            loaderBill.style.display = "block";
            paymentFeeds.innerHTML =
                `<p class='alert alert-danger p-1'>Both payment semester and billing semester must be of the same value</p>`;
            setTimeout(() => {
                paymentFeeds.innerHTML = null;
                loaderBill.style.display = "none";
            }, 5000);
            return;
        }
        // After checking the required inputs are not empty and is valid the form is then submitted
        var formdata = new FormData(paymentForm)
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        formdata.append("studentName", studentName);
        Swal.fire({
            title: 'Are you sure you want to record payment?',
            text: "Or click cancel to abort!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit'

        }).then((result) => {

            if (result.value) {
                Swal.fire({
                    text: "Recording payment please wait, this may take a while",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/payment/store`, {
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
                        text: "Payment recorded  successfully",
                        type: "success"
                    });
                    $("#add-payment-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    // paymentTable.ajax.reload(false, null);
                    // paymentTable.ajax.url(`${appUrl}/api/payment/fetch_payment/${school_code}`)
                    //     .load();
                    paymentForm.reset();
                    // localStorage.setItem('paymentReceipt', JSON.stringify(data.data));
                    // window.open(`${appUrl}/modules/payment/modals/receipt`, '_blank');

                }).catch(function(err) {
                    if (err) {
                        Swal.fire({
                            type: "error",
                            text: "Processing failed",
                        });
                    }
                })
            }
        })
    });

    let stuSelector = document.getElementById('stu');
    let semSelector = document.getElementById("add-payment-bill-semester");
    // let selectors = document.getElementsByClassName("bill-selectors");
    var balance = document.getElementById('add-payment-balance');
    var bill = document.getElementById('add-payment-bill');
    const paymentFeeds = document.getElementById("add-payment-feed");
    var selectedStudentEvent = null;
    // const selectedSemesterEvent;

    $(stuSelector).on("select2:select", function(e) {
        semSelector.removeAttribute("disabled");
        selectedStudentEvent = e.params.data.id;

        $.ajax({
            url: `${appUrl}/api/payment/fetch_student`,
            type: "POST",
            data: {
                'student_no': selectedStudentEvent,
                'school_code': school_code,
            },
        }).done(function(data) {
            document.getElementById("stu-prog-desc").innerText = null;
            document.getElementById("stu-prog-desc").innerText = data.data.prog_desc;

        });
    });

    //When a student is selected these api calls are called
    $(semSelector).on("select2:select", function(e) {

        let selectedStu = e.params.data.id;

        loaderBalance.style.display = "block";
        // loaderStudent.style.display = "block";
        loaderBill.style.display = "block";

        //This ajax call is to fetch students' current bill
        $.ajax({
            url: `${appUrl}/api/payment/fetch_student_bill`,
            type: "POST",
            data: {
                'student_no': selectedStudentEvent,
                'semester': selectedStu,
                'school_code': school_code,
            },
        }).done(function(data) {
            if (!data.ok) {
                paymentFeeds.innerHTML =
                    `<p class='alert alert-danger p-1'>${data.msg}</p>`;
                setTimeout(() => {
                    paymentFeeds.innerHTML = null;
                    paymentForm.reset();
                    $("select").val(null).trigger('change');
                    loaderBalance.style.display = "none";
                    // loaderStudent.style.display = "none";
                    loaderBill.style.display = "none";
                }, 5000);
                return;
            }
            loaderBill.style.display = "none";
            paymentForm.bill.value = null;
            paymentForm.bill.value = data.data.total_bill;
        });

        //Api call to fetch students details
        // $.ajax({
        //     url: `${appUrl}/api/payment/fetch_student`,
        //     type: "POST",
        //     data: {
        //         'student_no': selectedStu,
        //         'school_code': school_code,
        //     },
        // }).done(function(data) {
        //     if (!data.ok) {
        //         paymentFeeds.innerHTML =
        //             `<p class='alert alert-danger p-1'>${data.msg}</p>`;
        //         setTimeout(() => {
        //             paymentForm.reset();
        //             $("select").val(null).trigger('change');
        //             paymentFeeds.innerHTML = null;
        //         }, 3000);
        //         return;
        //     }
        //     loaderStudent.style.display = "none";
        //     paymentForm.student_no.value = null;
        //     paymentForm.student_no.value = data.data.student_no;

        // });

        //This is to fetch the selected student balance
        paymentForm.balance.value = null;
        $.ajax({
            url: `${appUrl}/api/payment/fetch_student_balance`,
            type: "POST",
            data: {
                'student_no': selectedStudentEvent,
                'semester': selectedStu,
                'school_code': school_code,
            },
        }).done(function(data) {
            if (!data.ok) {
                paymentFeeds.innerHTML =
                    `<p class='alert alert-danger p-1'>${data.msg}</p>`;
                setTimeout(() => {
                    paymentFeeds.innerHTML = null;
                    paymentForm.reset();
                    $("select").val(null).trigger('change');
                    loaderBalance.style.display = "none";
                    // loaderStudent.style.display = "none";
                    loaderBill.style.display = "none";
                }, 5000);
                return;
            }
            if (data.data === null) {
                paymentForm.balance.value = null;
                paymentForm.balance.value = "0";
                loaderBalance.style.display = "none";

            } else {
                paymentForm.balance.value = null;
                paymentForm.balance.value = data.data;
                loaderBalance.style.display = "none";
            }

        });

        paymentForm.arrears.value = null;
        $.ajax({
            url: `${appUrl}/api/payment/fetch_student_arrears`,
            type: "POST",
            data: {
                'student_no': selectedStudentEvent,
                'semester': selectedStu,
                'school_code': school_code,
            },
        }).done(function(data) {
            if (!data.ok) {
                paymentFeeds.innerHTML =
                    `<p class='alert alert-danger p-1'>${data.msg}</p>`;
                setTimeout(() => {
                    paymentFeeds.innerHTML = null;
                    paymentForm.reset();
                    $("select").val(null).trigger('change');
                    loaderBalance.style.display = "none";
                    // loaderStudent.style.display = "none";
                    loaderBill.style.display = "none";
                }, 5000);
                return;
            }
            if (data.data === null) {
                paymentForm.arrears.value = null;
                paymentForm.arrears.value = "0";
                // loaderBalance.style.display = "none";

            } else {
                paymentForm.arrears.value = null;
                paymentForm.arrears.value = data.data;
                // loaderBalance.style.display = "none";
            }

        });


        //This is to fetch the selected student total payment that academic year and term
        paymentForm.totalPaid.value = null;
        $.ajax({
            url: `${appUrl}/api/payment/fetch_student_total_paid`,
            type: "POST",
            data: {
                'student_no': selectedStudentEvent,
                'semester': selectedStu,
                'school_code': school_code,
            },
        }).done(function(data) {
            if (!data.ok) {
                paymentFeeds.innerHTML =
                    `<p class='alert alert-danger p-1'>${data.msg}</p>`;
                setTimeout(() => {
                    paymentFeeds.innerHTML = null;
                    paymentForm.reset();
                    $("select").val(null).trigger('change');
                    loaderBalance.style.display = "none";
                    // loaderStudent.style.display = "none";
                    loaderBill.style.display = "none";
                }, 5000);
                return;
            }
            if (data.data === null) {
                paymentForm.totalPaid.value = null;
                paymentForm.totalPaid.value = "0";
                loaderTotalPaid.style.display = "none";

            } else {
                paymentForm.totalPaid.value = null;
                paymentForm.totalPaid.value = data.data.total_paid;
                loaderTotalPaid.style.display = "none";
            }

        });

        paymentForm.overallPaid.value = null;
        $.ajax({
            url: `${appUrl}/api/payment/fetch_student_overall_paid`,
            type: "POST",
            data: {
                'student_no': selectedStudentEvent,
                'semester': selectedStu,
                'school_code': school_code,
            },
        }).done(function(data) {
            if (!data.ok) {
                paymentFeeds.innerHTML =
                    `<p class='alert alert-danger p-1'>${data.msg}</p>`;
                setTimeout(() => {
                    paymentFeeds.innerHTML = null;
                    paymentForm.reset();
                    $("select").val(null).trigger('change');
                    loaderBalance.style.display = "none";
                    // loaderStudent.style.display = "none";
                    loaderBill.style.display = "none";
                }, 5000);
                return;
            }
            if (data.data === null) {
                paymentForm.overallPaid.value = null;
                paymentForm.overallPaid.value = "0";
                // loaderTotalPaid.style.display = "none";

            } else {
                paymentForm.overallPaid.value = null;
                paymentForm.overallPaid.value = data.data;
                // loaderTotalPaid.style.display = "none";
            }
        });
    });

    /**
     *This is to populate required inputs for payment type
     */
    let requiredInputs = {
        "cheque": `<div class='row'>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Cheque Bank</label>
                                <div class="input-group">
                                    <input type="text" name="cheque_bank" class="form-control form-control-sm" placeholder="Enter Bank name" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Cheque No.</label>
                                <div class="input-group">
                                    <input type="text" name="cheque_no" class="form-control form-control-sm" placeholder="Enter Cheque No." required>
                                </div>
                            </div>
                        </div>
    </div>`,

        "momo": `<div class='row'>
        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Mobile money Name</label>
                                <div class="input-group">
                                    <input type="text" name="momoName" class="form-control form-control-sm" placeholder="Enter mobile money name">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Mobile money number</label>
                                <div class="input-group">
                                    <input type="text" name="momoNo" class="form-control form-control-sm" placeholder="Enter mobile money No.">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Transaction ID</label>
                                <div class="input-group">
                                    <input type="text" name="momoTransid" class="form-control form-control-sm" placeholder="Enter transaction ID No.">
                                </div>
                            </div>
                        </div>

        </div>`
    };

    function showRequiredInputs(whichInputs, output) {
        let out = document.getElementById(output);
        out.innerHTML = null;

        switch (whichInputs) {
            case 'cheque':
                out.innerHTML = requiredInputs.cheque;
                break;
            case 'momo':
                out.innerHTML = requiredInputs.momo
        }
    }

    const paymentType = document.getElementById('payment-type');

    $(paymentType).on("select2:select", function(e) {

        let selectedPaymentType = e.params.data.id;
        showRequiredInputs(selectedPaymentType, "required-inputs-output");
    });






    /**
     * Script for online payment
     */
    // const onlinePaymentForm = document.forms['add-online-payment-form'];
    // const onlineloaderBill = document.getElementById("loader-online-bill");
    // const onlineloaderBalance = document.getElementById("loader-online-balance");
    // const onlineloaderTotalPaid = document.getElementById("loader-online-total-paid");
    // const onlineloaderStudent = document.getElementById("loader-online-student");

    // $(onlinePaymentForm).submit(function(e) {
    //     e.preventDefault();
    //     let studentName = e.target.student.options[e.target.student.selectedIndex].text;

    //     // After checking the required inputs are not empty and is valid the form is then submitted
    //     let formdata = new FormData(onlinePaymentForm)
    //     formdata.append("createuser", createuser);
    //     formdata.append("school_code", school_code);
    //     formdata.append("studentName", studentName);
    //     Swal.fire({
    //         title: 'Are you sure you want to pay fees online?',
    //         text: "Or click cancel to abort!",
    //         type: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Submit'

    //     }).then((result) => {

    //         if (result.value) {
    //             Swal.fire({
    //                 text: "Recording payment please wait...",
    //                 showConfirmButton: false,
    //                 allowEscapeKey: false,
    //                 allowOutsideClick: false
    //             });
    //             fetch(`${appUrl}/api/payment/online_payment`, {
    //                 method: "POST",
    //                 body: formdata,
    //                 headers: {
    //                     "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
    //                 }
    //             }).then(function(res) {
    //                 return res.json()
    //             }).then(function(data) {

    //                 if (!data.ok) {
    //                     Swal.fire({
    //                         text: data.msg,
    //                         type: "error"
    //                     });
    //                     return;
    //                 }
    //                 onlinePaymentForm.reset();

    //                 $("#add-payment-modal").modal('hide');
    //                 $("select").val(null).trigger('change');

    //                 Swal.fire({
    //                     text: "Loading payment portal please wait...",
    //                     showConfirmButton: false,
    //                     allowEscapeKey: false,
    //                     allowOutsideClick: false,
    //                     timer: 2800,
    //                 });

    //                 setTimeout(() => {
    //                     window.open(`${data.data.data.authorization_url}`, '_blank');
    //                 }, 2000)

    //             }).catch(function(err) {
    //                 if (err) {
    //                     Swal.fire({
    //                         type: "error",
    //                         text: "Processing failed",
    //                     });
    //                 }
    //             })
    //         }
    //     })
    // });
</script>
