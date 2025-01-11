<div class="modal fade" id="add-service-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-medium" role="document" style="margin-top: 5%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Service </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="add-service-form">
                    @csrf
                     <div class="row mt-3">
                       {{-- <div class="col">
                            <label for="add-staff-dept">School</label>
                            <select class="form-select form-control" id="add-staff-dept" name="school_name">
                                <option value="">--Select--</option>
                                @foreach ($school as $item)
                                    <option value="{{ $item->school_code }}">{{ $item->school_name }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Service name</label>
                                <input type="text" class="form-control" name="service_name" id="add-department-name">
                            </div>
                        </div>
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Service cost</label>
                                <input type="text" class="form-control" name="service_cost" id="add-department-name"
                                    required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" form="add-service-form" class="btn btn-primary btn-sm">Save
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    //adding staffs
    let addServiceForm = document.forms['add-service-form']
    $('#add-service-form').submit(function(e) {
        e.preventDefault()
        let formdata = new FormData(addServiceForm);
        Swal.fire({
            title: '',
            text: 'Are you sure you want to add service?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: 'Submit'
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    text: "Adding...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/services/admin_service`, {
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
                        text: "Service added successfully",
                        type: "success"
                    });
                    $("#add-service-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    staffTable.ajax.reload(false, null);
                    addStaffForm.reset();
                }).catch(function(err) {
                    console.error("Error during fetch:", err);
                    console.log("HTTP status code:", err.status);

                    // Parse JSON error response, if available
                    if (err.headers.get("content-type") && err.headers.get("content-type")
                        .includes("application/json")) {
                        err.json().then(function(data) {
                            console.error("Server error details:", data);
                            Swal.fire({
                                text: data.msg ||
                                "Adding service failed", // Assuming the error message is in a 'msg' property
                                type: "error"
                            });
                        }).catch(function(parseError) {
                            console.error("Error parsing JSON response:", parseError);
                            Swal.fire({
                                text: "Adding service failed",
                                type: "error"
                            });
                        });
                    } else {
                        // If not a JSON response, show a generic error message
                        Swal.fire({
                            text: "Adding service failed",
                            type: "error"
                        });
                    }
                });

            }
        })
    })
</script>
