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
                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']); ?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <table id="monthlysaleCommission" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']); ?></th>
                            <th><?php echo get_phrases(['dealer', 'name']); ?></th>
                            <th><?php echo get_phrases(['voucher', 'no']); ?></th>
                            <th><?php echo get_phrases(['date']); ?></th>
                            <th><?php echo get_phrases(['total', 'kg']); ?></th>
                            <th><?php echo get_phrases(['commission', 'rate']) . '(' . get_phrases(['kg']) . ')'; ?></th>
                            <th><?php echo get_phrases(['commission', 'amount']); ?></th>
                            <th><?php echo get_phrases(['create', 'by']); ?></th>

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- dealer modal button -->
<div class="modal fade bd-example-modal-xl" id="commission-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="commissionModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open_multipart('commission/save_dealer_monthly_commission', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"'); ?>
            <div class="modal-body">

                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                <div class="row form-group">
                    <label for="dealer" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <?php echo form_dropdown('dealer_id', $dealer_list, null, 'class="form-control select2" id="dealer_id" onchange="dealer_soldinfo()"') ?>
                    </div>

                </div>

                <div class="row form-group">
                    <label for="target_end" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['select', 'month']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <select class="form-control select2" name="commission_month" id="commission_month" onchange="dealer_soldinfo()">
                            <option value="" selected>Select Month</option>
                            <?php for ($i = 1; $i <= 12; $i++) {
                                if ($i < 10) {
                                    $m = '0' . $i;
                                } else {
                                    $m = $i;
                                } ?>
                                <option value="<?php echo date('Y') . '-' . $m; ?>"><?php $ldate = date("Y" . '-' . $m);
                                                                                $time = strtotime($ldate);
                                                                                echo $newformat = date('F Y', $time); ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="target_end" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['commission', 'rate/', 'kg']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <input type="text" name="commission_rate" placeholder="<?php echo get_phrases(['commission', 'rate']); ?>" class="form-control valid_number" id="commission_rate" required readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="target_end" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['sold', 'quantity']) . '(' . get_phrases(['kg']) . ')' ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <input type="text" name="sold_qty" placeholder="<?php echo get_phrases(['sold', 'quantity']) . '(' . get_phrases(['kg']) . ')'; ?>" class="form-control valid_number" id="sold_qty" required readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="target_end" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['commission', 'amount',]) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <input type="text" name="commission_amount" placeholder="<?php echo get_phrases(['commission', 'amount']); ?>" class="form-control valid_number" id="commission_amount" required readonly>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']); ?></button>
                <button type="submit" class="btn btn-success modal_action actionBtn"></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>



