<div class="modal fade" id="notice-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white" id="exampleModalLabel">Send News Feed To Your Students</h5>
                <button class="close text-white" type="" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="notice-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="section">News Type*</label>
                                <select name="type" class="form-control form-control-sm select2" required="">
                                    <option value="">--Select--</option>
                                    @foreach ($notice as $item)
                                        <option value="{{ $item->type_code }}">{{ $item->type_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="section">Recipient*</label>
                                <select name="course_recipient" class="form-control form-control-sm select2" required>
                                    <option value="">--Select--</option>
                                    <option value="ALL">ALL STUDENTS</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->course_code }}">{{ $course->course_title }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Title*</label>
                                <input type="text" name="title" class="form-control form-control-sm" required>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Details*</label>
                                <div class="">
                                    <textarea name="details" class="form-control form-control-sm" placeholder="Write your message." cols="20"
                                        rows="7" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Start Date*</label>
                                <input type="date" name="date_start" class="form-control form-control-sm" required>
                            </div>

                        </div>
                        <div class="col">
                            <label for="">End Date*</label>
                            <input type="date" name="date_end" class="form-control form-control-sm" required>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Posted By</label>
                                <input type="text" name="posted_by" class="form-control form-control-sm">
                            </div>

                        </div>
                        <div class="col">
                            <label for="">Image</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" name="fileToUpload"
                                        id="inputGroupFileAddon01">Upload</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="fileToUpload" class="custom-file-input"
                                        id="fileToUpload" aria-describedby="inputGroupFileAddon01">
                                    <label class="custom-file-label" for="inputGroupFile01"></label>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-sm" type="reset">Reset</button>
                <button class="btn btn-primary btn-sm" form="notice-form" type="submit" name="submit">Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    var noticeForm = document.getElementById("notice-form")
    $(noticeForm).submit(function(e) {
        e.preventDefault();

        // After checking the required inputs are not empty and is valid the form is then submitted
        var formdata = new FormData(noticeForm)
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            title: 'Are you sure you want to post notice?',
            text: "Or click cancel to abort!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit'

        }).then((result) => {

            if (result.value) {
                Swal.fire({
                    text: "Posting please wait...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/notice/add_notice`, {
                    method: "POST",
                    body: formdata,
                    headers: {
                        "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
                    }
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
                        text: "Notice posted  successfully",
                        type: "success"
                    });
                    $("#notice-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    currNoticeTable.ajax.reload(false, null);
                    prevNoticeTable.ajax.reload(false, null);
                    allNoticeTable.ajax.reload(false, null);
                    noticeForm.reset();

                }).catch(function(err) {
                    if (err) {
                        Swal.fire({
                            text: "Process failed"
                        });
                    }
                })
            }
        })
    });
</script>
