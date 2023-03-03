<!-- <link href="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet"> -->
<div class="row">
    <div class="col-sm-12">
       <div class="card">
            
            <div class="card-header py-2">
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb" class="order-sm-last p-0">
                            <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                                <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle;?></a></li>
                                <li class="breadcrumb-item active"><?php echo $title;?></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="text-right">
                    <?php if ($hasCreateAccess) { ?>
                        <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'new']);?></button>
                    <?php } ?>
                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>

            </div>

            <div class="card-body">
                <div class="row form-group">  
                    <div class="col-sm-2"></div>
                    <div class="col-sm-2">
                        <input type="text" name="search_date" id="search_date" class="form-control" placeholder="<?php echo get_phrases(['select', 'voucher', 'date']); ?>" autocomplete="off">
                    </div>
                    <?php if(session('isAdmin')==true){ ?>
                    <div class="col-sm-3">
                        <?php echo form_dropdown('branch_id','','','class="custom-select" id="branch_id"');?>
                    </div>
                    <?php }?>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-warning resetBtn text-white"><?php echo get_phrases(['reset']);?></button>
                    </div> 
                </div>
                <table id="lclist"  class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['serial']);?></th>
                            <th><?php echo get_phrases(['branch', 'name']);?></th>
                            <th><?php echo get_phrases(['voucher', 'no']);?></th>
                            <th><?php echo get_phrases(['debit', 'amount']);?></th>
                            <th><?php echo get_phrases(['credit', 'amount']);?></th>
                            <th><?php echo get_phrases(['remarks']);?></th>
                            <th><?php echo get_phrases(['voucher', 'date']);?></th>
                            <th><?php echo get_phrases(['created', 'by']);?></th>
                            <th><?php echo get_phrases(['status']);?></th>
                            <th><?php echo get_phrases(['action']);?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- category modal button -->
<div class="modal fade bd-example-modal-xl" id="lc-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="lcModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
    
            <?php echo form_open_multipart('lc/loan_repay', 'class="needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            
                <div class="modal-body">
                    
                    <div class="row form-group">
                        <label for="voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['voucher','no'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                            <input name="voucher_no"  type="text" class="form-control" id="voucher_no" placeholder="<?php echo get_phrases(['voucher','no'])?>" autocomplete="off" readonly >
                        </div>
                        <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                            <input name="date"  type="text" class="form-control datepicker1" id="date" placeholder="<?php echo get_phrases(['date'])?>"  value="<?php echo date('d/m/Y')?>" autocomplete="off" readonly>
                        </div>
                    </div>
                    
                    <div class="row form-group">
                        <label for="lc_loan" class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['LC', 'loan']) ?> </label>
                        <div class="col-sm-4">
                            <select name="lc_loan" id="lc_loan" class="custom-select form-control">
                                <option value=""><?php echo get_phrases(['select', 'loan']) ?></option>
                                <?php if (!empty($lc_loan)) { ?>
                                    <?php foreach ($lc_loan as $key => $value) { ?>
                                        <option value="<?php echo $value->HeadCode; ?>"><?php echo $value->HeadName; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <label for="spr_item_list" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['LC', 'no'])?><i class="text-danger">*</i></label>
                        <div class="col-sm-2">
                            <div id="spr_item_select">
                                <?php echo form_dropdown('spr_item_list', '', '', 'class="custom-select" id="spr_item_list" required');?>
                            </div> 
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="payment_method" class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['payment', 'type']) ?> </label>
                        <div class="col-sm-4">
                            <select name="payment_method" id="payment_method" class="custom-select form-control">
                                <option value=""><?php echo get_phrases(['select', 'method']) ?></option>
                                <option value="<?php echo $predhead->cashCode?>"><?php echo get_phrases(['cash', 'payment']) ?></option>
                                <option value="1"><?php echo get_phrases(['bank', 'payment']) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group hidden" id="bank_div">
                        <label for="bank_id" class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['bank']) ?> </label>
                        <div class="col-sm-4">
                            <select name="bank_id" id="bank_id" class="custom-select form-control">
                                <option value=""><?php echo get_phrases(['select', 'bank']) ?></option>
                                <?php if (!empty($bank_list)) { ?>
                                    <?php foreach ($bank_list as $key => $value) { ?>
                                        <option value="<?php echo $value->HeadCode; ?>"><?php echo $value->HeadName; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group hidden" id="loan_div">
                        <label for="loan_due" class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['total','due']) ?> </label>
                        <div class="col-sm-4">
                            <input name="loan_due"  type="text" class="form-control" id="loan_due"  autocomplete="off" readonly >
                        </div>
                        <label for="loan_pay" class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['total','pay']) ?> </label>
                        <div class="col-sm-4">
                            <input name="loan_pay"  type="text" class="form-control" id="loan_pay"  autocomplete="off"  >
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                    <button type="submit" class="btn btn-success modal_action"></button>
                </div>

            <?php echo form_close();?>

        </div>
    </div>