<script type="text/javascript">
    function reload_table() {
        $('#monthlysaleCommission').DataTable().ajax.reload();
    }



    var showCallBackData = function() {
        $('#id').val('');
        $('#action').val('add');
        $('.ajaxForm')[0].reset();
        $('#commission-modal').modal('hide');
        $('#monthlysaleCommission').DataTable().ajax.reload(null, false);
        location.reload();
    }
    $(document).ready(function() {
        "use strict";

        $('#monthlysaleCommission').DataTable({
            responsive: true,
            lengthChange: true,
            "aaSorting": [
                [3, "asc"]
            ],
            "columnDefs": [{
                "bSortable": false,
                "aTargets": [0, 1, 2, 4, 5, 6, 7]
            }, ],
            dom: "<'row' <'col-md-4'l><'col-md-4'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [

                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title: 'commission_List-<?php echo date('Y-m-d'); ?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },

                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title: 'commission_List-<?php echo date('Y-m-d'); ?>',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title: 'commission_List-<?php echo date('Y-m-d'); ?>',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title: 'commission_List-<?php echo date('Y-m-d'); ?>',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title: 'commission_List-<?php echo date('Y-m-d'); ?>',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                }

            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': _baseURL + 'commission/getdealer_sales_commission',
                'data': function(d) {
                    d.csrf_stream_name = csrf_val;
                }
            },
            'columns': [{
                    data: 'id'
                },
                {
                    data: 'name'
                },
                {
                    data: 'voucher_no'
                },
                {
                    data: 'generate_month'
                },
                {
                    data: 'total_kg',
                    class: "text-center"
                },
                {
                    data: 'commission_rate'
                },
                {
                    data: 'commission_amount'
                },
                {
                    data: 'created_by'
                }
            ],
        });





        $('.addShowModal').on('click', function() {
            // department list
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');
            $('#target_start').val('');
            $('#target_end').val('');
            $('#monthly_comm_tk_per_kg').val('');
            $('#yearly_comm_tk_per_kg').val('');
            $('#total_commission_tk_kg').val('');
            $('#another_addition').val('');
            $('#comments').val('');
            $('#commissionModalLabel').text('<?php echo get_phrases(['add', 'commission']); ?>');
            $('.modal_action').text('<?php echo get_phrases(['save']); ?>');
            $('#commission-modal').modal('show');

        });

        $('#monthlysaleCommission').on('click', '.actionEdit', function(e) {
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            var id = $(this).attr('data-id');
            var submit_url = _baseURL + 'commission/getCommissionDetailsById/' + id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType: 'JSON',
                data: {
                    'csrf_stream_name': csrf_val
                },
                success: function(data) {
                    $('#commission-modal').modal('show');

                    $('#action').val('update');
                    $('#commissionModalLabel').text('<?php echo get_phrases(['update', 'commission', 'setting']); ?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']); ?>');
                    $('#id').val(data.commission.id);
                    $('#target_start').val(data.commission.target_start);
                    $('#target_end').val(data.commission.target_end);
                    $('#monthly_comm_tk_per_kg').val(data.commission.instant_comm_tk_kg);
                    $('#yearly_comm_tk_per_kg').val(data.commission.yearly_comm_tk_kg);
                    $('#total_commission_tk_kg').val(data.commission.total_comm_tk_kg);
                    $('#another_addition').val(data.commission.another_addition);
                    $('#comments').val(data.commission.comments);
                },
                error: function() {

                }
            });

        });
        // delete commission
        $('#monthlysaleCommission').on('click', '.actionDelete', function(e) {
            e.preventDefault();

            var id = $(this).attr('data-id');

            var submit_url = _baseURL + "commission/deletemonthly_commission/" + id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"]) ?>');
            if (check == true) {
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {
                        'csrf_stream_name': csrf_val
                    },
                    dataType: 'JSON',
                    success: function(res) {
                        if (res.success == true) {
                            toastr.success(res.message, '<?php echo get_phrases(["record"]) ?>');
                            $('#monthlysaleCommission').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.error(res.message, '<?php echo get_phrases(["record"]) ?>');
                        }
                    },
                    error: function() {

                    }
                });
            }
        });

    });

    function dealer_soldinfo() {
        var id = $("#dealer_id").val();
        var month = $("#commission_month").val();
        $.ajax({
            type: 'POST',
            url: _baseURL + 'commission/getDealerSalesCommissininfo/' + id,
            dataType: 'JSON',
            data: {
                'csrf_stream_name': csrf_val,
                'month': month
            },
            success: function(data) {
                var status = data.status;
                if (status == 1) {
                    $('#commission_rate').val(data.dealer.commission_rate);
                    $('#sold_qty').val(data.sold);
                    commission_calculation();
                } else {
                    toastr.error('Commission of this Month Already Generated');
                }

            },
            error: function() {

            }
        });
    }



    function commission_calculation() {
        var rate = $('#commission_rate').val();
        var total_kg = $('#sold_qty').val();
        var commission_amount = (rate ? parseFloat(rate) : 0) * (total_kg ? parseFloat(total_kg) : 0);
        $('#commission_amount').val(commission_amount);
    }
</script>