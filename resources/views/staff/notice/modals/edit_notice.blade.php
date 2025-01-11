<div class="modal fade" id="edit-notice-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white" id="exampleModalLabel">Update Notice</h5>
                <a href="#"><span class="close text-white" data-dismiss="modal" aria-label="Close"
                        aria-hidden="false">×</span></a>
            </div>
            <div class="modal-body">
                <form id="edit-notice-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="text" name="transid" id="edit-transid" hidden required>
                        <div class="col">
                            <div class="form-group">
                                <label for="section">News Type</label>
                                <select name="type" id="edit-notice-type" class="form-control form-control-sm select2" required="">
                                    <option value="">--Select--</option>
                                    @foreach ($notice as $item)
                                    <option value="{{$item->type_code}}">{{$item->type_desc}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="section">Recipient</label>
                                <select name="recipient" id="edit-notice-recipient" class="form-control form-control-sm select2" required="">
                                    <option value="">--Select--</option>
                                    @foreach ($recipient as $item)
                                    <option value="{{$item->recipient_code}}">{{$item->recipient_desc}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" id="edit-notice-title" name="title" class="form-control form-control-sm" required>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Details</label>
                                <div class="">
                                    <textarea name="details" id="edit-notice-details" class="form-control form-control-sm"
                                        placeholder="Write your message." cols="20" rows="7" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Start Date</label>
                                <input type="date" name="date_start" id="edit-notice-start_date" class="form-control form-control-sm" required>
                            </div>

                        </div>
                        <div class="col">
                            <label for="">End Date</label>
                            <input type="date" name="date_end" id="edit-notice-end_date" class="form-control form-control-sm" required>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Posted By</label>
                                <input type="text" name="posted_by" id="edit-notice-post" class="form-control form-control-sm">
                            </div>

                        </div>
                        <div class="col">
                            <label for="">Image</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" name="fileToUpload"
                                        id="inputGroupFileAddon1">Upload</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="fileToUpload" class="custom-file-input" id="input-file"
                                        aria-describedby="inputGroupFileAddon01">
                                    <label class="custom-file-label" for="inputGroupFile01"></label>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-sm" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-sm" form="edit-notice-form" type="submit" name="submit"> <i class=""></i>
                    Update</button>
            </div>
        </div>
    </div>
</div>
<script>
     // Add the following code if you want the name of the file appear on select
     $(".custom-file-input").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    const editNoticeForm = document.forms["edit-notice-form"];
    $(editNoticeForm).submit(function (e) {
        e.preventDefault();


        // After checking the required inputs are not empty and is valid the form is then submitted
        var formdata = new FormData(editNoticeForm)
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            title: 'Are you sure you want to update notice details?',
            text: "Or click cancel to abort!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit'

        }).then((result) => {

            if (result.value) {
                Swal.fire({
                    text: "Updating please wait...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/notice/update`, {
                    method: "POST",
                    body: formdata,
                    headers: {
                        "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
                    }
                }).then(function (res) {
                    return res.json()
                }).then(function (data) {
                    if (!data.ok) {
                        Swal.fire({
                            text: data.msg,
                            type: "error"
                        });
                        return;
                    }
                    Swal.fire({
                        text: "Notice updated  successfully",
                        type: "success"
                    });
                    $("#edit-notice-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    currNoticeTable.ajax.reload(false, null);
                    prevNoticeTable.ajax.reload(false, null);
                    allNoticeTable.ajax.reload(false, null);
                    editNoticeForm.reset();

                }).catch(function (err) {
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
