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
                        <label for="filter_voucher_no" class="font-weight-600"><?php echo get_phrases(['voucher','no']) ?> </label>
                        <input type="text" name="filter_voucher_no" id="filter_voucher_no" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <label for="filter_date" class="font-weight-600"><?php echo get_phrases(['date']) ?> </label>
                        <input type="date" name="filter_date" id="filter_date" class="form-control">
                    </div>                    
                    <div class="col-sm-3">
                        <label for="filter_production_id" class="font-weight-600"><?php echo get_phrases(['plan','number']) ?> </label>
                        <select name="filter_production_id" id="filter_production_id" class="custom-select form-control" >
                            <option value=""></option>
                            <?php if(!empty($production_list)){ ?>
                                <?php foreach ($production_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->voucher_no;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="filter_sub_store_id" class="font-weight-600"><?php echo get_phrases(['plant']) ?> </label>
                        <select name="filter_sub_store_id" id="filter_sub_store_id" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($sub_store_list)){ ?>
                                <?php foreach ($sub_store_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <br>
                        <button type="button" class="btn btn-success mt-2" onclick="reload_table()"><?php echo get_phrases(['filter']);?></button>
                        <button type="button" class="btn btn-warning mt-2" onclick="reset_table()"><?php echo get_phrases(['reset']);?></button>
                    </div>
                </div>
                <table id="item_approveList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['voucher','no']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
                            <th><?php echo get_phrases(['plan','number']);?></th>
                            <th><?php echo get_phrases(['plant']);?></th>
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

<!-- item request modal button -->
<div class="modal fade bd-example-modal-xl" id="ItemApproveDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="ItemApproveDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('production/add_item_approve', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="sub_store_id" id="sub_store_id" />
                <input type="hidden" name="production_id" id="production_id" />
                <input type="hidden" name="action" id="action" value="" />
                
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['production','plan']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="ItemApproveDetails_production_id"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="ItemApproveDetails_voucher_no" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['plant']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="ItemApproveDetails_sub_store_id" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="ItemApproveDetails_date"></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['finished','goods']) ?> : </label>
                    <div class="col-sm-10">
                        <div id="ItemApproveDetails_finished_goods" ></div>
                        
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-12">
                        <div id="item_details_preview"></div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['notes']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="ItemApproveDetails_notes" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['request','by']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="ItemApproveDetails_request_by" ></div>                        
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <!-- <button id="reject" data-id="reject" class="btn btn-danger actionBtn" ><?php //echo get_phrases(['reject']);?></button> -->
                <button id="approve" data-id="approve" class="btn btn-success actionBtn" ><?php echo get_phrases(['approve']);?></button>
                <button id="collect" data-id="collect" class="btn btn-success actionBtn" ><?php echo get_phrases(['collect']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    function reload_table(){
        $('#item_approveList').DataTable().ajax.reload();
    }

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('update');        
        $('.ajaxForm')[0].reset();        
        $('#ItemApproveDetails-modal').modal('hide');
        $('#item_approveList').DataTable().ajax.reload(null, false);
    }

    function reset_table(){
        $('#filter_machine_id').val('').trigger('change');
        $('#filter_store_id').val('').trigger('change');
        $('#filter_production_voucher_no').val('').trigger('change');
        $('#filter_voucher_no').val('').trigger('change');
        $('#filter_date').val('');
        $('#item_approveList').DataTable().ajax.reload();
    }

    $(document).ready(function() { 
       "use strict";


        $('#approve,#collect').on('click', function(e) {
            var type = $(this).attr('data-id');
            $('#action').val(type);

           if(type=='approve') {
                var result = check_quantity();
                if( !result ){
                    e.preventDefault();
                    return false;
                }
                
            }
            if(type=='reject') {                
                var item_counter = parseInt($('#item_counter').val());
                for(var i=1; i<=item_counter; i++){
                    $('#receive_details_id'+i).removeAttr('required');                    
                }                
            }

        });

        $('#item_approveList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'production/getItemRequestDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#ItemApproveDetails-modal').modal('show');
                    $('#ItemApproveDetailsModalLabel').text('<?php echo get_phrases(['consumption','request','details']);?>');
                    
                    $('#id').val(data.id);

                    $('#ItemApproveDetails_sub_store_id').text(data.sub_store_name);
                    $('#ItemApproveDetails_date').text(data.date);
                    $('#ItemApproveDetails_voucher_no').text(data.voucher_no);
                    $('#ItemApproveDetails_production_id').text(data.production_plan);
                    $('#ItemApproveDetails_notes').text(data.notes);
                    $('#ItemApproveDetails_request_by').text(data.request_by_name);
                    $('#ItemApproveDetails_finished_goods').text(data.finished_goods);
                    
                    $('#sub_store_id').val(data.sub_store_id);
                    $('#production_id').val(data.production_id);

                    //$('#reject').hide();
                    $('#approve').hide();
                    $('#collect').hide();
                    
                    get_item_details_preview(id);

                },error: function() {

                }
            });   

        });


        $('#item_approveList').on('click', '.actionApprove', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'production/getItemApproveDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#ItemApproveDetails-modal').modal('show');
                    $('#ItemApproveDetailsModalLabel').text('<?php echo get_phrases(['consumption','request','approval']);?>');
                    
                    $('#id').val(data.id);

                    $('#ItemApproveDetails_sub_store_id').text(data.sub_store_name);
                    $('#ItemApproveDetails_date').text(data.date);
                    $('#ItemApproveDetails_voucher_no').text(data.voucher_no);
                    $('#ItemApproveDetails_production_id').text(data.production_plan);
                    $('#ItemApproveDetails_notes').text(data.notes);
                    $('#ItemApproveDetails_request_by').text(data.request_by_name);
                    $('#ItemApproveDetails_finished_goods').text(data.finished_goods);
                    
                    $('#sub_store_id').val(data.sub_store_id);
                    $('#production_id').val(data.production_id);
                    
                    //$('#reject').show();
                    //$('#reject').prop('disabled', false);
                    $('#approve').show();
                    $('#approve').prop('disabled', true);
                    $('#collect').hide();

                    get_item_details(id, data.production_id);

                },error: function() {

                }
            });   

        });

        $('#item_approveList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [5,6] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>{
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'ItemApprove_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                <?php } if($hasExportAccess){ ?>{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'ItemApprove_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'ItemApprove_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'ItemApprove_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'ItemApprove_List-<?php echo date('Y-m-d');?>',
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
               'url': _baseURL + 'production/getItemApprove',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.production_id = $('#filter_production_id').val();
                        d.sub_store_id = $('#filter_sub_store_id').val();
                        d.voucher_no = $('#filter_voucher_no').val();
                        d.date = $('#filter_date').val();
                    }
            },
          'columns': [
             { data: 'id' },
             { data: 'voucher_no' },
             { data: 'date' },
             { data: 'production_plan' },
             { data: 'sub_store_name' },
             { data: 'status' },
             { data: 'button'}
          ],
        });

        $('#item_approveList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        
        $('#picture').on('change', function () {
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
            imgPreview($(this), fileExtension);
        }); 

    });

    function change_avail_qty(id, item_counter){
        var avail_qty = parseFloat($('#receive_avail_qty_'+id).val());
        $('#avail_qty'+item_counter).val(avail_qty);
        
        var qty = parseFloat($('#qty'+item_counter).val());
        if(qty <= avail_qty){
            $('#aqty'+item_counter).val(qty);
        } else {
            $('#aqty'+item_counter).val(avail_qty);
        }
        
        check_quantity();
    }

    function item_info(item_id,sl){
        //return;

        $.ajax({
            url: _baseURL+"production/getItemDetailsById/"+item_id,
            type: 'POST',
            data: {'csrf_stream_name':csrf_val, item_id: item_id},
            dataType:"JSON",
            async: false,
            success: function (data) {
                $('#unit'+sl).html(data.item.unit_name);
            }
        });
    }

    function get_item_details_preview(request_id){

        if(request_id !='' ){
            var submit_url = _baseURL+'production/getItemRequestQuantityDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'request_id':request_id },
                async: false,
                success: function(data) {
                    $('#item_details_preview').html(data);
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    function get_item_details(request_id, production_id){

        if(request_id !='' ){
            var submit_url = _baseURL+'production/getItemApproveQuantityDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'request_id':request_id, 'production_id':production_id },
                async: false,
                success: function(data) {
                    $('#item_details_preview').html(data);
                    check_quantity();
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    function check_quantity_exceed(sl){
        var req_qty = ($('#qty'+sl).val())?parseFloat($('#qty'+sl).val()):0;
        var aqty = ($('#aqty'+sl).val())?parseFloat($('#aqty'+sl).val()):0;
        var avail_qty = ($('#avail_qty'+sl).val())?parseFloat($('#avail_qty'+sl).val()):0;
        //var min_qty = (avail_qty < req_qty)?avail_qty:req_qty;
        if(aqty > avail_qty && avail_qty <= req_qty ){
            toastr.warning('<?php echo get_notify('Quantity_should_be_less_than_or_equal_to_available_quantity'); ?>');
            $('#aqty'+sl).val(avail_qty);
        } else if(aqty > req_qty){
            toastr.warning('<?php echo get_notify('Quantity_should_be_equal_to_required_quantity'); ?>');
            $('#aqty'+sl).val(req_qty);
        }
        check_quantity();
    }

    function check_quantity(){
        var item_counter = parseInt($('#item_counter').val());
        
        var qty = 0;
        var aqty = 0;
        for(var i=1; i<=item_counter; i++){
            qty = ($('#qty'+i).val()=='')?0:parseFloat($('#qty'+i).val());
            aqty = ($('#aqty'+i).val()=='')?0:parseFloat($('#aqty'+i).val());

            if( aqty > qty || aqty < qty){
                //toastr.warning('<?php //echo get_notify('Quantity_should_be_equal_to_required_quantity'); ?>');
                $('#approve').prop('disabled', true);
                return false;
            }
        }
        $('#approve').prop('disabled', false);
        return true;
    }
</script>