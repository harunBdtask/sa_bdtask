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

                        <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'instant', 'commission', 'rate']); ?></button>

                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']); ?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <table id="commissionSettingList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']); ?></th>
                            <th><?php echo get_phrases(['monthly', 'target/', 'ton']); ?></th>
                            <th><?php echo get_phrases(['instant', 'com./', 'kg']); ?></th>
                            <th><?php echo get_phrases(['yearly', 'com./', 'kg']); ?></th>
                            <th><?php echo get_phrases(['total', 'com./', 'kg']) . '(' . get_phrases(['kg']) . ')'; ?></th>
                            <th><?php echo get_phrases(['another', 'addition']); ?></th>
                            <th><?php echo get_phrases(['comments']); ?></th>
                            <th><?php echo get_phrases(['create', 'by']); ?></th>
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
            <?php echo form_open_multipart('commission/add_new_commission_setting', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"'); ?>
            <div class="modal-body">

                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                <div class="row form-group">
                    <label for="target_start" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['target', 'start']) . '(' . get_phrases(['ton']) . ')' ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <input type="number" name="target_start" class="form-control" value="" id="target_start" placeholder="<?php echo get_phrases(['enter', 'start', 'amount']); ?>">
                    </div>

                </div>

                <div class="row form-group">
                    <label for="target_end" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['target', 'end']) . '(' . get_phrases(['ton']) . ')' ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <input type="number" name="target_end" placeholder="<?php echo get_phrases(['enter', 'end', 'amount']); ?>" class="form-control" id="target_end" required>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="target_end" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['monthly', 'com.', 'taka/', 'kg']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <input type="text" name="monthly_comm_tk_per_kg" placeholder="<?php echo get_phrases(['enter', 'monthly', 'commission', 'tk/', 'kg']); ?>" class="form-control valid_number" id="monthly_comm_tk_per_kg" onkeyup="rate_summary()" required>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="yearly_comm_tk_per_kg" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['yearly', 'com.', 'taka/', 'kg']) ?> <i class="text-danger"></i></label>
                    <div class="col-sm-8">
                        <input type="text" name="yearly_comm_tk_per_kg" placeholder="<?php echo get_phrases(['enter', 'yearly', 'commission', 'tk/', 'kg']); ?>" class="form-control valid_number" onkeyup="rate_summary()" id="yearly_comm_tk_per_kg">
                    </div>
                </div>

                <div class="row form-group">
                    <label for="total_commission_tk_kg" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['total', 'com.', 'taka/', 'kg']) ?> <i class="text-danger"></i></label>
                    <div class="col-sm-8">
                        <input type="text" name="total_commission_tk_kg" placeholder="<?php echo get_phrases(['enter', 'total', 'commission', 'tk/', 'kg']); ?>" class="form-control valid_number" id="total_commission_tk_kg" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="another_addition" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['another', 'addition']) ?> <i class="text-danger"></i></label>
                    <div class="col-sm-8">
                        <input type="text" name="another_addition" placeholder="<?php echo get_phrases(['enter', 'another', 'addition']); ?>" class="form-control" id="another_addition">
                    </div>
                </div>

                <div class="row form-group">
                    <label for="comments" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['commnets']) ?> <i class="text-danger"></i></label>
                    <div class="col-sm-8">

                        <textarea name="comments" placeholder="<?php echo get_phrases(['enter', 'another', 'addition']); ?>" class="form-control" id="comments"></textarea>
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
        $('#commissionSettingList').DataTable().ajax.reload();
    }



    var showCallBackData = function() {
        $('#id').val('');
        $('#action').val('add');
        $('.ajaxForm')[0].reset();
        $('#commission-modal').modal('hide');
        $('#commissionSettingList').DataTable().ajax.reload(null, false);
        location.reload();
    }
    $(document).ready(function() {
        "use strict";

        $('#commissionSettingList').DataTable({
            responsive: true,
            lengthChange: true,
            "aaSorting": [
                [3, "asc"]
            ],
            "columnDefs": [{
                "bSortable": false,
                "aTargets": [0, 1, 2, 4, 5]
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
                'url': _baseURL + 'commission/getcommission_list',
                'data': function(d) {
                    d.csrf_stream_name = csrf_val;
                }
            },
            'columns': [{
                    data: 'id'
                },
                {
                    data: 'monthly_target'
                },
                {
                    data: 'instant_comm_tk_kg'
                },
                {
                    data: 'yearly_comm_tk_kg',
                    class: "text-center"
                },
                {
                    data: 'total_comm_tk_kg'
                },
                {
                    data: 'another_addition'
                },
                {
                    data: 'comments'
                },
                {
                    data: 'create_by'
                },
                {
                    data: 'button'
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
            $('.modal_action').text('<?php echo get_phrases(['add']); ?>');
            $('#commission-modal').modal('show');

        });

        $('#commissionSettingList').on('click', '.actionEdit', function(e) {
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
        $('#commissionSettingList').on('click', '.actionDelete', function(e) {
            e.preventDefault();

            var id = $(this).attr('data-id');

            var submit_url = _baseURL + "commission/deletecommission_setting/" + id;
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
                            $('#commissionSettingList').DataTable().ajax.reload(null, false);
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

    $('body').on('keypress', '.valid_number', function(event) {
        var charCode = (event.which) ? event.which : event.keyCode;
        if (charCode != 46 && charCode != 45 && charCode > 31 &&
            (charCode < 48 || charCode > 57)) {
            toastr["error"]('Please Input Valid Number');
            return false;
        }


        return true;

    });

    function rate_summary() {
        var monthly_rate = $('#monthly_comm_tk_per_kg').val();
        var yearly_rate = $('#yearly_comm_tk_per_kg').val();
        var total_rate = (monthly_rate ? parseFloat(monthly_rate) : 0) + (yearly_rate ? parseFloat(yearly_rate) : 0);
        $('#total_commission_tk_kg').val(total_rate);
    }
</script>