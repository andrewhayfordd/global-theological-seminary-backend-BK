@extends('layout.app')
@section('page-name', 'Services')
@section('page-content')

    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Service</li>
                    </ul>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="admissionInfo-tab" data-toggle="tab" href="#admissionInfo" role="tab"
                aria-controls="admissionInfo" aria-selected="false">Services</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="StudentInfo-tab" data-toggle="tab" href="#studentBill" role="tab"
                aria-controls="studentBill" aria-selected="false">Requests</a>
        </li>
    </ul>

    <div role="tabpanel" class=" tab-pane active card shadow mb-4" id="all-payments">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active mt-3" id="admissionInfo" role="tabpanel"
                aria-labelledby="admissionInfo-tab">
                <div class="card-body">
                    <div style="margin-left: 90%; margin-top: -20px">
                        <button class="ml-2 btn-primary btn btn-sm rounded" id="add-service-btn">Add Service</button>
                    </div>
                    <div class="table-responsive" style="margin-top: 10px">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="payment-table">
                            <thead>
                                <tr>
                                    <th>Service Code</th>
                                    <th>Service Name</th>
                                    <th>Service Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $service)
                                    <tr>
                                        <td>{{ $service->service_code }}</td>
                                        <td>{{ $service->service_name }}</td>
                                        <td>{{ $service->service_cost }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="studentBill" role="tabpanel" aria-labelledby="StudentInfo-tab">
                <div class="card-body">
                    <div class="table-responsive" style="margin-top: 20px">
                        <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                            width='100%' id="payment-table-student">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Service Name</th>
                                    <th>Service Cost</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stuServices as $item)
                                    <tr>
                                        <td>{{ $item->fname }} {{ $item->mname }} {{ $item->lname }}</td>
                                        <td>{{ $item->service_name }}</td>
                                        <td>{{ $item->service_cost }}</td>
                                        <td>
                                            <select name="status" class="status" data-request-id="{{ $item->id }}">
                                                <option value="pending" selected>Pending</option>
                                                <option value="processing">Processing</option>
                                                <option value="ready">Ready</option>
                                                <option value="completed">Completed</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modules.adminservices.modals.add_service')

    <script>
        $(document).ready(function() {
            $("#add-service-btn").click(function() {
                $("#add-service-modal").modal("show");
            });
        });

        $(document).ready(function() {
            // Initialize a variable to store the original status
            var originalStatus;

            // Attach an event listener to the dropdown
            $('.status').on('focus', function() {
                // Store the original status when the dropdown gains focus
                originalStatus = $(this).val();
            });

            $('.status').change(function() {
                var $dropdown = $(
                this); // Reference to the specific dropdown that triggered the change event
                var selectedStatus = $dropdown.val();
                var requestId = $dropdown.data('request-id');

                // Show confirmation dialog
                Swal.fire({
                    title: 'Update Status',
                    text: 'Are you sure you want to update the status?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: 'Update'
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                            text: "Adding...",
                            showConfirmButton: false,
                            allowEscapeKey: false,
                            allowOutsideClick: false
                        });

                        // Send AJAX request to update the status
                        $.ajax({
                            url: '/api/services/serviceRequest_update',
                            method: 'POST',
                            data: {
                                id: requestId,
                                status: selectedStatus
                            },
                            success: function(response) {
                                // Handle success response
                                Swal.fire({
                                    type: 'success',
                                    title: 'Success',
                                    text: response.msg
                                });

                                // Update only the specific dropdown that triggered the change event
                                $dropdown.val(selectedStatus);
                            },
                            error: function(error) {
                                // Handle error response
                                Swal.fire({
                                    type: 'error',
                                    title: 'Error',
                                    text: error.responseJSON.msg
                                });

                                // Revert the dropdown value on error
                                $dropdown.val(originalStatus);
                            }
                        });
                    } else {
                        // User clicked "Cancel" or closed the dialog
                        // Revert the dropdown value
                        $dropdown.val(originalStatus);
                    }
                });
            });
        });
    </script>
@endsection
