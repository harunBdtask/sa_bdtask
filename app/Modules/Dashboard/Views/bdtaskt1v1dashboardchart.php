<div class="row">
    <div class="col-sm-6 col-md-12 col-lg-6 col-xl-6 d-flex mb-4">
        <div class="info-box d-flex position-relative rounded flex-fill w-100 overflow-hidden gradient-nine">
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l3 opacity-3" style="width: 8rem; height: 8rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l2 opacity-5" style="width: 6.5rem; height: 6.5rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l1 opacity-5" style="width: 5rem; height: 5rem;"></div>
            <span class="info-box-icon d-flex align-self-center text-center">
                <img src="<?php echo base_url() ?>/assets/dist/img/icon/png/002-water-control.png" alt="" height="64" width="64">
            </span>
            <div class="info-box-content d-flex flex-column justify-content-center">
                <span class="info-box-text fw-bold fs-17px">Today's Total Vouchers</span>
                <span class="info-box-number d-block fw-black counter"><?php echo $todaysvoucher?></span>
                <div class="progress">
                    <div class="progress-bar" style="width: 90%"></div>
                </div>
                <div class="progress-description fs-14"><i class="fa fa-caret-down"></i> Vouhcer Date <?php echo date('Y-m-d')?></div>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-sm-6 col-md-12 col-lg-6 col-xl-6 d-flex mb-4">
        <div class="info-box d-flex position-relative rounded flex-fill w-100 overflow-hidden gradient-ten">
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l3 opacity-3" style="width: 8rem; height: 8rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l2 opacity-5" style="width: 6.5rem; height: 6.5rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l1 opacity-5" style="width: 5rem; height: 5rem;"></div>
            <span class="info-box-icon d-flex align-self-center text-center">
                <img src="<?php echo base_url() ?>/assets/dist/img/icon/png/006-manufacturing.png" alt="" height="64" width="64">
            </span>
            <div class="info-box-content d-flex flex-column justify-content-center">
                <span class="info-box-text fw-bold fs-17px">Total Pending Voucher</span>
                <span class="info-box-number d-block fw-black counter"><?php echo $total_pendingvoucher;?></span>
                <div class="progress">
                    <div class="progress-bar" style="width: 90%"></div>
                </div>
                <div class="progress-description fs-14"><i class="fa fa-caret-down"></i> Pending Vouchers</div>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    
    
</div>



<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Account Payable</h6>
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
                <div id="multipleValue"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Income expenditure</h6>
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
                <div id="radiusPieChart"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Account receivable</h6>
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
                <div id="multipleValueTwo"></div>
            </div>
        </div>

        
    </div>
   
</div>
<script src="<?php echo base_url()?>/assets/plugins/amcharts4/core.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/amcharts4/charts.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/amcharts4/plugins/wordCloud.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/amcharts4/themes/animated.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/jquery.counterup/jquery.waypoints.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/jquery.counterup/jquery.counterup.min.js"></script>

<script>
    $(document).ready(function() {
        "use strict";
        $('.counter').counterUp({
            delay: 1,
            time: 500,
        });
        
    });

    (function ($) {
    "use strict";
 
     var data = {
         "test1" : [<?php echo $payablegraph?>]
        };

      
    var amChartsd = {
        initialize: function () {
            this.combinedBullet();
            this.combinedBulletTwo();
            this.radiusPieChart();
            
        },
        combinedBullet: function () {
            am4core.ready(function () {
                // Themes begin
               
                
                am4core.useTheme(am4themes_animated);
                // Themes end
              
                
                // Create chart instance
                var chart = am4core.create("multipleValue", am4charts.XYChart);

                // Add data
                chart.data =  [<?php echo $payablegraph?>];

               
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";
                categoryAxis.renderer.grid.template.location = 0;
                
                 
                
                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.title.text = "Payable Amount";
                // Create series
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.dataFields.valueY = "value";
                series.dataFields.categoryX = "category";
                series.name = "Payable Amount";
                series.tooltipText = "{name}\n[bold font-size: 20]{valueY}[/]";
                series.fill = chart.colors.getIndex(0);
                series.strokeWidth = 0;
                series.clustered = false;
                series.columns.template.width = am4core.percent(40);
                series.toBack();

                chart.cursor = new am4charts.XYCursor();

                // Add legend
                chart.legend = new am4charts.Legend();
                chart.legend.position = "top";

            }); // end am4core.ready()

        },
        combinedBulletTwo: function () {
            am4core.ready(function () {
                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end

                // Create chart instance
                var chart = am4core.create("multipleValueTwo", am4charts.XYChart);

                // Add data
                chart.data = [<?php echo $receivablegraph?>];

                // Create axes
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";
                categoryAxis.renderer.grid.template.location = 0;
                
                 
                
                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.title.text = "Receivable Amount";
                // Create series
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.dataFields.valueY = "value";
                series.dataFields.categoryX = "category";
                series.name = "Receivable Amount";
                series.tooltipText = "{name}\n[bold font-size: 20]{valueY}[/]";
                series.fill = chart.colors.getIndex(2);
                series.strokeWidth = 0;
                series.clustered = false;
                series.columns.template.width = am4core.percent(40);
                series.toBack();

                chart.cursor = new am4charts.XYCursor();

                // Add legend
                chart.legend = new am4charts.Legend();
                chart.legend.position = "top";

            }); // end am4core.ready()

        },
        radiusPieChart: function () {
            am4core.ready(function () {

                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end

                // Create chart
                var chart = am4core.create("radiusPieChart", am4charts.PieChart);
                chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

                chart.data = [
                    {
                        country: "Income",
                        value: <?php echo ($piedata['income']> 0 ?$piedata['income']:1);?>,
                        //color : am4core.color("#3CD3AD")
                    },
                    {
                        country: "Expenses",
                        value: <?php echo ($piedata['expenses'] > 0?$piedata['expenses'] : 1);?>,
                        //color : am4core.color("#FFC837")
                    }
                ];

                var series = chart.series.push(new am4charts.PieSeries());
                series.dataFields.value = "value";
                series.dataFields.radiusValue = "value";
                series.dataFields.category = "country";
                series.slices.template.cornerRadius = 6;
                //series.slices.template.propertyFields.fill = "color";
                series.colors.step = 1;

                series.hiddenState.properties.endAngle = -90;

                chart.legend = new am4charts.Legend();

            }); // end am4core.ready()
        }, 
        
}
$(document).ready(function () {
        "use strict"; // Start of use strict
        amChartsd.initialize();
    });
}(jQuery));
 

</script>