</div>





<!-- view voucher details -->
<div class="modal fade bd-example-modal-xl" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="lcModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
    
            <?php echo form_open_multipart('lc/add_lc', 'class="needs-validation" ');?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="viewDetails">
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>


<script type="text/javascript">
    
    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');       
        $('#lc-modal').modal('hide'); 
        $('#ajaxForm')[0].reset();        
        $('#lclist').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
        "use strict";

        // $('#search_date').datepicker({dateFormat: 'yy-mm-dd'});

        $('.addShowModal').on('click', function(){

            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            // $('#lc_loan').val('').trigger('change');
            // $('#loan_div').addClass('hidden');
            getMAXID('acc_transaction','ID','voucher_no','LCP-');
            $('#date').val('<?php echo date('d/m/Y')?>');
            $('#action').val('add');    
            $('#lcModalLabel').text('Add New')
            $('.modal_action').text('Save');
            $('#lc-modal').modal('show');
        });


        $('body').on('change', '#payment_method', function() {
            var val = this.value;
            if (val == 1) {
                $('#bank_div').removeClass('hidden');
            } else {
                $('#bank_div').addClass('hidden');
            }
        });


        $('body').on('change', '#lc_loan', function() {
            var val = this.value;
            var submit_url = _baseURL+"lc/lc_loan_info"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, 'coaid':val},
                dataType: 'JSON',
                success: function(res) {
                    // var amount = res.credit - res.debit;
                    // $('#loan_due').val(amount);
                    // $('#loan_pay').val(amount);
                    // $('#loan_div').removeClass('hidden');
                    
                    $('#spr_item_list option:first-child').val('').trigger('change');
                    $('#spr_item_list').empty();
                    $('#spr_item_list').select2({
                        placeholder: '<?php echo get_phrases(['select','item']); ?>' ,
                        data : res.loan_history
                    });
                    var option = new Option('', '', true, true);
                    $("#spr_item_list").append(option).trigger('change');
                    
                }
            }); 
        });

        $('body').on('change', '#spr_item_list', function() {
            var item_id = $(this).val(); 
            var coaid = $('#lc_loan').val();
            preloader_ajax();
            $.ajax({
                url: _baseURL+"lc/lc_loan_info",
                type: 'POST',
                data: {'csrf_stream_name':csrf_val, 'coaid':coaid, 'item_id': item_id},
                dataType:"JSON",
                async: true,
                success: function (res) {
                    var amount = res.due_amount;
                    $('#loan_due').val(amount);
                    $('#loan_pay').val(amount);
                    $('#loan_div').removeClass('hidden');
                }
            });
        });




        $('#lclist').on('click', '.viewAction', function(e){
            e.preventDefault();
            var VNo = $(this).attr('data-id');
            $('#details-modal').modal('show');
            var submit_url = _baseURL+"account/vouchers/getVoucherDetails/"+VNo; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(response) {
                    $('#viewDetails').html('');
                    $('#viewDetails').html(response.data);
                }
            });  
        });



        $('#lclist').DataTable({
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [8,9] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>{
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    className: 'btn-light',
                    title : 'Debit_voucher_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                <?php } if($hasExportAccess){ ?>{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    className: 'btn-light',
                    title : 'Debit_voucher_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    className: 'btn-light',
                    title : 'Debit_voucher_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    className: 'btn-light',
                    title : 'Debit_voucher_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    className: 'btn-light',
                    title : 'Debit_voucher_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                }
            <?php }?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'account/vouchers/getVoucherList/LCP',
               'data': function ( d ) {
                    d.csrf_stream_name = csrf_val;
                    d.search_date      = $('#search_date').val();
                    d.branch_id        = $('#branch_id').val();
                }
            },
          'columns': [
             { data: 'ID' },
             { data: 'branch_name'},
             { data: 'VNo'},
             { data: 'totaldebit' },
             { data: 'totalcredit' },
             { data: 'Narration' },
             { data: 'VDate' },
             { data: 'created_by' },
             { data: 'button'},
             { data: 'action' }
          ],
        });


    });


  


</script>