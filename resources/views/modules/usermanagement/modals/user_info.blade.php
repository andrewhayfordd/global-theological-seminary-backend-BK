<div class="modal fade" id="info-user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">User Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container bootstrap snippets bootdey">
                    <div class="panel-body inf-content">
                        <div class="row">
                            <div class="col-md-4">
                                <img alt="" style="width:600px;" title=""
                                    class="img-circle img-thumbnail isTooltip"
                                    src="https://bootdey.com/img/Content/avatar/avatar7.png"
                                    data-original-title="Usuario">

                            </div>
                            <div class="col-md-6">

                                <span><strong>Email</strong>: <p id="user-info-email"></p> </span>
                                <span><strong>Phone</strong>: <p id="user-info-phone"></p> </span>
                                <span><strong>Role</strong>: <p id="user-info-type"></p> </span>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>




<script>
    $('#user-table').on('click', '.view-btn', function() {
        var userdata = userTable.row($(this).parents('tr')).data()
        $('#user-info-email').html(userdata.Email)
        $('#user-info-phone').html(userdata.Phone)
        $("#user-info-type").html(userdata.userType)
        $('#info-user-modal').modal('show')
    })
</script>
