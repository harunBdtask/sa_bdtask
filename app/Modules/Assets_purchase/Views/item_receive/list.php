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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['create','MR']);?></button>  
                        <?php } ?> 
                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">                    
                    <div class="col-sm-2">
                        <label for="filter_store_id" class="font-weight-600"><?php echo get_phrases(['store']) ?> </label>
                        <select name="filter_store_id" id="filter_store_id" class="custom-select form-control" >
                            <option value=""></option>
                            <?php if(!empty($store_list)){ ?>
                                <?php foreach ($store_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="filter_supplier_id" class="font-weight-600"><?php echo get_phrases(['supplier']) ?> </label>
                        <select name="filter_supplier_id" id="filter_supplier_id" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($supplier_list)){ ?>
                                <?php foreach ($supplier_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="filter_item_id" class="font-weight-600"><?php echo get_phrases(['item']) ?> </label>
                        <select name="filter_item_id" id="filter_item_id" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($item_list)){ ?>
                                <?php foreach ($item_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->item_code.'-'.$value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="filter_voucher_no" class="font-weight-600"><?php echo get_phrases(['voucher','no']) ?> </label>
                        <input type="text" name="filter_voucher_no" id="filter_voucher_no" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <label for="filter_date" class="font-weight-600"><?php echo get_phrases(['date']) ?> </label>
                        <input type="date" name="filter_date" id="filter_date" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <br>
                        <button type="button" class="btn btn-success mt-2" onclick="reload_table()"><?php echo get_phrases(['filter']);?></button>
                        <button type="button" class="btn btn-warning mt-2" onclick="reset_table()"><?php echo get_phrases(['reset']);?></button>
                    </div>
                </div>
                <table id="item_receiveList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['purchase','order']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
                            <th><?php echo get_phrases(['SPR']);?></th>
                            <th><?php echo get_phrases(['supplier']);?></th>
                            <th><?php echo get_phrases(['grand','total']);?></th>
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

<!-- item modal button -->
<div class="modal fade bd-example-modal-xl" id="item_receive-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl custom-modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="item_receiveModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('assets_purchase/add_item_receive', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                <input type="hidden" name="supplier_id" id="supplier_id" value="">
                <input type="hidden" name="modal_show" id="modal_show" value="">

                <div class="row form-group">
                    <label for="voucher_show" class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['PO','no'])?>: </label>
                    <div class="col-sm-4">
                        <div id="voucher_show" ></div>
                        <div id="select_po" class="hidden">
                            <?php echo form_dropdown('po_id', '', '', 'class="custom-select" id="po_id" required');?>
                        </div>
                    </div>
                    <label for="store_id" class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['store'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <select name="store_id" id="store_id" class="custom-select form-control" required>
                            <option value=""></option>
                            <?php if (!empty($store_list)) { ?>
                                <?php foreach ($store_list as $key => $value) { ?>
                                    <option value="<?php echo $value->id; ?>"><?php echo $value->nameE; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="date_show" class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['order','date'])?>: </label>
                    <div class="col-sm-4">
                        <div id="date_show" ></div>
                    </div>
                    <label for="supplier_name" class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['supplier'])?>:</label>
                    <div class="col-sm-4">
                        <div id="supplier_name"></div>
                    </div>
                </div>

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
                    <label for="driver_name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['driver','name'])?></label>
                    <div class="col-sm-4">
                        <input name="driver_name" type="text" class="form-control" id="driver_name" autocomplete="off" >
                    </div>
                    <label for="driver_mobile" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['driver','mobile'])?></label>
                    <div class="col-sm-4">
                        <input name="driver_mobile" type="text" class="form-control" id="driver_mobile" autocomplete="off" >
                    </div>
                </div>
                <div class="row form-group">
                    <label for="truck_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['truck','no'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="truck_no" type="text" class="form-control" id="truck_no" autocomplete="off" required >
                    </div>
                    <label for="scale_attachment" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['scale','attachment'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="scale_attachment" type="file" class="form-control" id="scale_attachment" required >
                        <small id="attachmentHelp" class="form-text text-muted">Allow file types: jpg, jpeg, png, gif, pdf</small>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="chalan_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['chalan','no'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="chalan_no" type="text" class="form-control" id="chalan_no" autocomplete="off" required >
                    </div>
                    <label for="chalan_attachment" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['chalan','attachment'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="chalan_attachment" type="file" class="form-control" id="chalan_attachment" required >
                        <small id="attachmentHelp" class="form-text text-muted">Allow file types: jpg, jpeg, png, gif, pdf</small>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="gr_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['GR','no'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="gr_no" type="text" class="form-control" id="gr_no" autocomplete="off" required >
                    </div>
                </div>
                
                <div class="row mt-2">
                        <table class="table table-sm table-stripped w-100" id="receive_table">
                        <thead>
                            <tr>
                                <th width="20%"><?php echo get_phrases(['item', 'name'])?></th>
                                <th width="5%"><?php echo get_phrases(['unit']);?></th>
                                <th width="5%" class="text-right"><?php echo get_phrases(['total','quantity'])?></th>
                                <th width="5%" class="text-right"><?php echo get_phrases(['received','quantity'])?></th>
                                <th width="5%" class="text-right"><?php echo get_phrases(['remain','quantity'])?></th>
                                <th width="10%" class="text-right"><?php echo get_phrases(['quantity'])?></th>
                                <th width="10%" class="text-center"><?php echo get_phrases(['remarks'])?></th>
                                <th width="10%" class="text-center"><?php echo get_phrases(['QC','attachment'])?></th>

                            </tr>
                        </thead>
                        <tbody id="item_purchase_details_receive">
                            
                        </tbody>
                        
                        </table>   
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success modal_action actionBtn"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>


<!-- item receive modal button -->
<div class="modal fade bd-example-modal-xl" id="itemPurchaseDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl custom-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemPurchaseDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printContent">
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['supplier']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemPurchaseDetails_supplier_id" ></div>
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['quotation', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemPurchaseDetails_quatation"></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['purchase', 'order','no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemPurchaseDetails_voucher_no" ></div>
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemPurchaseDetails_date"></div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['SPR', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemPurchaseDetails_spr" ></div>                        
                    </div>
                    
                </div>

                <div id="item_details_preview"></div>

                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['sub', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemPurchaseDetails_sub_total" ></div>
                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['vat']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemPurchaseDetails_vat" ></div>
                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['grand', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemPurchaseDetails_grand_total" ></div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
               
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">

$('#preloader-wrapper').removeClass('hidden');
    
    
    $('body').on('click', '.addShowModal', function(e) {
        e.preventDefault();
        get_po_list();
        $('.ajaxForm').removeClass('was-validated');
        $('#voucher_show').addClass('hidden');
        $('#select_po').removeClass('hidden');
        $('#item_purchase_details_receive').html('');
        $('#modal_show').val('show');
        var id = 0;
        $('#po_id').on('change', function (e) {
            id = $(this).val(); 
            getItemReceiveById(id);
        });
        getItemReceiveById(id);

    });

    function reset_table(){
        $('#filter_store_id').val('').trigger('change');
        $('#filter_supplier_id').val('').trigger('change');
        $('#filter_item_id').val('').trigger('change');
        $('#filter_voucher_no').val('');
        $('#filter_date').val('');
        $('#item_receiveList').DataTable().ajax.reload();
    }

    //get_po_list
    function get_po_list(){
        preloader_ajax();
        $.ajax({
            url: _baseURL+"assets_purchase/get_po_list",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val},
            dataType:"json",
            async: true,
            success: function (data) {
                $('#po_id option:first-child').val('').trigger('change');
                $('#po_id').empty();
                $('#po_id').select2({
                    placeholder: '<?php echo get_phrases(['select','PO']); ?>' ,
                    data : data
                });
                var option = new Option('', '', true, true);
                $("#po_id").append(option).trigger('change');

            }
        });
    }

    function getItemReceiveById(id){
        $('#store_id').val('').trigger('change');
        $('#supplier_id').val('');
        $('#driver_name').val('');
        $('#driver_mobile').val('');
        $('#truck_no').val('');
        $('#scale_attachment').val('');
        $('#chalan_no').val('');
        $('#chalan_attachment').val('');
        $('#gr_no').val('');
        if (id) {
            var submit_url = _baseURL+'purchase/getItemReceiveById/'+id;
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: true,
                success: function(data) {
                    $('#item_receive-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('receive');
                    $('#item_receiveModalLabel').text('<?php echo get_phrases(['item', 'receive']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['receive']);?>');
                    $('.modal_action').prop('disabled', false);
    
                    $('#supplier_name').html(data.supplier_name);
                    $('#supplier_id').val(data.supplier_id);
                    // $('#store_name').html(data.store_name);
                    // $('#store_id').val(data.store_id);
                    $('#voucher_show').html(data.voucher_no);
                    $('#date_show').html(data.date);
    
                    $('#agree_type').val(data.agree_type);
                    $('#credit_limit').val(data.credit_limit);
                    $('#used_credit').val(data.used_credit);
                    $('#credit_period').val(data.credit_period);
                    
                    getMAXID('wh_material_receive','id','voucher_no','MR-');
    
                    //$('#voucher_no').prop('readonly', false);
                    $('#date').val('<?php echo date('d/m/Y')?>');
                    $('#receipt').val('');
                    
                    $('.receive_info').show();
                    
                    if(data.agree_type=='3'){
                        $('.payment_table').hide();
                    } else {
                        $('.payment_table').show();
                    }
    
                    get_purchase_details(id);
    
                },error: function() {
    
                }
            });  
        }else{
            $('#item_receive-modal').modal('show');
        }
    }

    function reload_table(){
        $('#item_receiveList').DataTable().ajax.reload();
    }


    var showCallBackData = function (data) {  
        $('#item_receive-modal').modal(data);
        $('#po_id').val('').trigger('change');
        $('#store_id').val('').trigger('change');
        $('#supplier_name').html('');
        $('#date_show').html('');
        $('#item_purchase_details_receive').html('');
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();      
        $('#item_receiveList').DataTable().ajax.reload(null, false);
    }

    function get_purchase_details(purchase_id){

        if(purchase_id !='' ){
            var submit_url = _baseURL+'assets_purchase/getItemPurchaseDetailsById';
            var action = $('#action').val();

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'purchase_id':purchase_id, 'action':action },
                success: function(data) {
                    $('#item_purchase_details_receive').html(data);

                    if(action=='return'){
                        $('.return_info').show();
                        $('.order_info').hide();
                    } else {
                        $('.return_info').hide();
                        $('.order_info').show();
                    }
                    if(action=='receive'){
                        $('.price').prop('readonly', false); 
                    } else {
                        $('.price').prop('readonly', true);
                    }
                    $('#receive_table > tfoot > tr > td').attr('colspan','9');
                    
                    calculation_all();

                    if(action=='receive'){                   
                        add_payment_row();
                    }
                }
            });
        } else {
            $('#item_purchase_details_receive').html('');
        }
    }

    function add_payment_row(){
        var due = $('#due').val();
        var agree_type = $('#agree_type').val();
        var required = 'required';
        if(agree_type !='2'){
            due = '';
            required = '';
        } 
        var html = '<tr><td><select name="payment_method[]" class="form-control custom-select payment_method" '+required+'><option value="">Select</option>'+
                <?php foreach($payment_method_list as $payment_method){
                        if( stripos($payment_method->nameE, 'advance') === false && stripos($payment_method->nameE, 'patient') === false && stripos($payment_method->nameE, 'invoice') === false ){
                    ?>
                    '<option value="<?php echo $payment_method->id;?>"><?php echo $payment_method->nameE;?></option>'+
                <?php }
                }?>
                '</select></td><td><input type="text" name="amount[]" class="form-control text-right onlyNumber amount" '+required+' autocomplete="off" onkeyup="payment_amount_cal()" value="'+due+'"></td><td><button type="button" class="btn btn-success addPaymentRow" ><i class="fa fa-plus"></i></button></td></tr>';

        $("#payment_method_div").html(html);
        payment_amount_cal();

        $('select').select2({
            placeholder: '<?php echo get_phrases(['select', 'option']);?>'                
        });
    }

    $(document).ready(function() { 
       "use strict";       


        $('#receive_invoice').bind('change', function() {

          //this.files[0].size gets the size of your file.
          if(this.files[0].size > (2*1024*1024) ){
            toastr.warning('<?php echo get_notify('File_size_should_be_less_than_or_equal_to_2MB'); ?>');
            $(this).val('');
          }

        });
        
        $('body').on('click', '.modal_action', function(e) {
            var action = $('#action').val();  

            if(action == 'receive'){
                var agree_type = $('#agree_type').val();
                var credit_limit = parseFloat($('#credit_limit').val());
                var used_credit = parseFloat($('#used_credit').val());
                var due = parseFloat($('#due').val());

                if(agree_type != '2' && due > 0 ){
                    var total_credit = used_credit+due;
                    if(total_credit > credit_limit){
                        alert('<?php echo get_notify('Credit_limit_exceeded_for_this_supplier')?>');
                        //e.preventDefault();                        
                        //return false;
                    }
                } else if( due > 0 ){
                    toastr.warning('<?php echo get_notify('Credit_not_allowed_for_this_supplier')?>');
                    e.preventDefault();                        
                    return false;
                }
            }

            if(action == 'return'){
                var result = avail_quantity_check();
                if( !result ){
                    e.preventDefault();                        
                    return false;
                }
            }

        });

        $('body').on('click', '.addPaymentRow', function() {
            var due = $('#due').val();

            var html = '<tr><td><select name="payment_method[]" class="form-control custom-select payment_method" required><option value="">Select</option>'+
                <?php foreach($payment_method_list as $payment_method){
                        if( stripos($payment_method->nameE, 'advance') === false && stripos($payment_method->nameE, 'patient') === false && stripos($payment_method->nameE, 'invoice') === false ){
                    ?>
                    '<option value="<?php echo $payment_method->id;?>"><?php echo $payment_method->nameE;?></option>'+
                <?php }
                } ?>
                '</select></td><td><input type="text" name="amount[]" class="form-control text-right onlyNumber amount" required autocomplete="off" onkeyup="payment_amount_cal()" value="'+due+'"></td><td><button type="button" class="btn btn-danger removePaymentRow" ><i class="fa fa-minus"></i></button></td></tr>';

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
                toastr.warning("<?php echo get_notify('There_only_one_row_you_can_not_delete.'); ?>");
            } 
        }); 

        $('#item_receiveList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#select_po').addClass('hidden');
            $('#voucher_show').removeClass('hidden');
            $('#item_purchase_details_receive').html('');
            $('#modal_show').val('hide');
            var id = $(this).attr('data-id');
            getItemReceiveById(id); 

        });

        /* 
        $('#item_receiveList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#item_purchase_details_receive').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'assets_purchase/getItemReceiveById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#item_receive-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('receive');
                    $('#item_receiveModalLabel').text('<?php //echo get_phrases(['item', 'receive']);?>');
                    $('.modal_action').text('<?php //echo get_phrases(['receive']);?>');
                    $('.modal_action').prop('disabled', false);

                    $('#supplier_name').html(data.supplier_name);
                    $('#supplier_id').val(data.supplier_id);
                    $('#store_id').val(data.store_id);
                    $('#voucher_show').html(data.voucher_no);
                    $('#date_show').html(data.date);

                    $('#agree_type').val(data.agree_type);
                    $('#credit_limit').val(data.credit_limit);
                    $('#used_credit').val(data.used_credit);
                    $('#credit_period').val(data.credit_period);
                    
                    getMAXID('wh_material_receive','id','voucher_no','MR-');

                    $('#date').val('<?php //echo date('d/m/Y')?>');
                    $('#receipt').val('');
                    
                    $('.receive_info').show();
                    
                    if(data.agree_type=='3'){
                        $('.payment_table').hide();
                    } else {
                        $('.payment_table').show();
                    }

                    get_purchase_details(id);

                },error: function() {

                }
            });   

        }); */



        $('#item_receiveList').on('click', '.actionReturn', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_purchase_details_receive').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'assets_purchase/getItemReceiveById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#item_receive-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('return');
                    $('#item_receiveModalLabel').text('<?php echo get_phrases(['item', 'return','to','supplier']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['return']);?>');
                    $('.modal_action').prop('disabled', false);

                    $('#supplier_name').html(data.supplier_name);
                    $('#supplier_id').val(data.supplier_id);
                    $('#store_name').html(data.store_name);
                    $('#store_id').val(data.store_id);
                    $('#voucher_show').html(data.voucher_no);
                    $('#date_show').html(data.date);

                    $('#payment_method_div').html('');

                    //$('#voucher_no').val('RET-<?php //echo $return_voucher_no; ?>');
                    getMAXID('wh_material_return','id','voucher_no','GRETI-');

                    //$('#voucher_no').prop('readonly', false);
                    $('#date').val('<?php echo date('d/m/Y')?>');

                    $('.receive_info').hide();
                    
                    get_purchase_details(id);

                },error: function() {

                }
            });   

        });

        $('#item_receiveList').on('click', '.actionApprove', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_purchase_details_receive').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'assets_purchase/getItemReceiveById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                async: false,
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#item_receive-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('approve');
                    $('#item_receiveModalLabel').text('<?php echo get_phrases(['item','receive','pending', 'approval']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['approve']);?>');
                    $('.modal_action').prop('disabled', false);

                    $('#supplier_name').html(data.supplier_name);
                    $('#supplier_id').val(data.supplier_id);
                    $('#store_name').html(data.store_name);
                    $('#store_id').val(data.store_id);
                    $('#voucher_show').html(data.voucher_no);
                    $('#date_show').html(data.date);

                    $('#voucher_no').val(data.receive_voucher_no);
                    $('#voucher_no').prop('readonly', true);
                    $('#date').val(data.receive_date);
                    $('#receipt').val(data.receipt);
                    //$('#receipt').prop('readonly', true);
                    $('#payment_method').val(data.payment_method).trigger('change');
                    $('#payment_method').prop('disabled', true);

                    $('.receive_info').show();
                    $('.payment_table').hide();
                    
                    get_purchase_details(id);
                   

                },error: function() {

                }
            });   

        });

       
        $('#item_receiveList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'assets_purchase/getItemReceiveDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                async: false,
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#itemPurchaseDetails-modal').modal('show');
                    $('#itemPurchaseDetailsModalLabel').text('<?php echo get_phrases(['purchase', 'order','details']);?>');

                    $('#itemPurchaseDetails_supplier_id').text(data.supplier_name);
                    $('#itemPurchaseDetails_date').text(data.date);
                    $('#itemPurchaseDetails_voucher_no').text(data.voucher_no);
                    $('#itemPurchaseDetails_spr').text(data.r_voucher);
                    $('#itemPurchaseDetails_quatation').text(data.q_id);
                    $('#itemPurchaseDetails_store_id').text(data.store_name);
                    $('#itemPurchaseDetails_sub_total').text(data.sub_total);
                    $('#itemPurchaseDetails_grand_total').text(data.grand_total);
                    $('#itemPurchaseDetails_vat').text(data.po_vat);
                    $('#itemPurchaseDetails_receive_grand_total').text((data.receive_grand_total)?data.receive_grand_total:0);
                                        
                    get_item_details(id, data.supplier_id);

                },error: function() {

                }
            });   

        });

        $('#item_receiveList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [7] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>{
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'ItemReceive_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                <?php } if($hasExportAccess){ ?>{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'ItemReceive_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'ItemReceive_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'ItemReceive_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'ItemReceive_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'assets_purchase/getItemReceive',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.store_id = $('#filter_store_id').val();
                        d.supplier_id = $('#filter_supplier_id').val();
                        d.voucher_no = $('#filter_voucher_no').val();
                        d.date = $('#filter_date').val();
                    }
            },
          'columns': [
             { data: 'id' },
             { data: 'voucher_no' },
             { data: 'date' },
             { data: 'spr' },
             { data: 'supplier_name' },
             { data: 'grand_total', className: 'text-right' },
             { data: 'status'},
             { data: 'button'}
          ],
        });

        $('#item_receiveList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        
        $('#picture').on('change', function () {
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
            imgPreview($(this), fileExtension);
        }); 

        // delete item_receive
        $('#item_receiveList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"assets_purchase/deleteItemReceive/"+id;
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
                            $('#item_receiveList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, '<?php echo get_phrases(["record"])?>');
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });

    function get_item_details(purchase_id, supplier_id){

        if(purchase_id !='' ){
            var submit_url = _baseURL+'assets_purchase/getItemPurchasePricingDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'purchase_id':purchase_id, 'supplier_id':supplier_id },
                success: function(data) {
                    $('#item_details_preview').html(data);
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    function avail_quantity_check(){
        var item_counter = $('#item_counter').val();
        for(var i=1; i<=item_counter; i++){
            var avail_qty = parseFloat($("#avail_qty"+i).val());
            var qty = parseFloat($("#qty"+i).val());
            if(qty > avail_qty && qty >0 ){
                toastr.warning('<?php echo get_notify('Return_quantity_should_be_less_than_or_equal_to_available_quantity'); ?>');
                return false;
            }
        }
        return true;
    }

    function calculation_all(){
        var item_counter = $('#item_counter').val();
        for(var i=1; i<=item_counter; i++){
            calculation(i);
        }
    }

    function carton_calculation(sl){
        
        var carton = ($("#carton"+sl).val()=='')?0:parseInt($("#carton"+sl).val());
        var carton_qty = ($("#carton_qty"+sl).val()=='')?0:parseInt($("#carton_qty"+sl).val());
        var box_qty = ($("#box_qty"+sl).val()=='')?0:parseFloat($("#box_qty"+sl).val());

        if( carton >0 ){
            $("#qty"+sl).val(carton*carton_qty*box_qty);
            $("#box"+sl).val(carton_qty);
            $("#box"+sl).prop('readonly', true);
            $("#qty"+sl).prop('readonly', true);

            calculation(sl);
        } else {
            $("#box"+sl).prop('readonly', false);

            // box_calculation(sl);
        }

    }

    function box_calculation(sl){
        
        var box = ($("#box"+sl).val()=='')?0:parseInt($("#box"+sl).val());
        var box_qty = ($("#box_qty"+sl).val()=='')?0:parseFloat($("#box_qty"+sl).val());
        if( box >0 ){
            $("#qty"+sl).val(box*box_qty);
            $("#qty"+sl).prop('readonly', true);
        } else {
            $("#qty"+sl).val('');
            $("#qty"+sl).prop('readonly', false);
        }

        // calculation(sl);
    }

    function calculation(sl){
        
        var qty = ($("#qty"+sl).val()=='')?0:parseFloat($("#qty"+sl).val());
        var action = $("#action").val();

        var receive_qty = ($("#receive_qty"+sl).val()=='')?0:parseInt($("#receive_qty"+sl).val()); 
        if(qty > receive_qty && action=='return'){
            toastr.warning('<?php echo get_notify('Return_quantity_should_be_less_than_or_equal_to_received_quantity'); ?>');
            $("#qty"+sl).val(receive_qty);
            qty = receive_qty;
        }
        var avail_qty = ($("#avail_qty"+sl).val()=='')?0:parseInt($("#avail_qty"+sl).val()); 
        if(qty > avail_qty && qty >0 && action=='return'){
            toastr.warning('<?php echo get_notify('Return_quantity_should_be_less_than_or_equal_to_available_quantity'); ?>');
            $("#qty"+sl).val(avail_qty);
            qty = avail_qty;
        }

        var org_qty = ($("#org_qty"+sl).val()=='')?0:parseInt($("#org_qty"+sl).val());
        if(qty > org_qty && action=='receive'){
            toastr.warning('<?php echo get_notify('Purchase_quantity_should_be_less_than_or_equal_to_order_quantity'); ?>');
            $("#qty"+sl).val(0);
            qty = 0;
        }

        var price = ($("#price_"+sl).val()=='')?0:$("#price_"+sl).val();
        var total = parseFloat(qty) * parseFloat(price);
        $("#total_price_"+sl).val(total.toFixed(2));

        var vat_applicable = $("#vat_applicable"+sl).val();
        if(vat_applicable=='1'){
            var vat_amount = parseFloat(total) * <?php echo $vat/100; ?>;
            $("#vat_amount"+sl).val(vat_amount.toFixed(2));
        } else {
            $("#vat_amount"+sl).val(0);
        }
        
        // calculation_total();
    }

    function calculation_total(){        
                
        var sub_total = 0;
        $(".total").each(function () {
            sub_total += (this.value)?parseFloat(this.value):0;
        }); 

        var vat_total = 0; 
        $(".vat_amount").each(function () {
            vat_total += (this.value)?parseFloat(this.value):0;
        });

        $("#vat").val(vat_total.toFixed(2));
        
        $("#sub_total").val(sub_total.toFixed(2));

        var grand_total = sub_total+vat_total;
        $("#grand_total").val(grand_total.toFixed(2));

        var receive_qty = 0; 
        $(".receive_qty").each(function () {
            receive_qty += (this.value)?parseFloat(this.value):0;
        });

        if(receive_qty > 0 ){
            $('.modal_action').removeAttr('disabled');
        } else {
            $('.modal_action').attr('disabled','disabled');
        }

        totalReceipt();
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
        var total    =  ($("#grand_total").val()=='')?0:$("#grand_total").val();
        var rec =  ($("#receipt").val()=='')?0:$("#receipt").val();
        
        var recTotal = parseFloat(total) - parseFloat(rec);
        if(recTotal < 0){
            recTotal = 0;
        }
        $("#due").val(recTotal.toFixed(2));

    }

    
    function item_info(item_id,sl){

        $.ajax({
            url: _baseURL+"assets_purchase/getItemDetailsById/"+item_id,
            type: 'POST',
            data: {'csrf_stream_name':csrf_val,item_id: item_id},
            dataType:"JSON",
            async: false,
            success: function (data) {
                $('#unit'+sl).html(data.item.unit_name);
            }
        });
    }

    function po_calculation(sl) {
        var qty = $("#qty" + sl).val();
        var avail_qty = $("#avail_qty" + sl).val();
        if (parseFloat(qty) == NaN) {
            qty = 0;
        }
        if (parseFloat(qty) > parseFloat(avail_qty) || parseFloat(qty) < 0) {
            alert('Invalid Quantity');
            $("#qty" + sl).val('');
        }
        $("#po_avail_qty" + sl).val(avail_qty);
        $("#po_total_qty" + sl).val(qty);
        po_calculation_total();
    }

    function po_calculation_total() {
        var sub_total = 0;
        var total_qty = 0;
        var remain = 0;
        
        $(".po_subtotal").each(function() {
            if (this.value) {
                sub_total += parseFloat(this.value);
            }
        });
        $(".po_total_qty").each(function() {
            if (this.value) {
                total_qty += parseFloat(this.value);
            }
        });
        
       
        $("#po_sub_total").val(sub_total.toFixed(2));
        $("#po_sub_total_qty").val(total_qty.toFixed(2));

        if(parseFloat(total_qty) != 0){
            $('.modal_action').removeAttr('disabled');
        } else {
            // alert('Invalid Quantity');
            $('.modal_action').attr('disabled','disabled');
        }
        
        remain = parseFloat(sub_total) - parseFloat(total_qty);
        if (remain == 0) {
            $("#is_received").val('1');
        }else{
            $("#is_received").val('0');
        }

    }

</script>