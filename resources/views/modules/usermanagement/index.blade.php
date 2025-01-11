@extends('layout.app')
@section('page-name', 'User')
@section('page-content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">@yield('page-name')
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">User</li>
                    </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4 class="card-title">List of all Users</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col mt-2 ">
                    <form id="filter-user">
                        <div class="row mb-3">
                            <div class="col mt-2 ml-4">

                                <select class="form-select select2" aria-label="Default select example" id="select-branch">
                                    <option value="">--Select Branch--</option>
                                    @foreach ($branches as $item)
                                        <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mt-2 ml-4">

                                <select class="form-select select2" aria-label="Default select example" id="select-program">
                                    <option value="">--Select Program--</option>
                                    @foreach ($programs as $item)
                                        <option value="{{ $item->prog_code }}">{{ $item->prog_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mt-2 ml-4">

                                <select class="form-select select2" aria-label="Default select example"
                                    id="select-department">
                                    <option value="">--Select Department--</option>
                                    @foreach ($departments as $item)
                                        <option value="{{ $item->dept_code }}">{{ $item->dept_desc }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col" style="padding-top: 5px;">
                                <button class="btn btn-md btn-outline-primary" type="submit" form="filter-user"><i
                                        class="fa fa-filter"></i></button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="table-">
                <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                    width='100%' id="user-table">
                    <thead>
                        <tr>
                            <th>User Id</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data is fetched here using ajax --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('modules.usermanagement.modals.add_user')
    @include('modules.usermanagement.modals.edit_user')
    @include('modules.usermanagement.modals.user_info')
    <script>
        var userTable = $('#user-table').DataTable({
            "lengthChange": false,
            dom: 'Bfrtip',
            ajax: {
                url: `${appUrl}/api/user/${school_code}`,
                type: "GET",
            },
            processing: true,
            responsive: true,
            // pageLength: 15,
            columns: [{
                    data: "userId"
                },

                {
                    data: "Email"
                },
                {
                    data: "Phone"
                },

                {
                    data: "action"
                }
            ],
            buttons: [{
                    extend: 'print',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    title: `${loggedInUserSchoolName} - Staff List`,
                    attr: {
                        class: "btn btn-sm btn-info rounded-right"
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
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
                    text: "Add User",
                    attr: {
                        class: "ml-2 btn-primary btn btn-sm rounded"
                    },
                    action: function(e, dt, node, config) {
                        $("#add-user-modal").modal("show")
                    }
                },
            ]
        });
        //Deleting user 
        $('#user-table').on("click", ".delete-btn", function() {
            var userdata = userTable.row($(this).parents('tr')).data()
            swal.fire({
                title: '',
                text: 'Are you sure you want to delete user?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.value) {
                    swal.fire({
                        text: 'Deleting...',
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });
                    $.ajax({
                        url: `${appUrl}/api/user/delete/${userdata.Email}`,
                        type: 'post'
                    }).done(function(data) {
                        if (!data.ok) {
                            Swal.fire({
                                text: data.msg,
                                type: "error"
                            });
                            return;
                        }
                        Swal.fire({
                            text: "User member deleted successfully",
                            type: "success"
                        });
                        userTable.ajax.reload(false, null);
                    }).fail(() => {
                        alert('Processing failed!')
                    })
                }
            })
        })
        //filtering users
        var formdata = {}
        $('#filter-user').submit(function(e) {
            e.preventDefault()
            var selectprogram = $("#select-program").val()
            var selectbranch = $("#select-branch").val()
            var selectdepartment = $("#select-department").val()
            formdata = {
                "school": `${school_code}`,
                "program": selectprogram,
                "branch": selectbranch,
                "department": selectdepartment

            }
            //checking if the object is not null
            if (formdata["program"] !== "" || formdata["branch"] !== "" || formdata["department"] !== "") {
                formdata = JSON.stringify(formdata)
                userTable.ajax.url(`${appUrl}/api/user/filteruser/${formdata}`).load()

            }
            $("select").val(null).trigger('change');

          
        })
    </script>

@endsection
