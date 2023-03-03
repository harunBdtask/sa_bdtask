<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="row">
        <div class="col-sm-6 col-md-12 col-lg-6 col-xl-4 d-flex mb-3">
            <div
                class="info-box d-flex position-relative rounded flex-fill w-100 bg-warning overflow-hidden gradient-nine">
                <div class="position-br mb-n5 mr-n5 radius-round bgc-purple-l3 opacity-3"
                    style="width: 8rem; height: 8rem;"></div>
                <div class="position-br mb-n5 mr-n5 radius-round bgc-purple-l2 opacity-5"
                    style="width: 6.5rem; height: 6.5rem;"></div>
                <div class="position-br mb-n5 mr-n5 radius-round bgc-purple-l1 opacity-5"
                    style="width: 5rem; height: 5rem;"></div>
                <span class="info-box-icon d-flex align-self-center text-center">
                    <img src="<?php echo base_url() ?>/assets/dist/img/icon/png/007-factory-1.png" alt="" height="64" width="64">
                </span>
                <div class="info-box-content d-flex flex-column justify-content-center">
                    <span class="info-box-text fw-bold fs-17px">Total Employment</span>
                    <span class="info-box-number d-block fw-black counter"><?php echo $total_employees > 0?$total_employees:'0';?></span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <!-- <div class="progress-description fs-14"><i class="fa fa-caret-down"></i> 70%
                        Increase in 30 Days</div> -->
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-sm-6 col-md-12 col-lg-6 col-xl-4 d-flex mb-3">
            <div
                class="info-box d-flex position-relative rounded flex-fill w-100 bg-success overflow-hidden gradient-sixteen">
                <div class="position-br mb-n5 mr-n5 radius-round bgc-purple-l3 opacity-3"
                    style="width: 8rem; height: 8rem;"></div>
                <div class="position-br mb-n5 mr-n5 radius-round bgc-purple-l2 opacity-5"
                    style="width: 6.5rem; height: 6.5rem;"></div>
                <div class="position-br mb-n5 mr-n5 radius-round bgc-purple-l1 opacity-5"
                    style="width: 5rem; height: 5rem;"></div>
                <span class="info-box-icon d-flex align-self-center text-center">
                    <img src="<?php echo base_url() ?>/assets/dist/img/icon/png/001-economic-disparities.png" alt="" height="64"
                        width="64">
                </span>
                <div class="info-box-content d-flex flex-column justify-content-center">
                    <span class="info-box-text fw-bold fs-17px">Active Employee</span>
                    <span class="info-box-number d-block fw-black counter"><?php echo $active_employees > 0?$active_employees:'0';?></span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <!-- <div class="progress-description fs-14"><i class="fa fa-caret-up"></i> 16%
                        increase compare to last week</div> -->
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-sm-6 col-md-12 col-lg-6 col-xl-4 d-flex mb-3">
            <div class="info-box d-flex position-relative rounded flex-fill w-100 overflow-hidden gradient-six">
          <div class=" position-br mb-n5 mr-n5 radius-round bgc-purple-l3 opacity-3"
                style="width: 8rem; height: 8rem;"></div>
            <div class="position-br mb-n5 mr-n5 radius-round bgc-purple-l2 opacity-5"
                style="width: 6.5rem; height: 6.5rem;"></div>
            <div class="position-br mb-n5 mr-n5 radius-round bgc-purple-l1 opacity-5"
                style="width: 5rem; height: 5rem;"></div>
            <span class="info-box-icon d-flex align-self-center text-center">
                <img src="<?php echo base_url() ?>/assets/dist/img/icon/png/006-manufacturing.png" alt="" height="64" width="64">
            </span>
            <div class="info-box-content d-flex flex-column justify-content-center">
                <span class="info-box-text fw-bold fs-17px">Inactive Employee</span>
                <span class="info-box-number d-block fw-black counter"><?php echo $inactive_employees > 0?$inactive_employees:'0';?></span>
                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <!-- <div class="progress-description fs-14"><i class="fa fa-caret-down"></i> 70%
                    Increase in 30 Days</div> -->
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>

