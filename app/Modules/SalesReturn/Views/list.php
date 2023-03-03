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
                <table id="returnList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']); ?></th>
                            <th><?php echo get_phrases(['dealer', 'name']); ?></th>
                            <th><?php echo get_phrases(['dO', 'no']); ?></th>
                            <th><?php echo get_phrases(['challan','no']); ?></th>
                            <th><?php echo get_phrases(['date']); ?></th>
                            <th><?php echo get_phrases(['return', 'by']); ?></th>
                            <th><?php echo get_phrases(['total', 'amount']); ?></th>
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
<div class="modal fade bd-example-modal-xl" id="returnDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl custom-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="returnDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="return_details_modal">



                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']); ?></button>

            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    var showCallBackData = function() {
        $('#id').val('');
        $('#action').val('add');
        $('.ajaxForm')[0].reset();
        $('#do-modal').modal('hide');
        $('#returnList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() {
        "use strict";

        $('#returnList').on('click', '.actionPreview', function(e) {
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');

            var id = $(this).attr('data-id');

            var submit_url = _baseURL + 'return/sales_return/returnDetailsbyId/' + id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {
                    'csrf_stream_name': csrf_val
                },
                success: function(data) {
                    $("#return_details_modal").html(data);
                    $('#returnDetails-modal').modal('show');
                    $('#returnDetailsModalLabel').text('<?php echo get_phrases(['return', 'details']); ?>');

                },
                error: function() {

                }
            });

        });

        var mydatatable = $('#returnList').DataTable({
            responsive: true,
            lengthChange: true,
            "aaSorting": [
                [3, "desc"]
            ],
            "columnDefs": [{
                "bSortable": false,
                "aTargets": [0, 1, 2, 4, 5, 6, 7]
            }, ],
            'lengthMenu': [
                [25, 50, 100, 250, 500, -1],
                [25, 50, 100, 250, 500, "All"]
            ],
            dom: "<'row'<?php if ($hasExportAccess || $hasPrintAccess) {
                            echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>";
                        } else {
                            echo "<'col-md-6'l><'col-md-6'f>";
                        } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if ($hasPrintAccess) { ?> {
                        extend: 'print',
                        text: '<i class="fa fa-print custool" title="Print"></i>',
                        titleAttr: 'Print',
                        className: 'btn-light',
                        title: 'Sales_Return-<?php echo date('Y-m-d'); ?>',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                <?php }
                if ($hasExportAccess) { ?> {
                        extend: 'copyHtml5',
                        text: '<i class="far fa-copy custool" title="Copy"></i>',
                        titleAttr: 'Copy',
                        className: 'btn-light',
                        title: 'Sales_Return-<?php echo date('Y-m-d'); ?>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                        titleAttr: 'Excel',
                        className: 'btn-light',
                        title: 'Sales_Return-<?php echo date('Y-m-d'); ?>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                        titleAttr: 'CSV',
                        className: 'btn-light',
                        title: 'Sales_Return-<?php echo date('Y-m-d'); ?>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                        titleAttr: 'PDF',
                        className: 'btn-light',
                        title: "Sales_Return- <?php echo date('Y-m-d'); ?>",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                <?php } ?>
            ],
            'processing' : true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': _baseURL + 'return/sales_return/getSalesreturnList',
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
                    data: 'do_no'
                },
                {
                    data: 'challan_no'
                },
                {
                    data: 'date'
                },
                {
                    data: 'return_by'
                },
                {
                    data: 'total_amount'
                },
                {
                    data: 'button'
                }
            ],
        });




    });


</script>