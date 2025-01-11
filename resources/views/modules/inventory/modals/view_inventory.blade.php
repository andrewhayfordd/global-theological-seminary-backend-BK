<div class="modal fade" id="view-inventory-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">View Inventory Details</h5>
                <button class="close" type="" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Item Name </label>
                                <input readonly type="text" required id="view-inventory-name"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Supplier Code </label>
                                <input readonly type="text" required id="view-supplier-code"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Item Quantity </label>
                                <input readonly  type="text" required  id="view-inventory-quantity"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Supply Date</label>
                                <input readonly  type="text" id="view-inventory-supply-date"
                                    class="form-control form-control-sm">
                            </div>

                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


