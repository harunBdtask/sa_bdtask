<div class="row g-3">
    <div class="col-sm-6 col-md-12 col-lg-6 col-xl-3 d-flex mb-3">
        <div class="info-box d-flex position-relative rounded flex-fill w-100 bg-info overflow-hidden gradient-one">
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l3 opacity-3" style="width: 8rem; height: 8rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l2 opacity-5" style="width: 6.5rem; height: 6.5rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l1 opacity-5" style="width: 5rem; height: 5rem;"></div>
            <span class="info-box-icon d-flex align-self-center text-center">
                <img src="<?php echo base_url() ?>/assets/dist/img/icon/png/008-factory-2.png" alt="" height="64" width="64">
            </span>
            <div class="info-box-content d-flex flex-column justify-content-center">
                <span class="info-box-text fw-bold fs-17px">Total Production</span>
                <span class="info-box-number d-block fw-black counter"><?php echo ($total_pro->qty ? $total_pro->qty : 0); ?></span>
                <!-- <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                </div>
                <div class="progress-description fs-14"><i class="fa fa-caret-up"></i> 70%
                    increase compare to last 30 Days</div> -->
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-md-12 col-lg-6 col-xl-3 d-flex mb-3">
        <div class="info-box d-flex position-relative rounded flex-fill w-100 bg-success overflow-hidden gradient-two">
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l3 opacity-3" style="width: 8rem; height: 8rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l2 opacity-5" style="width: 6.5rem; height: 6.5rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l1 opacity-5" style="width: 5rem; height: 5rem;"></div>
            <span class="info-box-icon d-flex align-self-center text-center">
                <img src="<?php echo base_url() ?>/assets/dist/img/icon/png/001-economic-disparities.png" alt="" height="64" width="64">
            </span>
            <div class="info-box-content d-flex flex-column justify-content-center">
                <span class="info-box-text fw-bold fs-17px">Today Production</span>
                <span class="info-box-number d-block fw-black counter"><?php echo ($today_pro->qty ? $today_pro->qty : 0); ?></span>
                <!-- <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                </div>
                <div class="progress-description fs-14"><i class="fa fa-caret-up"></i> 16%
                    increase compare to last week</div> -->
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-md-12 col-lg-6 col-xl-3 d-flex mb-3">
        <div class="info-box d-flex position-relative rounded flex-fill w-100 bg-warning overflow-hidden gradient-three">
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l3 opacity-3" style="width: 8rem; height: 8rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l2 opacity-5" style="width: 6.5rem; height: 6.5rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l1 opacity-5" style="width: 5rem; height: 5rem;"></div>
            <span class="info-box-icon d-flex align-self-center text-center">
                <img src="<?php echo base_url() ?>/assets/dist/img/icon/png/007-factory-1.png" alt="" height="64" width="64">
            </span>
            <div class="info-box-content d-flex flex-column justify-content-center">
                <span class="info-box-text fw-bold fs-17px">Total Consumptions</span>
                <span class="info-box-number d-block fw-black counter"><?php echo ($total_con->qty ? $total_con->qty : 0); ?></span>
                <!-- <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                </div>
                <div class="progress-description fs-14"><i class="fa fa-caret-down"></i> 70% Increase in 30 Days</div> -->
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-md-12 col-lg-6 col-xl-3 d-flex mb-3">
        <div class="info-box d-flex position-relative rounded flex-fill w-100 bg-danger overflow-hidden gradient-four">
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l3 opacity-3" style="width: 8rem; height: 8rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l2 opacity-5" style="width: 6.5rem; height: 6.5rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l1 opacity-5" style="width: 5rem; height: 5rem;"></div>
            <span class="info-box-icon d-flex align-self-center text-center">
                <img src="<?php echo base_url() ?>/assets/dist/img/icon/png/006-manufacturing.png" alt="" height="64" width="64">
            </span>
            <div class="info-box-content d-flex flex-column justify-content-center">
                <span class="info-box-text fw-bold fs-17px">Today Consumptions</span>
                <span class="info-box-number d-block fw-black counter"><?php echo ($today_con->qty ? $today_con->qty : 0); ?></span>
                <!-- <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                </div>
                <div class="progress-description fs-14"><i class="fa fa-caret-down"></i> 70%
                    Increase in 30 Days</div> -->
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Production Chart</h6>
                    </div>

                    <div class="text-right">
                        <div class="actions">
                            <a href="<?php echo base_url() ?>/#" class="action-item"><i class="ti-reload"></i></a>
                            <!-- <div class="dropdown action-item" data-toggle="dropdown">
                                <a href="<?php echo base_url() ?>/#" class="action-item"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu">
                                    <a href="<?php echo base_url() ?>/#" class="dropdown-item">Refresh</a>
                                    <a href="<?php echo base_url() ?>/#" class="dropdown-item">Manage Widgets</a>
                                    <a href="<?php echo base_url() ?>/#" class="dropdown-item">Settings</a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="barChartTwo" height="110"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        "use strict"; // Start of use strict

        //bar chart
        var chartColors = {
            gray: '#185a9d',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: '#7474BF',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(231,233,237)'
        };

        // draws a rectangle with a rounded top
        Chart.helpers.drawRoundedTopRectangle = function(ctx, x, y, width, height, radius) {
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
            draw: function() {
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
        // var emp_type_wise_data = getJsonDataThree(_baseURL + "dashboard/emp_type_wise_data");

        // var emp_type_labels = emp_type_wise_data.employee_types;
        // var emp_type_wise_male = emp_type_wise_data.male;
        // var emp_type_wise_female = emp_type_wise_data.female;

        var emp_type_labels = ['Production', 'OnTime Delivery', 'Undelivery', 'Process Loss'];
        var emp_type_wise_male = [<?php echo ($currnt_month_pro ? $currnt_month_pro : 0); ?>, <?php echo ($ontime_delivery_curr ? $ontime_delivery_curr : 0); ?>, <?php echo ($undelivery_curr ? $undelivery_curr : 0); ?>, <?php echo ($current_process_loss->qty ? $current_process_loss->qty : 0); ?>];
        var emp_type_wise_female = [<?php echo ($prev_month_pro ? $prev_month_pro : 0); ?>, <?php echo ($ontime_delivery_prev ? $ontime_delivery_prev : 0); ?>, <?php echo ($undelivery_prev ? $undelivery_prev : 0); ?>, <?php echo ($previous_process_loss->qty ? $previous_process_loss->qty : 0); ?>];

        var ctx = document.getElementById("barChartTwo").getContext("2d");
        var myBar = new Chart(ctx, {
            type: 'roundedBar',
            data: {
                labels: emp_type_labels,
                datasets: [{
                    label: 'Current Month',
                    backgroundColor: chartColors.green,
                    data: emp_type_wise_male
                }, {
                    label: 'Previous Month',
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
    $(document).ready(function() {
        "use strict";
        $('.counter').counterUp({
            delay: 1,
            time: 500,
        });
    });
</script>