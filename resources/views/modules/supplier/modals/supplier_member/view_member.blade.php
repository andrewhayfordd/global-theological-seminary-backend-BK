<div class="modal fade" id="view-supplier-member-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">View Supplier Member Details</h5>
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
                                <label for=""> Name </label>
                                <input type="text" readonly id="view-supplier-member-name"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Phone </label>
                                <input type="text" readonly id="view-supplier-member-phone"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Position</label>
                                <input type="text" readonly  id="view-supplier-member-position"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Supplier Name </label>
                                <input type="text" readonly  id="view-supplier-member-supplier_name"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Supplier Phone </label>
                                <input type="text" readonly id="view-supplier-member-supplier_phone"
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
