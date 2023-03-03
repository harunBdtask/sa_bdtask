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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'quotation']); ?></button>
                        <?php } ?>
                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']); ?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">
                    <!-- <div class="col-sm-2">
                        <label for="filter_date" class="font-weight-600"><?php //echo get_phrases(['date']) ?> </label>
                        <input type="date" name="filter_date" id="filter_date" class="form-control">
                    </div> -->
                    <div class="col-sm-2">
                        <label for="filter_name" class="font-weight-600"><?php echo get_phrases(['SPR', 'no']) ?> </label>
                        <select name="filter_spr" id="filter_name" class="custom-select form-control">
                            <option value=""></option>
                            <?php if (!empty($approved_requisition_list)) { ?>
                                <?php foreach ($approved_requisition_list as $key => $value) { ?>
                                    <option value="<?php echo $value->id; ?>"><?php echo $value->voucher_no; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <br>
                        <button type="button" class="btn btn-success mt-2" onclick="reload_table()"><?php echo get_phrases(['filter']);?></button>
                        <button type="button" class="btn btn-warning mt-2" onclick="reset_table()"><?php echo get_phrases(['reset']);?></button>
                    </div>
                </div>
                <div class="row hidden" id="filter_headline">
                    <div class="col-md-3"></div>
                    <div class="col-md-6" id="headline_html">
                        
                    </div>
                    <div class="col-md-3"></div>
                </div>
                <!-- <div class="mb-3">
                    <span class="font-weight-600 fs-16">Toggle column:</span> <a href="javascript:void(0)" class="toggle-vis" data-column="0">Name</a> - <a href="javascript:void(0)" class="toggle-vis" data-column="1">Position</a>
                </div> -->
                <table id="quatationList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']); ?></th>
                            <th><?php echo get_phrases(['SPR', 'no']); ?></th>
                            <th><?php echo get_phrases(['supplier', 'name']); ?></th>
                            <th><?php echo get_phrases(['date']); ?></th>
                            <th><?php echo get_phrases(['SPR','status']); ?></th>
                            <th><?php echo get_phrases(['purchase','status']); ?></th>
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
           <?php echo form_open_multipart('purchase/add_quatation', 'class="po_ajaxForm needs-validation" id="po_ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                
                <input type="hidden" name="id" id="po_id" />
                <input type="hidden" name="action" id="po_action" value="add" />
                    <div class="row form-group">
                        <label for="requisition_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['SPR','no']) ?> <i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                            <select name="requisition_id" id="requisition_id" class="custom-select form-control">
                                <option value=""></option>
                                <?php if (!empty($requisition_list)) { ?>
                                    <?php foreach ($requisition_list as $key => $value) { ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->voucher_no; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <label for="supplier_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['supplier', 'name']) ?> <i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                            <select name="supplier_id" id="supplier_id" class="custom-select form-control" required>
                                <option value=""></option>
                                <?php if (!empty($supplier_list)) { ?>
                                    <?php foreach ($supplier_list as $key => $value) { ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->nameE .' ( '. $value->code_no .' )'; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date']) ?> <i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                            <input type="date" name="date" id="date" class="form-control">
                        </div>
                        <label for="remarks" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['remarks']) ?> </label>
                        <div class="col-sm-4">
                            <input type="text" name="remarks" id="remarks" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="attachment" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['attachment'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                            <input name="attachment" type="file" class="form-control" id="attachment" required >
                            <small id="attachmentHelp" class="form-text text-muted">Allow file types: jpg, jpeg, png, gif, pdf</small>
                        </div>
                        <!-- <label for="attachment" class="col-sm-2 col-form-label"><?php //echo get_phrases(['attachment']) ?> <i class="text-danger">*</i></label>
                        <div class="col-sm-7">
                            <div class="custom-file">
                                <input type="file" name="attachment" class="custom-file-input" aria-describedby="attachmentHelp" id="attachment" accept=".png, .jpg, .jpeg, .gif, .pdf" required>
                                <small id="attachmentHelp" class="form-text text-muted">Allow file types: jpg, jpeg, png, gif, pdf</small>
                                <label class="custom-file-label" for="attachment"><?php //echo get_phrases(['please', 'choose', 'file']); ?></label>
                            </div>
                        </div> -->
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-3">
                            <div id="filePreview"></div>
                        </div>
                    </div>
                    

                    <input type="hidden" name="item_counter" id="po_item_counter" value="1">
                    <div class="row mt-2">
                         <table class="table table-sm table-stripped w-100" id="purchase_table">
                            <thead>
                                <tr>
                                    <th width="15%" class="text-center"><?php echo get_phrases(['raw', 'material', 'name'])?><i class="text-danger">*</i></th>
                                    <th width="5%" class="text-right"><?php echo get_phrases(['request','quantity']) ?></th>
                                    <th width="5%" class="text-left"><?php echo get_phrases(['unit'])?></th>
                                    <th width="10%" class="text-right"><?php echo get_phrases(['unit', 'price']) ?></th>
                                    <th width="10%" class="text-right"><?php echo get_phrases(['total', 'price']) ?></th>
                                    <th width="5%" class="text-right"><?php echo get_phrases(['action'])?></th>

                                </tr>
                            </thead>
                            <tbody id="po_item_div">
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><b><?php echo get_phrases(['sub', 'total'])?></b></td>
                                    <td><input type="text" name="sub_total" class="form-control text-right" id="po_sub_total" readonly=""></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b><?php echo get_phrases(['vat'])?></b></td>
                                    <td><input type="text" name="vat" class="form-control text-right" id="po_vat" readonly=""></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b><?php echo get_phrases(['grand', 'total'])?></b></td>
                                    <td><input type="text" name="grand_total" class="form-control text-right" id="po_grand_total" readonly=""></td>
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
            <?php echo form_open_multipart('purchase/requisition_approve', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData55"');?>
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
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['SPR', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_date"></div>                        
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['supplier', 'name']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_supplier_id" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['remarks']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_remarks"></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['quotation', 'file']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="purchaseOrderDetails_filePreview"></div>
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
                <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                <button type="button" class="btn btn-info" onclick="makePdf('printContent')"><?php echo get_phrases(['download']);?></button>
            </div>
            <?php echo form_close();?>
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
        $('#filter_name').val('').trigger('change');
        let table = $('#quatationList').DataTable();
        table.column(1).visible(true);
        $('#filter_headline').addClass('hidden');
        table.ajax.reload();
    }

    function reload_table(){
        let table = $('#quatationList').DataTable();
        var filter_name = $('#filter_name').val();
        if (filter_name != '') {
            var submit_url = _baseURL+'purchase/getSpr';
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val, 'spr':filter_name},
                async: true,
                success: function(data) {
                    $('#headline_html').html('<h3>Search Result for: '+data.voucher_no+' </h3>')
                }
            }); 
            table.column(1).visible(false);
            $('#filter_headline').removeClass('hidden');
        }else{
            table.column(1).visible(true);
            $('#filter_headline').addClass('hidden');
        }
        table.ajax.reload();
    }

    var showCallBackData = function() {
        $('#id').val('');
        $('#action').val('add');
        $('.po_ajaxForm')[0].reset();
        $('#item_purchase-modal').modal('hide');
        $('#quatationList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() {
        "use strict";
       
        $('#item_list').addClass('hidden');


        var title = $("#testtitle").html();
        $('#quatationList').DataTable({
            searching: false,
            responsive: true,
            lengthChange: true,
            "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [0,4,5,6] },
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
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': _baseURL + 'purchase/getQuatation',
                'data':function ( d ) {
                    d.csrf_stream_name = csrf_val;
                    d.date = $('#filter_date').val();
                    d.spr = $('#filter_name').val();
                }
            },
            'columns': [
                { data: 'id' ,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'voucher_no'
                },
                {
                    data: 'code_no'
                },
                {
                    data: 'date'
                },
                {
                    data: 'spr_status'
                },
                {
                    data: 'status'
                },
                {
                    data: 'button'
                }
            ],
        });
    
        // $('a.toggle-vis').on('click', function (e) {
        //     e.preventDefault();
        //     var column = table.column($(this).attr('data-column'));
        //     column.visible(!column.visible());
        // });
    
        //actionPreview
        $('#quatationList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.add_ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'purchase/getSprDetailsById/'+id;
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: true,
                success: function(data) {
                    $('#purchaseOrderDetails-modal').modal('show');
                    $('#purchaseOrderDetailsModalLabel').text('<?php echo get_phrases(['quotation', 'details']);?>');

                    $('#purchaseOrderDetails_supplier_id').text(data.s_name+' ( '+data.s_code+' )');
                    $('#purchaseOrderDetails_voucher_no').text(data.spr_no);
                    $('#purchaseOrderDetails_date').text(data.date);                      
                    $('#purchaseOrderDetails_remarks').text(data.remarks);                      
                    $('#purchaseOrderDetails_sub_total').text(data.sub_total);
                    $('#purchaseOrderDetails_grand_total').text(data.grand_total);
                    $('#purchaseOrderDetails_vat').text(data.vat);
                    $('#purchaseOrderDetails_filePreview').html('<a href="<?php echo base_url() ?>' + data.file + ' " target="_blank" rel="noopener noreferrer" class="btn btn-primary"><i class="fa fa-download"></i> </a>');

                    $('#approve').hide();
                    $('#id').val('');

                    get_item_details(data.spr_id, id);

                },error: function() {

                }
            });  

        });


        //addModal
        $('.addShowModal').on('click', function(){
            $('.po_ajaxForm').removeClass('was-validated');
            $('#po_id').val('');
            $('#po_action').val('add');
            $('#requisition_id').prop('disabled', false);
            $('#remarks').val('');
            // $('#date').val('');
            $('#date').val('<?php echo date("Y-m-d");?>'); 
            $('#filePreview').addClass('hidden');
            $('#supplier_id').val('').trigger('change'); 
            $('#requisition_id').val('').trigger('change'); 
            getMAXID('wh_material_requisition','id','po_voucher_no','SPR-');
            // var item_list = $('#item_list').html();
            var item_list = $('#item_list').html('');
            //add spr modal show first row
            var html = '<tr>'+
                            '<td><select name="item_id[]" id="item_id1" class="form-control custom-select" onchange="po_item_info(this.value,1)" required>'+item_list+'</select></td>'+
                            '<td class="valign-middle" align="right"><span id="requested_qnty1"></span></td>'+
                            // '<td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty1" onkeyup="po_calculation(1)" required autocomplete="off" ></td>'+
                            '<td class="valign-middle"><span id="po_unit1"></span></td>'+
                            '<td><input type="text" name="po_price[]" class="form-control text-right onlyNumber" id="po_price_1" onkeyup="po_calculation(1)" required autocomplete="off" ></td>'+
                            '<td class="valign-middle" align="right"><input type="text" name="total[]" class="form-control po_subtotal text-right" id="po_total_price_1" readonly=""></td>'+
                            '<td class="valign-middle" align="right"><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td>'+
                            '<input type="hidden" name="existing[]" id="existing1" value="0">'+
                            '<input type="hidden" name="po_qty[]" id="po_qty1" value="1" />'+
                            '<input type="hidden" name="vat_applicable[]" id="vat_applicable1" />'+
                            '<input type="hidden" name="vat_amount[]" id="vat_amount1" class="vat_total" />'+
                        '</tr>';      
            $("#po_item_div").html(html); 

            $("#po_item_counter").val(1);

            po_calculation(1);
            
            $('#item_id1').select2({
                placeholder: "<?php echo get_phrases(['select','item']);?>"                
            });

            $('#heading_carton').text('<?php echo get_phrases(['no','of','box']); ?>');

            $('#item_purchaseModalLabel').text('<?php echo get_phrases(['add','quotation', 'form']);?>');
            $('.po_modal_action').text('<?php echo get_phrases(['add']);?>');
            $('.po_modal_action').prop('disabled', false);
            $('#item_purchase-modal').modal('show');
        });


        //editModal
        $('#quatationList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.po_ajaxForm').removeClass('was-validated');
            $('#filePreview').removeClass('hidden');
            $("#attachment").removeAttr('required');
            $('#item_details_preview').html('');
            $('#po_action').val('update');
            // $("#requisition_id").removeAttr('required');
            $('#requisition_id').prop('disabled', true);
            var id = $(this).attr('data-id');
            $('#po_id').val(id);
            var submit_url = _baseURL+'purchase/getQuatationById/'+id;
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
                        $('#item_purchaseModalLabel').text('<?php echo get_phrases(['update', 'quotation']);?>');

                        $('#requisition_id').val(data.requisition_id).trigger('change');
                        $('#supplier_id').val(data.supplier_id).trigger('change');
                        $('#date').val(data.date);   
                        $('#remarks').val(data.remarks);
                        $('#filePreview').html('<a href="<?php echo base_url() ?>' + data.file + ' " target="_blank" rel="noopener noreferrer" class="btn btn-primary"><i class="fa fa-download"></i> </a>');   
                        // $('#filePreview').html('<img src="<?php echo base_url() ?>' + data.file + ' " width="100%"/>');   

                        
                        

                        $('.po_modal_action').text('<?php echo get_phrases(['update']);?>');
                        $('.po_modal_action').prop('disabled', false);

                        get_item_details_edit(id);
                    }

                },error: function() {

                }
            });  

        });

        // delete
        $('#quatationList').on('click', '.actionDelete', function(e) {
            e.preventDefault();

            var id = $(this).attr('data-id');

            var submit_url = _baseURL + "purchase/deleteQuatation/" + id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"]) ?>');
            if (check == true) {
                preloader_ajax();
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
                            $('#quatationList').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.error(res.message, '<?php echo get_phrases(["record"]) ?>');
                        }
                    },
                    error: function() {

                    }
                });
            }
        });



        // add spr modal tbody new item
        $('body').on('click', '.addRow', function() {
            var item_counter = parseInt($("#po_item_counter").val()); 
            item_counter += 1;
            $("#po_item_counter").val(item_counter);
            var item_list = $('#item_list').html();
            var html = '<tr>'+
                            '<td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td>'+
                            '<td class="valign-middle" align="right"><span id="requested_qnty'+item_counter+'"></span></td>'+
                            // '<td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty'+item_counter+'" onkeyup="po_calculation('+item_counter+')" required autocomplete="off" ></td>'+
                            '<td class="valign-middle"><span id="po_unit'+item_counter+'"></span></td>'+
                            '<td><input type="text" name="po_price[]" id="po_price_'+item_counter+'" class="form-control text-right" onkeyup="po_calculation('+item_counter+')" required autocomplete="off"/></td>'+
                            '<td class="valign-middle" align="right"><input type="text" name="total[]" class="form-control po_subtotal text-right" id="po_total_price_'+item_counter+'" readonly=""></td>'+
                            '<td class="valign-middle" align="right"><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button></td>'+
                            '<input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0">'+
                            '<input type="hidden" name="po_qty[]" id="po_qty'+item_counter+'" value="1" />'+
                            '<input type="hidden" name="vat_applicable[]" id="vat_applicable'+item_counter+'" />'+
                            '<input type="hidden" name="vat_amount[]" id="vat_amount'+item_counter+'" class="vat_total" />'+
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


        //get SPR items
        $('#requisition_id').on('change', function (e) {
            var requisition_id = $(this).val(); 
            var qid = $('#po_id').val(); 
            if(requisition_id){
                get_item_list(requisition_id, qid);
            }
        }); 

    
    });


    function downloadContent(id) {
        var html = $("#"+id).html();
        var submit_url = _baseURL+'purchase/quatation_dompdf';
        preloader_ajax();
        $.ajax({
            type: 'POST',
            url: submit_url,
            // dataType : 'json',
            async: true,
            data: { 'csrf_stream_name':csrf_val, 'html':html },
            xhrFields: {
                responseType: 'blob' // to avoid binary data being mangled on charset conversion
            },
            success: function(blob, status, xhr) {
                // check for a filename
                var filename = "";
                var disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                }
                if (typeof window.navigator.msSaveBlob !== 'undefined') {
                    // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                    window.navigator.msSaveBlob(blob, filename);
                } else {
                    var URL = window.URL || window.webkitURL;
                    var downloadUrl = URL.createObjectURL(blob);
                    if (filename) {
                        // use HTML5 a[download] attribute to specify filename
                        var a = document.createElement("a");
                        // safari doesn't support this yet
                        if (typeof a.download === 'undefined') {
                            window.location.href = downloadUrl;
                        } else {
                            a.href = downloadUrl;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                        }
                    } else {
                        window.location.href = downloadUrl;
                    }
                    setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
                }
            }
        });
    }

    // update modal tbody
    function edit_item_row(item_counter){
        var item_list = $('#item_list').html();

        var html = '<tr>'+
                '<td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td>'+
                '<td class="valign-middle" align="right"><span id="requested_qnty'+item_counter+'"></span></td>'+
                // '<td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty'+item_counter+'" onkeyup="po_calculation('+item_counter+')" required autocomplete="off" ></td>'+
                '<td class="valign-middle"><span id="po_unit'+item_counter+'"></span></td>'+
                '<td><input type="text" name="po_price[]" id="po_price_'+item_counter+'" class="form-control text-right" onkeyup="po_calculation('+item_counter+')" required autocomplete="off"/></td>'+
                '<td class="valign-middle" align="right"><input type="text" name="total[]" class="form-control po_subtotal text-right" id="po_total_price_'+item_counter+'" readonly=""></td>'+
                '<td class="valign-middle" align="right"><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td>'+
                '<input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0">'+
                '<input type="hidden" name="po_qty[]" id="po_qty'+item_counter+'" value="1" />'+
                '<input type="hidden" name="vat_applicable[]" id="vat_applicable'+item_counter+'" />'+
                '<input type="hidden" name="vat_amount[]" id="vat_amount'+item_counter+'" class="vat_total" />'+
            '</tr>';
        $("#po_item_div").append(html); 
        $('#item_id'+item_counter).select2({
            placeholder: '<?php echo get_phrases(['select','item']);?>'                
        });
    }


    function get_item_details(spr_id, quatation_id){
        if(spr_id !='' ){
            var submit_url = _baseURL+'purchase/getQuatationPricingDetailsById';
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: true,
                data: {'csrf_stream_name':csrf_val, 'purchase_id':spr_id, 'quatation_id':quatation_id },
                success: function(data) {
                    $('#item_details_preview').html(data);
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    
    //get_spr_list
    function get_item_list(requisition_id, qid){
        preloader_ajax();
        $.ajax({
            url: _baseURL+"purchase/get_spr_list",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val, requisition_id: requisition_id},
            dataType:"html",
            async: true,
            success: function (data) {
                $('#item_list').html('');
                $("#po_item_div").html(''); 
                $('#item_list').html(data);
                if (qid > 0) {
                    get_item_details_edit(qid)
                }else{
                    first_item_row();
                }
            }
        });
    }


    function get_item_details_edit(purchase_id){
        if(purchase_id !='' ){
            var submit_url = _baseURL+'purchase/get_quatation_details_by_id';
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'json',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'quatation_id':purchase_id },
                success: function(data) {
                    if(data != null){
                        $("#po_item_div").html(''); 
                        var item_counter = 1;
                        $.each(data, function(index, value){
                            edit_item_row(item_counter);

                            // $('#requisition_id').val(data.requisition_id).trigger('change');
                            $("#item_id"+item_counter).val(value.item_id).trigger('change');
                            $("#po_unit"+item_counter).text(value.unit_name);    
                            $("#requested_qnty"+item_counter).text(value.qty);  
                            $("#po_qty"+item_counter).val(value.qty);  
                            $("#vat_applicable"+item_counter).val(value.vat_applicable);   
                            $("#po_price_"+item_counter).val(value.price);
                            $("#po_org_price_"+item_counter).val(value.price);
                            $("#po_total_price_"+item_counter).val(value.total);
                            
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


    //add quatation spr items first row
    function first_item_row(){
        var item_list = $('#item_list').html();
        var html = '<tr>'+
                        '<td><select name="item_id[]" id="item_id1" class="form-control custom-select" onchange="po_item_info(this.value,1)" required>'+item_list+'</select></td>'+
                        '<td class="valign-middle" align="right"><span id="requested_qnty1"></span></td>'+
                        // '<td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty1" onkeyup="po_calculation(1)" required autocomplete="off" ></td>'+
                        '<td class="valign-middle"><span id="po_unit1"></span></td>'+
                        '<td><input type="text" name="po_price[]" class="form-control text-right onlyNumber" id="po_price_1" onkeyup="po_calculation(1)" required autocomplete="off" ></td>'+
                        '<td class="valign-middle" align="right"><input type="text" name="total[]" class="form-control po_subtotal text-right" id="po_total_price_1" readonly=""></td>'+
                        '<td class="valign-middle" align="right"><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td>'+
                        '<input type="hidden" name="existing[]" id="existing1" value="0">'+
                        '<input type="hidden" name="po_qty[]" id="po_qty1" value="1" />'+
                        '<input type="hidden" name="vat_applicable[]" id="vat_applicable1" />'+
                        '<input type="hidden" name="vat_amount[]" id="vat_amount1" class="vat_total" />'+
                    '</tr>';      
        $("#po_item_div").html(html); 
        $("#po_item_counter").val(1);
        po_calculation(1);
        $('select').select2({
            placeholder: '<?php echo get_phrases(['select','item']);?>'                
        });
    }


    //select item
    function po_item_info(item_id,sl){
        if (item_id != '') {
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
            var requisition_id = $('#requisition_id').val();
            var action = $('#po_action').val();
            var existing = $('#existing'+sl).val();
            preloader_ajax();
            $.ajax({
                url: _baseURL+"purchase/getSprItemDetailsById",
                type: 'POST',
                data: {'csrf_stream_name':csrf_val,item_id: item_id,requisition_id:requisition_id},
                dataType:"JSON",
                async: false,
                success: function (data) {
                    if(data != null && ( action=='add' || (action=='update' && existing=='0'))){
                        $('#po_unit'+sl).html(data.unit_name);
                        $('#requested_qnty'+sl).html(data.qty);
                        $('#po_qty'+sl).val(data.qty);
                        $('#vat_applicable'+sl).val(data.is_vat);
    
                        po_calculation(sl);
                    }
                }
            });
        }
    }


    function po_calculation(sl){
        
        var qty = ($("#po_qty"+sl).val()=='')?0:$("#po_qty"+sl).val();
        var price = ($("#po_price_"+sl).val()=='')?0:$("#po_price_"+sl).val();
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
        $(".po_subtotal").each(function () {
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