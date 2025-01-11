<div class="modal fade" id="add-expense-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" style="min-width: 45%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Expense</h5>
            </div>
            <div class="modal-body">
                <form id="add-expense-form">
                    @csrf
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label">Amount <span class="text-danger">*</span></label>
                                <input  class="form-control" min="0" step=".01" name="amount" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Academic Year <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="acyear" required id="add-prog-bill-batch">
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
                                <select class="form-control select2" name="branch" required>
                                    <option value="" selected>--Select--</option>
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
                                <select class="form-control select2" name="acterm" required>
                                    <option value="" selected>--Select--</option>
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
                                <select class="form-control select2" name="exp_type" required>
                                    <option value="" selected>--Select--</option>
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
                                <select class="form-control select2 transaction-type" name="trans_type" id="transaction-type"
                                    required>
                                    <option value="" selected>--Select--</option>
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
                            <textarea name="note" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-primary" form="add-expense-form" type="submit">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="reset" form="add-expense-form" class="btn btn-light">Reset</button>
                </div>
            </div>
        </div>
    </div>
</div>
