@extends('layout.app')

@section('page-content')
<style>
    /* zoom icon on hover */
    .zoom {
        transition: transform .3s;
    }
    .zoom:hover {
        transform: scale(1.1);
        /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    }
</style>
    <!-- modules-->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card zoom shadow h-100 py-2 bg-info">
                <a href="{{ config('app.url') }}/courses">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Total Courses </div>
                                <div class="h5 mb-0 font-weight-bold text-white">
                                    {{ $courses }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-female fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card zoom shadow h-100 py-2 " style="background-color: #f59b26">
                <a href="{{ config('app.url') }}/program">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Total Programs </div>
                                <div class="h5 mb-0 font-weight-bold text-white">
                                    {{ $prog }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card zoom shadow h-100 py-2 bg-success">
                <a href="{{ config('app.url') }}/student">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Total Students </div>
                                <div class="h5 mb-0 font-weight-bold text-white">
                                    {{ $students }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card zoom shadow h-100 py-2 bg-primary">
                <a href="{{ config('app.url') }}/staff">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Total Staff</div>
                                <div class="h5 mb-0 font-weight-bold text-white">
                                    {{ $staff }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12 mb-4">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 text-primary">Total Students In a Programme</h6>

                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <canvas id="myChart" width="100%" height="35"></canvas>
                    <!-- <div id="barContainer" style="height: 600px; width: 100%;"></div> -->
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5 mb-5">
        <div class="col-md-6 col-lg-12">
            <div class="card card-chart shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 text-primary">Programme Breakdown</h6>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-striped table-hover dataTable js-exportable"
                            id="class-breakdown-table" style="width: 100%">
                            <thead class="">
                                <tr>
                                    <th>Programme</th>
                                    <th>Male</th>
                                    <th>Female</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2020 <a
                            href="https://www.bootstrapdash.com/" class="text-muted" target="_blank">Bootstrapdash</a>.
                        All rights reserved.</span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center text-muted">Free <a
                            href="https://www.bootstrapdash.com/" class="text-muted" target="_blank">Bootstrap
                            dashboard</a> templates from Bootstrapdash.com</span>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.4.1/chart.min.js"
        integrity="sha512-5vwN8yor2fFT9pgPS9p9R7AszYaNn0LkQElTXIsZFCL7ucT8zDCAqlQXDdaqgA1mZP47hdvztBMsIoFxq/FyyQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // $("#notice-table").DataTable({
        //     dom: "tp",
        //     ajax: {
        //         url: `${appUrl}/api/notice/fetch_all_notice/${school_code}`,
        //         type: "GET",
        //         headers: {
        //             "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE"
        //         }
        //     },
        //     pageLength: "5",
        //     ordering: false,
        //     sorting: false,
        //     processing: true,
        //     responsive: true,
        //     columns: [{
        //             data: "news_title"
        //         },
        //         {
        //             data: "news_details"
        //         },
        //         {
        //             data: "post"
        //         },
        //     ],
        // });
        document.addEventListener("DOMContentLoaded", () => {
            //Render total students in class
            $.ajax({
                url: `${appUrl}/api/dashboard/total_student_by_prog/${school_code}`,
                type: 'GET',
            }).done(function(data) {

                let totals = [];
                let labels = [];

                data.data.forEach(function(grade) {
                    totals.push(grade.total_grade);
                    labels.push(grade.prog_desc);
                });


                var ctx = document.getElementById('myChart');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Students',
                            data: totals,
                            backgroundColor: [
                                'rgba(255, 205, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(201, 203, 207, 0.2)'
                            ],
                            borderColor: [
                                'rgb(255, 205, 86)',
                                'rgb(75, 192, 192)',
                                'rgb(255, 99, 132)',
                                'rgb(255, 159, 64)',
                                'rgb(54, 162, 235)',
                                'rgb(153, 102, 255)',
                                'rgb(201, 203, 207)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }

                });
            });

            // Render payment chart
            // $.ajax({
            //     url: `${appUrl}/api/dashboard/fetch_fullpayment/${school_code}`,
            //     type: 'GET',
            //     headers: {
            //         "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
            //     }
            // }).done(function(data) {
            //     // console.log(data);
            //     // console.log(data.debt[0].debt);
            //     // return
            //     let debts = [];

            //     data.debt.forEach(function(debt) {
            //         debts.push(debt.debt);
            //     });

            //     data.fullpayment.forEach(function(debt) {
            //         debts.push(debt.fullpayment);
            //     });
            //     let ctx = document.getElementById('nutContainer').getContext('2d');
            //     var myChart = new Chart(ctx, {
            //         type: 'bar',
            //         data: {
            //             labels: ["Debtors", "Full Payment"],
            //             datasets: [{
            //                 label: 'School Fees Report',
            //                 data: debts,
            //                 backgroundColor: [
            //                     'rgba(255, 99, 132, 0.2)',
            //                     'rgba(255, 159, 64, 0.2)',
            //                     // 'rgba(255, 205, 86, 0.2)',
            //                     // 'rgba(75, 192, 192, 0.2)',
            //                     // 'rgba(54, 162, 235, 0.2)',
            //                     // 'rgba(153, 102, 255, 0.2)',
            //                     // 'rgba(201, 203, 207, 0.2)'
            //                 ],
            //                 borderColor: [
            //                     'rgb(255, 99, 132)',
            //                     'rgb(255, 159, 64)',
            //                     // 'rgb(255, 205, 86)',
            //                     // 'rgb(75, 192, 192)',
            //                     // 'rgb(54, 162, 235)',
            //                     // 'rgb(153, 102, 255)',
            //                     // 'rgb(201, 203, 207)'
            //                 ],
            //                 borderWidth: 1

            //             }]
            //         },
            //         options: {
            //             scales: {
            //                 yAxes: [{
            //                     ticks: {
            //                         beginAtZero: true
            //                     }
            //                 }]
            //             }
            //         }

            //     });
            // });

            //Render gender stats
            // $.ajax({
            //     url: `${appUrl}/api/dashboard/fetch_gender/${school_code}`,
            //     type: 'GET',
            //     headers: {
            //         "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE",
            //     }
            // }).done(function(data) {
            //     console.log(data.female);
            //     // console.log(data.debt[0].debt);
            //     // return
            //     var pie = document.getElementById('gender-stats').getContext('2d');
            //     var myPie = new Chart(pie, {
            //         type: 'pie',
            //         data: {
            //             labels: ['Male', 'Female'],
            //             datasets: [{
            //                 backgroundColor: [
            //                     'rgb(255, 99, 132)',
            //                     'rgb(54, 162, 235)',
            //                     'rgb(255, 205, 86)'
            //                 ],
            //                 data: [data.male, data.female]
            //             }]
            //         },
            //         backgroundColor: [
            //             'rgb(255, 99, 132)',
            //             'rgb(54, 162, 235)',
            //             'rgb(255, 205, 86)'
            //         ],

            //         options: {
            //             title: {
            //                 display: true,
            //                 text: 'Student Stats'
            //             }
            //         }
            //     });
            // });

            // Render regional case breakdown table
            var classBreakDownTable = $("#class-breakdown-table").DataTable({
                dom: "rtp",
                ajax: {
                    url: `${appUrl}/api/dashboard/fetch_class_breakdown/${school_code}`,
                    type: "GET",
                },
                processing: true,
                responsive: true,
                columns: [{
                        data: "class"
                    },
                    {
                        data: "male"
                    },
                    {
                        data: "female"
                    },
                    {
                        data: "total"
                    },

                ],
            });

            // Render regional case breakdown table
            // var programTable = $("#program-table").DataTable({
            //     dom: "rtp",
            //     ajax: {
            //         url: `${appUrl}/api/dashboard/fetch_program_breakdown/${school_code}`,
            //         type: "GET",
            //         headers: {
            //             "Authorization": "d16xA0oqWRi2barEd1Ru3JVM3uveym6nw2ntVsfSUl0kf8T5XNVhSykpoqswweeJI7OjiYTc1rtkDTKE"
            //         }
            //     },
            //     processing: true,
            //     responsive: true,
            //     columns: [{
            //             data: "program"
            //         },
            //         {
            //             data: "male"
            //         },
            //         {
            //             data: "female"
            //         },
            //         {
            //             data: "total"
            //         },

            //     ],
            //     buttons: [{
            //         text: "Refresh",
            //         attr: {
            //             class: "ml-2 btn-secondary btn btn-sm rounded"
            //         },
            //         action: function(e, dt, node, config) {
            //             dt.ajax.reload(false, null);
            //         }
            //     }],
            // });
        });
    </script>
@endsection
<!-- main content here -->






<!-- partial:partials/_settings-panel.html -->
<!-- <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="typcn typcn-cog-outline"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close typcn typcn-times"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
    -->
<!-- partial -->

<!-- partial -->
