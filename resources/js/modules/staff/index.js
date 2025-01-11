

$("#staff-table").DataTable({
    dom: "Bfrtip",
    order: [],
    ordering: true,
    // ajax: {
    //     url: `/api/student`,
    //     type: "GET"
    // },
    processing: true,
    // responsive: true,
    autoWidth: false,
    columns: [
        
        {
            data:"studentname"
        }, 
        {
           data:"studentid"
        },
        {
            data:"action",
        }
        
    ],
    buttons: [
        {
            text: "Add Department",
            attr: {
                class: "ml-2 btn-primary btn btn-sm rounded"
            },
            action: function (e, dt, node, config) {
                $("#add-student-modal").modal("show");
            }
        },
       
        {
            text: "Refresh",
            attr: {
                class: "ml-2 btn-secondary btn btn-sm rounded"
            },
            action: function (e, dt, node, config) {
                dt.ajax.reload(false, null);
            }
        },
      
    ],
});
