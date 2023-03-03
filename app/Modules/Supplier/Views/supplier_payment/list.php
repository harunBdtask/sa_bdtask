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
                        <?php if( $hasCreateAccess ){ ?>
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['new','payment']);?></button> 
                       <?php } ?>       
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="supplier_paymentList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['voucher','no']);?></th>
                            <th><?php echo get_phrases(['paid','amount']);?></th>
                            <th><?php echo get_phrases(['paid','date']);?></th>
                            <th><?php echo get_phrases(['supplier']);?></th>
                            <th><?php echo get_phrases(['store']);?></th>
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

<!-- item receive modal button -->
<div class="modal fade bd-example-modal-xl" id="itemReceiveDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemReceiveDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('supplier/add_supplier_payment', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body" id="printContent">
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="action" id="action" value="add">
                <input type="hidden" name="paid_total" id="paid_total" value="">
                <input type="hidden" name="due_total" id="due_total" value="">
                <input type="hidden" name="receipt" id="receipt" value="">
                <input type="hidden" name="due" id="due" value="">
                <input type="hidden" name="supplier_id" id="supplier_id" value="">

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['supplier']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_supplier_id" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_store_id"></div>                        
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['receive','voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_receive_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['receive','date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_receive_date"></div>                        
                    </div>
                </div>

                <div id="item_details_preview"></div>

                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['sub', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receive_sub_total" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['vat']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_vat" ></div>                        
                    </div>
                </div>
                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['grand', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receive_grand_total" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['first', 'payment']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receipt" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['first', 'due']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_due" ></div>                        
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo ucwords(get_phrases(['already', 'paid'])) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="paid_amount" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['remaining','amount']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="total_due" ></div>                        
                    </div>
                </div>
                <div class="row form-group payment_info">
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
                    <div class="col-sm-12">

                        <table class="table table-bordered table-striped" id="payment_table">
                            <thead>
                                <tr class="table-primary">
                                    <th class="text-center"><?php echo get_phrases(['payment', 'method'])?></th>
                                    <th class="text-center"><?php echo get_phrases(['payment','information'])?></th>
                                    <th class="text-center"><?php echo get_phrases(['amount'])?></th>
                                    <th class="text-center"><?php echo get_phrases(['action'])?></th>
                                </tr>
                            </thead>
                            <tbody id="payment_method_div">
                               
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row form-group payment_details">
                    <label for="print_voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['payment','voucher','no'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="print_voucher_no" ></div>
                    </div>
                    <label for="print_date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['payment','date'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="print_date" ></div>
                    </div>
                </div>
                <div class="row payment_details">
                    <div class="col-sm-12">                        
                        <div id="payment_preview"></div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo ucwords(get_phrases(['current', 'payment'])) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="current_paid" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['current', 'due']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="current_due" ></div>                        
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" id="back" class="btn btn-primary"><?php echo get_phrases(['back']);?></button>
                <button type="submit" id="payment" class="btn btn-success actionBtn"><?php echo get_phrases(['payment']);?></button>
                
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="payment-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="paymentModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                
                <div class="row form-group">
                    <label for="nameE" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['receive', 'voucher', 'no']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <select name="receive_voucher_no" id="receive_voucher_no" class="custom-select form-control" required>
                            <option value=""><?php echo get_phrases(['select','category']) ?></option>
                            <?php if(!empty($receive_list)){ ?>
                                <?php foreach ($receive_list as $key => $value) {?>
                                    <option value="<?php echo $value->purchase_id;?>"><?php echo $value->voucher_no;?></option>
                                <?php }?>
                            <?php }?>
                        </select>                              
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" class="btn btn-success" id="receive_details"><?php echo get_phrases(['receive','details']);?></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-xl" id="supplierPaymentDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="supplierPaymentDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="40px" ><br><br>
                        <h6><?php echo $settings_info->title.' ( '.$settings_info->nameA.' )'; ?></h6>
                        <h5><?php echo get_phrases(['supplier', 'payment', 'invoice']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['supplier'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="supplierPaymentDetails_supplier_id" ></div>
                    </div>
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['store'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="supplierPaymentDetails_store_id" ></div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['receive','voucher','no'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="supplierPaymentDetails_receive_voucher_no" ></div>
                    </div>
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['receive','date'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="supplierPaymentDetails_receive_date" ></div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['payment','voucher','no'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="preview_voucher_no" ></div>
                    </div>
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['payment','date'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="preview_date" ></div>
                    </div>
                </div>
                <div class="row payment_details">
                    <div class="col-sm-12">                        
                        <div id="print_payment_preview"></div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo ucwords(get_phrases(['total', 'payment'])) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="preview_paid" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['due','amount']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="preview_due" ></div>                        
                    </div>
                </div>              

                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['payment', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                    <div class="col-sm-6 text-right">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['authorized', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" id="print" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                 <button type="button" id="journal_voucher" class="btn btn-info viewJV" data-id=""><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']);?></button>
            </div>
        </div>
    </div>
</div>

<!-- view voucher details -->
<div class="modal fade bd-example-modal-lg" id="jv-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="detailsModalLabel"><?php echo get_phrases(['view', 'voucher', 'details']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="viewDetails">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#itemReceiveDetails-modal').modal('hide');
        $('#supplier_paymentList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
        
      

        $('.addShowModal').on('click', function(){
            //$('.ajaxForm').removeClass('was-validated');
            //$('#id').val('');
            //$('#action').val('add');
            
            $('#paymentModalLabel').text('<?php echo get_phrases(['receive', 'voucher']);?>');
            //$('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('#payment-modal').modal('show');

        });

        $('#back').on('click', function(){
            $('#itemReceiveDetails-modal').modal('hide');
            $('#payment-modal').modal('show');

        });

        $('#payment').on('click', function (e) {
            var due_total = parseFloat($('#due_total').val());
            var receipt = parseFloat($('#receipt').val());
            if( receipt > due_total ){                
                toastr.warning('<?php echo get_notify('Amount_should_not_be_greater_than_due_amount')?>');
                e.preventDefault();                        
                return false;
            }
            if( receipt <=0 ){                
                toastr.warning('<?php echo get_notify('Amount_should_not_be_zero')?>');
                e.preventDefault();                        
                return false;
            }
        });

        $('#receive_details').on('click', function(){
            //e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $('#receive_voucher_no').val();
            if(id == ''){
                toastr.warning('<?php echo get_notify('Receive_voucher_no_is_required'); ?>');
                return false;
            }
            var submit_url = _baseURL+'supplier/getItemReceiveDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#payment-modal').modal('hide');
                    $('#itemReceiveDetails-modal').modal('show');
                    $('#itemReceiveDetailsModalLabel').text('<?php echo get_phrases(['payment','details']);?>');

                    $('#itemReceiveDetails_supplier_id').text(data.supplier_name);
                    $('#itemReceiveDetails_store_id').text(data.store_name);

                    $('#itemReceiveDetails_receive_voucher_no').text(data.receive_voucher_no);
                    $('#itemReceiveDetails_receive_date').text(data.receive_date);                    
                    $('#itemReceiveDetails_vat').text(data.vat);                    
                    $('#itemReceiveDetails_due').text(data.due);    
                    $('#itemReceiveDetails_receipt').text(data.receipt);                    
                    //$('#itemReceiveDetails_payment_method').text(data.payment_method);                    
                    $('#itemReceiveDetails_receive_sub_total').text((data.receive_sub_total)?data.receive_sub_total:0);
                    $('#itemReceiveDetails_receive_grand_total').text((data.receive_grand_total)?data.receive_grand_total:0);
                    
                    $('#due_total').val(data.due);       
                    $('#due').val(data.due);  
                    $('#id').val(data.receive_id);  
                    $('#supplier_id').val(data.supplier_id);  

                    $('.payment_info').show();  
                    $('#payment_table').show();  
                    $('#payment').show();  
                    $('#payment').prop('disabled', false);
                    $('#back').show();  
                    $('.payment_details').hide();  

                    getMAXID('wh_supplier_payment','id','voucher_no','SUPI-');

                    get_item_details(id);
                    get_payment_due(data.receive_id);

                },error: function() {

                }
            });  

        });

        $('body').on('click', '.addPaymentRow', function() {
            var due = $('#due').val();

            var html = '<tr><td><select name="payment_method[]" class="form-control custom-select payment_method" required><option value="">Select</option>'+
                <?php foreach($payment_method_list as $payment_method){?>
                    '<option value="<?php echo $payment_method->id;?>"><?php echo $payment_method->nameE;?></option>'+
                <?php }?>
                '</select></td><td><input type="text" name="pay_acc_no[]" class="form-control" autocomplete="off"></td><td><input type="text" name="amount[]" class="form-control text-right onlyNumber amount" required autocomplete="off" onkeyup="payment_amount_cal()" value="'+due+'"></td><td><button type="button" class="btn btn-danger removePaymentRow" ><i class="fa fa-minus"></i></button></td></tr>';

            $("#payment_method_div").append(html);
            payment_amount_cal();

            $('select').select2({
                placeholder: '<?php echo get_phrases(['select','method']);?>'                
            });
        });


        $('body').on('click', '.removePaymentRow', function() {
            var rowCount = $('#payment_table >tbody >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
                payment_amount_cal();
            }else{
                toastr.warning('<?php echo get_notify("There_only_one_row_you_can_not_delete."); ?>');
            } 
        }); 

        $('#supplier_paymentList').on('click', '.printPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#print_payment_preview').html('');
                       
            var receive_id = $(this).attr('receive-id');
            var id = $(this).attr('data-id');
            var purchase_id = $(this).attr('purchase-id');
            var submit_url = _baseURL+'supplier/getItemReceiveDetailsById/'+purchase_id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#supplierPaymentDetails-modal').modal('show');
                    $('#supplierPaymentDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

                    $('#supplierPaymentDetails_supplier_id').text(data.supplier_name);
                    $('#supplierPaymentDetails_store_id').text(data.store_name);
                    $('#supplierPaymentDetails_receive_voucher_no').text(data.receive_voucher_no);
                    $('#supplierPaymentDetails_receive_date').text(data.receive_date);         

                    $('.payment_details').show();  

                    get_payment_details_preview(id);

                },error: function() {

                }
            });  

        });

        $('#supplier_paymentList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            $('#payment_preview').html('');
            
            var receive_id = $(this).attr('receive-id');
            var id = $(this).attr('data-id');
            var purchase_id = $(this).attr('purchase-id');
            var submit_url = _baseURL+'supplier/getItemReceiveDetailsById/'+purchase_id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemReceiveDetails-modal').modal('show');
                    $('#itemReceiveDetailsModalLabel').text('<?php echo get_phrases(['payment','details']);?>');

                    $('#itemReceiveDetails_supplier_id').text(data.supplier_name);
                    $('#itemReceiveDetails_store_id').text(data.store_name);

                    $('#itemReceiveDetails_receive_voucher_no').text(data.receive_voucher_no);
                    $('#itemReceiveDetails_receive_date').text(data.receive_date);                    
                    $('#itemReceiveDetails_vat').text(data.vat);                    
                    $('#itemReceiveDetails_due').text(data.due);                    
                    $('#itemReceiveDetails_receipt').text(data.receipt);                    
                    //$('#itemReceiveDetails_payment_method').text(data.payment_method);                    
                    $('#itemReceiveDetails_receive_sub_total').text((data.receive_sub_total)?data.receive_sub_total:0);
                    $('#itemReceiveDetails_receive_grand_total').text((data.receive_grand_total)?data.receive_grand_total:0);

                    $('.payment_info').hide();  
                    $('#payment_table').hide();  
                    $('#payment').hide();  
                    $('#back').hide();  

                    get_item_details(purchase_id);

                    $('.payment_details').show();  

                    get_payment_details(id);

                },error: function() {

                }
            });  

        });


        $('#supplier_paymentList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [6] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'Items_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                <?php } if($hasExportAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'SupplierPayment_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'SupplierPayment_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'SupplierPayment_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'SupplierPayment_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'supplier/getSupplierPayment',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'voucher_no' },
             { data: 'paid_amount', className: 'text-right' },
             { data: 'paid_date' },
             { data: 'supplier_name' },
             { data: 'store_name' },
             { data: 'button'},
          ],
        });

        $('#supplier_paymentList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        

        $('#supplier_paymentList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'supplier/getSupplierPaymentById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#supplier_payment-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('update');
                    $('#supplier_paymentModalLabel').text('<?php echo get_phrases(['update', 'purchase']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');

                    $('#nameE').val(data.nameE);
                    $('#nameA').val(data.nameA);
                    $('#cat_id').val(data.cat_id).trigger('change');
                    $('#unit_id').val(data.unit_id).trigger('change');
                    $('#price').val(data.price);

                    

                },error: function() {

                }
            });   

        });
        // delete supplier_payment
        $('#supplier_paymentList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"supplier/deleteSupplierPayment/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    async: false,
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, '<?php echo get_phrases(["record"])?>');
                            $('#supplier_paymentList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, '<?php echo get_phrases(["record"])?>');
                        }
                    },error: function() {

                    }
                });
            }   
        });

        // view journal voucher
        $('.viewJV').on('click', function(e){
            e.preventDefault();
            var VNo = $(this).attr('data-id');
            $('#jv-modal').modal('show');
            var submit_url = _baseURL+"account/vouchers/getVoucherDetails/"+VNo; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                async: false,
                success: function(response) {
                    $('#viewDetails').html('');
                    $('#viewDetails').html(response.data);
                }
            });  
        });

    });


    function get_item_details(purchase_id){

        if(purchase_id !='' ){
            var submit_url = _baseURL+'supplier/getItemPricingDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'purchase_id':purchase_id },
                success: function(data) {
                    $('#item_details_preview').html(data);
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    function get_payment_details(id){

        if(id !='' ){
            var submit_url = _baseURL+'supplier/getPaymentDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'json',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'id':id },
                success: function(data) {
                    $('#paid_amount').html(data.previous_paid);
                    $('#total_due').html(data.previous_due);
                    $("#current_paid").html(data.paid_amount);
                    $("#current_due").html(data.due);
                    $('#print_voucher_no').html(data.voucher_no);
                    $('#print_date').html(data.paid_date);

                    $('#payment_preview').html(data.html);
                }
            });
        } else {
            $('#payment_preview').html('');
        }
    }

    function get_payment_details_preview(id){

        if(id !='' ){
            var submit_url = _baseURL+'supplier/getPaymentDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'json',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'id':id },
                success: function(data) {
                    $("#preview_paid").html(data.paid_amount);
                    $("#preview_due").html(data.due);
                    $('#preview_voucher_no').html(data.voucher_no);
                    $('#preview_date').html(data.paid_date);
                    
                    $('#journal_voucher').attr('data-id', data.voucher_no);

                    $('#print_payment_preview').html(data.html);
                    
                }
            });
        } else {
            $('#print_payment_preview').html('');
        }
    }

    function get_payment_due(receive_id){

        if(receive_id !='' ){
            var submit_url = _baseURL+'supplier/getPaymentDueById';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'json',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'receive_id':receive_id },
                success: function(data) {
                    $('#paid_amount').html(data.paid_amount);
                    $('#total_due').html(data.due_amount);

                    $('#paid_total').val(data.paid_amount);
                    $('#due_total').val(data.due_amount);
                    $('#due').val(data.due_amount);

                    add_payment_row();
                }
            });
        } else {
            $('#paid_amount').html('0.00');
            $('#total_due').html('0.00');
        }
    }


    function add_payment_row(){
        var due = $('#due').val();
        var html = '<tr><td><select name="payment_method[]" class="form-control custom-select payment_method" required><option value="">Select</option>'+
                <?php foreach($payment_method_list as $payment_method){
                        if( stripos($payment_method->nameE, 'advance') === false && stripos($payment_method->nameE, 'patient') === false && stripos($payment_method->nameE, 'invoice') === false ){
                    ?>
                    '<option value="<?php echo $payment_method->id;?>"><?php echo $payment_method->nameE;?></option>'+
                <?php }
                } ?>
                '</select></td><td><input type="text" name="pay_acc_no[]" class="form-control" autocomplete="off"></td><td><input type="text" name="amount[]" class="form-control text-right onlyNumber amount" autocomplete="off" onkeyup="payment_amount_cal()" value="'+due+'" required></td><td><button type="button" class="btn btn-success addPaymentRow" ><i class="fa fa-plus"></i></button></td></tr>';

        $("#payment_method_div").html(html);
        payment_amount_cal();

        $('select').select2({
            placeholder: '<?php echo get_phrases(['select','method']);?>'                
        });
    }

    function payment_amount_cal(){
        var amount_total = 0; 
        $(".amount").each(function () {
            amount_total += (this.value)?parseFloat(this.value):0;
        });
        
        $("#receipt").val(amount_total.toFixed(2));

        totalReceipt();
    }

    function totalReceipt(){
        var due_total    =  ($("#due_total").val()=='')?0:parseFloat($("#due_total").val());
        var rec =  ($("#receipt").val()=='')?0:parseFloat($("#receipt").val());
        
        var due = due_total - rec;
        if(due < 0){
            due = 0;
        }
        $("#due").val(due.toFixed(2));

        $("#current_paid").html(rec.toFixed(2));
        $("#current_due").html(due.toFixed(2));
    }
</script>