@extends('layout.app')
@section('page-name', 'Inventory')
@section('page-content')
    <x-page-header />

    <x-tab-bar>
        <x-tab-button id="inventory-tab-button" href="#inventory-tab-content" class="active" label="Inventory" />
        <x-tab-button id="inventory-item-tab-button" href="#inventory-item-tab-content" label="Inventory Item" />
    </x-tab-bar>

    <x-tab-content-container>

        {{-- Inventory --}}
        <x-tab-content id="inventory-tab-content" class="active">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="inventory-table">
                            <thead>
                                <tr>
                                    <th class="all">Item Name</th>
                                    <th class="all">Quantity</th>
                                    <th class="all">Supply Date</th>
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
            {{-- End Inventory --}}
        </x-tab-content>

        {{-- Inventory Item --}}
        <x-tab-content id="inventory-item-tab-content">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="inventory-item-table">
                            <thead>
                                <tr>
                                    <th class="all">Item Name</th>
                                    <th class="all">Item Code</th>
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
            {{-- End of inventory item tab --}}
        </x-tab-content>
    </x-tab-content-container>
    @include('modules.inventory.modals.add_inventory_item')
    @include('modules.inventory.modals.edit_inventory_item')
    @include('modules.inventory.modals.add_inventory')
    @include('modules.inventory.modals.view_inventory')
    @include('modules.inventory.modals.edit_inventory')
    @push('scripts')
        <script>
            let InventoryItemTable = $('#inventory-item-table').DataTable({
                lengthChange: true,
                dom: 'Bfrtip',
                ajax: {
                    url: `${appUrl}/api/inventory-item/all`,
                    type: "GET"
                },
                processing: true,
                responsive: true,
                columns: [{
                        data: 'item_desc',
                    },
                    {
                        data: 'item_code',
                    },
                    {
                        data: 'action',
                    },
                ],
                buttons: [{
                        extend: 'print',
                        title: `${loggedInUserSchoolName} - Inventory Items List`,
                        attr: {
                            class: "btn btn-sm btn-info rounded-right"
                        },
                        exportOptions: {
                            columns: [0, 1, 2, ]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: `${loggedInUserSchoolName} - Inventory Items List`,
                        attr: {
                            class: "btn btn-sm btn-info rounded-right"
                        },
                        exportOptions: {
                            columns: [0, 1, 2, ]
                        },
                    },
                    {
                        extend: 'excel',
                        title: `${loggedInUserSchoolName} - Inventory Items List`,
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
                        text: "Add Inventory Item",
                        attr: {
                            class: "ml-2 btn-primary btn btn-sm rounded"
                        },
                        action: function(e, dt) {
                            $('#add-inventory-item-modal').modal('show');
                        }
                    }
                ]
            });

            //Edit Inventory Item
            $('#inventory-item-table').on('click', '.update-btn', function() {
                let data = InventoryItemTable.row($(this).parents('tr')).data();
                $("#edit-inventory-item-modal").modal('show');

                $("#edit-inventory-name").val(data.item_desc);
                $("#edit-inventory-code").val(data.item_code);
            });

            //Deleting Inventory Item
            $('#inventory-item-table').on('click', '.delete-btn', function() {
                let data = InventoryItemTable.row($(this).parents('tr')).data();
                var formdata = new FormData();
                formdata.append("createuser", createuser);
                formdata.append("item_code", data.item_code);
                Swal.fire({
                    // title: 'Do you want to edit this student assessment?',
                    text: "Do you want to delete this inventory item?",
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
                        fetch(`${appUrl}/api/inventory-item/delete`, {
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
                                    text: data.msg ? 'Inventory item created successfully' :
                                        'Unknown error occurred',
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

        <script>

            let InventoryTable = $('#inventory-table').DataTable({
                lengthChange: true,
                dom: 'Bfrtip',
                ajax: {
                    url: `${appUrl}/api/inventory/all`,
                    type: "GET"
                },
                processing: true,
                responsive: true,
                columns: [{
                        data: 'item_code',
                    },
                    {
                        data: 'item_quantity',
                    },
                    {
                        data: 'supply_date',
                    },
                    {
                        data: 'action',
                    },
                ],
                buttons: [{
                        extend: 'print',
                        title: `${loggedInUserSchoolName} - Inventory List`,
                        attr: {
                            class: "btn btn-sm btn-info rounded-right"
                        },
                        exportOptions: {
                            columns: [0, 1, 2, ]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: `${loggedInUserSchoolName} - Inventory List`,
                        attr: {
                            class: "btn btn-sm btn-info rounded-right"
                        },
                        exportOptions: {
                            columns: [0, 1, 2, ]
                        },
                    },
                    {
                        extend: 'excel',
                        title: `${loggedInUserSchoolName} - Inventory List`,
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
                        text: "Add Inventory",
                        attr: {
                            class: "ml-2 btn-primary btn btn-sm rounded"
                        },
                        action: function(e, dt) {
                            $('#add-inventory-modal').modal('show');
                        }
                    }
                ]
            });

            //View Inventory data
            $('#inventory-table').on('click', '.info-btn', function() {
                let data = InventoryTable.row($(this).parents('tr')).data();
                $("#view-inventory-modal").modal('show');
                $("#view-inventory-name").val(data.item_code);
                $("#view-inventory-quantity").val(data.item_quantity);
                $("#view-inventory-supply-date").val(data.supply_date);
            });

            //Display data for updating
            $('#inventory-table').on('click', '.update-btn', function() {
                let data = InventoryTable.row($(this).parents('tr')).data();
                $("#edit-inventory-modal").modal('show');

                $("#edit-inv-name").val(data.item_code);
                $("#edit-inventory-quantity").val(data.item_quantity);
                $("#edit-inventory-supply-date").val(data.supply_date);
                $("#edit-inv-code").val(data.item_name);
                $("#edit-inv-transid").val(data.transid);
            });

             //Deleting Inventory
             $('#inventory-table').on('click', '.delete-btn', function() {
                let data = InventoryTable.row($(this).parents('tr')).data();
                var formdata = new FormData();
                formdata.append("createuser", createuser);
                formdata.append("item_code", data.item_name);
                formdata.append("transid", data.transid);
                Swal.fire({
                    // title: 'Do you want to edit this student assessment?',
                    text: "Do you want to delete this inventory?",
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
                        fetch(`${appUrl}/api/inventory/delete`, {
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
                                    text: data.msg ? 'Inventory item created successfully' :
                                        'Unknown error occurred',
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
    @endpush
@endsection
