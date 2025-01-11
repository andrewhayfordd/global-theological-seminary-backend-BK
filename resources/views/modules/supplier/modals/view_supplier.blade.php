<div class="modal fade" id="view-supplier-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">View Supplier Details</h5>
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
                                <label for="">Name </label>
                                <input readonly type="text" required id="view-supplier-name"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Supplier Code </label>
                                <input readonly type="text" required id="view-supplier-code"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Phone </label>
                                <input readonly  type="text" required  id="view-supplier-phone"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Email</label>
                                <input readonly  type="text" id="view-supplier-email"
                                    class="form-control form-control-sm">
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Address</label>
                                {{-- <input readonly  type="text" id="view-supplier-address"
                                    class="form-control form-control-sm"> --}}
                                    <textarea name="address" readonly id="view-supplier-address"
                                    class="form-control form-control-sm"> </textarea>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Members Count</label>
                                <input readonly  type="text" id="view-supplier-members"
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


