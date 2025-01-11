<div class="modal fade" id="edit-expense-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" style="min-width: 45%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Expense</h5>
            </div>
            <div class="modal-body">
                <form id="edit-expense-form">
                    @csrf
                    <input type="hidden" name="exp_id" id="exp_id">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label">Amount <span class="text-danger">*</span></label>
                                <input  class="form-control" min="0" step=".01" name="amount" id="edit-amount" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Academic Year <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="acyear" id="edit-acyear" required id="add-prog-bill-batch">
                                    <option value="" selected>--Select--</option>
                                    @forelse($batches as $item)
                                        <option value="{{ $item->code }}">{{ $item->label }}</option>
                                    @empty
                                        <option value="">No data found</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Branch <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="branch" id="edit-branch" required>
                                    <option value="">--Select--</option>
                                    @forelse ($branches as $item)
                                        <option value="{{ $item->code }}">{{ $item->label }}</option>
                                    @empty
                                        <option value="">No data found</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Semester <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="acterm" id="edit-acterm" required>
                                    <option value="" >--Select--</option>
                                    @forelse ($semesters as $item)
                                        <option value="{{ $item->code }}">{{ $item->label }}</option>
                                    @empty
                                        <option value="">No data found</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <label>Expense Type <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="exp_type" id="edit-exp_type" required>
                                    <option value="">--Select--</option>
                                    @forelse ($categories as $item)
                                        <option value="{{ $item->code }}">{{ $item->label }}</option>
                                    @empty
                                        <option value="">No data found</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Transaction Type<span class="text-danger">*</span></label>
                                <select class="form-control select2 transaction-type" name="trans_type" id="edit-transaction-type"
                                    required>
                                    <option value="">--Select--</option>
                                    <option value="bank">Bank</option>
                                    <option value="cash">Cash</option>
                                    <option value="cheque">Cheque</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="transaction-container">
                        
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label>Note</label>
                            <textarea name="note" id="edit-note" class="form-control" rows="5"> </textarea>
                        </div>
                    </div>
                    
                </form>
                <div class="modal-footer">
                    <button class="btn btn-primary" form="edit-expense-form" type="submit">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="reset" form="edit-expense-form" class="btn btn-light">Reset</button>
                </div>
            </div>
        </div>
    </div>
</div>
