<div class="modal fade" id="edit-student-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width:65%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Student Details</h5>
                <button class="close" type="" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body p-0">
                <!-- modal body starts -->
                <div class="card shadow-none">
                    <!-- card start -->
                    <div class="card-header">
                        <ul class="nav nav-pills nav-justified" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-bank-details-tab" data-toggle="pill"
                                    href="#student-program-details" role="tab" aria-controls="pills-account-details"
                                    aria-selected="false">Programs Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-personal-details-tab" data-toggle="pill"
                                    href="#student-personal-details" role="tab"
                                    aria-controls="pills-personal-details" aria-selected="true">Personal Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-academic-details-tab" data-toggle="pill"
                                    href="#student-academic-details" role="tab"
                                    aria-controls="pills-academic-details" aria-selected="false">Occupational status</a>
                            </li>


                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- card body starts -->
                        <div class="tab-content" id="pills-tabContent">
                            {{-- PERSONAL DETAILS TAB --}}
                            <div class="tab-pane fade" id="student-personal-details" role="tabpanel"
                                aria-labelledby="pills-personal-details-tab">
                                <form id="edit-student-registration-form" action="/profile" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <input type="text" name="id" id="edit-student-transid" hidden required>
                                        {{-- First name --}}
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>First Name <span
                                                        class="text-danger font-weight-bold">*</span></label>
                                                <input type="text" placeholder="eg. Emmanuel, Bismark, John"
                                                    name="fname" id="edit-student-fname" class="form-control "
                                                    required>
                                            </div>
                                        </div>

                                        {{-- Middle name --}}
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Middle Name</label>
                                                <input type="text" placeholder="eg. Osei, Owusu" name="mname"
                                                    id="edit-student-mname" class="form-control ">
                                            </div>
                                        </div>

                                        {{-- Last name --}}
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Last Name <span
                                                        class="text-danger font-weight-bold">*</span></label>
                                                <input type="text" placeholder="eg. Gadasu, Yeboah, Kwakye"
                                                    name="lname" id="edit-student-lname" class="form-control "
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        {{-- Gender --}}
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label id="gen">Gender </label>
                                                <select name="gender" id="edit-student-gender"
                                                    class=" form-control  select2" id="edit-student-gender">
                                                    <option value="">-- Select --</option>
                                                    <option value="M">Male</option>
                                                    <option value="F">Female</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Phone --}}
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Phone </label>
                                                <input name="student_phone" minlength="10" placeholder="eg. 024XXXXXXX"
                                                    id="edit-student-phone" class="form-control " >
                                            </div>
                                        </div>

                                        {{-- Email --}}
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Email <span
                                                        class="text-danger font-weight-bold"></span></label>
                                                <input type="email" placeholder="contact@mail.com" name="email"
                                                    id="edit-student-email" class="form-control " >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        {{-- Marital status --}}
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label id="crc">Marital Status</label>
                                                <select name="marital_status" id="edit-student-marital-status"
                                                    class=" form-control select2">
                                                    <option value="">-- Select --</option>
                                                    <option value="S">Single</option>
                                                    <option value="M">Married</option>
                                                    <option value="D">Divorce</option>
                                                    <option value="W">Widowed</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Postal address --}}
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Postal Address</label>
                                                <input type="text" id="edit-student-postal_address" placeholder="eg. P.O. Box AN 111 Accra North"
                                                    name="postal_address" class="form-control">
                                            </div>
                                        </div>
                                        {{-- Residential address --}}

                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label>Residential Address (GPS)</label>
                                                <input type="text" class="form-control "
                                                    id="edit-student-residential_address"
                                                    name="residential_address">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        {{-- Date of birth --}}
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label>Date Of Birth</label>
                                                <input type="date" class="form-control " id="edit-student-dob"
                                                    name="dob">
                                            </div>
                                        </div>

                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label>Church name and where you fellowship</label>
                                                <input type="text" class="form-control" id="add-church-fellowship" name="church_name">
                                            </div>
                                        </div>

                                        <div class="col-sm">
                                        <div class="form-group">
                                                <label>Are you physically challenged</label>
                                                <select name="physical_challenge" id="add-student-physical-challenge"
                                                    class=" form-control select2">
                                                    <option value="">-- Select --</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                    {{-- Emergency Contact --}}
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label>Emergency Contact Name</label>
                                                <input type="text" class="form-control "
                                                    id="edit-student-personal-emergency-contact-name" name="emergency_contact_name">
                                            </div>
                                        </div>

                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label>Emergency Contact Number</label>
                                                <input type="text" class="form-control "
                                                    id="edit-student-personal-emergency-contact-name" name="emergency_contact_number">
                                            </div>
                                        </div>
                                    {{-- Branch --}}
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label id="crc">Branch</label>
                                                <select name="branch" id="edit-student-branch"
                                                    class=" form-control select2">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($branch as $item)
                                                        <option value="{{ $item->branch_code }}">
                                                            {{ $item->branch_desc }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Picture --}}
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label>Student Picture</label>
                                                <input type="file" name="image" class="form-control "
                                                    id="upload">
                                            </div>
                                        </div>

                            </div>

                            {{-- OCCUPATIONAL STATUS TAB --}}
                            <div class="tab-pane fade" id="student-academic-details" role="tabpanel"
                                aria-labelledby="pills-academic-details-tab">

                                <div class="row">
                                    {{-- Academic qualification --}}

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label id="crc">Level of education</label>
                                            <select name="education_level" id="edit-student-education-level"
                                                class=" form-control  select2">
                                                <option value="">-- Select --</option>
                                                <option value="primary">Primary</option>
                                                <option value="jhs">Jhs</option>
                                                <option value="shs">Shs</option>
                                                <option value="tech">Technical/vocational</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- Name of employer --}}
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Name of Employer</label>
                                            <input type="text" placeholder="" name="employer" id="edit-student-employername"
                                                class="form-control ">
                                        </div>
                                    </div>

                                    {{-- Name of referee --}}
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Name of Referee</label>
                                            <input type="text" placeholder="" name="refree" id="edit-student-refereename"
                                                class="form-control ">
                                        </div>
                                    </div>
                                    {{-- Referee Phone Number --}}
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Referee Phone Number<span
                                                    class="text-danger font-weight-bold"></span></label>
                                            <input name="refree_phone" placeholder="eg. 024XXXXXXX"
                                                id="edit-student-refree-phone" class="form-control ">
                                        </div>
                                    </div>
                                    {{-- Referee occupation --}}
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Referee occupation</label>
                                            <input type="text" placeholder="" name="referee_occupation"
                                                id="edit-student-refree-occu" class="form-control ">
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Referee Residential Address</label>
                                            <input type="text"
                                                placeholder="eg. Hse No C/223 Nii Amasah Street, Darkuman"
                                                class="form-control " id="refree_address"
                                                name="edit-student-resaddress">
                                        </div>
                                    </div>
                                </div>


                            </div>

                            {{-- PROGRAMS DETAILS TAB --}}
                            <div class="tab-pane fade show active" id="student-program-details" role="tabpanel"
                                aria-labelledby="pills-bank-details-tab">
                                   
                                <div class="row">
                                         {{-- Student id --}}
                                         <div class="col-sm">
                                            <div class="form-group">
                                                <label>Student Number <span class="text-danger">*</span></label>
                                                <input type="text" placeholder="" name="student_id" id="edit-student-id" class="form-control ">
                                            </div>
                                        </div>
                                    {{-- Bank programs --}}
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label id="crc">Programs <span
                                                    class="text-danger font-weight-bold">*</span></label>
                                            <select name="program" id="edit-student-programs"
                                                class=" form-control  select2" required>
                                                <option value="">-- Select --</option>
                                                @foreach ($prog as $item)
                                                    <option value={{ $item->prog_code }}>{{ $item->prog_desc }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                   
                                    
                                    {{-- <input type="text" name="student_id" hidden id="edit-student-id"> --}}
                                    {{-- class session --}}
                                    
                                    
                                </div>

                                <div class="row">
                                <div class="col-sm">
                                        <div class="form-group">
                                        <label id="reason">Why do you seek training in this programme? <span
                                                    class="text-danger font-weight-bold">*</span></label>
                                                <input type="text" class="form-control" id="program-reason" name="program_reason">
                                        </div>
                                    </div>
                                <div class="col-sm">
                                        <div class="form-group">
                                            <label id="class_session">Class session <span
                                                    class="text-danger font-weight-bold">*</span></label>
                                            <select name="session" id="edit-student-session"
                                                class=" form-control  select2" required>
                                                <option value="">-- Select --</option>
                                                @foreach ($sess as $item)
                                                    <option value={{ $item->session_code }}>
                                                        {{ $item->session_desc }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label id="class_session">Batch <span
                                                    class="text-danger font-weight-bold">*</span></label>
                                            <select name="batch" id="edit-student-batch"
                                                class=" form-control  select2" required>
                                                <option value="">-- Select --</option>
                                                @foreach ($batch as $item)
                                                    <option value={{ $item->batch_code }}>
                                                        {{ $item->batch_desc }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label id="crc">Current Level</label>
                                            <select name="current_level" id="edit-student-current-level"
                                                class=" form-control  select2">
                                                <option value="">--select--</option>
                                                @foreach ($level as $item)
                                                    <option value="{{ $item->level_code }}">{{ $item->level_desc }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
        <!-- Basic Qualification - SSSCE/WASSCE/GBCE -->
        <div class="col-sm-12">
            <h5>Basic Qualification - SSSCE/WASSCE/GBCE</h5>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label>English Language</label>
                <input type="text" name="english_language_grade" id="edit-student-english_language_grade" placeholder="Grade" class="form-control">
                <input type="text" name="english_language_year" placeholder="Year Obtained" class="form-control mt-2">
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label>Mathematics</label>
                <input type="text" name="mathematics_grade" placeholder="Grade" class="form-control">
                <input type="text" name="mathematics_year" placeholder="Year Obtained" class="form-control mt-2">
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label>Integrated Science or Social Science</label>
                <input type="text" name="science_grade" placeholder="Grade" class="form-control">
                <input type="text" name="science_year" placeholder="Year Obtained" class="form-control mt-2">
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label>Elective 1</label>
                <input type="text" name="elective1_grade" placeholder="Grade" class="form-control">
                <input type="text" name="elective1_year" placeholder="Year Obtained" class="form-control mt-2">
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label>Elective 2</label>
                <input type="text" name="elective2_grade" placeholder="Grade" class="form-control">
                <input type="text" name="elective2_year" placeholder="Year Obtained" class="form-control mt-2">
            </div>
        </div>

         <!-- Additional Educational Qualifications -->
        <div class="col-sm-12 mt-4">
            <h5>Additional Educational Qualifications</h5>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label>Name of School</label>
                <input type="text" name="school_attended_name" placeholder="Name of School" class="form-control">
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label>Certificate Awarded</label>
                <input type="text" name="certificate_awarded" placeholder="Certificate Awarded" class="form-control">
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label>Date Awarded</label>
                <input type="date" name="date_awarded" class="form-control">
            </div>
        </div>

        <!-- Religious Affiliation -->
        <div class="col-sm-12 mt-4">
            <div class="form-group">
                <label>Religious Affiliation</label>
                <input type="text" name="religious_affiliation" placeholder="Religious Affiliation" class="form-control">
            </div>
        </div>

        <!-- Sponsorship -->
        <div class="col-sm-12 mt-4">
            <h5>Sponsorship (Please tick as applicable)</h5>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sponsorship" value="self" id="sponsorshipSelf">
                <label class="form-check-label" for="sponsorshipSelf">
                    Self
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sponsorship" value="others" id="sponsorshipOthers">
                <label class="form-check-label" for="sponsorshipOthers">
                    Others
                </label>
            </div>
            <div class="form-group mt-2">
                <label>Name and Address of Sponsor(s)</label>
                <textarea name="sponsor_details" class="form-control" rows="3"></textarea>
            </div>
        </div>
    </div>
                            </div>

                            {{-- Form buttons --}}
                            <div class="row border-top pt-3 mt-2 border-dark">
                                <div class="col text-right">
                                    <p class="text-info" id="bank-details-form-feedback"></p>
                                    <button class="btn btn-sm btn-light" type="button"
                                    data-dismiss="modal">Cancel</button>
                                    <button form="edit-student-registration-form" class="btn btn-sm btn-light"
                                        type="reset" id="bank-details-form-reset-btn">Reset</button>
                                    <button form="edit-student-registration-form" class="btn btn-sm btn-primary"
                                        type="submit" name="submit">Save</button>
                                </div>
                            </div>
                            </form>
                        </div>

                        </form>
                    </div>
                </div>
            </div> <!-- end card body -->
        </div> <!-- card ends -->
    </div> <!-- modal body ends -->
</div> <!-- modal content ends -->
</div> <!-- modal dialog ends -->
</div>

<script>
    var editStudentRegForm = document.getElementById("edit-student-registration-form");


    $(editStudentRegForm).submit(function(e) {
        e.preventDefault();

        var formdata = new FormData(editStudentRegForm)
        Swal.fire({
            title: 'Are you sure you want to update student details?',
            text: "Or click cancel to abort!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit'

        }).then((result) => {

            if (result.value) {
                Swal.fire({
                    text: "Processing...",
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false
                });
                fetch(`${appUrl}/api/student/edit`, {
                    method: "POST",
                    body: formdata,
                }).then(function(res) {

                    return res.json()

                }).then(function(data) {

                    if (!data.ok) {
                        Swal.fire({
                            text: data.msg,
                            type: "error"
                        });
                        return;
                    }
                    Swal.fire({
                        text: "Student details updated  successfully",
                        type: "success"
                    });
                    $("#edit-student-modal").modal('hide');
                    $("select").val(null).trigger('change');
                    studentTable.ajax.reload(false, null);
                    editStudentRegForm.reset();

                }).catch(function(err) {
                    if (err) {
                        Swal.fire({
                            text: "Processing failed",
                            type: "error"
                        });
                        return;
                    }
                })
            } else {
                swal.fire("Cancelled", "...", "error");

            }

        })
    });
</script>
