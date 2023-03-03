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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add','SPR']);?></button>  
                       <?php } ?> 
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">    
                    <div class="col-sm-2">
                        <label for="filter_voucher_no" class="font-weight-600"><?php echo get_phrases(['SPR','no']) ?> </label>
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
                <table id="purchase_orderList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['SPR','no']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
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
<div class="modal fade bd-example-modal-xl" id="item_purchase-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl custom-modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="item_purchaseModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('assets_purchase/add_requisition', 'class="po_ajaxForm needs-validation" id="po_ajaxForm" novalidate="" data="po_showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="po_id" />
                <input type="hidden" name="action" id="po_action" value="add" />
                    <div class="row">
                        <div style="display: none;">
                            <label for="po_store_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['store'])?> <i class="text-danger">*</i></label>
                            <div class="col-sm-2">
                                <select name="store_id" id="po_store_id" class="form-control custom-select" >
                                    <?php foreach($store_list as $value){?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                                </select>
                            </div>
                        </div>
                        <label for="po_voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['SPR','no.'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-1">
                            <input name="voucher_no"  type="text" class="form-control" id="po_voucher_no" placeholder="<?php echo get_phrases(['purchase','order', 'no'])?>" autocomplete="off" readonly >
                        </div>
                        <label for="po_date" class="col-sm-1 col-form-label font-weight-600"><?php echo get_phrases(['date'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-1">
                            <input name="date"  type="text" class="form-control datepicker1" id="po_date" placeholder="<?php echo get_phrases(['date'])?>"  value="<?php echo date('d/m/Y')?>" autocomplete="off" readonly>
                        </div>
                    </div>
                    

                    <input type="hidden" name="item_counter" id="po_item_counter" value="1">
                    <div class="row mt-2">
                         <table class="table table-sm table-stripped w-100" id="purchase_table">
                            <thead>
                                <tr>
                                    <th width="15%" class="text-center"><?php echo get_phrases(['item', 'name'])?><i class="text-danger">*</i></th>
                                    <th width="5%" class="text-center"><?php echo get_phrases(['present','stock'])?></th>
                                    <th width="10%" class="text-center"><?php echo get_phrases(['total','quantity'])?></th>
                                    <th width="5%" class="text-left"><?php echo get_phrases(['unit'])?></th>
                                    <th width="5%" class="text-left"><?php echo get_phrases(['last','receive','date'])?></th>
                                    <th width="5%" class="text-left"><?php echo get_phrases(['last','receive','quantity'])?></th>
                                    <th width="5%" class="text-center"><?php echo get_phrases(['monthly', 'consumption'])?></th>
                                    <th width="5%" class="text-left"><?php echo get_phrases(['where', 'use'])?></th>
                                    <th width="5%"><?php echo get_phrases(['action'])?></th>

                                </tr>
                            </thead>
                            <tbody id="po_item_div">
                                
                            </tbody>
                            <input type="hidden" name="sub_total" class="form-control text-right" id="po_sub_total" readonly="">
                            <input type="hidden" name="grand_total" class="form-control text-right" id="po_grand_total" readonly="">
                            <input type="hidden" name="vat" class="form-control text-right" id="po_vat" readonly="">
                         </table>   
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success po_modal_action actionBtn"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<!-- item receive modal button -->
<div class="modal fade bd-example-modal-xl" id="purchaseOrderDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl custom-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="purchaseOrderDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('assets_purchase/requisition_approve', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body" >
                <input type="hidden" name="id" id="id" value="" required>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['SPR', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_date"></div>                        
                    </div>
                </div>

                <div id="item_details_preview"></div>

                <!-- <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['sub', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="purchaseOrderDetails_sub_total" ></div>                        
                    </div>
                </div>
                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['vat']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="purchaseOrderDetails_vat" ></div>                        
                    </div>
                </div>
                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['grand', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="purchaseOrderDetails_grand_total" ></div>                        
                    </div>
                </div> -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success actionBtn" id="approve"><?php echo get_phrases(['approve']);?></button>
                
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<div id="item_list"></div>

<script type="text/javascript">

    function reload_table(){
        $('#purchase_orderList').DataTable().ajax.reload();
    }

    var showCallBackData = function () {
        $('#id').val('');               
        $('.ajaxForm')[0].reset();        
        $('#purchaseOrderDetails-modal').modal('hide');
        $('#purchase_orderList').DataTable().ajax.reload(null, false);
    }
    var po_showCallBackData = function () {
        $('#po_id').val('');        
        $('#po_action').val('add');        
        $('.po_ajaxForm')[0].reset();        
        $('#item_purchase-modal').modal('hide');
        $('#purchase_orderList').DataTable().ajax.reload(null, false);
    }
    function reset_table(){
        $('#filter_voucher_no').val('');
        $('#filter_date').val('');
        $('#purchase_orderList').DataTable().ajax.reload();
    }

    function reload_max_id(){
        getMAXID('wh_bag_requisition','id','po_voucher_no','SPR-');
    }

    function first_item_row(){
        var item_list = $('#item_list').html();

        var html = '<tr>'+
                        '<td><select name="item_id[]" id="item_id1" class="form-control custom-select" onchange="po_item_info(this.value,1)" required>'+item_list+'</select></td>'+
                        '<td class="valign-middle text-right"><span id="main_stock1"></span></td>'+
                        '<td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty1" onkeyup="po_calculation(1)" required autocomplete="off" ></td>'+
                        '<td class="valign-middle"><span id="po_unit1"></span></td>'+
                        '<td><input type="text" name="total[]" class="form-control po_total text-right" id="po_total_price_1" readonly=""></td>'+
                        '<td><input type="hidden" name="existing[]" id="existing1" value="0"><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td>'+
                        '<input type="hidden" name="store[]" id="store1" />'+
                    '</tr>';      
        $("#po_item_div").html(html); 
        $("#po_item_counter").val(1);
        po_calculation(1);
        $('select').select2({
            placeholder: '<?php echo get_phrases(['select','item']);?>'                
        });
    }

    $(document).ready(function() { 
        "use strict";

        $('#item_list').hide();

        $('#reload_max_id').on('click', function() {
            reload_max_id();
        });

        //add row
        $('body').on('click', '.addRow', function() {
            var item_counter = parseInt($("#po_item_counter").val()); 
            item_counter += 1;
            $("#po_item_counter").val(item_counter);
            var item_list = $('#item_list').html();
            // add spr modal tbody new item
            var html = '<tr>'+
                            '<td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td>'+
                            '<td><input type="text" name="main_stock[]" id="main_stock'+item_counter+'" class="form-control text-right" readonly></td>'+
                            '<td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty'+item_counter+'" onkeyup="po_calculation('+item_counter+')" required autocomplete="off" ></td>'+
                            '<td class="valign-middle"><span id="po_unit'+item_counter+'"></span></td>'+
                            '<td><input type="text" name="last_purchase_date[]" id="last_purchase_date'+item_counter+'" class="form-control text-right" readonly></td>'+
                            '<td><input type="text" name="last_purchase_qnty[]" id="last_purchase_qnty'+item_counter+'" class="form-control text-right" readonly></td>'+
                            '<td class="valign-middle"><input type="text" name="monthly_consumption[]" id="monthly_consumption_'+item_counter+'" class="form-control text-right"></td>'+
                            '<td class="valign-middle"><span id="where_use'+item_counter+'"></span></td>'+
                            '<td><input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0"><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button></td>'+
                            '<input type="hidden" name="store[]" id="store'+item_counter+'" />'+
                        '</tr>';

            $("#po_item_div").append(html); 
            $('select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });
        });

        //remove row
        $('body').on('click', '.removeRow', function() {
            var rowCount = $('#purchase_table >tbody >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
                po_calculation_total();
            }else{
                toastr.warning('<?php echo get_notify("There_only_one_row_you_can_not_delete."); ?>');
            } 
        });

        //add modal
        $('.addShowModal').on('click', function(){
            $('.po_ajaxForm').removeClass('was-validated');
            get_item_list();
            $('#po_id').val('');
            $('#po_action').val('add');
            $('#supplier_id').val('');
            $('#po_store_id').val('').trigger('change'); 
            getMAXID('wh_bag_requisition','id','po_voucher_no','SPR-');
            var item_list = $('#item_list').html();
            //add spr modal first row
            var html = '<tr>'+
                            '<td><select name="item_id[]" id="item_id1" class="form-control custom-select" onchange="po_item_info(this.value,1)" required>'+item_list+'</select></td>'+
                            '<td><input type="text" name="main_stock[]" id="main_stock1" class="form-control text-right" readonly></td>'+
                            '<td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty1" onkeyup="po_calculation(1)" required autocomplete="off" ></td>'+
                            '<td class="valign-middle"><span id="po_unit1"></span></td>'+
                            '<td class="valign-middle"><input type="text" name="last_purchase_date[]" id="last_purchase_date1" class="form-control text-right" readonly></td>'+
                            '<td class="valign-middle text-right"><input type="text" name="last_purchase_qnty[]" id="last_purchase_qnty1" class="form-control text-right" readonly></td>'+
                            '<td class="valign-middle"><input type="text" name="monthly_consumption[]" id="monthly_consumption_1"  class="form-control text-right"></td>'+
                            '<td class="valign-middle"><span id="where_use1"></span></td>'+
                            '<td><input type="hidden" name="existing[]" id="existing1" value="0"><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td>'+
                            '<input type="hidden" name="store[]" id="store1" />'+
                        '</tr>';      
            $("#po_item_div").html(html); 
            $("#po_item_counter").val(1);
            po_calculation(1);
            $('select').select2({
                placeholder: "<?php echo get_phrases(['select','item']);?>"                
            });
            $('#heading_carton').text('<?php echo get_phrases(['no','of','box']); ?>');
            $('#item_purchaseModalLabel').text('<?php echo get_phrases(['purchase','requisition', 'form']);?>');
            $('.po_modal_action').text('<?php echo get_phrases(['add']);?>');
            $('.po_modal_action').prop('disabled', false);
            $('#item_purchase-modal').modal('show');
        });

        //single details
        $('#purchase_orderList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'assets_purchase/getRequisitionDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#purchaseOrderDetails-modal').modal('show');
                    $('#purchaseOrderDetailsModalLabel').text('<?php echo get_phrases(['SPR','details']);?>');

                    $('#purchaseOrderDetails_supplier_id').text(data.supplier_name);
                    $('#purchaseOrderDetails_store_id').text(data.store_name);

                    $('#purchaseOrderDetails_voucher_no').text(data.voucher_no);
                    $('#purchaseOrderDetails_date').text(data.date);                      
                    // $('#purchaseOrderDetails_sub_total').text(data.sub_total);
                    // $('#purchaseOrderDetails_grand_total').text(data.grand_total);
                    // $('#purchaseOrderDetails_vat').text(data.vat);

                    $('#approve').hide();
                    $('#id').val('');

                    get_item_details(id, data.supplier_id);

                },error: function() {

                }
            });  

        });

        $('#purchase_orderList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [4] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>{
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'PurchaseOrder_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                <?php } if($hasExportAccess){ ?>{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'PurchaseOrder_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'PurchaseOrder_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'PurchaseOrder_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'PurchaseOrder_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'assets_purchase/getRequisition',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.store_id = $('#filter_store_id').val();
                        d.supplier_id = $('#filter_supplier_id').val();
                        d.item_id = $('#filter_item_id').val();
                        d.voucher_no = $('#filter_voucher_no').val();
                        d.date = $('#filter_date').val();
                    }
            },
          'columns': [
             { data: 'id' },
             { data: 'voucher_no' },
             { data: 'date' },
            //  { data: 'grand_total', className: 'text-right' },
             { data: 'status'},
             { data: 'button'},
          ],
        });

        $('#purchase_orderList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        
        //edit modal
        $('#purchase_orderList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#item_details_preview').html('');
            get_item_list();
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'assets_purchase/getRequisitionDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    if(data.received == 1){
                        toastr.warning('<?php echo get_phrases(['already','received']); ?>');
                    } else if(data != null){
                        $('#item_purchase-modal').modal('show');
                        $('#item_purchaseModalLabel').text('<?php echo get_phrases(['purchase','requisition','update']);?>');

                        $('#po_voucher_no').val(data.voucher_no);
                        $('#po_date').val(data.date);      

                        $('#po_id').val(id);
                        $('#po_action').val('update');

                        $('.po_modal_action').text('<?php echo get_phrases(['update']);?>');
                        $('.po_modal_action').prop('disabled', false);

                        get_item_details_edit(id);
                    }

                },error: function() {

                }
            });  

        });

        $('#purchase_orderList').on('click', '.actionApprove', function(e){
             e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'assets_purchase/getRequisitionDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#purchaseOrderDetails-modal').modal('show');
                    $('#purchaseOrderDetailsModalLabel').text('<?php echo get_phrases(['purchase','order','pending','approval']);?>');

                    $('#purchaseOrderDetails_supplier_id').text(data.supplier_name);
                    $('#purchaseOrderDetails_store_id').text(data.store_name);

                    $('#purchaseOrderDetails_voucher_no').text(data.voucher_no);
                    $('#purchaseOrderDetails_date').text(data.date);                      
                    // $('#purchaseOrderDetails_vat').text(data.vat);                      
                    // $('#purchaseOrderDetails_sub_total').text(data.sub_total);
                    // $('#purchaseOrderDetails_grand_total').text(data.grand_total);

                    $('#id').val(id);
                    $('#approve').show();
                    $('#approve').prop('disabled', false);

                    get_item_details(id, data.supplier_id);

                },error: function() {

                }
            });  

        });
        // delete purchase_order
        $('#purchase_orderList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"assets_purchase/deleteRequisition/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    async: false,
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, '<?php echo get_phrases(["record"])?>');
                            $('#purchase_orderList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, '<?php echo get_phrases(["record"])?>');
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });


    function get_item_details_edit(purchase_id){

        if(purchase_id !='' ){
            var submit_url = _baseURL+'assets_purchase/getRequisitionItemDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'json',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'purchase_id':purchase_id },
                success: function(data) {
                    if(data != null){
                        var item_counter = 1;
                        $("#po_item_div").html(''); 
                        $.each(data, function(index, value){
                            add_item_row(item_counter);

                            $("#item_id"+item_counter).val(value.item_id).trigger('change');
                            $("#po_carton"+item_counter).val(value.carton);
                            $("#carton_qty"+item_counter).val(value.carton_qty);
                            $("#po_box"+item_counter).val(value.box);
                            if(value.carton >0 ){                               
                                $('#po_box'+item_counter).prop('readonly', true);
                            } else {
                                $('#po_box'+item_counter).prop('readonly', false);
                            }
                            $("#box_qty"+item_counter).val(value.box_qty);    
                            $("#po_qty"+item_counter).val(value.qty);                      
                            $("#store"+item_counter).val(value.store_id);                      
                            
                            $("#where_use"+item_counter).text(value.where_use_name);
                            $("#po_unit"+item_counter).text(value.unit_name);
                            $("#main_stock"+item_counter).val(value.last_stock_qnty);
                            $("#last_purchase_qnty"+item_counter).val(value.last_purchase_qnty);
                            $("#last_purchase_date"+item_counter).val(value.last_purchase_date);
                            $("#po_price_"+item_counter).val(value.price);
                            $("#monthly_consumption_"+item_counter).val(value.monthly_consumption);
                            $("#po_org_price_"+item_counter).val(value.price);
                            $("#po_total_price_"+item_counter).val(value.total);
                            $("#vat_applicable"+item_counter).val(value.vat_applicable);
                            $("#vat_amount"+item_counter).val(value.total * <?php echo $vat/100; ?>);

                            po_calculation(item_counter);

                            item_counter += 1;
                            $("#po_item_counter").val(item_counter);
                        });
                    } 
                }
            });
        } else {
            $('#po_item_div').html('');
        }
    }

    function add_item_row(item_counter){
        var item_list = $('#item_list').html();

        var button_color='success';
        var button_type='plus';
        var button_class='addRow';
        if( item_counter > 1){
            var button_color='danger';
            var button_type='minus';
            var button_class='removeRow';
        }
        // update modal tbody
        var html = '<tr>'+
                        '<td>'+
                            '<select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select>'+
                        '</td>'+
                        '<td><input type="text" name="main_stock[]" class="form-control text-right" id="main_stock'+item_counter+'" readonly ></td>'+
                        '<td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty'+item_counter+'" onkeyup="po_calculation('+item_counter+')" required autocomplete="off" ></td>'+
                        '<td class="valign-middle"><span id="po_unit'+item_counter+'"></span></td>'+
                        '<td><input type="text" name="last_purchase_date[]" class="form-control text-right" id="last_purchase_date'+item_counter+'" readonly ></td>'+
                        '<td><input type="text" name="last_purchase_qnty[]" class="form-control text-right" id="last_purchase_qnty'+item_counter+'" readonly ></td>'+
                        '<td><input type="text" name="monthly_consumption[]" class="form-control text-right onlyNumber" id="monthly_consumption_'+item_counter+'" required autocomplete="off" ></td>'+
                        '<td class="valign-middle"><span id="where_use'+item_counter+'"></span></td>'+
                        '<td><input type="hidden" name="existing[]" id="existing'+item_counter+'" value="1"><button type="button" class="btn btn-'+button_color+' '+button_class+'" ><i class="fa fa-'+button_type+'"></i></button></td>'+
                        '<input type="hidden" name="store[]" id="store'+item_counter+'" />'+
                    '</tr>';

        $("#po_item_div").append(html); 
        $('select').select2({
            placeholder: '<?php echo get_phrases(['select','item']);?>'                
        });
    }

    function get_item_details(purchase_id, supplier_id){

        if(purchase_id !='' ){
            var submit_url = _baseURL+'assets_purchase/getRequisitionPricingDetailsById';

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

    function get_item_list(){
         $.ajax({
            url: _baseURL+"assets_purchase/get_item_list_bag",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val},
            dataType:"html",
            async: false,
            success: function (data) {
                $('#item_list').html(data);
                first_item_row();
            }
        });
    }

    function po_item_info(item_id,sl){
        var item_counter = parseInt($("#po_item_counter").val()); 
        var item_id_each = 0;
        for(var i=1; i<=item_counter; i++){
            item_id_each = $("#item_id"+i).val();
            if(item_id == item_id_each &&  i!=sl){
                toastr.warning('<?php echo get_notify('Same_item_can_not_be_added'); ?>');
                $("#item_id"+sl).val('').trigger('change');
                return false;
            }
        }
        var supplier_id = $('#supplier_id').val();
        var action = $('#po_action').val();
        var existing = $('#existing'+sl).val();

        $.ajax({
            url: _baseURL+"assets_purchase/getSupplierItemDetailsByIdBag",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val,item_id: item_id,supplier_id:supplier_id},
            dataType:"JSON",
            async: false,
            success: function (data) {
                if(data != null && ( action=='add' || (action=='update' && existing=='0'))){

                    $('#main_stock'+sl).val((data.main_stock)?data.main_stock:0);
                    $('#sub_stock'+sl).html((data.sub_stock)?data.sub_stock:0);

                    $('#po_unit'+sl).html(data.unit_name);
                    $('#store'+sl).val(data.store_id);
                    $('#last_purchase_qnty'+sl).val(data.last_purchase_qty);
                    $('#last_purchase_date'+sl).val(data.last_purchase_date);
                    $('#where_use'+sl).html(data.where_use_name);
                    $('#monthly_consumption_'+sl).val(data.aprox_monthly_consumption);
                    $('#po_price_'+sl).val(data.price);
                    $('#po_org_price_'+sl).val(data.price);
                    $('#vat_applicable'+sl).val(data.vat_applicable);

                    if( parseFloat(data.box_qty) >0 ){
                        $('#box_qty'+sl).val(data.box_qty);
                        $('#po_box'+sl).val('');
                        $("#po_box"+sl).prop('readonly', false);
                    } else {
                        $('#box_qty'+sl).val('');
                        $('#po_box'+sl).val('');
                        $("#po_box"+sl).prop('readonly', true);
                    }
                    if( parseInt(data.carton_qty) >0 && parseFloat(data.box_qty) >0 ){
                        $('#carton_qty'+sl).val(data.carton_qty);
                        $('#po_carton'+sl).val('');
                        $("#po_carton"+sl).prop('readonly', false);

                    } else {
                        $('#carton_qty'+sl).val('');
                        $('#po_carton'+sl).val('');
                        $("#po_carton"+sl).prop('readonly', true);
                    } 
                    $('#po_qty'+sl).val('');
                    $("#po_qty"+sl).prop('readonly', false);

                    po_calculation(sl);
                }
            }
        });
    }

    function po_carton_cal(sl){
        
        var carton = ($("#po_carton"+sl).val()=='')?0:parseInt($("#po_carton"+sl).val());
        var carton_qty = ($("#carton_qty"+sl).val()=='')?0:parseInt($("#carton_qty"+sl).val());
        var box_qty = ($("#box_qty"+sl).val()=='')?0:parseFloat($("#box_qty"+sl).val());

        if( carton > 0 && carton_qty >0 && box_qty >0 ){
            var total_qty = carton * carton_qty * box_qty;
            $("#po_qty"+sl).val(total_qty);
            $("#po_qty"+sl).prop('readonly', true);
            $("#po_box"+sl).val(carton_qty);
            $("#po_box"+sl).prop('readonly', true);

            $('#heading_carton').text('<?php echo get_phrases(['carton','qty']); ?>');
        } else if( carton_qty >0 && box_qty >0 ){
            var total_qty = carton_qty * box_qty;
            $("#po_qty"+sl).val(total_qty);
            $("#po_qty"+sl).prop('readonly', true);
            $("#po_box"+sl).val(carton_qty);
            $("#po_box"+sl).prop('readonly', false);

            $('#heading_carton').text('<?php echo get_phrases(['no','of','box']); ?>');
        } else {
            $("#po_qty"+sl).val('');
            $("#po_qty"+sl).prop('readonly', false);
            $("#po_box"+sl).val('');
            $("#po_box"+sl).prop('readonly', false);

            $('#heading_carton').text('<?php echo get_phrases(['no','of','box']); ?>');
        }
        po_calculation(sl);
    }

    function po_box_cal(sl){
        
        var po_box = ($("#po_box"+sl).val()=='')?0:$("#po_box"+sl).val();
        var box_qty = ($("#box_qty"+sl).val()=='')?0:$("#box_qty"+sl).val();

        if(parseInt(po_box) > 0){
            var total_qty = parseInt(po_box) * parseFloat(box_qty);
            $("#po_qty"+sl).val(total_qty);
            $("#po_qty"+sl).prop('readonly', true);
        } else {
            $("#po_qty"+sl).val('');
            $("#po_qty"+sl).prop('readonly', false);
        }

        po_calculation(sl);
    }

    function po_calculation(sl){
        
        var qty = ($("#po_qty"+sl).val()=='')?0:$("#po_qty"+sl).val();
        var price = ($("#po_price_"+sl).val()=='')?0:$("#po_price_"+sl).val();
        var org_price = ($("#po_org_price_"+sl).val()=='')?0:$("#po_org_price_"+sl).val();
        if(price > org_price){
            toastr.warning('<?php echo get_notify('Purchase_price_is_greated_than_default_price'); ?>');
        }
        var total = parseFloat(qty) * parseFloat(price);
        $("#po_total_price_"+sl).val(total.toFixed(2));

        var vat_applicable = ($("#vat_applicable"+sl).val()=='')?0:$("#vat_applicable"+sl).val();
        if(vat_applicable =='1' ){
            $("#vat_amount"+sl).val(total * <?php echo $vat/100; ?>);
        } else {
            $("#vat_amount"+sl).val(0);
        }
        
        po_calculation_total();
    }

    function po_calculation_total(){        
                
        var sub_total = 0; 
        var vat_total = 0; 
        $(".po_total").each(function () {
            sub_total += parseFloat(this.value);       
        });
        $(".vat_total").each(function () {
            vat_total += parseFloat(this.value);       
        });

        $("#po_vat").val(vat_total.toFixed(2));
        $("#po_sub_total").val(sub_total.toFixed(2));
        var grand_total = sub_total+vat_total;
        $("#po_grand_total").val(grand_total.toFixed(2));
        
    }

</script>