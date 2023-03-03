
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
                <canvas id="lineChart" height="88"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
    (function($) {
        "use strict";
        var chartJs = {
            initialize: function() {
                this.lineChart();
            },
            lineChart: function() {
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
                    labels: ['Production', 'OnTime Delivery', 'Undelivery', 'Process Loss'],
                    datasets: [{
                            label: "Current Month",
                            borderColor: "#6e48aa",
                            borderWidth: "1",
                            pointBackgroundColor: '#6e48aa',
                            pointBorderWidth: 4,
                            pointBorderColor: '#fff',
                            pointRadius: 6,
                            backgroundColor: gradientSys,
                            data: [<?php echo ($currnt_month_pro ? $currnt_month_pro : 0); ?>, <?php echo ($ontime_delivery_curr ? $ontime_delivery_curr : 0); ?>, <?php echo ($undelivery_curr ? $undelivery_curr : 0); ?>, <?php echo ($current_process_loss->qty ? $current_process_loss->qty : 0); ?>]
                        },
                        {
                            label: "Previous Month",
                            borderColor: "#185a9d",
                            borderWidth: "1",
                            pointBackgroundColor: '#185a9d',
                            pointBorderWidth: 4,
                            pointBorderColor: '#fff',
                            pointRadius: 6,
                            backgroundColor: gradientDia,
                            pointHighlightStroke: "rgba(26,179,148,1)",
                            data: [<?php echo ($prev_month_pro ? $prev_month_pro : 0); ?>, <?php echo ($ontime_delivery_prev ? $ontime_delivery_prev : 0); ?>, <?php echo ($undelivery_prev ? $undelivery_prev : 0); ?>, <?php echo ($previous_process_loss->qty ? $previous_process_loss->qty : 0); ?>]
                        }
                    ]
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
            }
        };
        // Initialize
        $(document).ready(function() {
            "use strict";
            chartJs.initialize();
        });

    }(jQuery));
</script>