<div class="row">
    <div class="col-md-12 col-xl-8 d-flex">
        <div class="card mb-3 flex-fill w-100">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Attendance (Current Month)</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a href="#" class="action-item"><i class="ti-reload"></i></a>
                            <div class="dropdown action-item" data-toggle="dropdown">
                                <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">Refresh</a>
                                    <a href="#" class="dropdown-item">Manage Widgets</a>
                                    <a href="#" class="dropdown-item">Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="barChart" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-4 d-flex">
        <div class="card mb-3 flex-fill w-100">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Best employees of the month</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a href="#" class="action-item"><i class="ti-reload"></i></a>
                            <div class="dropdown action-item" data-toggle="dropdown">
                                <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">Refresh</a>
                                    <a href="#" class="dropdown-item">Manage Widgets</a>
                                    <a href="#" class="dropdown-item">Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display table-bordered table-striped table-hover dynamic-height">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Previous month avg. present</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($best_employees as $key => $row) { ?>
                                <tr>
                                    <th><?php echo $row['employee_name'];?></th>
                                    <td><?php echo number_format($row['avg_present'] ,2);?>%</td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 d-flex">
        <div class="card mb-3 flex-fill w-100">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Department wise strength</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a href="#" class="action-item"><i class="ti-reload"></i></a>
                            <div class="dropdown action-item" data-toggle="dropdown">
                                <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">Refresh</a>
                                    <a href="#" class="dropdown-item">Manage Widgets</a>
                                    <a href="#" class="dropdown-item">Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="lineChart" height="80"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-4 d-flex">
        <div class="card mb-3 flex-fill w-100">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Designation wise strength</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a href="#" class="action-item"><i class="ti-reload"></i></a>
                            <div class="dropdown action-item" data-toggle="dropdown">
                                <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">Refresh</a>
                                    <a href="#" class="dropdown-item">Manage Widgets</a>
                                    <a href="#" class="dropdown-item">Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display table-bordered table-striped table-hover dynamic-height">
                        <thead>
                            <tr>
                                <th>Designation</th>
                                <th>Total employees</th>
                                <th>Male</th>
                                <th>Female</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($designation_wise_strgth_data as $key => $designation_wise_strgth) { ?>
                                <tr>
                                    <th><?php echo $designation_wise_strgth['designation'];?></th>
                                    <td><?php echo $designation_wise_strgth['total_employees'];?></td>
                                    <td><?php echo $designation_wise_strgth['male_employees'];?></td>
                                    <td><?php echo $designation_wise_strgth['female_employees'];?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-8 d-flex">
        <div class="card mb-3 flex-fill w-100">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Type of employee</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a href="#" class="action-item"><i class="ti-reload"></i></a>
                            <div class="dropdown action-item" data-toggle="dropdown">
                                <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">Refresh</a>
                                    <a href="#" class="dropdown-item">Manage Widgets</a>
                                    <a href="#" class="dropdown-item">Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="barChartTwo" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-8 d-flex">
        <div class="card flex-fill w-100">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Employee status</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a href="#" class="action-item"><i class="ti-reload"></i></a>
                            <div class="dropdown action-item" data-toggle="dropdown">
                                <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">Refresh</a>
                                    <a href="#" class="dropdown-item">Manage Widgets</a>
                                    <a href="#" class="dropdown-item">Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="barChartThree" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-4 d-flex">
        <div class="card flex-fill w-100">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Employee on leave (Current Month)</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a href="#" class="action-item"><i class="ti-reload"></i></a>
                            <div class="dropdown action-item" data-toggle="dropdown">
                                <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">Refresh</a>
                                    <a href="#" class="dropdown-item">Manage Widgets</a>
                                    <a href="#" class="dropdown-item">Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display table-bordered table-striped table-hover dynamic-height">
                        <thead>
                            <tr>
                                <th>Employee name</th>
                                <th>Designation</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                                <th>1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                            </tr> -->
                            <?php foreach ($employee_on_leave as $key => $emp_on_leave) { ?>
                                <tr>
                                    <th><?php echo $emp_on_leave['first_name'].' '.$emp_on_leave['last_name'];?></th>
                                    <td><?php echo $emp_on_leave['designation_name'];?></td>
                                    <td><?php echo $emp_on_leave['department_name'];?></td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!--/.body content-->

<script>
    $(document).ready(function () {
        "use strict"; // Start of use strict

        // Set the Ajax cal method to use everywhere in this page..
        function getJsonData(submit_url){

            var respoData = null;

            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                async: false,
                success: function(res) {
                    respoData = res;
                }
            }); 

            return respoData;
        }

        // var deptmnt_wise_strength = getJsonData(_baseURL+"dashboard/deptmnt_wise_strength");
        // console.log(deptmnt_wise_strength);

        // End

        // Setting dynamic height for the datatable
        $('.dynamic-height').DataTable({
            scrollY: '35vh',
            scrollCollapse: true,
            paging: false,
            searching: false
        });
        // End

        //bar chart
        var chartColors = {
            gray: '#e4e4e4',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: '#7474BF',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(231,233,237)'
        };

        // draws a rectangle with a rounded top
        Chart.helpers.drawRoundedTopRectangle = function (ctx, x, y, width, height, radius) {
            ctx.beginPath();
            ctx.moveTo(x + radius, y);
            // top right corner
            ctx.lineTo(x + width - radius, y);
            ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
            // bottom right corner
            ctx.lineTo(x + width, y + height);
            // bottom left corner
            ctx.lineTo(x, y + height);
            // top left 
            ctx.lineTo(x, y + radius);
            ctx.quadraticCurveTo(x, y, x + radius, y);
            ctx.closePath();
        };

        Chart.elements.RoundedTopRectangle = Chart.elements.Rectangle.extend({
            draw: function () {
                var ctx = this._chart.ctx;
                var vm = this._view;
                var left, right, top, bottom, signX, signY, borderSkipped;
                var borderWidth = vm.borderWidth;

                if (!vm.horizontal) {
                    // bar
                    left = vm.x - vm.width / 2;
                    right = vm.x + vm.width / 2;
                    top = vm.y;
                    bottom = vm.base;
                    signX = 1;
                    signY = bottom > top ? 1 : -1;
                    borderSkipped = vm.borderSkipped || 'bottom';
                } else {
                    // horizontal bar
                    left = vm.base;
                    right = vm.x;
                    top = vm.y - vm.height / 2;
                    bottom = vm.y + vm.height / 2;
                    signX = right > left ? 1 : -1;
                    signY = 1;
                    borderSkipped = vm.borderSkipped || 'left';
                }

                // Canvas doesn't allow us to stroke inside the width so we can
                // adjust the sizes to fit if we're setting a stroke on the line
                if (borderWidth) {
                    // borderWidth shold be less than bar width and bar height.
                    var barSize = Math.min(Math.abs(left - right), Math.abs(top - bottom));
                    borderWidth = borderWidth > barSize ? barSize : borderWidth;
                    var halfStroke = borderWidth / 2;
                    // Adjust borderWidth when bar top position is near vm.base(zero).
                    var borderLeft = left + (borderSkipped !== 'left' ? halfStroke * signX : 0);
                    var borderRight = right + (borderSkipped !== 'right' ? -halfStroke * signX : 0);
                    var borderTop = top + (borderSkipped !== 'top' ? halfStroke * signY : 0);
                    var borderBottom = bottom + (borderSkipped !== 'bottom' ? -halfStroke * signY : 0);
                    // not become a vertical line?
                    if (borderLeft !== borderRight) {
                        top = borderTop;
                        bottom = borderBottom;
                    }
                    // not become a horizontal line?
                    if (borderTop !== borderBottom) {
                        left = borderLeft;
                        right = borderRight;
                    }
                }

                // calculate the bar width and roundess
                var barWidth = Math.abs(left - right);
                var roundness = this._chart.config.options.barRoundness || 0.5;
                var radius = barWidth * roundness * 0.5;

                // keep track of the original top of the bar
                var prevTop = top;

                // move the top down so there is room to draw the rounded top
                top = prevTop + radius;
                var barRadius = top - prevTop;

                ctx.beginPath();
                ctx.fillStyle = vm.backgroundColor;
                ctx.strokeStyle = vm.borderColor;
                ctx.lineWidth = borderWidth;

                // draw the rounded top rectangle
                Chart.helpers.drawRoundedTopRectangle(ctx, left, (top - barRadius + 1), barWidth, bottom - prevTop, barRadius);

                ctx.fill();
                if (borderWidth) {
                    ctx.stroke();
                }

                // restore the original top value so tooltips and scales still work
                top = prevTop;
            }
        });

        Chart.defaults.roundedBar = Chart.helpers.clone(Chart.defaults.bar);

        Chart.controllers.roundedBar = Chart.controllers.bar.extend({
            dataElementType: Chart.elements.RoundedTopRectangle
        });

        // Attendance Graph Report
        var daily_attendnace = getJsonData(_baseURL+"dashboard/daily_attendnace_emp");
        // console.log(daily_attendnace);

        var labelData = daily_attendnace.days;
        var presentData = daily_attendnace.respo_present;
        var absentData = daily_attendnace.respo_absent;

        // var labelData = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];
        // var currentMonth = [25, 20, 30, 22, 17, 10, 18, 26, 28, 26, 20, 32, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        // var previousData = [15, 10, 20, 12, 6, 7, 10, 15, 15, 20, 15, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        var ctx = document.getElementById("barChart").getContext("2d");
        var myBar = new Chart(ctx, {
            type: 'roundedBar',
            data: {
                labels: labelData,
                datasets: [{
                    label: 'Present',
                    backgroundColor: chartColors.green,
                    data: presentData
                }, {
                    label: 'Absent',
                    backgroundColor: chartColors.gray,
                    data: absentData
                }]
            },
            options: {
                legend: false,
                responsive: true,
                barRoundness: 1,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            padding: 10
                        },
                        gridLines: {
                            borderDash: [2],
                            borderDashOffset: [2],
                            drawBorder: false,
                            drawTicks: false
                        }
                    }],
                    xAxes: [{
                        maxBarThickness: 15,
                        gridLines: {
                            lineWidth: [0],
                            drawBorder: false,
                            drawOnChartArea: false,
                            drawTicks: false
                        },
                        ticks: {
                            padding: 20
                        }
                    }]
                }
            }
        });
    });
