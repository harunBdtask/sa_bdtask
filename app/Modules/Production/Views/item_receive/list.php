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
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">                    
                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
                        <label for="filter_machine_id" class="font-weight-600"><?php echo get_phrases(['plant']) ?> </label>
                        <select name="filter_machine_id" id="filter_machine_id" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($machine_list)){ ?>
                                <?php foreach ($machine_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="filter_voucher_no" class="font-weight-600"><?php echo get_phrases(['plan','no']) ?>  </label>
                        <select name="filter_voucher_no" id="filter_voucher_no" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($production_list)){ ?>
                                <?php foreach ($production_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->voucher_no; ?></option>
                                <?php }?>
                            <?php }?>
                        </select>
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
                            <th><?php echo get_phrases(['plan','no']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
                            <th><?php echo get_phrases(['store']);?></th>
                            <th><?php echo get_phrases(['plant']);?></th>
                            <th><?php echo get_phrases(['status']);?></th>
                            <th><?php echo get_phrases(['received','status']);?></th>
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
           <?php echo form_open_multipart('production/add_item_receive', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                <!-- <input type="hidden" name="store_id" id="store_id" value=""> -->
                <input type="hidden" name="machine_id" id="machine_id" value="">
                <input type="hidden" name="agree_type" id="agree_type" value="">
                <input type="hidden" name="credit_limit" id="credit_limit" value="">
                <input type="hidden" name="used_credit" id="used_credit" value="">
                <input type="hidden" name="credit_period" id="credit_period" value="">

                    <div class="row form-group">
                        <label for="machine_name" class="col-sm-1 text-right font-weight-600"><?php echo get_phrases(['plant'])?>:</label>
                        <div class="col-sm-3">
                            <div id="machine_name"></div>
                        </div>
                        <label for="voucher_show" class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['plan','no'])?>: </label>
                        <div class="col-sm-2">
                            <div id="voucher_show" ></div>
                        </div>
                        <label for="date_show" class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['plan','date'])?>: </label>
                        <div class="col-sm-2">
                            <div id="date_show" ></div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label for="store_id" class="col-sm-1 text-right font-weight-600"><?php echo get_phrases(['store'])?> <i class="text-danger">*</i>:</label>
                        <div class="col-sm-3">
                            <select name="store_id" id="store_id" class="form-control custom-select" required>
                            <?php if(!empty($store_list)){ ?>
                                <?php foreach($store_list as $value){?>
                                <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                               <?php }?>

                            <?php }?>
                            </select>
                        </div>
                        <label for="voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['voucher','no'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-2">
                            <input name="voucher_no"  type="text" class="form-control" id="voucher_no" placeholder="<?php echo get_phrases(['voucher','no'])?>" autocomplete="off" readonly >
                        </div>
                        <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['receive','date'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-2">
                            <input name="date"  type="text" class="form-control datepicker1" id="date" placeholder="<?php echo get_phrases(['receive','date'])?>"  value="<?php echo date('d/m/Y')?>" autocomplete="off" readonly>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                         <table class="table table-sm table-stripped w-100" id="receive_table">
                            <thead>
                                <tr>
                                    <th width="20%" class="text-center"><?php echo get_phrases(['finished', 'goods'])?></th>
                                    <th width="10%" class="return_info text-right"><?php echo get_phrases(['received','quantity'])?></th>
                                    <th width="10%" class="return_info text-right"><?php echo get_phrases(['available','qty'])?></th>
                                    <th width="5%" class="text-right"><?php echo get_phrases(['bag','size']);?></th>
                                    <th width="5%" class="text-right"><?php echo get_phrases(['material']);?> KG</th>
                                    <th width="10%" class="text-right"><?php echo get_phrases(['output','bags']);?></th>
                                    <th width="10%" class="text-right"><?php echo get_phrases(['output'])?> KG</th>
                                    <th width="10%" class="text-right">WIP KG <i class="text-danger">*</i></th>
                                    <th width="10%" class="text-right"><?php echo get_phrases(['process','loss']);?> KG</th>
                                    <th width="10%" class="text-center"><?php echo get_phrases(['bag','cost']);?> <i class="text-danger">*</i></th>
                                    <th width="10%" class="text-center"><?php echo get_phrases(['batch','no']);?></th>
                                    <th width="10%" class="text-center"><?php echo get_phrases(['expiry','date']);?> <i class="text-danger">*</i></th>

                                </tr>
                            </thead>
                            <tbody id="item_production_details_receive">
                                
                            </tbody>
                            <tfoot>

                            </tfoot>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemPurchaseDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printContent">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="<?php echo base_url() . $setting->logo; ?>" class="img-responsive" alt=""><strong><?php echo $setting->title; ?></strong><br>
                        <?php echo $setting->address; ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['plant']) ?> : </label>
                    <div class="col-sm-3">
                        <div id="itemPurchaseDetails_machine_id" ></div>
                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['plan','no']) ?> : </label>
                    <div class="col-sm-1">
                        <div id="itemPurchaseDetails_voucher_no" ></div>
                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-2">
                        <div id="itemPurchaseDetails_date"></div>
                        
                    </div>
                </div>

                <div id="item_details_preview"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
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

    function makePdf(id) {
        preloader_ajax();
        $.ajax({
            async: true,
            success: function(data) {
                getPDF(id);
            }
        }); 
    }

    function reload_table(){
        $('#item_receiveList').DataTable().ajax.reload();
    }

    function reset_table(){
        $('#filter_machine_id').val('').trigger('change');
        $('#filter_store_id').val('').trigger('change');
        $('#filter_voucher_no').val('');
        $('#filter_date').val('');
        $('#item_receiveList').DataTable().ajax.reload();
    }

    var showCallBackData = function () {  
        $('#item_receive-modal').modal('hide');
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();      
        $('#item_receiveList').DataTable().ajax.reload(null, false);
    }

    function get_production_details(production_id){

        if(production_id !='' ){
            var submit_url = _baseURL+'production/getItemPurchaseDetailsById';
            var action = $('#action').val();

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'production_id':production_id, 'action':action },
                success: function(data) {
                    $('#item_production_details_receive').html(data);

                    if(action=='return'){
                        $('.return_info').show();
                        $('.order_info').hide();
                    } else {
                        $('.return_info').hide();
                        $('.order_info').show();
                    }
                    //$('#receive_table > tfoot > tr > td').attr('colspan','9');
                    
                    calculation_all();

                }
            });
        } else {
            $('#item_production_details_receive').html('');
        }
    }


    $(document).ready(function() { 
       "use strict";       

       
        $('body').on('click', '.modal_action', function(e) {
            var action = $('#action').val();  

            if(action == 'return'){
                var result = avail_quantity_check();
                if( !result ){
                    e.preventDefault();                        
                    return false;
                }
            }

        });


        $('#item_receiveList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_production_details_receive').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'production/getItemReceiveById/'+id;

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
                    $('#item_receiveModalLabel').text('<?php echo get_phrases(['production','entry']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['receive']);?>');
                    $('.modal_action').prop('disabled', false);

                    $('#machine_name').html(data.machine_name);
                    $('#machine_id').val(data.machine_id);
                    //$('#store_name').html(data.store_name);
                    $('#store_id').val(data.store_id).trigger('change');
                    $('#voucher_show').html(data.voucher_no);
                    $('#date_show').html(data.date);

                    $('#agree_type').val(data.agree_type);
                    $('#credit_limit').val(data.credit_limit);
                    $('#used_credit').val(data.used_credit);
                    $('#credit_period').val(data.credit_period);
                    
                    getMAXID('wh_receive','id','voucher_no','PROD-');

                    //$('#voucher_no').prop('readonly', false);
                    $('#date').val('<?php echo date('d/m/Y')?>');
                    $('#receipt').val('');
                    
                    $('.receive_info').show();
                    
                    get_production_details(id);

                },error: function() {

                }
            });   

        });

        $('#item_receiveList').on('click', '.actionReturn', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_production_details_receive').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'production/getItemReceiveById/'+id;

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
                    $('#item_receiveModalLabel').text('<?php echo get_phrases(['finished','goods', 'return']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['return']);?>');
                    $('.modal_action').prop('disabled', false);

                    $('#machine_name').html(data.machine_name);
                    $('#machine_id').val(data.machine_id);
                    $('#store_name').html(data.store_name);
                    $('#store_id').val(data.store_id);
                    $('#voucher_show').html(data.voucher_no);
                    $('#date_show').html(data.date);

                    //$('#voucher_no').val('RET-<?php //echo $return_voucher_no; ?>');
                    getMAXID('wh_return','id','voucher_no','GRETI-');

                    //$('#voucher_no').prop('readonly', false);
                    $('#date').val('<?php echo date('d/m/Y')?>');

                    $('.receive_info').hide();
                    
                    get_production_details(id);

                },error: function() {

                }
            });   

        });

        $('#item_receiveList').on('click', '.actionApprove', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_production_details_receive').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'production/getItemReceiveById/'+id;

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
                    $('#item_receiveModalLabel').text('<?php echo get_phrases(['production','approval']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['approve']);?>');
                    $('.modal_action').prop('disabled', false);

                    $('#machine_name').html(data.machine_name);
                    $('#machine_id').val(data.machine_id);
                    $('#store_name').html(data.store_name);
                    $('#store_id').val(data.store_id);
                    $('#voucher_show').html(data.voucher_no);
                    $('#date_show').html(data.date);

                    $('#voucher_no').val(data.receive_voucher_no);
                    $('#voucher_no').prop('readonly', true);
                    $('#date').val(data.receive_date);
                    $('#receipt').val(data.receipt);

                    $('.receive_info').show();
                    
                    get_production_details(id);
                   

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
            var submit_url = _baseURL+'production/getItemReceiveDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                async: false,
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#itemPurchaseDetails-modal').modal('show');
                    $('#itemPurchaseDetailsModalLabel').text('<?php echo get_phrases(['production','plan','details']);?>');

                    $('#itemPurchaseDetails_machine_id').text(data.machine_name);
                    $('#itemPurchaseDetails_date').text(data.date);
                    $('#itemPurchaseDetails_voucher_no').text(data.voucher_no);
                    //$('#itemPurchaseDetails_store_id').text(data.store_name);
                                        
                    get_item_details(id, data.machine_id);

                },error: function() {

                }
            });   

        });

        var title = $("#testtitle").html();
        $('#item_receiveList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [5,6,7] },
            ],
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
                        columns: 'th:not(:last-child)'
                    }
                },
                <?php } if($hasExportAccess){ ?>{
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'ItemReceive_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'ItemReceive_List-<?php echo date('Y-m-d');?>',
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
               'url': _baseURL + 'production/getItemReceive',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.store_id = $('#filter_store_id').val();
                        d.machine_id = $('#filter_machine_id').val();
                        d.voucher_no = $('#filter_voucher_no').val();
                        d.date = $('#filter_date').val();
                    }
            },
          'columns': [
             { data: 'id' },
             { data: 'voucher_no' },
             { data: 'date' },
             { data: 'store_name' },
             { data: 'machine_name' },
             { data: 'status'},
             { data: 'received_status'},
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
            
            var submit_url = _baseURL+"production/deleteItemReceive/"+id;
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

    function get_item_details(production_id, machine_id){

        if(production_id !='' ){
            var submit_url = _baseURL+'production/getItemPurchasePricingDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'production_id':production_id, 'machine_id':machine_id },
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

            box_calculation(sl);
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

        calculation(sl);
    }

    function calculation(sl){
        
        var qty = ($("#qty"+sl).val()=='')?0:parseFloat($("#qty"+sl).val());
        var action = $("#action").val();

        var org_qty = ($("#org_qty"+sl).val()=='')?0:parseFloat($("#org_qty"+sl).val());
        var bag_size = ($("#bag_size"+sl).val()=='')?0:parseFloat($("#bag_size"+sl).val());
        if(qty > (org_qty * bag_size ) && action=='receive'){
            toastr.warning('<?php echo get_notify('Receive_quantity_should_be_less_than_or_equal_to_production_quantity'); ?>');
            $("#qty"+sl).val(0);
        }

        var receive_qty = ($("#receive_qty"+sl).val()=='')?0:parseInt($("#receive_qty"+sl).val()); 
        if(qty > receive_qty && action=='return'){
            toastr.warning('<?php echo get_notify('Return_quantity_should_be_less_than_or_equal_to_received_quantity'); ?>');
            $("#qty"+sl).val(receive_qty);
        }
        var avail_qty = ($("#avail_qty"+sl).val()=='')?0:parseInt($("#avail_qty"+sl).val()); 
        if(qty > avail_qty && qty >0 && action=='return'){
            toastr.warning('<?php echo get_notify('Return_quantity_should_be_less_than_or_equal_to_available_quantity'); ?>');
            $("#qty"+sl).val(avail_qty);
        }
        
        //calculation_total();
    }

    function calculation_total(){        
           
        var receive_qty = 0; 
        $(".receive_qty").each(function () {
            receive_qty += (this.value)?parseFloat(this.value):0;
        });

        if(receive_qty >0 ){
            $('.modal_action').removeAttr('disabled');
        } else {
            $('.modal_action').attr('disabled','disabled');
        }

    }

   
    function item_info(item_id,sl){

        $.ajax({
            url: _baseURL+"production/getItemDetailsById/"+item_id,
            type: 'POST',
            data: {'csrf_stream_name':csrf_val,item_id: item_id},
            dataType:"JSON",
            async: false,
            success: function (data) {
                $('#unit'+sl).html(data.item.unit_name);
            }
        });
    }

    function bag_cal(sl){
        
        var bag_size = ($("#bag_size"+sl).val()=='')?0:parseFloat($("#bag_size"+sl).val());
        var act_bags = ($("#act_bags"+sl).val()=='')?0:parseFloat($("#act_bags"+sl).val());
        var qty = ($("#qty"+sl).val()=='')?0:parseFloat($("#qty"+sl).val());
        var org_qty = ($("#org_qty"+sl).val()=='')?0:parseFloat($("#org_qty"+sl).val());
        var wip_kg = ($("#wip_kg"+sl).val()=='')?0:parseFloat($("#wip_kg"+sl).val());

        if( bag_size == 0 && (act_bags >0 || qty >0)){
            toastr.warning('<?php echo get_notify('Enter_bag_size'); ?>');
            $("#act_bags"+sl).val('');
            $("#qty"+sl).val('');
            $("#wip_kg"+sl).val('');
            $("#loss_kg"+sl).val('');
            
            return false;
        }
        if( act_bags == 0 && qty >0){
            toastr.warning('<?php echo get_notify('Enter_bags'); ?>');
            $("#qty"+sl).val('');
            $("#wip_kg"+sl).val('');
            $("#loss_kg"+sl).val('');
            
            return false;
        }
        if( act_bags > 0 ){
            var total_qty = act_bags * bag_size;
            var total_org_qty = org_qty * bag_size;

            if((total_qty + wip_kg) > total_org_qty){
                toastr.warning('<?php echo get_notify('invalid_quantity'); ?>');
                $("#act_bags"+sl).val(org_qty.toFixed(0));

                $("#wip_kg"+sl).val('');
                $("#wip_kg"+sl).prop('readonly', true);
                $("#loss_kg"+sl).val('');
                $("#loss_kg"+sl).prop('readonly', true);

                $("#qty"+sl).val(total_org_qty.toFixed(2));
                $("#qty"+sl).prop('readonly', true);
            } else {
                $("#wip_kg"+sl).prop('readonly', false);

                $("#qty"+sl).val(total_qty.toFixed(2));
                $("#qty"+sl).prop('readonly', true);

                if( org_qty >0 ){
                    var loss_kg = total_org_qty - (total_qty + wip_kg);
                    if(loss_kg <0 ){
                        loss_kg = 0;
                    }
                    $("#loss_kg"+sl).val(loss_kg.toFixed(2));
                    $("#loss_kg"+sl).prop('readonly', true);

                }
            }
        }

    }
</script>