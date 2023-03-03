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
                    <div class="col-sm-2 hidden">
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
                        <label for="filter_supplier_id" class="font-weight-600"><?php echo get_phrases(['supplier', 'name']) ?> </label>
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
                        <label for="filter_item_id" class="font-weight-600"><?php echo get_phrases(['materials','name']) ?> </label>
                        <select name="filter_item_id" id="filter_item_id" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($item_list)){ ?>
                                <?php foreach ($item_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE.'-'.$value->company_code;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="filter_voucher_no" class="font-weight-600"><?php echo get_phrases(['PO','no']) ?> </label>
                        <select name="filter_voucher_no" id="filter_voucher_no" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($all_po)){ ?>
                                <?php foreach ($all_po as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->voucher_no; ?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="filter_spr_no" class="font-weight-600"><?php echo get_phrases(['SPR','no']) ?> </label>
                        <select name="filter_spr_no" id="filter_spr_no" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($all_spr)){ ?>
                                <?php foreach ($all_spr as $key => $value) {?>
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
                <table id="purchase_orderList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th width="5%"><?php echo get_phrases(['sl']);?></th>
                            <th width="10%"><?php echo get_phrases(['purchase','order']);?></th>
                            <th width="5%"><?php echo get_phrases(['date']);?></th>
                            <th width="5%"><?php echo get_phrases(['SPR', 'no']);?></th>
                            <th width="10%"><?php echo get_phrases(['supplier', 'name']);?></th>
                            <th width="5%"><?php echo get_phrases(['quotation','no']);?></th>
                            <th width="5%"><?php echo get_phrases(['value']);?></th>
                            <th width="5%"><?php echo get_phrases(['purchase','status']);?></th>
                            <th width="5%"><?php echo get_phrases(['received','status']);?></th>
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
           <?php echo form_open_multipart('purchase/add_purchase_order', 'class="po_ajaxForm needs-validation" id="po_ajaxForm" novalidate="" data="po_showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" id="q_id" value="0" />
                <input type="hidden" name="id" id="po_id" />
                <input type="hidden" name="remain_po_quantity" id="remain_po_quantity" />
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
                    <label for="spr_list" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['SPR'])?><i class="text-danger">*</i></label>
                    <div class="col-sm-2">
                        <div id="requisition_select">
                        <?php echo form_dropdown('requisition_id', '', '', 'class="custom-select" id="requisition_id" required');?>
                            <!-- <select name="requisition_id" id="requisition_id" class="custom-select form-control">
                                <option value=""></option>
                                <?php if (!empty($requisition_list)) { ?>
                                    <?php foreach ($requisition_list as $key => $value) { ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->voucher_no; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select> -->
                        </div>
                        <div id="po_spr_name" class="form-control"></div>  
                    </div>
                    <label for="spr_item_list" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['SPR', 'item'])?><i class="text-danger">*</i></label>
                    <div class="col-sm-2">
                        <div id="spr_item_select">
                            <?php echo form_dropdown('spr_item_list', '', '', 'class="custom-select" id="spr_item_list" required');?>
                        </div>
                        <div id="po_item_name" class="form-control"></div>  
                    </div>
                    <label for="po_quantity" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['quantity'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-2">
                        <input name="po_quantity" type="number" onkeyup="po_quantity_cal();" class="form-control" id="po_quantity" autocomplete="off" required>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['SPR','quantity'])?></label>
                    <div class="col-sm-2">
                        <div id="spr_quantity" class="form-control"></div>
                    </div>
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['purchased','quantity'])?></label>
                    <div class="col-sm-2">
                        <div id="purchased_quantity" class="form-control"></div>
                    </div>
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['remain','quantity'])?></label>
                    <div class="col-sm-2">
                        <div id="remain_quantity" class="form-control"></div>
                    </div>
                </div>
                
                
                
                <div id="quatation_details_preview"></div>

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
<div class="modal fade bd-example-modal-xl" id="purchaseOrderDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl custom-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="purchaseOrderDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('purchase/purchase_order_approve', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body" id="printContent">
                <input type="hidden" name="id" id="id" value="" required>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="<?php echo base_url() . $setting->logo; ?>" class="img-responsive" alt=""><strong><?php echo $setting->title; ?></strong><br>
                        <?php echo $setting->address; ?>
                    </div>
                </div>
                <hr>
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
                <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                <button type="button" class="btn btn-info" onclick="makePdf('printContent')"><?php echo get_phrases(['download']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="purchaseOrderPrintDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="purchaseOrderPrintDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printContent2">


                <div class="body-content mb-5">
                    <div id="printContent" class="mt-3 mb-3" style="max-width: 1020px; margin: auto; background-color: #fff; padding: 50px">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <h4><img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="40px" ><?php echo $settings_info->title; ?></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>PO No: <span id="purchaseOrderPrintDetails_po_voucher_no"></span></strong>
                            </div>
                            <div class="col-sm-4 text-center">
                                <h4><u>Purchase Order</u></h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                <strong>Date: <span id="purchaseOrderPrintDetails_po_date"></span></strong>
                            </div>
                        </div>

                        <div>
                            <strong>To,</strong> <br />
                            <strong><span id="purchaseOrderPrintDetails_supplier_id" ></span></strong> <br />
                            <span id="purchaseOrderPrintDetails_supplier_address" ></span> <br />
                            Mobile: <span id="purchaseOrderPrintDetails_supplier_mobile"></span>
                        </div>
                        <br />

                        <div>
                            <p><strong>Subject: Purchase Order for Supply <span id="purchaseOrderPrintDetails_item_name"></span></strong></p>
                        </div>
                        <br />

                        <div style="text-align: justify;">
                            <p>Against your quotation and verbal negotiated on dated <span id="purchaseOrderPrintDetails_qdate"></span> and management approval, you are requested to supply of the following item as mentioned bellows on the following terms & condition:</p>
                        </div>
                        <br />

                        <div>
                            <table class="table table-bordered">
                                <tbody style="vertical-align: top; text-align: center">
                                    <tr>
                                        <th>SL No.</th>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Unit Price</th>
                                        <th>Total Amount</th>
                                        <th>Remarks</th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td><strong><span id="purchaseOrderPrintDetails_item_name2"></span></strong></td>
                                        <td><span id="purchaseOrderPrintDetails_item_qty"></span></td>
                                        <td><span id="purchaseOrderPrintDetails_unit_name"></span></td>
                                        <td><span id="purchaseOrderPrintDetails_unit_price"></span>/-</td>
                                        <td><span id="purchaseOrderPrintDetails_total_price"></span>/-</td>
                                        <td><span id="purchaseOrderPrintDetails_q_remarks"></span></td>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-right">Total Payable Tk</th>
                                        <th><span id="purchaseOrderPrintDetails_total_price2"></span>/-</th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <p><strong>In Words: <span id="purchaseOrderPrintDetails_amount_in_word"></span> Taka Only</strong></p>
                            <?php //echo ucwords(strtolower(numberToWords(12589000))); ?>
                        </div>
                        <br />

                        <div id="purchaseOrderPrintDetails_terms_conditions"></div>
                        <br />

                        <div>
                            <p>Hope you will agree and supply the item sooner. Your kind cooperation will be highly appreciated.</p>
                        </div>

                        <div>
                            <p>Thanking you,</p>
                        </div>
                        <br />

                        <div>
                            <p><span id="purchaseOrderPrintDetails_signature"></span></p>
                            <p>
                                <strong><span id="purchaseOrderPrintDetails_fullname"></span></strong><br />
                                <strong>Managing Director</strong>
                            </p>
                            <strong>Copy to:</strong>
                            <strong>
                                <ol>
                                    <li>Chairman</li>
                                    <li>Project Coordinator</li>
                                    <li>GM-Operations</li>
                                    <li>AGM-A/C & Finance</li>
                                    <li>Factory</li>
                                    <li>Office Copy</li>
                                </ol>
                            </strong>
                        </div>

                    </div>
                </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" class="btn btn-success" onclick="printContent('printContent2')"><?php echo get_phrases(['print']);?></button>
                <button type="button" class="btn btn-info" onclick="makePdf('printContent2')"><?php echo get_phrases(['download']);?></button>
            </div>
        </div>
    </div>
</div>

<div id="item_list"></div>

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
    $('#preloader-wrapper').removeClass('hidden');

    function reload_table(){
        $('#purchase_orderList').DataTable().ajax.reload();
    }

    function makePdf(id) {
        preloader_ajax();
        $.ajax({
            async: true,
            success: function(data) {
                getPDF(id);
            }
        }); 
    }

    function reset_table(){
        $('#filter_supplier_id').val('').trigger('change');
        $('#filter_item_id').val('').trigger('change');
        $('#filter_voucher_no').val('').trigger('change');
        $('#filter_spr_no').val('').trigger('change');
        $('#filter_date').val('');
        let table = $('#purchase_orderList').DataTable();
        // table.column(1).visible(true);
        // $('#filter_headline').addClass('hidden');
        table.ajax.reload();
    }

    var showCallBackData = function () {
        $('#id').val('');               
        $('.ajaxForm')[0].reset();        
        $('#purchaseOrderDetails-modal').modal('hide');
        $('#purchaseOrderPrintDetails-modal').modal('hide');
        $('#purchase_orderList').DataTable().ajax.reload(null, false);
    }
    var po_showCallBackData = function () {
        $('#po_id').val('');        
        $('#po_action').val('add');        
        $('.po_ajaxForm')[0].reset();        
        $('#item_purchase-modal').modal('hide');
        $('#purchase_orderList').DataTable().ajax.reload(null, false);
    }

    function reload_max_id(){
        var y = new Date().getFullYear();
        getMAXID('wh_material_purchase','id','po_voucher_no','SAAF/Purchase/'+y+'-');
    }


    function get_spr_item_list(requisition_id){
        preloader_ajax();
        $.ajax({
            url: _baseURL+"purchase/get_spr_item_list",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val, requisition_id: requisition_id},
            dataType:"json",
            async: true,
            success: function (data) {
                $('#spr_item_list option:first-child').val('').trigger('change');
                $('#spr_item_list').empty();
                $('#spr_item_list').select2({
                    placeholder: '<?php echo get_phrases(['select','item']); ?>' ,
                    data : data
                });
                var option = new Option('', '', true, true);
                $("#spr_item_list").append(option).trigger('change');

            }
        });
    }


    function select_spr(){
        preloader_ajax();
        $.ajax({
            url: _baseURL+"purchase/select_spr",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val},
            dataType:"json",
            async: true,
            success: function (data) {
                $('#requisition_id option:first-child').val('').trigger('change');
                $('#requisition_id').empty();
                $('#requisition_id').select2({
                    placeholder: '<?php echo get_phrases(['select','SPR']); ?>' ,
                    data : data
                });
                var option = new Option('', '', true, true);
                $("#requisition_id").append(option).trigger('change');

            }
        });
    }

    //get_quatation_list
    function get_quatation_list(requisition_id, item_id, po_id=null){
        if(item_id !='' ){
            preloader_ajax();
            $.ajax({
                url: _baseURL+"purchase/get_quatation_list",
                type: 'POST',
                data: {'csrf_stream_name':csrf_val, requisition_id: requisition_id, item_id: item_id, po_id: po_id},
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

    function po_quantity_cal() {
        var sl = $('#q_id').val();
        if (sl != 0) {
            radioBtn_Click(sl);
        }else{
            $('.modal_action').attr('disabled','disabled');
            alert('Invalid Quantity');
            $("#po_quantity").val('');
        }
    }

    function radioBtn_Click(sl) {
        var q_id = $("#q_id").val(sl);
        var qty = $('#po_quantity').val(); 
        var remain_po_quantity = $('#remain_po_quantity').val(); 
        var spr_qty = $("#spr_qty" + sl).val();
        if (parseFloat(qty) > parseFloat(spr_qty) || parseFloat(qty) <= 0 || parseFloat(spr_qty) <= 0 || parseFloat(remain_po_quantity) <= 0) {
            $('.modal_action').attr('disabled','disabled');
            alert('Invalid Quantity');
            $("#po_quantity").val('');
        } else {
            $('.modal_action').removeAttr('disabled');
        }
    }

    $(document).ready(function() { 
        "use strict";
                
        var timeText = new Date();
        $('#printContent2').prepend('<p style="font-size:10px">Print Date: '+timeText.toLocaleString()+'</p>');

         var title = $("#testtitle").html();
         //data list
         $('#purchase_orderList').DataTable({ 
            language: {
                searchPlaceholder: "Search records"
            },
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [0,7,8,9] },
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
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'PurchaseOrder_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'PurchaseOrder_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'purchase/getPurchaseOrder',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.store_id = $('#filter_store_id').val();
                        d.supplier_id = $('#filter_supplier_id').val();
                        d.item_id = $('#filter_item_id').val();
                        d.voucher_no = $('#filter_voucher_no').val();
                        d.spr_no = $('#filter_spr_no').val();
                        d.date = $('#filter_date').val();
                    }
            },
          'columns': [
             { data: 'id' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
             },
             { data: 'voucher_no' },
             { data: 'date' },
             { data: 'r_voucher' },
             { data: 'supplier_name' },
             { data: 'quatation_id' },
             { data: 'grand_total', className: 'text-right' },
             { data: 'status'},
             { data: 'receive_status'},
             { data: 'button'},
          ],
        });

        $('#item_list').hide();

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
        
        //add PO modal
        $('.addShowModal').on('click', function(){
            $('.po_ajaxForm').removeClass('was-validated');
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
            $('#po_quantity').val('');
            $('#quatation_details_preview').html('');
            $('#spr_quantity').text('');
            $('#purchased_quantity').text('');
            $('#remain_quantity').text('');
            $('#remain_po_quantity').val('');
            $('#po_action').val('add');

            select_spr();
            reload_max_id();
            // getMAXID('wh_material_purchase','id','po_voucher_no','SAAF/Purchase/2021-');
            
            $('#item_purchaseModalLabel').text('<?php echo get_phrases(['add', 'purchase','order', 'form']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('.modal_action').prop('disabled', true);
            $('#item_purchase-modal').modal('show');
        });


        // $('#reload_max_id').on('click', function() {
        //     reload_max_id();
        // });

        $('#purchase_orderList').on('click', '.actionPreviewPrint', function(e){
             e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'purchase/get_po_info';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val, 'po_id':id},
                async: false,
                success: function(data) {
                    $('#purchaseOrderPrintDetails-modal').modal('show');
                    $('#purchaseOrderPrintDetailsModalLabel').text('<?php echo get_phrases(['purchase','order','print']);?>');

                    $('#purchaseOrderPrintDetails_supplier_id').text(data.supplier_name);
                    $('#purchaseOrderPrintDetails_supplier_mobile').text(data.supplier_mobile);
                    $('#purchaseOrderPrintDetails_supplier_address').text(data.supplier_address);
                    $('#purchaseOrderPrintDetails_po_date').text(data.date);
                    $('#purchaseOrderPrintDetails_fullname').text(data.fullname);
                    $('#purchaseOrderPrintDetails_item_name').text(data.item_name);
                    $('#purchaseOrderPrintDetails_item_name2').text(data.item_name);
                    $('#purchaseOrderPrintDetails_item_qty').text(data.item_qty);
                    $('#purchaseOrderPrintDetails_q_remarks').text(data.q_remarks);
                    $('#purchaseOrderPrintDetails_qdate').text(data.qdate);
                    $('#purchaseOrderPrintDetails_total_price').text(data.total_price);
                    $('#purchaseOrderPrintDetails_total_price2').text(data.total_price);
                    $('#purchaseOrderPrintDetails_amount_in_word').text(data.amount_in_word);
                    $('#purchaseOrderPrintDetails_unit_name').text(data.unit_name);
                    $('#purchaseOrderPrintDetails_unit_price').text(data.unit_price);
                    $('#purchaseOrderPrintDetails_po_voucher_no').text(data.voucher_no);
                    $('#purchaseOrderPrintDetails_terms_conditions').html(data.terms_conditions);
                    if (data.signature != null) {
                        $('#purchaseOrderPrintDetails_signature').html('<img class="img-thambnail" src="<?php echo base_url() ?>' + data.signature + ' " height="70px" width="70px"/>');   
                    }

                },error: function() {

                }
            });  

        });


        //details view
        $('#purchase_orderList').on('click', '.actionPreview', function(e){
             e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#terms_conditions').addClass('hidden');
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'purchase/getPurchaseOrderDetailsById/'+id;
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: true,
                success: function(data) {
                    $('#purchaseOrderDetails-modal').modal('show');
                    $('#purchaseOrderDetailsModalLabel').text('<?php echo get_phrases(['purchase','order','details']);?>');

                    $('#purchaseOrderDetails_supplier_id').text(data.supplier_name);
                    $('#purchaseOrderDetails_store_id').text(data.store_name);
                    $('#purchaseOrderDetails_spr').text(data.r_voucher);
                    $('#purchaseOrderDetails_quatation').text(data.q_id);

                    $('#purchaseOrderDetails_voucher_no').text(data.voucher_no);
                    $('#purchaseOrderDetails_date').text(data.date);                      
                    $('#purchaseOrderDetails_sub_total').text(data.sub_total);
                    $('#purchaseOrderDetails_grand_total').text(data.grand_total);
                    $('#purchaseOrderDetails_vat').text(data.vat);

                    $('#approve').hide();
                    $('#id').val('');

                    get_item_details(id, data.supplier_id);

                },error: function() {

                }
            });  

        });

        //edit
        $('#purchase_orderList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            var id = $(this).attr('data-id');
            $('#po_id').val(id);
            $('#po_action').val('update');
            $('.modal_action').text('<?php echo get_phrases(['update']);?>');
            $('#item_details_preview').html('');
            
            var submit_url = _baseURL+'purchase/getPurchaseOrderDetailsById/'+id;
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

                        $('#po_store_id').val(data.store_id);

                        $('#requisition_id').val(data.requisition_id);
                        $('#requisition_id').removeAttr('required');
                        $('#requisition_select').addClass('hidden');
                        $('#po_spr_name').removeClass('hidden');
                        $('#po_spr_name').text(data.r_voucher);

                        $('#spr_item_list').val(data.item_id);
                        $('#spr_item_list').removeAttr('required');
                        $('#spr_item_select').addClass('hidden');
                        $('#po_item_name').removeClass('hidden');
                        $('#po_item_name').text(data.item_name);

                        get_quatation_list(data.requisition_id, data.item_id, id);

                        $('#po_voucher_no').val(data.voucher_no);
                        $('#po_quantity').val(data.po_qty);      
                        $('#po_date').val(data.date);      

                        $('.modal_action').removeAttr('disabled');

                    }

                },error: function() {

                }
            });  

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

        $('#purchase_orderList').on('click', '.actionApprove', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#item_details_preview').html('');

            $('#terms_conditions').removeClass('hidden');
            $("#summernote").summernote("code", "");
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'purchase/getPurchaseOrderDetailsById/'+id;
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: true,
                success: function(data) {
                    $('#purchaseOrderDetails-modal').modal('show');
                    $('#purchaseOrderDetailsModalLabel').text('<?php echo get_phrases(['purchase','order','pending','approval']);?>');

                    $('#purchaseOrderDetails_supplier_id').text(data.supplier_name);
                    $('#purchaseOrderDetails_store_id').text(data.store_name);
                    $('#purchaseOrderDetails_spr').text(data.r_voucher);
                    $('#purchaseOrderDetails_quatation').text(data.q_id);
                    $('#purchaseOrderDetails_voucher_no').text(data.voucher_no);
                    $('#purchaseOrderDetails_date').text(data.date);                      
                    $('#purchaseOrderDetails_sub_total').text(data.sub_total);
                    $('#purchaseOrderDetails_grand_total').text(data.grand_total);
                    $('#purchaseOrderDetails_vat').text(data.vat);

                    $('#id').val(id);
                    $('#approve').show();
                    $('#approve').removeAttr('disabled');

                    get_item_details(id, data.supplier_id);

                },error: function() {

                }
            });  

        });

        // delete purchase_order
        $('#purchase_orderList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"purchase/deletePurchaseOrder/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                preloader_ajax();
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    async: true,
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



    function get_item_details(purchase_id, supplier_id){

        if(purchase_id !='' ){
            var submit_url = _baseURL+'purchase/getPurchaseOrderPricingDetailsById';
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: true,
                data: {'csrf_stream_name':csrf_val, 'purchase_id':purchase_id, 'supplier_id':supplier_id },
                success: function(data) {
                    $('#item_details_preview').html(data);
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    function get_item_list(supplier_id){
        preloader_ajax();
        $.ajax({
            url: _baseURL+"purchase/get_item_list",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val, supplier_id: supplier_id},
            dataType:"html",
            async: true,
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
        preloader_ajax();
        $.ajax({
            url: _baseURL+"purchase/getSupplierItemDetailsById",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val,item_id: item_id,supplier_id:supplier_id},
            dataType:"JSON",
            async: true,
            success: function (data) {
                if(data != null && ( action=='add' || (action=='update' && existing=='0'))){

                    $('#main_stock'+sl).html((data.main_stock)?data.main_stock:0);
                    $('#sub_stock'+sl).html((data.sub_stock)?data.sub_stock:0);

                    $('#po_unit'+sl).html(data.unit_name);
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


</script>