@extends('layout.app')
@section('page-name', 'Supplier')
@section('page-content')
    <x-page-header />
    <x-tab-bar>
        <x-tab-button id="supplier-tab-button" href="#supplier-tab-content" class="active" label="Supplier" />
        <x-tab-button id="supplier-member-tab-button" href="#supplier-member-tab-content" label="Supplier Contacts" />
    </x-tab-bar>

    <x-tab-content-container>
        {{-- Supplier --}}
        <x-tab-content id="supplier-tab-content" class="active">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="supplier-table">
                            <thead>
                                <tr>
                                    <th class="all">Supplier Code</th>
                                    <th class="all">Name</th>
                                    <th class="all">Phone</th>
                                    <th class="all">Address</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data is fetched here using ajax --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </x-tab-content>

        {{-- End Supplier tab content --}}

        {{-- Supplier Member --}}
        <x-tab-content id="supplier-member-tab-content">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="supplier-member-table">
                            <thead>
                                <tr>
                                    <th class="all">Name</th>
                                    <th class="all">Phone</th>
                                    <th class="all">Supplier</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data is fetched here using ajax --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </x-tab-content>

        {{-- End of supplier tab --}}


    </x-tab-content-container>

    @include('modules.supplier.modals.add_supplier')
    @include('modules.supplier.modals.edit_supplier')
    @include('modules.supplier.modals.view_supplier')
    @include('modules.supplier.modals.supplier_member.add_member')
    @include('modules.supplier.modals.supplier_member.edit_member')
    @include('modules.supplier.modals.supplier_member.view_member')
    @push('scripts')
        <script>
            let SupplierTable = $('#supplier-table').DataTable({
                lengthChange: true,
                dom: 'Bfrtip',
                ajax: {
                    url: `${appUrl}/api/supplier/all`,
                    type: "GET",
                },
                processing: true,
                responsive: true,
                columns: [{
                        data: 'supplier_code',
                    },
                    {
                        data: 'name',

                    },
                    {
                        data: 'phone',
                    },
                    {
                        data: 'address_limit',
                    },
                    {
                        data: 'action',
                    },
                ],
                buttons: [{
                        extend: 'print',
                        title: `${loggedInUserSchoolName} - Suppliers List`,
                        attr: {
                            class: "btn btn-sm btn-info rounded-right"
                        },
                        exportOptions: {
                            columns: [0, 1, 2, ]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: `${loggedInUserSchoolName} - Suppliers List`,
                        attr: {
                            class: "btn btn-sm btn-info rounded-right"
                        },
                        exportOptions: {
                            columns: [0, 1, 2, ]
                        },
                    },
                    {
                        extend: 'excel',
                        title: `${loggedInUserSchoolName} - Supplier List`,
                        attr: {
                            class: "btn btn-sm btn-info rounded-right"
                        },
                        exportOptions: {
                            columns: [0, 1, 2, ]
                        }
                    },
                    {
                        text: "Refresh",
                        attr: {
                            class: "ml-2 btn-secondary btn btn-sm rounded"
                        },
                        action: function(e, dt, node, config) {
                            dt.ajax.reload(false, null);
                        }
                    },
                    {
                        text: "Add Supplier",
                        attr: {
                            class: "ml-2 btn-primary btn btn-sm rounded"
                        },
                        action: function(e, dt) {
                            $('#add-supplier-modal').modal('show');
                        }
                    }
                ]
            });

            // Delete a supplier
            $('#supplier-table').on('click', '.delete-btn', function() {
                let data = SupplierTable.row($(this).parents('tr')).data();
                var formdata = new FormData();
                formdata.append("createuser", createuser);
                formdata.append("school_code", school_code);
                formdata.append("transid", data.transid);
                Swal.fire({
                    // title: 'Do you want to edit this student assessment?',
                    text: "Do you want to delete this supplier?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete',
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                            text: "please wait...",
                            showConfirmButton: false,
                            allowEscapeKey: false,
                            allowOutsideClick: false
                        });
                        fetch(`${appUrl}/api/supplier/delete`, {
                            method: "POST",
                            body: formdata,
                            headers: {
                                "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
                            }
                        }).then(function(res) {
                            return res.json();
                        }).then(function(data) {
                            if (!data.ok) {
                                Swal.fire({
                                    title: data.msg,
                                    text: data.errors_all.join(' and '),
                                    type: "error"
                                });
                                return;
                            }
                            Swal.fire({
                                text: data.msg,
                                type: "success"
                            });
                            SupplierTable.ajax.reload(false, null);
                        }).catch(function(err) {
                            if (err) {
                                console.log(err);
                                Swal.fire({
                                    type: "error",
                                    text: "Deleting supplier details failed, Try again later"
                                });
                            }
                        })
                    }
                })
            });

            //End supplier delete process

            //Display data for updating
            $('#supplier-table').on('click', '.update-btn', function() {
                let data = SupplierTable.row($(this).parents('tr')).data();
                $("#edit-supplier-modal").modal('show');

                $("#edit-supplier-transid").val(data.transid);
                $("#edit-supplier-name").val(data.name);
                $("#edit-supplier-phone").val(data.phone);
                $("#edit-supplier-email").val(data.email);
                $("#edit-supplier-address").val(data.address);
            });

            //End  Display data for updating

            //View Supplier member data
            $('#supplier-table').on('click', '.info-btn', function() {
                let data = SupplierTable.row($(this).parents('tr')).data();
                $("#view-supplier-modal").modal('show');
                $("#view-supplier-name").val(data.name);
                $("#view-supplier-code").val(data.supplier_code);
                $("#view-supplier-phone").val(data.phone);
                $("#view-supplier-email").val(data.email);
                $("#view-supplier-address").val(data.address);
                $("#view-supplier-members").val(data.members);
            });
        </script>
        {{-- End of supplier Table and crud scripts  --}}


        <script>
            let SupplierMemberTable = $('#supplier-member-table').DataTable({
                lengthChange: true,
                dom: 'Bfrtip',
                ajax: {
                    url: `${appUrl}/api/supplier-member/all`,
                    type: "GET",
                },
                processing: true,
                responsive: true,
                columns: [{
                        data: 'full_name',

                    },
                    {
                        data: 'phone',
                    },
                    {
                        data: 'supplier_name',
                    },
                    {
                        data: 'action',
                    },
                ],
                buttons: [{
                        extend: 'print',
                        title: `${loggedInUserSchoolName} - Student List`,
                        attr: {
                            class: "btn btn-sm btn-info rounded-right"
                        },
                        exportOptions: {
                            columns: [0, 1, 2, ]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: `${loggedInUserSchoolName} - Student List`,
                        attr: {
                            class: "btn btn-sm btn-info rounded-right"
                        },
                        exportOptions: {
                            columns: [0, 1, 2, ]
                        },
                    },
                    {
                        extend: 'excel',
                        title: `${loggedInUserSchoolName} - Student List`,
                        attr: {
                            class: "btn btn-sm btn-info rounded-right"
                        },
                        exportOptions: {
                            columns: [0, 1, 2, ]
                        }
                    },
                    {
                        text: "Refresh",
                        attr: {
                            class: "ml-2 btn-secondary btn btn-sm rounded"
                        },
                        action: function(e, dt, node, config) {
                            dt.ajax.reload(false, null);
                        }
                    },
                    {
                        text: "Add Supplier Contact",
                        attr: {
                            class: "ml-2 btn-primary btn btn-sm rounded"
                        },
                        action: function(e, dt) {
                            $('#add-supplier-member-modal').modal('show');
                        }
                    }
                ]
            });

            //Delete member 
            $('#supplier-member-table').on('click', '.delete-btn', function() {
                let data = SupplierMemberTable.row($(this).parents('tr')).data();
                var formdata = new FormData();
                formdata.append("createuser", createuser);
                formdata.append("school_code", school_code);
                formdata.append("transid", data.transid);
                Swal.fire({
                    // title: 'Do you want to edit this student assessment?',
                    text: "Do you want to delete this member?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete',
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                            text: "please wait...",
                            showConfirmButton: false,
                            allowEscapeKey: false,
                            allowOutsideClick: false
                        });
                        fetch(`${appUrl}/api/supplier-member/delete`, {
                            method: "POST",
                            body: formdata,
                            headers: {
                                "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
                            }
                        }).then(function(res) {
                            return res.json();
                        }).then(function(data) {
                            if (!data.ok) {
                                Swal.fire({
                                    title: data.msg,
                                    text: data.errors_all.join(' and '),
                                    type: "error"
                                });
                                return;
                            }
                            Swal.fire({
                                text: data.msg,
                                type: "success"
                            });
                            SupplierMemberTable.ajax.reload(false, null);
                        }).catch(function(err) {
                            if (err) {
                                console.log(err);
                                Swal.fire({
                                    type: "error",
                                    text: "Deleting supplier member details failed, Try again later"
                                });
                            }
                        })
                    }
                })
            })
            // End delete member

            // View member 
            $('#supplier-member-table').on('click', '.info-btn', function() {
                let data = SupplierMemberTable.row($(this).parents('tr')).data();
                $('#view-supplier-member-modal').modal('show');
                $('#view-supplier-member-name').val(data.full_name);
                $('#view-supplier-member-phone').val(data.phone);
                $('#view-supplier-member-position').val(data.position_desc);
                $('#view-supplier-member-supplier_name').val(data.supplier_name);
                $('#view-supplier-member-supplier_phone').val(data.supplier_phone);
            })
            //End View member

            //Update member
            $('#supplier-member-table').on('click', '.update-btn', function() {
                let data = SupplierMemberTable.row($(this).parents('tr')).data();
                $('#edit-supplier-member-modal').modal('show');
                $('#edit-supplier-member-transid').val(data.transid);
                $('#edit-supplier-member-fname').val(data.fname);
                $('#edit-supplier-member-lname').val(data.lname);
                $('#edit-supplier-member-phone').val(data.phone);
                $('#edit-supplier-member-position').val(data.position).trigger('change');
                $('#edit-supplier-member-supplier').val(data.supplier_code).trigger('change');

            })
            //End update member
        </script>
    @endpush
@endsection
