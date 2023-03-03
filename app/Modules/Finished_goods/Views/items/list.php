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
                    <label for="filter_cat_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['category']) ?> </label>
                    <div class="col-sm-4">
                        <select name="filter_cat_id" id="filter_cat_id" class="custom-select form-control" >
                            <option value=""><?php echo get_phrases(['select','category']) ?></option>
                            <?php if(!empty($categories)){ ?>
                                <?php foreach ($categories as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>                        
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-success" onclick="reload_table()"><?php echo get_phrases(['filter']);?></button>
                        <button type="button" class="btn btn-warning" onclick="reset_table()"><?php echo get_phrases(['reset']);?></button>
                    </div>
                </div>
                <table id="itemsList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['item', 'name']);?></th>
                            <th><?php echo get_phrases(['item', 'code']);?></th>
                            <th><?php echo get_phrases(['category']);?></th>
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
           <?php echo form_open_multipart('finished_goods/add_items', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                <div class="row form-group">
                    <label for="nameE" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['item', 'name', 'english']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="nameE" placeholder="<?php echo get_phrases(['enter', 'item', 'name']);?>" class="form-control" id="nameE" required>                        
                    </div>
                    <label for="company_code" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['item', 'code']) ?> </label>
                    <div class="col-sm-4">
                        <input type="text" name="company_code" placeholder="<?php echo get_phrases(['enter', 'item', 'code']);?>" class="form-control" id="company_code" required>                        
                    </div>
                </div>
                <div class="row form-group">
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
                    <label for="cat_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['category']) ?> </label>
                    <div class="col-sm-4">
                        <select name="cat_id" id="cat_id" class="custom-select form-control" >
                            <option value=""><?php echo get_phrases(['select','category']) ?></option>
                            <?php if(!empty($categories)){ ?>
                                <?php foreach ($categories as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>                        
                    </div>
                </div>
                <div class="row form-group">
                    <label for="type_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['type']) ?> </label>
                    <div class="col-sm-4">
                        <select name="type_id" id="type_id" class="custom-select form-control" >
                            <option value=""><?php echo get_phrases(['select','type']) ?></option>
                            <?php if(!empty($goods_types)){ ?>
                                <?php foreach ($goods_types as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>                        
                    </div>
                    <label for="price" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['unit', 'price']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="price" placeholder="<?php echo get_phrases(['enter', 'item', 'price']);?>" class="form-control onlyNumber" id="price" required>                        
                    </div>
                </div>
                <div class="row form-group">
                    <label for="alert_qty" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['minimum','alert','quantity']) ?></label>
                    <div class="col-sm-4">
                        <input type="text" name="alert_qty" placeholder="<?php echo get_phrases(['enter','quantity']);?>" class="form-control onlyNumber" id="alert_qty">                        
                    </div>
                    <label for="minor_alert_qty" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['minor','alert','quantity']) ?></label>
                    <div class="col-sm-4">
                        <input type="text" name="minor_alert_qty" placeholder="<?php echo get_phrases(['enter','quantity']);?>" class="form-control onlyNumber" id="minor_alert_qty">                        
                    </div>
                    
                </div>
                <div class="row form-group">           
                    <label for="bag_weight" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['bag', 'weight']) ?>(KG) <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="bag_weight" placeholder="<?php echo get_phrases(['enter', 'bag', 'weight']);?>" class="form-control onlyNumber" id="bag_weight" required>                        
                    </div>             
                    <label for="com_rate" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['commission', 'rate']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="com_rate" placeholder="<?php echo get_phrases(['enter', 'commission', 'rate']);?>" class="form-control onlyNumber" id="com_rate" required>                        
                    </div>       
                </div>
                <div class="row form-group">   
                    <label for="vat_applicable" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['vat', 'applicable']) ?> </label>
                    <div class="col-sm-1 mt-2">
                        <input type="checkbox" name="vat_applicable" id="vat_applicable" value="1">                        
                    </div>
                    <label for="vat_applicable" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['has', 'expiry']) ?> </label>
                    <div class="col-sm-1 mt-2">
                        <input type="checkbox" name="has_expiry" id="has_expiry" value="1">                        
                    </div>
                    <label for="description" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['description']) ?> </label>
                    <div class="col-sm-4">
                        <textarea name="description" id="description" rows="3" class="form-control"></textarea>                      
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
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['item', 'name', 'english']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_nameE" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['item', 'code']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_company_code"></div>                        
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
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['unit', 'price']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_price"></div>                        
                    </div>          
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['alert', 'quantity']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_alert_qty"></div>                        
                    </div>
                </div>

                <div class="row form-group">  
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['bag','weight']) ?>(KG): </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_bag_weight"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['commission','rate']) ?>: </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_com_rate"></div>                        
                    </div>
                </div> 
                <div class="row form-group"> 
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['vat', 'applicable']) ?> : </label>
                    <div class="col-sm-1">
                        <div id="itemDetails_vat_applicable"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['has', 'expiry']) ?> : </label>
                    <div class="col-sm-1">
                        <div id="itemDetails_has_expiry"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['description']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_description"></div>                        
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

    function reset_table(){
        $('#filter_cat_id').val('').trigger('change');
        $('#itemsList').DataTable().ajax.reload();
    }

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#items-modal').modal('hide');
        $('#itemsList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";
        

        $('#itemsList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'finished_goods/getItemDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#itemDetails-modal').modal('show');
                    $('#itemDetailsModalLabel').text('<?php echo get_phrases(['item','details']);?>');

                    $('#itemDetails_nameE').text(data.item.nameE);
                    //$('#itemDetails_item_code').text(data.item.item_code);
                    $('#itemDetails_company_code').text(data.item.company_code);
                    $('#itemDetails_cat_id').text(data.item.cat_name);
                    $('#itemDetails_unit_id').text(data.item.unit_name);
                    $('#itemDetails_alert_qty').text(data.item.alert_qty);
                    $('#itemDetails_price').text(data.item.price);
                    $('#itemDetails_bag_weight').text(data.item.bag_weight);
                    $('#itemDetails_com_rate').text(data.item.com_rate);
                    $('#itemDetails_description').text(data.item.description);

                    if(data.item.vat_applicable == '1'){
                        $('#itemDetails_vat_applicable').text('Yes');
                    } else {
                        $('#itemDetails_vat_applicable').text('No');
                    }
                    if(data.item.has_expiry == '1'){
                        $('#itemDetails_has_expiry').text('Yes');
                    } else {
                        $('#itemDetails_has_expiry').text('No');
                    }


                },error: function() {

                }
            });   

        });

        $('#itemsList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [5] },
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
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Items_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Items_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Items_List-<?php echo date('Y-m-d');?>',
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
               'url': _baseURL + 'finished_goods/getItems',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.cat_id = $('#filter_cat_id').val();
                    }
            },
          'columns': [
             { data: 'id' },
             { data: 'nameE' },
             { data: 'company_code' },
             { data: 'cat_name' },
             { data: 'unit_name' },
             { data: 'button'}
          ],
        });

        $('#itemsList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        

        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');

            $('#nameE').val('');
            $('#cat_id').val('').trigger('change');
            $('#type_id').val('').trigger('change');
            $('#unit_id').val('').trigger('change');
            $('#vat_applicable').prop('checked', false);
            $('#has_expiry').prop('checked', false);
            //$('#company_code').val('');           
            $('#alert_qty').val('');    
            $('#minor_alert_qty').val('');    
            $('#price').val(''); 
            $('#bag_weight').val(''); 
            $('#com_rate').val(''); 
            $('#description').val(''); 

            getMAXID('wh_items','id','company_code','ITEM-');
            
            $('#itemsModalLabel').text('<?php echo get_phrases(['add', 'item']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('#items-modal').modal('show');

        });

        $('#itemsList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'finished_goods/getItemsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#items-modal').modal('show');
                    $('#id').val(data.item.id);
                    $('#action').val('update');
                    $('#itemsModalLabel').text('<?php echo get_phrases(['update', 'item']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');

                    $('#nameE').val(data.item.nameE);
                    $('#company_code').val(data.item.company_code);
                    $('#alert_qty').val(data.item.alert_qty);
                    $('#minor_alert_qty').val(data.item.minor_alert_qty);
                    $('#cat_id').val(data.item.cat_id).trigger('change');
                    $('#type_id').val(data.item.type_id).trigger('change'); 
                    $('#unit_id').val(data.item.unit_id).trigger('change'); 
                    $('#price').val(data.item.price);
                    $('#bag_weight').val(data.item.bag_weight); 
                    $('#com_rate').val(data.item.com_rate); 
                    $('#description').val(data.item.description); 

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
                    

                },error: function() {

                }
            });   

        });
        // delete items
        $('#itemsList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"finished_goods/deleteItems/"+id;
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