</script>
 <script>
    $(document).ready(function () {
        "use strict"; // Start of use strict

        // Set the Ajax cal method to use everywhere in this page..
        function getJsonDataThree(submit_url){

            var respoData = null;

            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                async: false,
                success: function(res) {
                    respoData = res;
                }
            }); 

            return respoData;
        }

        // var emp_type_wise_data = getJsonDataThree(_baseURL+"dashboard/emp_type_wise_data");
        // console.log(emp_type_wise_data);

        // End

        //bar chart
        var chartColors = {
            gray: '#e4e4e4',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: '#7474BF',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(231,233,237)'
        };

        // draws a rectangle with a rounded top
        Chart.helpers.drawRoundedTopRectangle = function (ctx, x, y, width, height, radius) {
            ctx.beginPath();
            ctx.moveTo(x + radius, y);
            // top right corner
            ctx.lineTo(x + width - radius, y);
            ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
            // bottom right corner
            ctx.lineTo(x + width, y + height);
            // bottom left corner
            ctx.lineTo(x, y + height);
            // top left 
            ctx.lineTo(x, y + radius);
            ctx.quadraticCurveTo(x, y, x + radius, y);
            ctx.closePath();
        };

        Chart.elements.RoundedTopRectangle = Chart.elements.Rectangle.extend({
            draw: function () {
                var ctx = this._chart.ctx;
                var vm = this._view;
                var left, right, top, bottom, signX, signY, borderSkipped;
                var borderWidth = vm.borderWidth;

                if (!vm.horizontal) {
                    // bar
                    left = vm.x - vm.width / 2;
                    right = vm.x + vm.width / 2;
                    top = vm.y;
                    bottom = vm.base;
                    signX = 1;
                    signY = bottom > top ? 1 : -1;
                    borderSkipped = vm.borderSkipped || 'bottom';
                } else {
                    // horizontal bar
                    left = vm.base;
                    right = vm.x;
                    top = vm.y - vm.height / 2;
                    bottom = vm.y + vm.height / 2;
                    signX = right > left ? 1 : -1;
                    signY = 1;
                    borderSkipped = vm.borderSkipped || 'left';
                }

                // Canvas doesn't allow us to stroke inside the width so we can
                // adjust the sizes to fit if we're setting a stroke on the line
                if (borderWidth) {
                    // borderWidth shold be less than bar width and bar height.
                    var barSize = Math.min(Math.abs(left - right), Math.abs(top - bottom));
                    borderWidth = borderWidth > barSize ? barSize : borderWidth;
                    var halfStroke = borderWidth / 2;
                    // Adjust borderWidth when bar top position is near vm.base(zero).
                    var borderLeft = left + (borderSkipped !== 'left' ? halfStroke * signX : 0);
                    var borderRight = right + (borderSkipped !== 'right' ? -halfStroke * signX : 0);
                    var borderTop = top + (borderSkipped !== 'top' ? halfStroke * signY : 0);
                    var borderBottom = bottom + (borderSkipped !== 'bottom' ? -halfStroke * signY : 0);
                    // not become a vertical line?
                    if (borderLeft !== borderRight) {
                        top = borderTop;
                        bottom = borderBottom;
                    }
                    // not become a horizontal line?
                    if (borderTop !== borderBottom) {
                        left = borderLeft;
                        right = borderRight;
                    }
                }

                // calculate the bar width and roundess
                var barWidth = Math.abs(left - right);
                var roundness = this._chart.config.options.barRoundness || 0.5;
                var radius = barWidth * roundness * 0.5;

                // keep track of the original top of the bar
                var prevTop = top;

                // move the top down so there is room to draw the rounded top
                top = prevTop + radius;
                var barRadius = top - prevTop;

                ctx.beginPath();
                ctx.fillStyle = vm.backgroundColor;
                ctx.strokeStyle = vm.borderColor;
                ctx.lineWidth = borderWidth;

                // draw the rounded top rectangle
                Chart.helpers.drawRoundedTopRectangle(ctx, left, (top - barRadius + 1), barWidth, bottom - prevTop, barRadius);

                ctx.fill();
                if (borderWidth) {
                    ctx.stroke();
                }

                // restore the original top value so tooltips and scales still work
                top = prevTop;
            }
        });

        Chart.defaults.roundedBar = Chart.helpers.clone(Chart.defaults.bar);

        Chart.controllers.roundedBar = Chart.controllers.bar.extend({
            dataElementType: Chart.elements.RoundedTopRectangle
        });

        // Ajax response data...
        var emp_type_wise_data = getJsonDataThree(_baseURL+"dashboard/emp_type_wise_data");

        var emp_type_labels      = emp_type_wise_data.employee_types;
        var emp_type_wise_male   = emp_type_wise_data.male;
        var emp_type_wise_female = emp_type_wise_data.female;

        // var emp_type_labels      = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        // var emp_type_wise_male   = [20, 20, 30, 22, 17, 10, 18, 26, 28, 26, 20, 32];
        // var emp_type_wise_female = [15, 10, 20, 12, 6, 7, 10, 15, 15, 20, 15, 20];

        var ctx = document.getElementById("barChartTwo").getContext("2d");
        var myBar = new Chart(ctx, {
            type: 'roundedBar',
            data: {
                labels: emp_type_labels,
                datasets: [{
                    label: 'Male',
                    backgroundColor: chartColors.green,
                    data: emp_type_wise_male
                }, {
                    label: 'Female',
                    backgroundColor: chartColors.gray,
                    data: emp_type_wise_female
                }]
            },
            options: {
                legend: false,
                responsive: true,
                barRoundness: 1,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            padding: 10
                        },
                        gridLines: {
                            borderDash: [2],
                            borderDashOffset: [2],
                            drawBorder: false,
                            drawTicks: false
                        }
                    }],
                    xAxes: [{
                        maxBarThickness: 15,
                        gridLines: {
                            lineWidth: [0],
                            drawBorder: false,
                            drawOnChartArea: false,
                            drawTicks: false
                        },
                        ticks: {
                            padding: 20
                        }
                    }]
                }
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        "use strict"; // Start of use strict

        // Set the Ajax cal method to use everywhere in this page..
        function getJsonDataFour(submit_url){

            var respoData = null;

            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                async: false,
                success: function(res) {
                    respoData = res;
                }
            }); 

            return respoData;
        }

        // var emp_status_wise_data = getJsonDataFour(_baseURL+"dashboard/emp_status_wise_data");
        // console.log(emp_status_wise_data);

        // End

        //bar chart
        var chartColors = {
            gray: '#e4e4e4',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: '#7474BF',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(231,233,237)'
        };

        // draws a rectangle with a rounded top
        Chart.helpers.drawRoundedTopRectangle = function (ctx, x, y, width, height, radius) {
            ctx.beginPath();
            ctx.moveTo(x + radius, y);
            // top right corner
            ctx.lineTo(x + width - radius, y);
            ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
            // bottom right corner
            ctx.lineTo(x + width, y + height);
            // bottom left corner
            ctx.lineTo(x, y + height);
            // top left 
            ctx.lineTo(x, y + radius);
            ctx.quadraticCurveTo(x, y, x + radius, y);
            ctx.closePath();
        };

        Chart.elements.RoundedTopRectangle = Chart.elements.Rectangle.extend({
            draw: function () {
                var ctx = this._chart.ctx;
                var vm = this._view;
                var left, right, top, bottom, signX, signY, borderSkipped;
                var borderWidth = vm.borderWidth;

                if (!vm.horizontal) {
                    // bar
                    left = vm.x - vm.width / 2;
                    right = vm.x + vm.width / 2;
                    top = vm.y;
                    bottom = vm.base;
                    signX = 1;
                    signY = bottom > top ? 1 : -1;
                    borderSkipped = vm.borderSkipped || 'bottom';
                } else {
                    // horizontal bar
                    left = vm.base;
                    right = vm.x;
                    top = vm.y - vm.height / 2;
                    bottom = vm.y + vm.height / 2;
                    signX = right > left ? 1 : -1;
                    signY = 1;
                    borderSkipped = vm.borderSkipped || 'left';
                }

                // Canvas doesn't allow us to stroke inside the width so we can
                // adjust the sizes to fit if we're setting a stroke on the line
                if (borderWidth) {
                    // borderWidth shold be less than bar width and bar height.
                    var barSize = Math.min(Math.abs(left - right), Math.abs(top - bottom));
                    borderWidth = borderWidth > barSize ? barSize : borderWidth;
                    var halfStroke = borderWidth / 2;
                    // Adjust borderWidth when bar top position is near vm.base(zero).
                    var borderLeft = left + (borderSkipped !== 'left' ? halfStroke * signX : 0);
                    var borderRight = right + (borderSkipped !== 'right' ? -halfStroke * signX : 0);
                    var borderTop = top + (borderSkipped !== 'top' ? halfStroke * signY : 0);
                    var borderBottom = bottom + (borderSkipped !== 'bottom' ? -halfStroke * signY : 0);
                    // not become a vertical line?
                    if (borderLeft !== borderRight) {
                        top = borderTop;
                        bottom = borderBottom;
                    }
                    // not become a horizontal line?
                    if (borderTop !== borderBottom) {
                        left = borderLeft;
                        right = borderRight;
                    }
                }

                // calculate the bar width and roundess
                var barWidth = Math.abs(left - right);
                var roundness = this._chart.config.options.barRoundness || 0.5;
                var radius = barWidth * roundness * 0.5;

                // keep track of the original top of the bar
                var prevTop = top;

                // move the top down so there is room to draw the rounded top
                top = prevTop + radius;
                var barRadius = top - prevTop;

                ctx.beginPath();
                ctx.fillStyle = vm.backgroundColor;
                ctx.strokeStyle = vm.borderColor;
                ctx.lineWidth = borderWidth;

                // draw the rounded top rectangle
                Chart.helpers.drawRoundedTopRectangle(ctx, left, (top - barRadius + 1), barWidth, bottom - prevTop, barRadius);

                ctx.fill();
                if (borderWidth) {
                    ctx.stroke();
                }

                // restore the original top value so tooltips and scales still work
                top = prevTop;
            }
        });

        Chart.defaults.roundedBar = Chart.helpers.clone(Chart.defaults.bar);

        Chart.controllers.roundedBar = Chart.controllers.bar.extend({
            dataElementType: Chart.elements.RoundedTopRectangle
        });

        // Ajax response data to show in the chart
        var emp_status_wise_data = getJsonDataFour(_baseURL+"dashboard/emp_status_wise_data");
        // console.log(emp_status_wise_data);

        var emp_status_wis_label  = emp_status_wise_data.employee_status;
        var emp_status_wis_male   = emp_status_wise_data.male;
        var emp_status_wis_female = emp_status_wise_data.female;

        // var emp_status_wis_label  = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        // var emp_status_wis_male   = [25, 20, 30, 22, 17, 10, 18, 26, 28, 26, 20, 32];
        // var emp_status_wis_female = [15, 10, 20, 12, 6, 7, 10, 15, 15, 20, 15, 20];

        var ctx = document.getElementById("barChartThree").getContext("2d");
        var myBar = new Chart(ctx, {
            type: 'roundedBar',
            data: {
                labels: emp_status_wis_label,
                datasets: [{
                    label: 'Male',
                    backgroundColor: chartColors.green,
                    data: emp_status_wis_male
                }, {
                    label: 'Female',
                    backgroundColor: chartColors.gray,
                    data: emp_status_wis_female
                }]
            },
            options: {
                legend: false,
                responsive: true,
                barRoundness: 1,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            padding: 10
                        },
                        gridLines: {
                            borderDash: [2],
                            borderDashOffset: [2],
                            drawBorder: false,
                            drawTicks: false
                        }
                    }],
                    xAxes: [{
                        maxBarThickness: 15,
                        gridLines: {
                            lineWidth: [0],
                            drawBorder: false,
                            drawOnChartArea: false,
                            drawTicks: false
                        },
                        ticks: {
                            padding: 20
                        }
                    }]
                }
            }
        });
    });
