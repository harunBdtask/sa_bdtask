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
                        <?php if ($hasCreateAccess) { ?>
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'dealer']); ?></button>
                        <?php } ?>
                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']); ?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">
                    <label for="filter_cat_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['reference', 'by']) ?> </label>
                    <div class="col-sm-4">
                        <?php echo  form_dropdown('reference_by', $referar_list, null, 'class="form-control select2" id="reference_by"') ?>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-success" onclick="reload_table()"><?php echo get_phrases(['filter']); ?></button>
                    </div>
                </div>
                <table id="dealersList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']); ?></th>
                            <th><?php echo get_phrases(['dealer', 'code']); ?></th>
                            <th><?php echo get_phrases(['name']); ?></th>
                            <th><?php echo get_phrases(['email']); ?></th>
                            <th><?php echo get_phrases(['phone', 'no']); ?></th>
                            <th><?php echo get_phrases(['dealer', 'type']); ?></th>
                            <th><?php echo get_phrases(['commission', 'rate']); ?></th>
                            <th><?php echo get_phrases(['affiliat', 'code']); ?></th>
                            <th><?php echo get_phrases(['region']); ?></th>
                            <th><?php echo get_phrases(['sales', 'officer']); ?></th>
                            <th><?php echo get_phrases(['reference', 'by']); ?></th>
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
<div class="modal fade bd-example-modal-xl" id="dealers-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="dealersModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open_multipart('sale/dealer/add_dealer', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"'); ?>
            <div class="modal-body">

                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                <input type="hidden" name="sales_officer" id="sales_officer" value="0" />
                <div class="row form-group">
                    <label for="dealer_code" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer', 'code']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input type="text" name="dealer_code" class="form-control" value="<?php echo $dealer_code; ?>" id="dealer_code" readonly>
                    </div>

                </div>
                <div class="row form-group">
                    <label for="affiliat_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['affiliat', 'code']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input type="text" name="affiliat_id" class="form-control" value="<?php echo $g_affiliate_code ?>" id="affiliat_id" readonly>
                    </div>

                </div>

                <div class="row form-group">
                    <label for="nameE" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer', 'name']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input type="text" name="name" placeholder="<?php echo get_phrases(['enter', 'dealer', 'name']); ?>" class="form-control" id="name" required>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="phone_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['phone', 'no']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input type="text" name="phone_no" placeholder="<?php echo get_phrases(['enter', 'Phone', 'no']); ?>" class="form-control" id="phone_no" required>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="email" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['email']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input type="email" name="email" placeholder="<?php echo get_phrases(['enter', 'email']); ?>" class="form-control" id="email">
                    </div>
                </div>

                <div class="row form-group">
                    <label for="address" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['address']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <textarea name="address" placeholder="<?php echo get_phrases(['enter', 'address']); ?>" class="form-control" id="address" required></textarea>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="type" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer', 'type']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <select name="type" class="form-control select2" id="dealer_type" required>
                            <option selected><?php echo get_phrases(['select', 'dealer', 'type']) ?></option>
                            <option value="1"><?php echo get_phrases(['cash', 'dealer']) ?></option>
                            <option value="2"><?php echo get_phrases(['credit', 'dealer']) ?></option>
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="commission_rate" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['commission', 'rate']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input type="text" name="commission_rate" id="commission_rate" class="form-control onlyNumber" required placeholder="<?php echo get_phrases(['enter', 'commission', 'rate']); ?>">
                    </div>
                </div>

                <div class="row form-group">
                    <label for="agrement_date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['agrement', 'date']) ?></label>
                    <div class="col-sm-6">
                        <input type="date" name="agrement_date" id="agrement_date" class="form-control" placeholder="<?php echo get_phrases(['enter', 'agrement', 'date']); ?>">
                    </div>
                </div>

                <div class="row form-group">
                    <label for="closing_date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['closing', 'date']) ?></label>
                    <div class="col-sm-6">
                        <input type="date" name="closing_date" id="closing_date" class="form-control" placeholder="<?php echo get_phrases(['enter', 'closing', 'date']); ?>">
                    </div>
                </div>

                <div class="row form-group">
                    <label for="credit_amount" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['credit', 'amount']) ?></label>
                    <div class="col-sm-6">
                        <input type="text" name="credit_amount" id="credit_amount" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['enter', 'credit', 'amount']); ?>">
                    </div>
                </div>


                <!-- <div class="row form-group">
                    <label for="sales_officer" class="col-sm-2 col-form-label font-weight-600"><?php //echo get_phrases(['sales', 'officer']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <?php //echo form_dropdown('sales_officer', $sales_officer, null, 'class="form-control select2" id="sales_officer"  required') ?>

                    </div>
                </div> -->

                <div class="row form-group">
                    <label for="zone_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['region']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <?php echo  form_dropdown('zone_id', $zone_list, null, 'class="form-control select2" id="zone_id"  required') ?>

                    </div>
                </div>

                <div class="row form-group">
                    <label for="email" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['reference', 'by']) ?> <i class="text-danger"></i></label>
                    <div class="col-sm-6">
                        <?php echo  form_dropdown('reference_id', null, null, 'class="form-control select2" id="reference_id"') ?>

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


