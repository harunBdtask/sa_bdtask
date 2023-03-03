<div class="row">
    <div class="col-sm-6 col-md-12 col-lg-6 col-xl-3 d-flex mb-3">
        <div class="info-box d-flex position-relative rounded flex-fill w-100 bg-info overflow-hidden gradient-one">
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l3 opacity-3" style="width: 8rem; height: 8rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l2 opacity-5" style="width: 6.5rem; height: 6.5rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l1 opacity-5" style="width: 5rem; height: 5rem;"></div>
            <span class="info-box-icon d-flex align-self-center text-center">
                <img src="<?php echo base_url() ?>/assets/dist/img/icon/png/008-factory-2.png" alt="" height="64" width="64">
            </span>
            <div class="info-box-content d-flex flex-column justify-content-center">
                <span class="info-box-text fw-bold fs-17px"><?php echo get_phrases(['active', 'now']); ?></span>
                <span class="info-box-number d-block fw-black counter"><?php echo count($dealers); ?></span>
                <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                </div>
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
                <span class="info-box-text fw-bold fs-17px"><?php echo get_phrases(['long', 'term', 'credit']); ?></span>
                <span class="info-box-number d-block fw-black counter"><?php echo number_format($dealer_credit->credit_amount?$dealer_credit->credit_amount:0); ?></span>
                <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                </div>
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
                <span class="info-box-text fw-bold fs-17px"><?php echo get_phrases(['short', 'term', 'credit']); ?></span>
                <span class="info-box-number d-block fw-black counter"><?php echo number_format($short_credit->due_amount?$short_credit->due_amount:0); ?></span>
                <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                </div>
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
                <span class="info-box-text fw-bold fs-17px"><?php echo get_phrases(['total', 'daily', 'sale']); ?></span>
                <span class="info-box-number d-block fw-black counter"><?php echo ($delivery_qnty->quantity?$delivery_qnty->quantity:0)/1000; ?></span>
                <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                </div>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-md-12 col-lg-6 col-xl-3 d-flex mb-3">
        <div class="info-box d-flex position-relative rounded flex-fill w-100 overflow-hidden gradient-five">
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l3 opacity-3" style="width: 8rem; height: 8rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l2 opacity-5" style="width: 6.5rem; height: 6.5rem;"></div>
            <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l1 opacity-5" style="width: 5rem; height: 5rem;"></div>
            <span class="info-box-icon d-flex align-self-center text-center">
                <img src="<?php echo base_url() ?>/assets/dist/img/icon/png/006-manufacturing.png" alt="" height="64" width="64">
            </span>
            <div class="info-box-content d-flex flex-column justify-content-center">
                <span class="info-box-text fw-bold fs-17px"><?php echo get_phrases(['daily', 'delivery']); ?></span>
                <span class="info-box-number d-block fw-black counter"><?php echo ($delivery_qnty->quantity?$delivery_qnty->quantity:0)/1000; ?></span>
                <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                </div>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-md-12 col-lg-6 col-xl-3 d-flex mb-3">
        <div class="info-box d-flex position-relative rounded flex-fill w-100 overflow-hidden gradient-six"">
                          <div class=" position-br mb-n5 mr-n5 radius-round bgc-purple-l3 opacity-3" style="width: 8rem; height: 8rem;"></div>
        <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l2 opacity-5" style="width: 6.5rem; height: 6.5rem;"></div>
        <div class="position-br	mb-n5 mr-n5 radius-round bgc-purple-l1 opacity-5" style="width: 5rem; height: 5rem;"></div>
        <span class="info-box-icon d-flex align-self-center text-center">
            <img src="<?php echo base_url() ?>/assets/dist/img/icon/png/006-manufacturing.png" alt="" height="64" width="64">
        </span>
        <div class="info-box-content d-flex flex-column justify-content-center">
            <span class="info-box-text fw-bold fs-17px"><?php echo get_phrases(['daily', 'undelivered']); ?></span>
            <span class="info-box-number d-block fw-black counter"><?php echo ( ($do_qnty->quantity?$do_qnty->quantity:0)-($delivery_qnty->quantity?$delivery_qnty->quantity:0) )/1000 ; ?></span>
            <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
            </div>
        </div>
        <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->
