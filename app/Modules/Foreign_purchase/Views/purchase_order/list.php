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
                        <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add','PO']);?></button> 
                    <?php } ?>
                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>

                </div>
            </div>

            <div class="card-body">
               
                <table id="purchase_orderList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['Po','Id']);?></th>
                            <th><?php echo get_phrases(['supplier']);?></th>
                            <th><?php echo get_phrases(['Lc ','Number']);?></th>
                            <th><?php echo get_phrases(['Subtotal']);?></th>
                            <th><?php echo get_phrases(['Vat']);?></th>
                            <th><?php echo get_phrases(['Grand',' Total']);?></th>
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
<div class="modal fade bd-example-modal-lg" id="item_purchase-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="item_purchaseModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('foreign_purchase/add_po', 'class="po_ajaxForm needs-validation" id="po_ajaxForm" novalidate="" data="po_showCallBackData"');?>
            <div class="modal-body">
                
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="po_id" />
                <input type="hidden" name="action" id="po_action" value="add" />
                <input type="hidden" name="store_id" value="1" />


                    <div class="row">

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="po_supplier_id" class="font-weight-600"><?php echo get_phrases(['supplier'])?> <i class="text-danger">*</i></label>
                                <select name="po_supplier_id" id="po_supplier_id" class="custom-select" >
                                    <?php foreach($supplier_list as $value){?>
                                        <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" name="supplier_id" id="supplier_id" value="">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label class="font-weight-600">Lc Number<i class="text-danger">*</i></label>

                                <select name="lc_number" id="lc_number" class="custom-select" >
                                    <?php foreach($lclist as $value){?>
                                        <option value="<?php echo $value->lc_number;?>"><?php echo $value->lc_number;?></option>
                                    <?php } ?>
                                </select>

                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label class="font-weight-600">Purchase Code <i class="text-danger">*</i></label>
                                <input name="po_code"  type="text" class="form-control" id="po_code" placeholder="<?php echo get_phrases(['purchase','order', 'no'])?>" autocomplete="off" readonly >
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label class="font-weight-600">PO Date <i class="text-danger">*</i></label>
                                <input name="date"  type="text" class="form-control datepicker1" id="po_date" placeholder="<?php echo get_phrases(['date'])?>"  value="<?php echo date('d/m/Y')?>" autocomplete="off" readonly>
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="item_counter" id="po_item_counter" value="1">

                    <div class="row mt-2">

                        <table class="table table-sm table-stripped w-100" id="purchase_table">
                            
                            <thead>
                                <tr>
                                    <th width="15%" class="text-center"><?php echo get_phrases(['item', 'name'])?><i class="text-danger">*</i></th>
                                    <th width="10%" class="text-center"><?php echo get_phrases(['quantity'])?></th>
                                    <th width="5%" class="text-left"><?php echo get_phrases(['unit'])?></th>
                                    <th width="5%" class="text-left"><?php echo get_phrases(['price'])?></th>
                                    <th width="5%"><?php echo get_phrases(['action'])?></th>
                                </tr>
                            </thead>

                            <tbody id="po_item_div">
                                
                            </tbody>


                            <!-- <input type="hidden" name="sub_total" class="form-control text-right" id="po_sub_total" readonly=""> -->
                            <!-- <input type="hidden" name="grand_total" class="form-control text-right" id="po_grand_total" readonly=""> -->
                            <!-- <input type="hidden" name="vat" class="form-control text-right" id="po_vat_amount" readonly=""> -->
                            
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-right"><b><?php echo get_phrases(['sub', 'total'])?></b></td>
                                    <td colspan="2"><input type="text" name="sub_total" class="form-control text-right" id="po_sub_total" readonly=""></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right"><b><?php echo get_phrases(['vat'])?></b></td>
                                    <td colspan="2"><input type="text" name="vat" class="form-control text-right" id="po_vat_amount" readonly=""></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right"><b><?php echo get_phrases(['grand', 'total'])?></b></td>
                                    <td colspan="2"><input type="text" name="grand_total" class="form-control text-right" id="po_grand_total" readonly=""></td>
                                </tr>
                            </tfoot>
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
            <?php echo form_open_multipart('foreign_purchase/po_approve', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body" >
                <input type="hidden" name="id" id="id" value="" required>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['Po', 'id']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['Po ','date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_date"></div>                        
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['party', '(', 'supplier', ')']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_supplier_id" ></div>                        
                    </div>
                    
                </div>


                <div id="item_details_preview"></div>

                <div class="row">                    
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
                </div>

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



$(document).ready(function() { 

        "use strict";
        function reload_table(){
            $('#purchase_orderList').DataTable().ajax.reload();
        }
        

        function reload_max_id(){
            getMAXID('wh_material_requisition','id','po_voucher_no','SPR-');
        }


        $('.addShowModal').on('click', function(){

            $('.po_ajaxForm').removeClass('was-validated');
            $('#po_id').val('');
            $('#po_action').val('add');

            $('#po_supplier_id').removeAttr('disabled');
            $('#po_store_id').val('').trigger('change'); 

            getMAXID('ah_po','row_id','po_code','PORM-');

            var item_list = $('#item_list').html();
            console.log(item_list);
            //add spr modal first row
            var html = '<tr>'+
                            '<td><select name="item_id[]" id="item_id1" class="form-control custom-select" onchange="po_item_info(this.value,1)" required>'+item_list+'</select><input type="hidden" name="po_vat[]" id="po_vat1" value="0"><input type="hidden" name="total_vat[]" class="total_vat" id="total_vat1" value="0"></td>'+
                            '<td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty1" onkeyup="po_calculation(1)" required autocomplete="off" ></td>'+
                            '<td class="valign-middle"><input type="text" name="po_unit[]" class="form-control text-right" id="po_unit1" readonly required autocomplete="off" ></td>'+
                            '<td class="valign-middle"><input type="text" name="price[]" id="price1" onkeyup="po_calculation(1)" class="po_price form-control text-right onlyNumber"></td>'+
                            '<td><input type="hidden" name="existing[]" id="existing1" value="0"><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td>'+
                        '</tr>'; 

            $("#po_item_div").html(html); 

            $("#po_item_counter").val(1);
            po_calculation(1);


            $('select').select2({
                placeholder: "<?php echo get_phrases(['select','item']);?>"                
            });

            $('#heading_carton').text('<?php echo get_phrases(['no','of','box']); ?>');
            $('#item_purchaseModalLabel').text('<?php echo get_phrases(['purchase', 'form']);?>');
            $('.po_modal_action').text('<?php echo get_phrases(['add']);?>');
            $('.po_modal_action').prop('disabled', false);
            $('#item_purchase-modal').modal('show');

        });


        $('#item_list').hide();

        $('#reload_max_id').on('click', function() {
            reload_max_id();
        });


        $('body').on('click', '.addRow', function() {
            
            var item_counter = parseInt($("#po_item_counter").val()); 
            item_counter += 1;
            $("#po_item_counter").val(item_counter);
            var item_list = $('#item_list').html();
            // add spr modal tbody new item
            var html = '<tr>'+
                            '<td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select><input type="hidden" name="po_vat[]" id="po_vat'+item_counter+'" value="0"><input type="hidden" name="total_vat[]" class="total_vat" id="total_vat'+item_counter+'" value="0"></td>'+
                            '<td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty'+item_counter+'" onkeyup="po_calculation('+item_counter+')" required autocomplete="off" ></td>'+
                            '<td class="valign-middle"><input type="text" name="po_unit[]" class="form-control text-right"  id="po_unit'+item_counter+'"  readonly required autocomplete="off" ></td>'+
                            '<td><input type="text" name="price[]" id="price'+item_counter+'" onkeyup="po_calculation('+item_counter+')" class="form-control po_price text-right onlyNumber"></td>'+
                            '<td><input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0"><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button></td>'+
                        '</tr>';

            $("#po_item_div").append(html); 
            $('select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });

        });


        $('body').on('click', '.removeRow', function() {
            var rowCount = $('#purchase_table >tbody >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
                po_calculation_total();
            }else{
                toastr.warning('<?php echo get_notify("There_only_one_row_you_can_not_delete."); ?>');
            } 
        });



        $('#purchase_orderList').on('click', '.actionApprove', function(e){

            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#item_details_preview').html('');

            var id = $(this).attr('data-id');
            // var submit_url = _baseURL+'foreign_purchase/getPurchaseOrderDetailsById/'+id;
            var submit_url = _baseURL+'foreign_purchase/getPurchaseDetailsById/'+id;

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

                    $('#purchaseOrderDetails_voucher_no').text(data.po_code);
                    $('#purchaseOrderDetails_date').text(data.po_date);                      
                    $('#purchaseOrderDetails_vat').text(data.po_vat);                      
                    $('#purchaseOrderDetails_sub_total').text(data.po_subtotal);
                    $('#purchaseOrderDetails_grand_total').text(data.po_grand_total);

                    $('#id').val(id);
                    $('#approve').show();
                    $('#approve').prop('disabled', false);

                    get_item_details(id, data.supplier_id);

                },error: function() {

                }
            });  

        });


        
        //actionPreview
        $('#purchase_orderList').on('click', '.actionPreview', function(e){

            e.preventDefault();
            $('.add_ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'foreign_purchase/getPurchaseDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#purchaseOrderDetails-modal').modal('show');

                    $('#purchaseOrderDetailsModalLabel').text('<?php echo get_phrases(['Op', 'details']);?>');

                    $('#purchaseOrderDetails_supplier_id').text(data.supplier_name);
                    $('#purchaseOrderDetails_voucher_no').text(data.po_code);
                    $('#purchaseOrderDetails_date').text(data.po_date);                         
                    $('#purchaseOrderDetails_sub_total').text(data.po_subtotal);
                    $('#purchaseOrderDetails_grand_total').text(data.po_grand_total);
                    $('#purchaseOrderDetails_vat').text(data.po_vat);
                    $('#purchaseOrderDetails_filePreview').html('<a href="<?php echo base_url() ?>' + data.file + ' " target="_blank" rel="noopener noreferrer" class="btn btn-primary"><i class="fa fa-download"></i> </a>');

                    $('#approve').hide();
                    $('#id').val('');

                    get_item_details(id);

                },error: function() {

                }
            });  
        });


        //editModal
        $('#purchase_orderList').on('click', '.actionEdit', function(e){

            e.preventDefault();
            $('.po_ajaxForm').removeClass('was-validated');
            $('#filePreview').removeClass('hidden');
            $("#attachment").removeAttr('required');
            $('#item_details_preview').html('');
            $('#po_action').val('update');
            $('#requisition_id').prop('disabled', true);

            var id = $(this).attr('data-id');
            $('#po_id').val(id);

            var submit_url = _baseURL+'foreign_purchase/getPurchaseDetailsById/'+id;

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

                        $('#item_purchaseModalLabel').text('<?php echo get_phrases(['update', 'purchase']);?>');
                        $('#requisition_id').val(data.requisition_id).trigger('change');
                        $('#po_supplier_id').val(data.po_supplier_id).trigger('change');
                        $('#date').val(data.po_date);   
                        $('#lc_number').val(data.lc_number);
                        $('#po_code').val(data.po_code);
                        
                        $('.po_modal_action').text('<?php echo get_phrases(['update']);?>');
                        $('.po_modal_action').prop('disabled', false);

                        get_item_details_edit(id);
                    }

                },error: function() {

                }
            });  

        });



        function get_item_details_edit(id){
            
            if(id !='' ){
                var submit_url = _baseURL+'foreign_purchase/get_purchase_item_details';
                preloader_ajax();

                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    dataType : 'json',
                    async: false,
                    data: {'csrf_stream_name':csrf_val, 'id':id },
                    success: function(data) {

                        console.log(data);
                        
                        if(data != null){
                            $("#po_item_div").html(''); 
                            var item_counter = 1;

                            $.each(data, function(index, value){

                                edit_item_row(item_counter);

                                $("#item_id"+item_counter).val(value.po_item_id).trigger('change');
                                $("#po_unit"+item_counter).text(value.unit_name);    
                                $("#po_qty"+item_counter).val(value.po_item_qty);  
                                $("#price"+item_counter).val(value.price);

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



        // update modal tbody
        function edit_item_row(item_counter){
            $("#po_item_counter").val(item_counter);
            var item_list = $('#item_list').html();
            // add spr modal tbody new item
            var html = '<tr>'+
                            '<td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select><input type="hidden" name="po_vat[]" id="po_vat'+item_counter+'" value="0"><input type="hidden" name="total_vat[]" class="total_vat" id="total_vat'+item_counter+'" value="0"></td>'+
                            '<td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty'+item_counter+'" onkeyup="po_calculation('+item_counter+')" required autocomplete="off" ></td>'+
                            '<td class="valign-middle"><input type="text" name="po_unit[]" class="form-control text-right"  id="po_unit'+item_counter+'"  readonly required autocomplete="off" ></td>'+
                            '<td><input type="text" name="price[]" id="price'+item_counter+'" onkeyup="po_calculation('+item_counter+')" class="form-control po_price text-right onlyNumber"></td>'+
                            '<td><input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0"><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td>'+
                        '</tr>';

            $("#po_item_div").append(html); 

            $('select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });
        }

        // delete purchase_order
        $('#purchase_orderList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"foreign_purchase/delete_po/"+id;
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



        $('#purchase_orderList').DataTable({ 

            responsive: true,
            lengthChange: true,
            "aaSorting": [[ 0, "desc" ]],
            "columnDefs": [
                { "bSortable": false, "aTargets": [4] },
            ],
            dom: "<'row'<?php if(@$hasExportAccess || @$hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if(@$hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'PurchaseOrder_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                <?php } if(@$hasExportAccess){ ?>
                {
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
               'url': _baseURL + 'foreign_purchase/getPo',
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
                { data: 'supplier_name' },
                { data: 'lc_number' },
                { data: 'po_subtotal' },
                { data: 'po_vat' },
                { data: 'po_grand_total' },
                { data: 'po_date' },
                { data: 'status'},
                { data: 'button'},
            ],
        });


        $('#purchase_orderList').on('draw.dt', function() {
            $('.custool').tooltip(); 
        });
        get_item_list(null);


        function get_item_details(id){

            if(id !='' ){
                var submit_url = _baseURL+'foreign_purchase/getPurchaseItemDetails';

                preloader_ajax();
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    dataType : 'html',
                    async: false,
                    data: {'csrf_stream_name':csrf_val, 'id':id},

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
                url: _baseURL+" foreign_purchase/get_item_list",
                type: 'POST',
                data: {'csrf_stream_name':csrf_val},
                dataType:"html",
                async: false,
                success: function (data) {
                    $('#item_list').html(data);
                }
            });
        }

    });



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

            url: _baseURL+"foreign_purchase/getItemInfo",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val,item_id: item_id},
            dataType:"JSON",
            async: false,
            success: function (data) {
                console.log(data);
                if(data != null && ( action=='add' || (action=='update' && existing=='0'))){

                    $('#po_unit'+sl).val(data.unit_nameeee);
                    $('#vat_applicable'+sl).val(data.vat_applicable);
                    $('#po_vat'+sl).val(data.vat_applicable);
                    $('#po_qty'+sl).val(0);

                    $('#price'+sl).val(0);
                    $("#po_qty"+sl).prop('readonly', false);

                    po_calculation(sl);
                }
            }
        });

    }



    function po_calculation(sl){

        var vat_applicable = ($("#po_vat"+sl).val()=='')?0:$("#po_vat"+sl).val();
        var price = ($("#price"+sl).val()=='')?0:$("#price"+sl).val();

        var vat  =  price * <?php echo @$vat/100; ?>
        
        if(vat_applicable =='1' ){
            $("#total_vat"+sl).val(vat);
        } else {
            $("#total_vat"+sl).val(0);
        }
        po_calculation_total();

    }



    function po_calculation_total(){        
                
        var sub_total = 0; 
        var vat_total = 0; 

        $(".po_price").each(function () {
            sub_total += parseFloat(this.value);       
        });


        $(".total_vat").each(function () {
            vat_total += parseFloat(this.value);       
        });

        $("#po_vat_amount").val(vat_total.toFixed(2));
        $("#po_sub_total").val(sub_total.toFixed(2));

        var grand_total = sub_total+vat_total;
        $("#po_grand_total").val(grand_total.toFixed(2));
    }




</script>