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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'item']);?></button>
                        <?php } ?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">                    
                    <div class="col-sm-4">
                        <label for="filter_cat_id" class="font-weight-600"><?php echo get_phrases(['assets', 'name']) ?> </label>
                        <select name="filter_cat_id" id="filter_cat_id" class="custom-select form-control" >
                            <?php if(!empty($cat_id)){ ?>
                                <option></option>
                                <?php foreach ($cat_id as $key => $value) {?>
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
                <table id="itemsList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['code']);?></th>
                            <th><?php echo get_phrases(['name']);?></th>
                            <th><?php echo get_phrases(['unit']);?></th>
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
<div class="modal fade bd-example-modal-xl" id="items-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('wh_assets/add_items', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="price" value="0" />
                <input type="hidden" name="action" id="action" value="add" />
                <div class="row form-group">
                    <label for="nameE" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['item', 'name']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="nameE" placeholder="<?php echo get_phrases(['enter', 'item', 'name']);?>" class="form-control" id="nameE" required>                        
                    </div>
                    <label for="store_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['parent','name']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <select name="cat_id" id="cat_id" class="custom-select form-control" required>
                            <?php if(!empty($cat_id)){ ?>
                                <option></option>
                                <?php foreach ($cat_id as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>                        
                    </div>
                </div>
                <div class="row form-group">
                    <label for="item_code" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['item', 'code']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="item_code" placeholder="<?php echo get_phrases(['enter', 'item', 'code']);?>" class="form-control" id="item_code" readonly>                        
                    </div>
                    <label for="unit_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['base','unit']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <select name="unit_id" id="unit_id" class="custom-select form-control" required>
                            <option value=""><?php echo get_phrases(['select','unit']) ?></option>
                            <?php if(!empty($units)){ ?>
                                <?php foreach ($units as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>                        
                    </div>
                </div>
                <div class="row form-group">
                    <label for="description" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['description']) ?> </label>
                    <div class="col-sm-8">
                        <textarea name="description" id="description" cols="80" rows="10" class="form-control"></textarea>                      
                    </div>
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


<!-- item modal button -->
<div class="modal fade bd-example-modal-xl" id="itemDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['item', 'name']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_nameE" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['where','store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_store" ></div>                        
                    </div>
                    <!-- <label class="col-sm-2 text-right font-weight-600"><?php //echo get_phrases(['price']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_price" ></div>                        
                    </div> -->
                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['item', 'code']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_item_code"></div>
                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['where','use']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_where_use"></div>
                        
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['item', 'unit']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_unit_id"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['item', 'category']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_cat_id" ></div>                        
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['standard', 'wastage','%']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_wastage" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['expire']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_expire"></div>                        
                    </div>
                </div>

                <div class="row form-group">               
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['alert', 'quantity']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_alert_qty"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['country', 'origin']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_country_origin"></div>                        
                    </div>
                </div>

                <div class="row form-group">               
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['monthly', 'consumption']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_aprox_monthly_consumption"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['vat', 'applicable']) ?> : </label>
                    <div class="col-sm-1">
                        <div id="itemDetails_vat_applicable"></div>                        
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['description']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_description" ></div>                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
               
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">

    function reload_table(){
        $('#itemsList').DataTable().ajax.reload();
    }

    function show_info_container(val){
        if(val=='sellable' || val=='both'){
            $('.sell_price').prop('readonly', false);
            $('.sell_price').prop('required', true);
        } else { 
            $('.sell_price').val('');
            $('.sell_price').prop('readonly', true);
            $('.sell_price').prop('required', false);           
        }
        if(val=='consumable' || val=='both'){
            $('.consumed_by_container').show();
            $('#consumed_by').prop('required', true);
        } else {
            $('.consumed_by_container').hide();
            $('#consumed_by').prop('required', false);
        }
    }

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#items-modal').modal('hide');
        $('#itemsList').DataTable().ajax.reload(null, false);
    }
    function reset_table(){
        $('#filter_cat_id').val('').trigger('change');
        $('#itemsList').DataTable().ajax.reload();
    }
    $(document).ready(function() { 
       "use strict";
        
        $('body').on('click', '.addRow', function() {

            var html = ' <tr><td><select name="supplier_id[]" class="form-control custom-select" required><option value="">Select</option>'+<?php foreach($supplier as $value){?>
                                        '<option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>'+
                                       <?php }?>
                                    '</select></td><td><input type="text" name="price[]" class="form-control text-right onlyNumber" required></td><td><input type="text" name="sell_price[]" class="sell_price form-control text-right onlyNumber"></td><td><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button></td></tr>';

            $("#supplier_div").append(html); 
            $('#supplier_div select').select2({
                placeholder: '<?php echo get_phrases(['select','supplier']);?>'                
            });

            var item_type = $("#item_type").val(); 
            show_info_container(item_type);
        });


        $('body').on('click', '.removeRow', function() {
            var rowCount = $('#supplier_table >tbody >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
            }else{
                toastr.warning('<?php echo get_notify("There_only_one_row_you_can_not_delete."); ?>');
            } 
        });

        $('#itemsList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'wh_assets/getItemDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#itemDetails-modal').modal('show');
                    $('#itemDetailsModalLabel').text('<?php echo get_phrases(['raw', 'material', 'details']);?>');
                    $('#itemDetails_nameE').text(data.item.nameE);
                    $('#itemDetails_store').text(data.item.store_name);
                    $('#itemDetails_item_code').text(data.item.item_code);
                    $('#itemDetails_cat_id').text(data.item.cat_name);
                    $('#itemDetails_unit_id').text(data.item.unit_name);
                    $('#itemDetails_alert_qty').text(data.item.alert_qty);
                    $('#itemDetails_aprox_monthly_consumption').text(data.item.aprox_monthly_consumption);
                    $('#itemDetails_where_use').text(data.item.w_use);
                    $('#itemDetails_wastage').text(data.item.wastage);
                    $('#itemDetails_price').text(data.item.price);
                    $('#itemDetails_expire').text(data.item.expire);
                    $('#itemDetails_country_origin').text(data.item.country_name);
                    $('#itemDetails_description').text(data.item.description);
                    if(data.item.vat_applicable == '1'){
                        $('#itemDetails_vat_applicable').text('Yes');
                    } else {
                        $('#itemDetails_vat_applicable').text('No');
                    }

                    var html = '';
                    $.each(data.supplier, function(index, value){

                        html += '<tr><td>'+value.supplier_name+'</td><td align="right">'+value.price+'</td><td align="right">'+value.sell_price+'</td></tr>';
                        
                    });

                    $("#itemDetails_supplier_table").html(html); 

                },error: function() {

                }
            });   

        });

        $('#itemsList').DataTable({ 
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
                    title : 'Items_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                <?php } if($hasExportAccess){ ?>{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Items_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Items_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Items_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Items_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'wh_assets/getItems',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.cat_id = $('#filter_cat_id').val();
                    }
            },
          'columns': [
             { data: 'id' },
             { data: 'item_code' },
             { data: 'nameE' },
             { data: 'unit_name' },
             { data: 'button'}
          ],
        });

        $('#itemsList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        
        $('#carton_qty').on('keyup', function () {
            var box_qty = $('#box_qty').val();
            if(box_qty ==''){
                toastr.warning('<?php echo get_notify('Box_quantity_is_required')?>');
                $(this).val('');
            }   
        }); 

        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');

            $('#nameE').val('');
            $('#expire').val('').trigger('change');
            $('#country_origin').val('BD').trigger('change');
            $('#cat_id').val('').trigger('change');
            $('#unit_id').val('30').trigger('change');
            $('#vat_applicable').prop('checked', false);
            $('#has_expiry').prop('checked', false);
            $('#store_id').val('').trigger('change');
            $('#company_code').val('');         
            $('#wastage').val('');         
            $('#price').val('');             
            $('#alert_qty').val('');     
            $('#item_type').val('').trigger('change');
            $('#consumed_by').val('').trigger('change');
            $('.consumed_by_container').hide();
            $('#consumed_by').prop('required', false);
            $('#sell_price_container').hide();

            var html = '<tr><td><select name="supplier_id[]" class="form-control custom-select" required><option value="">Select</option>'+<?php foreach($supplier as $value){?>
                                        '<option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>'+
                                       <?php }?>
                                    '</select></td><td><input type="text" name="price[]" class="form-control text-right onlyNumber" required></td><td><input type="text" name="sell_price[]" class="sell_price form-control text-right onlyNumber"></td><td><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td></tr>';

            $("#supplier_div").html(html); 
            $('#supplier_div select').select2({
                placeholder: '<?php echo get_phrases(['select','supplier']);?>'                
            });

            getMAXID('wh_assets','id','item_code','ITEM-');
            
            $('#itemsModalLabel').text('<?php echo get_phrases(['add', 'raw', 'material']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('#items-modal').modal('show');

        });

        $('#itemsList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'wh_assets/getItemsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#items-modal').modal('show');
                    $('#id').val(data.item.id);
                    $('#action').val('update');
                    $('#itemsModalLabel').text('<?php echo get_phrases(['update', 'raw', 'material']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');

                    $('#store_id').val(data.item.store_id).trigger('change');
                    $('#nameE').val(data.item.nameE);
                    $('#item_code').val(data.item.item_code);
                    $('#alert_qty').val(data.item.alert_qty);
                    $('#aprox_monthly_consumption').val(data.item.aprox_monthly_consumption);
                    $('#cat_id').val(data.item.cat_id).trigger('change');
                    $('#unit_id').val(data.item.unit_id).trigger('change');
                    $('#wastage').val(data.item.wastage);
                    $('#price').val(data.item.price);
                    $('#description').val(data.item.description);
                    $('#where_use').val(data.item.where_use).trigger('change');
                    $('#expire').val(data.item.expire).trigger('change');
                    $('#country_origin').val(data.item.country_origin).trigger('change');
                    if(data.item.vat_applicable == '1'){
                        $('#vat_applicable').prop('checked', true);  
                    } else {
                        $('#vat_applicable').prop('checked', false);  
                    }   
                    if(data.item.has_expiry == '1'){
                        $('#has_expiry').prop('checked', true);  
                    } else {
                        $('#has_expiry').prop('checked', false);  
                    }   
                    //$('#supplier_id').val(data.item.supplier_id).trigger('change');
                    if(data.item.item_type == 'sellable'){
                        $('.consumed_by_container').hide();
                        $('#consumed_by').prop('required', false);
                        //$('#sell_price_container').show();   
                        //$('#sell_price').val(data.item.sell_price);         
                    } else {
                        //$('#sell_price_container').hide(); 
                        $('.consumed_by_container').show();  
                        $('#consumed_by').prop('required', true);
                        $('#consumed_by').val(data.item.consumed_by).trigger('change');
                    }

                    var i=1;
                    var html = '';
                    var button = '';
                    var button_type = '';
                    var button_color = '';
                    $.each(data.supplier, function(index, value){

                        if(i==1){
                            button = 'plus';
                            button_type = 'addRow';
                            button_color = 'success';
                        } else {
                            button = 'minus';
                            button_type = 'removeRow';
                            button_color = 'danger';
                        }

                        html += '<tr><td><select name="supplier_id[]" id="supplier_id'+index+'" class="form-control custom-select" required><option value="">Select</option>'+<?php foreach($supplier as $value){?>
                                            '<option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>'+
                                           <?php }?>
                                        '</select></td><td><input type="text" name="price[]" class="form-control text-right onlyNumber" value="'+value.price+'" required></td><td><input type="text" name="sell_price[]" value="'+value.sell_price+'" class="sell_price form-control text-right onlyNumber"></td><td><button type="button" class="btn btn-'+button_color+' '+button_type+'" ><i class="fa fa-'+button+'"></i></button></td></tr>';
                        i++;
                    });

                    if(i==1){
                        html = '<tr><td><select name="supplier_id[]" class="form-control custom-select" required><option value="">Select</option>'+<?php foreach($supplier as $value){?>
                                            '<option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>'+
                                           <?php }?>
                                        '</select></td><td><input type="text" name="price[]" class="form-control text-right onlyNumber" required></td><td><input type="text" name="sell_price[]" class="sell_price form-control text-right onlyNumber"></td><td><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td></tr>';
                    }

                    $("#supplier_div").html(html); 

                    $('#supplier_div select').select2({
                        placeholder: '<?php echo get_phrases(['select','supplier']);?>'                
                    });

                    $.each(data.supplier, function(index, value){
                        $('#supplier_id'+index).val(value.supplier_id).trigger('change');
                    });
                },error: function() {

                }
            });   

        });
        // delete items
        $('#itemsList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"wh_assets/deleteItems/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, '<?php echo get_phrases(["record"])?>');
                            $('#itemsList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, '<?php echo get_phrases(["record"])?>');
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });
</script>