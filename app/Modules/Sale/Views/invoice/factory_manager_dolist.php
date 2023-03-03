<style>
    .badge {
        font-size: 90% !important;
    }
</style>
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
                    <div class="text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="doList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']); ?></th>
                            <th><?php echo get_phrases(['dealer', 'name']); ?></th>
                            <th><?php echo get_phrases(['dO', 'no']); ?></th>
                            <th><?php echo get_phrases(['challan', 'no']); ?></th>
                            <th><?php echo get_phrases(['date']); ?></th>
                            <th><?php echo get_phrases(['approved', 'by']); ?></th>
                            <th><?php echo get_phrases(['delivery', 'status']); ?></th>
                            <th><?php echo get_phrases(['status']); ?></th>
                            <th><?php echo get_phrases(['action']); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- do details -->
<div class="modal fade bd-example-modal-xl" id="doDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="doDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="printContent">
                <div id="do_details_modal">



                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']); ?></button>
                <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                <button type="button" class="btn btn-info" onclick="makePdf('printContent')"><?php echo get_phrases(['download']);?></button>
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
    var showCallBackData = function() {
        $('#id').val('');
        $('#action').val('add');
        $('.ajaxForm')[0].reset();
        $('#do-modal').modal('hide');
        $('#doList').DataTable().ajax.reload(null, false);
    }

    function makePdf(id) {
        preloader_ajax();
        $.ajax({
            async: true,
            success: function(data) {
                getPDF(id);
            }
        }); 
    }

    $(document).ready(function() {
        "use strict";

        $('#doList').on('click', '.actionPreview', function(e) {
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');

            var id = $(this).attr('data-id');

            var submit_url = _baseURL + 'sale/deliver_order/getdoDetailsdeliveryection/' + id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {
                    'csrf_stream_name': csrf_val
                },
                success: function(data) {

                    $("#do_details_modal").html(data);
                    $('#doDetails-modal').modal('show');
                    $('#doDetailsModalLabel').text('<?php echo get_phrases(['do', 'details']); ?>');

                },
                error: function() {

                }
            });

        });

        var title = $("#testtitle").html();
        var mydatatable = $('#doList').DataTable({
            responsive: true,
            lengthChange: true,
            "aaSorting": [
                [4, "desc"]
            ],
            "columnDefs": [{
                "bSortable": false,
                "aTargets": [0, 1, 2, 3, 5, 6, 7, 8]
            }, ],
            dom: "<'row'<?php if ($hasExportAccess || $hasPrintAccess) { echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if ($hasPrintAccess) { ?> {
                        extend: 'print',
                        text: '<i class="fa fa-print custool" title="Print"></i>',
                        titleAttr: 'Print',
                        className: 'btn-light',
                        title: '',
                        messageTop: title,
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                <?php }
                if ($hasExportAccess) { ?> {
                        extend: 'excelHtml5',
                        text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                        titleAttr: 'Excel',
                        className: 'btn-light',
                        title: 'dos_List-<?php echo date('Y-m-d'); ?>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                        titleAttr: 'CSV',
                        className: 'btn-light',
                        title: 'dos_List-<?php echo date('Y-m-d'); ?>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': _baseURL + 'sale/deliver_order/getfactorymando',
                'data': {
                    'csrf_stream_name': csrf_val
                }
            },
            'columns': [{
                    data: 'id'
                },
                {
                    data: 'dealer_name'
                },
                {
                    data: 'vouhcer_no'
                },
                {
                    data: 'challan_no'
                },
                {
                    data: 'do_date'
                },
                {
                    data: 'approved_by'
                },
                {
                    data: 'delivery_status'
                },
                {
                    data: 'status'
                },
                {
                    data: 'button'
                }
            ],
        });




    });

    function do_FactoryManager_aprroval(do_id) {
        $.ajax({
            type: 'POST',
            url: _baseURL + 'sale/deliver_order/fmanager_do_approval',
            dataType: 'JSON',
            data: {
                'do_id': do_id,
                'csrf_stream_name': csrf_val
            },
            success: function(data) {

                if (data.status = 1) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
                location.reload();
            },
            error: function() {

            }
        });
    }
</script>