</div>
<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 font-weight-600 mb-0"><?php echo get_phrases(['current', 'month', 'sales']); ?></h6>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="currentSales" class="table table-bordered table-hover table-striped mb-0">
            <thead>
                <tr>
                    <th><?php echo get_phrases(['sl']); ?></th>
                    <th><?php echo get_phrases(['item', 'name']); ?></th>
                    <th><?php echo get_phrases(['Qty/Mt']); ?></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 font-weight-600 mb-0"><?php echo get_phrases(['previous', 'month', 'sales']); ?></h6>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="previousSales" class="table table-bordered table-hover table-striped mb-0">
            <thead>
                <tr>
                    <th><?php echo get_phrases(['sl']); ?></th>
                    <th><?php echo get_phrases(['item', 'name']); ?></th>
                    <th><?php echo get_phrases(['Qty/Mt']); ?></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 font-weight-600 mb-0"><?php echo get_phrases(['finished', 'goods', 'stock', 'alert', 'with', 'minimum', 'orders', 'quantity']); ?></h6>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="minimumAlertList" class="table table-bordered table-hover table-striped mb-0">
            <thead>
                <tr>
                    <th><?php echo get_phrases(['sl']); ?></th>
                    <th><?php echo get_phrases(['item', 'name']); ?></th>
                    <th><?php echo get_phrases(['store', 'name']); ?></th>
                    <th><?php echo get_phrases(['present', 'stock']); ?></th>
                    <th><?php echo get_phrases(['alert', 'quantity']); ?></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 font-weight-600 mb-0"><?php echo get_phrases(['finished', 'goods', 'stock', 'alert', 'with', 'minor', 'orders', 'quantity']); ?></h6>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="minorAlertList" class="table table-bordered table-hover table-striped mb-0">
            <thead>
                <tr>
                    <th><?php echo get_phrases(['sl']); ?></th>
                    <th><?php echo get_phrases(['item', 'name']); ?></th>
                    <th><?php echo get_phrases(['store', 'name']); ?></th>
                    <th><?php echo get_phrases(['present', 'stock']); ?></th>
                    <th><?php echo get_phrases(['alert', 'quantity']); ?></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<span style="display:none;" id="testtitle">
    <div class="row">
        <div class="col-md-12 text-center">
            <img src="<?php echo base_url() . $setting->logo; ?>" class="img-responsive" alt=""><strong><?php echo $setting->title; ?></strong><br>
            <?php echo $setting->address; ?>
        </div>
    </div>
    <hr>
</span>

<script>
    $(document).ready(function() {
        "use strict";
        $('.counter').counterUp({
            delay: 1,
            time: 500,
        });

        var title = $("#testtitle").html();

        $('#minimumAlertList').DataTable({ 
            lengthChange: true,
             
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>{
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title: '',
                    messageTop: title,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'dashboard/finished_goods_store_list',
               'data':{'csrf_stream_name':csrf_val,'alert_type':'minimum'}
            },
            'columns': [
             { data: 'id' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
             },
             { data: 'item_name' },
             { data: 'store_name' },
             { data: 'stock' },
             { data: 'alert_qty' }
            ],
        });

        $('#minorAlertList').DataTable({ 
            lengthChange: true,
            
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>{
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title: '',
                    messageTop: title,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'dashboard/finished_goods_store_list',
               'data':{'csrf_stream_name':csrf_val,'alert_type':'minor'}
            },
            'columns': [
             { data: 'id' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
             },
             { data: 'item_name' },
             { data: 'store_name' },
             { data: 'stock' },
             { data: 'alert_qty' }
            ],
        });

        var from_date_current = "<?php echo date('Y-m-01'); ?>";
        var to_date_current = "<?php echo date('Y-m-t'); ?>";
        $('#currentSales').DataTable({ 
            lengthChange: true,
            
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>{
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title: '',
                    messageTop: title,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'dashboard/getSalesList',
               'data':{'csrf_stream_name':csrf_val,'from_date':from_date_current,'to_date':to_date_current}
            },
            'columns': [
             { data: 'id' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
             },
             { data: 'item_name' },
             { data: 'total' }
            ],
        });

        var from_date_previous = "<?php echo date('Y-m-d', strtotime('first day of last month')); ?>";
        var to_date_previous = "<?php echo date('Y-m-d', strtotime('last day of last month')); ?>";
        $('#previousSales').DataTable({ 
            lengthChange: true,
            
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>{
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title: '',
                    messageTop: title,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'dashboard/getSalesList',
               'data':{'csrf_stream_name':csrf_val,'from_date':from_date_previous,'to_date':to_date_previous}
            },
            'columns': [
             { data: 'id' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
             },
             { data: 'item_name' },
             { data: 'total' }
            ],
        });

    });
</script>