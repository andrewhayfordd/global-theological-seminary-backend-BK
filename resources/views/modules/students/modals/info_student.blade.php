<div class="modal fade" id="full-student-details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">STUDENT DETAILS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col text-center">
                        <div>
                            <img height="150" src="{{asset('user.jpg')}}" weight="150" alt="Student photo" class="rounded"
                                id="full-details-student-image">
                        </div>

                        <div class="h5" id="full-details-name"></div>
                        <p class="h6" id="full-details-student-code"></p>
                        <br>
                        <div class="row">
                            <div class="col-6 border-right">
                                <h6 class="">PERSONAL DETAILS</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Date Of Birth:
                                        <span id="full-details-dob">    N/A</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Gender:
                                        <span id="full-details-gender"> N/A</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Phone:
                                        <span id="full-details-phone">  N/A</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Email:
                                        <span id="full-details-email">  N/A</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-6">
                                <h6 class="">ACADEMIC DETAILS</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Programme:
                                        <span id="full-details-prog">   N/A</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Current Level:
                                        <span id="full-details-level">  N/A</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Batch:
                                        <span id="full-details-batch">  N/A</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Session:
                                        <span id="full-details-session">    N/A</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        {{-- <div class="row mt-3">
                            <div class="col">
                                <h6 class="">PARENT DETAILS</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Name:
                                        <span id="full-details-parent-name">N/A</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Phone:
                                        <span id="full-details-parent-phone">N/A</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Email:
                                        <span id="full-details-parent-email">N/A</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Parent Type:
                                        <span id="full-details-parent-type">    N/A</span>
                                    </li>
                                </ul>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col text-right">
                    <button class="btn btn-sm btn-light" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
