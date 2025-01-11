<div class="modal fade" id="edit-staff-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="add-staff-form" enctype="multipart/form-data">
                    @csrf
                    

                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">First name</label>
                                <input type="text" class="form-control"  name="first_name" 
                                    required id="first_name">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Last name</label>
                                <input type="text" class="form-control"  name="last_name" id="last_name"
                                    required >
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="">
                                <label for="exampleInputPassword1">Middle name</label>
                                <input type="text" class="form-control"  name="Middle_name" id="Middle_name"
                                    required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col">
                            <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="email">Phone number</label>
                        <input type="tel" name="phone_number" id="phone" class="form-control">
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="dob">Postal address</label>
                        <input type="text"  id="postal_address" class="form-control" name="postal_address">
                            
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col">
                            <label for="Residential-address">Residential address</label>
                        <input type="text"  id="Residential-address" class="form-control" name="residential_address">
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="Staff-type">Staff type</label>
                        <input type="text"  id="Staff-type" class="form-control" name="staff_type">
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="Profile-picture">Profile picture</label>
                        <input type="file"  id="Profile_picture" class="form-control" name="profile_pic">
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                               <label for="">Marital status</label>
                                <select class="form-select form-control" aria-label="Default select example" name="marital_status" id="marital_status">
                                    <option selected value="Married">Married</option>
                                    <option value="Single">Single</option>
                                   
                                  </select>
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                               <label for="">Gender</label>
                                <select class="form-select form-control" aria-label="Default select example" name="gender" id="gender">
                                    <option selected value="M">Male</option>
                                    <option value="F">Female</option>
                                   
                                  </select>
                            
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="dob">Date of birth</label>
                        <input type="date" name="dob" id="dob" class="form-control" >
                            
                        </div>
                    </div>
                   
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" form="edit-staff-form" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>