<!-- dealer modal button -->
<div class="modal fade bd-example-modal-xl" id="dealerDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="dealerDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['dealer', 'code']) ?> : </label>
                    <div class="col-sm-6">
                        <div id="dealerDetails_dealer_code"></div>

                    </div>

                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['affiliat', 'code']) ?> : </label>
                    <div class="col-sm-6">
                        <div id="dealerDetails_affiliat_code"></div>

                    </div>

                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['dealer', 'name']) ?> : </label>
                    <div class="col-sm-6">
                        <div id="dealerDetails_name"></div>
                    </div>

                </div>



                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['phone', 'no']) ?> : </label>
                    <div class="col-sm-6">
                        <div id="dealerDetails_phone_no"></div>
                    </div>

                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['email']) ?> : </label>
                    <div class="col-sm-6">
                        <div id="dealerDetails_email"></div>
                    </div>

                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['address']) ?> : </label>
                    <div class="col-sm-6">
                        <div id="dealerDetails_address"></div>
                    </div>

                </div>

                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['dealer', 'type']) ?> : </label>
                    <div class="col-sm-6">
                        <div id="dealerDetails_type"></div>
                    </div>

                </div>

                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['commission', 'rate']) ?> : </label>
                    <div class="col-sm-6">
                        <div id="dealerDetails_commission_rate"></div>
                    </div>

                </div>

                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['region']) ?> : </label>
                    <div class="col-sm-6">
                        <div id="dealerDetails_zone"></div>
                    </div>

                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['sales', 'officer']) ?> : </label>
                    <div class="col-sm-6">
                        <div id="dealerDetails_sales_officer"></div>
                    </div>

                </div>

                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['reference', 'by']) ?> : </label>
                    <div class="col-sm-6">
                        <div id="dealerDetails_refer_by"></div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']); ?></button>

            </div>

        </div>
    </div>
</div>


