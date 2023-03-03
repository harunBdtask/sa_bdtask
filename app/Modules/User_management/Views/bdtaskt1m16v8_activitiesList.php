<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb" class="order-sm-last p-0">
                            <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                                <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle; ?></a></li>
                                <li class="breadcrumb-item active"><?php echo $title; ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="minimumAlertList" class="table table-bordered table-hover table-striped mb-0">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']); ?></th>
                            <th><?php echo get_phrases(['action', 'name']); ?></th>
                            <th><?php echo get_phrases(['action', 'message']); ?></th>
                            <th><?php echo get_phrases(['table', 'name']); ?></th>
                            <th><?php echo get_phrases(['primary', 'key']); ?></th>
                            <th><?php echo get_phrases(['routes', 'url']); ?></th>
                            <th><?php echo get_phrases(['created', 'by']); ?></th>
                            <th><?php echo get_phrases(['created', 'date']); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
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
    <h4>
        <center><?php echo $title; ?></center>
    </h4>
</span>

<script type="text/javascript">
    $(document).ready(function() {
        "use strict";

        var title = $("#testtitle").html();

        $('#minimumAlertList').DataTable({
            lengthChange: true,

            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if ($hasPrintAccess) { ?> {
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
                        title: 'List-<?php echo date('Y-m-d'); ?>',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                        titleAttr: 'CSV',
                        className: 'btn-light',
                        title: 'List-<?php echo date('Y-m-d'); ?>',
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
                'url': _baseURL + 'permission/activities/getList',
                'data': {
                    'csrf_stream_name': csrf_val,
                }
            },
            'columns': [{
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'action'
                },
                {
                    data: 'type'
                },
                {
                    data: 'table_name'
                },
                {
                    data: 'action_id'
                },
                {
                    data: 'slug'
                },
                {
                    data: 'fullname'
                },
                {
                    data: 'created_date'
                }
            ],
        });


    });
</script>