</script>
<script>
    (function ($) {
        "use strict";

        // Set the Ajax cal method to use everywhere in this page..
        function getJsonDataTwo(submit_url){

            var respoData = null;

            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                async: false,
                success: function(res) {
                    respoData = res;
                }
            }); 

            return respoData;
        }

        var deptmnt_wise_strength = getJsonDataTwo(_baseURL+"dashboard/deptmnt_wise_strength");
        // console.log(deptmnt_wise_strength);

        // End

        var dept_wise_strength_label  = deptmnt_wise_strength.departments;
        var dept_wise_strength_male   = deptmnt_wise_strength.male;
        var dept_wise_strength_female = deptmnt_wise_strength.female;

        // var dept_wise_strength_label  = ['2 Dec', '4 Dec', '6 Dec', '8 Dec', '10 Dec', '12 Dec', '14 Dec', '16 Dec', '18 Dec', '20 Dec', '22 Dec', '24 Dec', '26 Dec', '28 Dec', '30 Dec'];
        // var dept_wise_strength_male   = [19, 15, 30, 20, 23, 20, 24, 28, 26, 23, 20, 21, 26, 29, 27];
        // var dept_wise_strength_female = [9, 21, 18, 26, 42, 33, 20, 30, 22, 27, 29, 31, 27, 25, 18, 16];

        var chartJs = {
            initialize: function () {
                this.lineChart();
                this.dountChart();
            },
            lineChart: function () {
                var chart = document.getElementById('lineChart').getContext('2d'),
                    gradientSys = chart.createLinearGradient(0, 0, 0, 450);

                gradientSys.addColorStop(0, 'rgba(110, 72, 170, 0.9)');
                gradientSys.addColorStop(1, 'rgba(110, 72, 170, 0)');
                gradientSys.addColorStop(1, 'rgba(110, 72, 170, 0)');

                let gradientDia = chart.createLinearGradient(0, 0, 0, 450);

                gradientDia.addColorStop(0, 'rgba(24, 90, 157, 0.9)');
                gradientDia.addColorStop(1, 'rgba(24, 90, 157, 0)');
                gradientDia.addColorStop(1, 'rgba(24, 90, 157, 0)');

                var data = {
                    labels: dept_wise_strength_label,
                    datasets: [{
                        label: "Male",
                        borderColor: "#6e48aa",
                        borderWidth: "1",
                        pointBackgroundColor: '#6e48aa',
                        pointBorderWidth: 4,
                        pointBorderColor: '#fff',
                        pointRadius: 6,
                        backgroundColor: gradientSys,
                        data: dept_wise_strength_male
                    },
                    {
                        label: "Female",
                        borderColor: "#185a9d",
                        borderWidth: "1",
                        pointBackgroundColor: '#185a9d',
                        pointBorderWidth: 4,
                        pointBorderColor: '#fff',
                        pointRadius: 6,
                        backgroundColor: gradientDia,
                        pointHighlightStroke: "rgba(26,179,148,1)",
                        data: dept_wise_strength_female
                    }]
                };

                var options = {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: {
                        easing: 'easeInOutQuad',
                        duration: 520
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                color: 'rgba(200, 200, 200, 0.05)',
                                lineWidth: 1
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: 'rgba(200, 200, 200, 0.08)',
                                lineWidth: 1
                            }
                        }]
                    },
                    elements: {
                        line: {
                            tension: 0.4
                        }
                    },
                    legend: {
                        display: false
                    },
                    point: {
                        backgroundColor: 'white'
                    },
                    //                tooltips: {
                    //                    titleFontFamily: 'Open Sans',
                    //                    backgroundColor: 'rgba(0,0,0,0.3)',
                    //                    titleFontColor: 'red',
                    //                    caretSize: 5,
                    //                    cornerRadius: 2,
                    //                    xPadding: 10,
                    //                    yPadding: 10
                    //                }
                };

                var chartInstance = new Chart(chart, {
                    type: 'line',
                    data: data,
                    options: options
                });
            },
            dountChart: function () {
                //                        Chart.pluginService.register({
                //                            beforeDraw: function (chart) {
                //                                var width = chart.chart.width,
                //                                        height = chart.chart.height,
                //                                        ctx = chart.chart.ctx;
                //                                ctx.restore();
                //                                var fontSize = (height / 114).toFixed(2);
                //                                ctx.font = fontSize + "em sans-serif";
                //                                ctx.textBaseline = "middle";
                //                                var text = chart.config.options.elements.center.text,
                //                                        textX = Math.round((width - ctx.measureText(text).width) / 2),
                //                                        textY = height / 2;
                //                                ctx.fillText(text, textX, textY);
                //                                ctx.save();
                //                            }
                //                        });
                var chartData = [{ "visitor": 25, "visit": 1 }, { "visitor": 6, "visit": 2 }, { "visitor": 3, "visit": 3 }, { "visitor": 3, "visit": 4 }, { "visitor": 1, "visit": 5 }]

                var visitorData = [],
                    sum = 0,
                    visitData = [];
                for (var i = 0; i < chartData.length; i++) {
                    visitorData.push(chartData[i]['visitor'])
                    visitData.push(chartData[i]['visit'])
                    sum += chartData[i]['visitor'];
                }

            }

        };
        // Initialize
        $(document).ready(function () {
            "use strict";
            chartJs.initialize();
        });

    }(jQuery));
</script>


<script>
    $(document).ready(function() {
        "use strict";
        $('.counter').counterUp({
            delay: 1,
            time: 500,
        });
    });
</script>