<!-- user modal info -->
<div class="modal fade bd-example-modal-lg" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="roleModalLabel"><?php echo get_phrases(['assign', 'sales', 'officer']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('sale/dealer/addMoreRole', 'class="needs-validation" id="addMoreRole" novalidate="" data="roleCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="dealer_id" id="dealer_id" />

                 <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['dealer', 'name']);?></label>
                            <input type="text" id="full_name" class="form-control" readonly="" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['add', 'more', 'sales', 'officer']);?></label>
                            <?php echo form_dropdown('role_ids[]','','','class="custom-select form-control" id="role_ids" multiple');?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success action_btn"><?php echo get_phrases(['save']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<script type="text/javascript">
    function reload_table() {
        $('#dealersList').DataTable().ajax.reload();
    }



    var roleCallBackData = function() {
        $('#addRoleModal').modal('hide');
        $('#addMoreRole').removeClass('was-validated');
        $('#dealersList').DataTable().ajax.reload(null, false);
        // location.reload();
    }

    var showCallBackData = function() {
        $('#id').val('');
        $('#action').val('add');
        $('.ajaxForm')[0].reset();
        $('#dealers-modal').modal('hide');
        $('#dealersList').DataTable().ajax.reload(null, false);
        location.reload();
    }

    $(document).ready(function() {
        "use strict";

        $('#dealersList').on('click', '.actionPreview', function(e) {
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');

            var id = $(this).attr('data-id');
            var submit_url = _baseURL + 'sale/dealer/getDealerDetailsById/' + id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType: 'JSON',
                data: {
                    'csrf_stream_name': csrf_val
                },
                success: function(data) {
                    $('#dealerDetails-modal').modal('show');
                    $('#dealerDetailsModalLabel').text('<?php echo get_phrases(['dealer', 'details']); ?>');
                    $('#dealerDetails_name').text(data.dealer.name);
                    $('#dealerDetails_dealer_code').text(data.dealer.dealer_code);
                    $('#dealerDetails_affiliat_code').text(data.dealer.affiliat_id);
                    $('#dealerDetails_phone_no').text(data.dealer.phone_no);
                    $('#dealerDetails_email').text(data.dealer.email);
                    $('#dealerDetails_address').text(data.dealer.address);
                    if (data.dealer.type == 1) {
                        var type = 'Cash Dealer';
                    } else {
                        var type = 'Credit Dealer';
                    }
                    $('#dealerDetails_type').text(type);
                    $('#dealerDetails_commission_rate').text(data.dealer.commission_rate);
                    $('#dealerDetails_zone').text(data.dealer.zone_name);
                    $('#dealerDetails_sales_officer').text(data.dealer.sales_officer);
                    $('#dealerDetails_refer_by').text(data.dealer.reference_by);


                },
                error: function() {

                }
            });

        });

        $('#dealersList').DataTable({
            responsive: true,
            lengthChange: true,
            "aaSorting": [
                [2, "asc"]
            ],
            "columnDefs": [{
                "bSortable": false,
                "aTargets": [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 11]
            }, ],
            dom: "<'row'<?php if ($hasExportAccess || $hasPrintAccess) { echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [<?php if ($hasPrintAccess) { ?> {
                        extend: 'print',
                        text: '<i class="fa fa-print custool" title="Print"></i>',
                        titleAttr: 'Print',
                        className: 'btn-light',
                        title: 'dealers_List-<?php echo date('Y-m-d'); ?>',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                <?php }if ($hasExportAccess) { ?> {
                        extend: 'copyHtml5',
                        text: '<i class="far fa-copy custool" title="Copy"></i>',
                        titleAttr: 'Copy',
                        className: 'btn-light',
                        title: 'dealers_List-<?php echo date('Y-m-d'); ?>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                        titleAttr: 'Excel',
                        className: 'btn-light',
                        title: 'dealers_List-<?php echo date('Y-m-d'); ?>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                        titleAttr: 'CSV',
                        className: 'btn-light',
                        title: 'dealers_List-<?php echo date('Y-m-d'); ?>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                        titleAttr: 'PDF',
                        className: 'btn-light',
                        title: 'dealers_List-<?php echo date('Y-m-d'); ?>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': _baseURL + 'sale/dealer/getdealers',
                'data': function(d) {
                    d.csrf_stream_name = csrf_val;
                    d.reference_by = $('#reference_by').val();
                }
            },
            'columns': [{
                    data: 'id'
                },
                {
                    data: 'dealer_code'
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'phone_no'
                },
                {
                    data: 'dealer_type'
                },
                {
                    data: 'commission_rate',
                    class: "text-center"
                },
                {
                    data: 'affiliat_id'
                },
                {
                    data: 'zone_name'
                },
                {
                    data: 'sales_officer'
                },
                {
                    data: 'reference_by'
                },
                {
                    data: 'button'
                }
            ],
        });


        // assign sales officer
        $('#dealersList').on('click', '.roleAction', function(e){
            e.preventDefault();
            $('#addMoreRole')[0].reset();
            $('#addMoreRole').removeClass('was-validated');
            $('#addRoleModal').modal('show');
            var id = $(this).attr('data-id');

            var submit_url = _baseURL+'sale/dealer/assignedOfficers/'+id;
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#dealer_id').val(data.id);
                    $('#full_name').val(data.name);
                    $('#role_ids').val(data.assigned_officer_ids).trigger('change');
                },error: function() {

                }
            });   
        });

        $('#role_ids').on('select2:unselecting', function (e) {
            var unselected_value = $('#role_ids').val();
            if(confirm('<?php echo get_phrases(['are_you_sure']);?>')){
        
            }else{
                var tt = unselected_value.split(',');
                $('#role_ids').val(tt).trigger('change');
            }
        });

       // role list
        $.ajax({
            type:'GET',
            url: _baseURL+'sale/dealer/getRoles',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#role_id, #role_ids").select2({
                placeholder: '<?php echo get_phrases(['select', 'option']);?>',
                data: data
            });
        });




        $('.addShowModal').on('click', function() {
            // department list
            $.ajax({
                type: 'GET',
                url: _baseURL + 'sale/dealer/getDealersDropdown',
                dataType: 'json',
                data: {
                    'csrf_stream_name': csrf_val
                },
            }).done(function(data) {
                $("#reference_id").select2({
                    placeholder: '<?php echo get_phrases(['select', 'dealer']); ?>',
                    data: data
                });
            });
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');
            $('#name').val('');
            $('#phone_no').val('');
            $('#email').val('');
            $('#commission_rate').val('');
            $('#agrement_date').val('');
            $('#closing_date').val('');
            $('#credit_amount').val('');
            $('#address').val('');
            $('#zone_id').val('').trigger('change');
            $('#reference_id').val('').trigger('change');
            $('#dealersModalLabel').text('<?php echo get_phrases(['add', 'dealer']); ?>');
            $('.modal_action').text('<?php echo get_phrases(['add']); ?>');
            $('#dealers-modal').modal('show');

        });

        $('#dealersList').on('click', '.actionEdit', function(e) {
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');

            $.ajax({
                type: 'GET',
                url: _baseURL + 'sale/dealer/getDealersDropdown',
                dataType: 'json',
                data: {
                    'csrf_stream_name': csrf_val
                },
            }).done(function(data) {
                $("#reference_id").select2({
                    placeholder: '<?php echo get_phrases(['select', 'dealer']); ?>',
                    data: data
                });
            });

            var id = $(this).attr('data-id');
            var submit_url = _baseURL + 'sale/dealer/getDealerDetailsById/' + id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType: 'JSON',
                data: {
                    'csrf_stream_name': csrf_val
                },
                success: function(data) {
                    $('#dealers-modal').modal('show');

                    $('#action').val('update');
                    $('#dealersModalLabel').text('<?php echo get_phrases(['update', 'dealer']); ?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']); ?>');
                    $('#affiliat_id').val(data.dealer.affiliat_id);
                    $('#dealer_code').val(data.dealer.dealer_code);
                    $('#name').val(data.dealer.name);
                    $('#phone_no').val(data.dealer.phone_no);
                    $('#email').val(data.dealer.email);
                    $('#address').val(data.dealer.address);
                    $('#commission_rate').val(data.dealer.commission_rate);
                    $('#agrement_date').val(data.dealer.agrement_date);
                    $('#closing_date').val(data.dealer.closing_date);
                    $('#credit_amount').val(data.dealer.credit_amount);
                    $('#zone_id').val(data.dealer.zone_id).trigger('change');
                    $('#dealer_type').val(data.dealer.type).trigger('change');
                    $('#reference_id').val(data.dealer.reference_id).trigger('change');
                    $('#sales_officer').val(data.dealer.sales_officer_id).trigger('change');
                    $('#id').val(data.dealer.id);

                },
                error: function() {

                }
            });

        });
        // delete dealers
        $('#dealersList').on('click', '.actionDelete', function(e) {
            e.preventDefault();

            var id = $(this).attr('data-id');

            var submit_url = _baseURL + "sale/dealer/deletedealers/" + id;
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
                            $('#dealersList').DataTable().ajax.reload(null, false);
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

    $('body').on('change', '#sales_officer', function(e) {
        var officer_id = this.value;
        var action = $("#action").val();
        var id = $("#id").val();
        if (action == 'update' && id == '') {
            return false;
        }
        $.ajax({
            type: 'POST',
            // url: _baseURL + 'sale/dealer/check_assigned_dealer',
            dataType: 'JSON',
            data: {
                'csrf_stream_name': csrf_val,
                officer_id: officer_id,
                id: id,
                action: action
            },
            success: function(data) {
                if (data.status == 1) {
                    $('#sales_officer').val('').trigger('change');
                    toastr.error(data.message);
                }
            },
            error: function() {

            }
        });

    });
</script>