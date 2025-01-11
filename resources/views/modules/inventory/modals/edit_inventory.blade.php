<div class="modal fade" id="edit-inventory-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit inventoryentory</h5>
                <button class="close" type="" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-inv-form">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Item Name <span class="font-weight-bold text-danger">*</span></label>
                                <input type="text" required name="item_desc" id="edit-inv-name"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    
                            <div class="form-group">
                                <input type="text" hidden name="item_code" id="edit-inv-code"
                                    class="form-control form-control-sm">
                            </div>

                            <div class="form-group">
                                <input type="text" hidden name="transid" id="edit-inv-transid"
                                    class="form-control form-control-sm">
                            </div>
                       
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Quantity <span class="font-weight-bold text-danger">*</span></label>
                                <input type="text" required name="item_quantity" id="edit-inventory-quantity"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Supply Date <span class="font-weight-bold text-danger">*</span></label>
                                <input type="date" required name="supply_date" id="edit-inventory-supply-date"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm" form="edit-inv-form" type="submit" name="submit">
                        Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#edit-inv-form").on('submit',function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        let editSupplierForm = document.getElementById('edit-inv-form');
        var formdata = new FormData(editSupplierForm);
        formdata.append("createuser", createuser);
        formdata.append("school_code", school_code);
        Swal.fire({
            // title: 'Do you want to edit this student assessment?',
            text: "Do you want to save changes?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Save',
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    text: "please wait...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/inventory/update`, {
                    method: "POST",
                    body: formdata,
                    headers: {
                        "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
                    }
                }).then(function(res) {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
            .then(function(data) {
                console.log('Server Response:', data);

                Swal.fire({
                    title: data.msg || 'Success!',
                    text: data.msg ? 'Inventory updated successfully' : 'Unknown error occurred',
                    type: "success"
                });

                // Optionally, you can reset the form here if needed
                // addInventoryForm.reset();
            })
            .catch(function(err) {
                console.error('Fetch Error:', err);
                Swal.fire({
                    type: "error",
                    text: "Adding inventory details failed. Please try again later."
                });
            });
            }
        })
    });
</script>