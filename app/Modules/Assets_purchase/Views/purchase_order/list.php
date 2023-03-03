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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add','purchase', 'order']);?></button>  
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
                <table id="purchase_orderList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th width="5%"><?php echo get_phrases(['sl']);?></th>
                            <th width="10%"><?php echo get_phrases(['purchase','order']);?></th>
                            <th width="5%"><?php echo get_phrases(['date']);?></th>
                            <th width="5%"><?php echo get_phrases(['SPR', 'no']);?></th>
                            <th width="10%"><?php echo get_phrases(['supplier']);?></th>
                            <th width="5%"><?php echo get_phrases(['quotation','no']);?></th>
                            <th width="10%"><?php echo get_phrases(['grand','total']);?></th>
                            <th width="10%"><?php echo get_phrases(['status']);?></th>
                            <th width="10%"><?php echo get_phrases(['action']);?></th>
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
           <?php echo form_open_multipart('assets_purchase/addPurchaseOrder', 'class="po_ajaxForm needs-validation" id="po_ajaxForm" novalidate="" data="po_showCallBackData"');?>
           <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" id="q_id" value="0" />
                <input type="hidden" name="id" id="po_id" />
                <input type="hidden" name="action" id="po_action" value="add" />
                <div class="row form-group">
                    <label for="po_voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['PO','no.'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-2">
                        <input name="voucher_no" type="text" class="form-control" id="po_voucher_no" autocomplete="off" readonly >
                    </div>
                    <label for="po_date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-2">
                        <input name="date"  type="text" class="form-control datepicker1" id="po_date" placeholder="<?php echo get_phrases(['date'])?>"  value="<?php echo date('d/m/Y')?>" autocomplete="off" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="requisition_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['SPR'])?><i class="text-danger">*</i></label>
                    <div class="col-sm-2">
                        <div id="requisition_select">
                            <select name="requisition_id" id="requisition_id" class="custom-select form-control">
                                <option value=""></option>
                                <?php if (!empty($requisition_list)) { ?>
                                    <?php foreach ($requisition_list as $key => $value) { ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->voucher_no; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div id="po_spr_name" class="form-control"></div>  
                    </div>
                    <label for="spr_item_list" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['quotation', 'no'])?><i class="text-danger">*</i></label>
                    <div class="col-sm-2">
                        <div id="spr_item_select">
                            <?php echo form_dropdown('spr_item_list', '', '', 'class="custom-select" id="spr_item_list" required');?>
                        </div>
                        <div id="po_item_name" class="form-control"></div>  
                    </div>
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['party', '(', 'supplier', ')'])?> : </label>
                    <div class="col-sm-2">
                        <div id="quotation_supplier" class="form-control"></div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['quotation','attachment'])?> : </label>
                    <div class="col-sm-2">
                        <div id="quotation_attachment"></div>
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['quotation','date'])?> : </label>
                    <div class="col-sm-2">
                        <div id="quotation_date"></div>
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['quotation','remarks'])?> : </label>
                    <div class="col-sm-2">
                        <div id="quotation_remarks"></div>
                    </div>
                </div>
                
                
                
                <div id="quatation_details_preview"></div>


                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['sub', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="quatation_details_sub_total" ></div>                        
                    </div>
                </div>
                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['vat']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="quatation_details_vat" ></div>                        
                    </div>
                </div>
                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['grand', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="quatation_details_grand_total" ></div>                        
                    </div>
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
            <?php echo form_open_multipart('assets_purchase/purchase_order_approve', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body" >
                <input type="hidden" name="id" id="id" value="" required>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['supplier']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_supplier_id" ></div>
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['quotation', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_quatation"></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['purchase', 'order']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_date"></div>                        
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['SPR', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_spr" ></div>                        
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
                

                <div id="terms_conditions" class="row">                    
                    <label class="col-sm-1 font-weight-600"><?php echo get_phrases(['terms', 'conditions']) ?> : <i class="text-danger">*</i></label>
                    <div class="col-sm-10">
                        <textarea name="terms_conditions" id="summernote" rows="10" cols="80" required></textarea>
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


<div class="modal fade bd-example-modal-xl" id="templatePrintView-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="templatePrintViewModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" >

                <div class="body-content mb-5">
                    <div id="printContent" class="mt-3 mb-3" style="max-width: 1020px; margin: auto; background-color: #fff; padding: 50px">
                        <div id="templateHeader"></div>
                        <div id="templatePrintView"></div>
                        <div id="templateFooter"></div>
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



<div id="item_list"></div>

<script type="text/javascript">

    function reload_table(){
        $('#purchase_orderList').DataTable().ajax.reload();
    }

    var showCallBackData = function () {
        $('#id').val('');               
        $('.ajaxForm')[0].reset();        
        $('#purchaseOrderDetails-modal').modal('hide');
        $('#templatePrintView-modal').modal('hide');
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
        $('#filter_store_id').val('').trigger('change');
        $('#filter_supplier_id').val('').trigger('change');
        $('#filter_item_id').val('').trigger('change');
        $('#filter_voucher_no').val('');
        $('#filter_date').val('');
        $('#purchase_orderList').DataTable().ajax.reload();
    }

    function reload_max_id(){
        getMAXID('wh_bag_purchase','id','po_voucher_no','PO-');
    }

  

    $(document).ready(function() { 
        "use strict";

        $('#item_list').hide();

        $('#reload_max_id').on('click', function() {
            reload_max_id();
        });

        //summernote
        $('#summernote').summernote({
            placeholder: 'Terms and Condition:',
            tabsize: 2,
            height: 300, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true // set focus to editable area after initializing summernote
        });


        //get SPR items
        $('#requisition_id').on('change', function (e) {
            $('.modal_action').prop('disabled', true);
            var requisition_id = $(this).val(); 
            
            if(requisition_id){
                get_spr_item_list(requisition_id);
            }
        }); 

        //get quatations
        $('#spr_item_list').on('change', function (e) {
            $('.modal_action').prop('disabled', true);
            var item_id = $(this).val(); 
            var requisition_id = $('#requisition_id').val(); 
            if(requisition_id){
                get_quatation_list(requisition_id, item_id);
            }
        });


        $('#purchase_orderList').on('click', '.actionPrintPreview', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'assets_purchase/getPrintView';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val, 'action_id':id},
                async: false,
                success: function(data) {
                    $('#templatePrintView-modal').modal('show');
                    $('#templatePrintViewModalLabel').text('<?php echo get_phrases(['print','view']);?>');
                    document.getElementById("templateHeader").style.marginTop=data.template_header+"px";
                    $('#templatePrintView').html(data.template);
                    document.getElementById("templateFooter").style.marginTop=data.template_footer+"px";
                }
            });  

        });


    


        $('#purchase_orderList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [8] },
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
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'PurchaseOrder_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'PurchaseOrder_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'PurchaseOrder_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'assets_purchase/getPurchaseOrder',
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
             { data: 'r_voucher' },
             { data: 'supplier_name' },
             { data: 'quatation_id' },
             { data: 'grand_total', className: 'text-right' },
             { data: 'status'},
             { data: 'button'},
          ],
        });

        $('#purchase_orderList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        

        $('.addShowModal').on('click', function(){
            $('.po_ajaxForm').removeClass('was-validated');
            
            $('#quatation_details_preview').html('');
            $('#quotation_attachment').html('');
            $('#quotation_supplier').text('');
            $('#quotation_date').text('');
            $('#quotation_remarks').text('');
            $('#quatation_details_sub_total').text('');
            $('#quatation_details_vat').text('');
            $('#quatation_details_grand_total').text('');

            $('#po_id').val('');
            $('#requisition_id').attr('required');
            $('#requisition_select').removeClass('hidden');
            $('#po_spr_name').addClass('hidden');

            $('#spr_item_list').attr('required');
            $('#spr_item_select').removeClass('hidden');
            $('#po_item_name').addClass('hidden');
            
            $('#requisition_id').prop('disabled', false);
            $('#requisition_id').val('').trigger('change');
            $('#spr_item_list').prop('disabled', false);
            $('#spr_item_list').val('').trigger('change');
            
            $('#po_action').val('add');

            reload_max_id();


            $('#item_purchaseModalLabel').text('<?php echo get_phrases(['purchase','order', 'form']);?>');
            $('.po_modal_action').text('<?php echo get_phrases(['add']);?>');
            $('.po_modal_action').prop('disabled', false);
            $('#item_purchase-modal').modal('show');
        });

        $('#purchase_orderList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            var id = $(this).attr('data-id');
            $('#po_id').val(id);
            $('#po_action').val('update');
            $('.po_modal_action').text('<?php echo get_phrases(['update']);?>');
            $('#item_details_preview').html('');
            
            var submit_url = _baseURL+'assets_purchase/getPurchaseOrderDetailsById/'+id;
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: true,
                success: function(data) {
                    if(data.received == 1){
                        toastr.warning('<?php echo get_phrases(['already','received']); ?>');
                    } else if(data != null){
                        $('#item_purchase-modal').modal('show');
                        $('#item_purchaseModalLabel').text('<?php echo get_phrases(['update','purchase','order']);?>');
                        $('.po_modal_action').prop('disabled', false);

                        $('#requisition_id').val(data.requisition_id);
                        $('#requisition_id').removeAttr('required');
                        $('#requisition_select').addClass('hidden');
                        $('#po_spr_name').removeClass('hidden');
                        $('#po_spr_name').text(data.r_voucher);
                        
                        get_spr_item_list(data.requisition_id, data.quatation_id)
                        $('#po_item_name').addClass('hidden');

                        get_quatation_list(data.requisition_id, data.quatation_id, id);

                        $('#po_voucher_no').val(data.voucher_no); 
                        $('#po_date').val(data.date);      


                    }

                },error: function() {

                }
            });  

        });

        $('#purchase_orderList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#terms_conditions').addClass('hidden');
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'assets_purchase/getPurchaseOrderDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#purchaseOrderDetails-modal').modal('show');
                    $('#purchaseOrderDetailsModalLabel').text('<?php echo get_phrases(['purchase','order','details']);?>');
                    $('#approve').hide();
                    $('#id').val('');
                    $('#purchaseOrderDetails_supplier_id').text(data.supplier_name);
                    $('#purchaseOrderDetails_store_id').text(data.store_name);
                    $('#purchaseOrderDetails_spr').text(data.r_voucher);
                    $('#purchaseOrderDetails_quatation').text(data.q_id);
                    $('#purchaseOrderDetails_voucher_no').text(data.voucher_no);
                    $('#purchaseOrderDetails_date').text(data.date);                      
                    $('#purchaseOrderDetails_sub_total').text(data.sub_total);
                    $('#purchaseOrderDetails_grand_total').text(data.grand_total);
                    $('#purchaseOrderDetails_vat').text(data.vat);
                    $('#input_remarks').val('');
                    get_item_details(data.requisition_id, data.quatation_id);

     

                },error: function() {

                }
            });  

        });

        $('#purchase_orderList').on('click', '.actionApprove', function(e){
             e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#item_details_preview').html('');

            $('#terms_conditions').removeClass('hidden');
            $("#summernote").summernote("code", "");
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'assets_purchase/getPurchaseOrderDetailsById/'+id;

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
                    $('#purchaseOrderDetails_spr').text(data.r_voucher);
                    $('#purchaseOrderDetails_quatation').text(data.q_id);
                    $('#purchaseOrderDetails_voucher_no').text(data.voucher_no);
                    $('#purchaseOrderDetails_date').text(data.date);                      
                    $('#purchaseOrderDetails_vat').text(data.vat);                      
                    $('#purchaseOrderDetails_sub_total').text(data.sub_total);
                    $('#purchaseOrderDetails_grand_total').text(data.grand_total);

                    $('#id').val(id);
                    $('#approve').show();
                    $('#approve').prop('disabled', false);

                    get_item_details(data.requisition_id, data.quatation_id);

                },error: function() {

                }
            });  

        });

        $('#purchase_orderList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"assets_purchase/deletePurchaseOrder/"+id;
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




    function get_item_details(requisition_id, item_id, po_id=null){
        if(requisition_id !='' ){
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: _baseURL+"assets_purchase/get_quatation_list",
                dataType : 'html',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'requisition_id': requisition_id, 'item_id': item_id, 'po_id': po_id},
                success: function(data) {
                    $('#item_details_preview').html(data);
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    //get_quatation_list
    function get_quatation_list(requisition_id, item_id, po_id=null){
        if(item_id !='' ){
            preloader_ajax();
            $.ajax({
                url: _baseURL+"assets_purchase/get_quatation_list",
                type: 'POST',
                data: {'csrf_stream_name':csrf_val, 'requisition_id': requisition_id, 'item_id': item_id, 'po_id': po_id},
                dataType:"html",
                async: true,
                success: function (data) {
                    $('#quatation_details_preview').html(data);
                }
            });
        } else {
            $('#quatation_details_preview').html('');
        }
    }

    //get_spr_list
    function get_spr_item_list(requisition_id, selected_id=null){
        preloader_ajax();
        $.ajax({
            url: _baseURL+"assets_purchase/get_spr_item_list",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val, requisition_id: requisition_id},
            dataType:"json",
            async: true,
            success: function (data) {
                $('#spr_item_list option:first-child').val('').trigger('change');
                $('#spr_item_list').empty();
                $('#spr_item_list').select2({
                    placeholder: '<?php echo get_phrases(['select','option']); ?>' ,
                    data : data
                });
                var option = new Option('', '', true, true);
                $("#spr_item_list").append(option).trigger('change');            
                $("#spr_item_list").val(selected_id).trigger('change');

            }
        });
    }

</script>