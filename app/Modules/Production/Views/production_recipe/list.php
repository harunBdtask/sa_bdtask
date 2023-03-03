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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['new', 'recipe']);?></button>  
                       <?php } ?> 
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">   
                    <div class="col-sm-4">
                        <label for="filter_item_id" class="font-weight-600"><?php echo get_phrases(['item']) ?> </label>
                        <select name="filter_item_id" id="filter_item_id" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($item_list)){ ?>
                                <?php foreach ($item_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE.' ( '.$value->company_code.' )';?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="filter_voucher_no" class="font-weight-600"><?php echo get_phrases(['recipe','no']) ?> </label>
                        <select name="filter_voucher_no" id="filter_voucher_no" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($recipe_list)){ ?>
                                <?php foreach ($recipe_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->voucher_no; ?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="filter_date" class="font-weight-600"><?php echo get_phrases(['date']) ?> </label>
                        <input type="date" name="filter_date" id="filter_date" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <br>
                        <button type="button" class="btn btn-success mt-2" onclick="reload_table()"><?php echo get_phrases(['filter']);?></button>
                        <button type="button" class="btn btn-warning mt-2" onclick="reset_table()"><?php echo get_phrases(['reset']);?></button>
                    </div>
                </div>
                <table id="item_productionList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['recipe','no']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
                            <th><?php echo get_phrases(['item']);?></th>
                            <th><?php echo get_phrases(['status']);?></th>
                            <th><?php echo get_phrases(['approval','status']);?></th>
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
<div class="modal fade bd-example-modal-xl" id="item_production-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="item_productionModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('production/recipe/add_item_production', 'class="po_ajaxForm needs-validation" id="po_ajaxForm" novalidate="" data="po_showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="po_id" />
                <input type="hidden" name="action" id="po_action" value="add" />
                    <div class="row form-group">
                        <label for="po_item_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['finished','goods'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                             <select name="item_id" id="po_item_id" class="custom-select" required>
                                <?php foreach($item_list as $value){?>
                                <option value="<?php echo $value->id;?>"><?php echo $value->nameE.' ( '.$value->company_code.' ) ';?></option>
                               <?php }?>
                            </select>
                        </div>
                        <label for="po_voucher_no" class="col-sm-1 col-form-label font-weight-600"><?php echo get_phrases(['Code'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-2">
                            <input name="voucher_no"  type="text" class="form-control" id="po_voucher_no" placeholder="<?php echo get_phrases(['voucher', 'no'])?>" autocomplete="off" readonly >
                        </div>
                        
                        <label for="po_date" class="col-sm-1 col-form-label font-weight-600"><?php echo get_phrases(['date'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-2">
                            <input name="date"  type="text" class="form-control datepicker1" id="po_date" placeholder="<?php echo get_phrases(['date'])?>"  value="<?php echo date('d/m/Y')?>" autocomplete="off" readonly>
                        </div>
                    </div>

                    <input type="hidden" name="item_counter" id="po_item_counter" value="1">
                    <div class="row mt-2">
                         <table class="table table-sm table-stripped w-100" id="production_table">
                            <thead>
                                <tr>
                                    <th width="60%" class="text-center"><?php echo get_phrases(['raw', 'material'])?><i class="text-danger">*</i></th>
                                    <th width="30%" class="text-center"><?php echo get_phrases(['percentage'])?></th>
                                    <th width="10%"><?php echo get_phrases(['action'])?></th>

                                </tr>
                            </thead>
                            <tbody id="po_item_div">
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td align="right" class="font-weight-bold"><?php echo get_phrases(['total']); ?></td>
                                    <td align="right"><span id="total_show"></span>%</td>
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
<div class="modal fade bd-example-modal-xl" id="recipeDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="recipeDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('production/recipe/item_production_approve', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body" id="printContent">
                <input type="hidden" name="id" id="id" value="" required>
                <input type="hidden" name="action" id="action" value="" required>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="<?php echo base_url() . $setting->logo; ?>" class="img-responsive" alt=""><strong><?php echo $setting->title; ?></strong><br>
                        <?php echo $setting->address; ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['finished','goods']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="recipeDetails_item_id"></div>                        
                    </div>
                    <label class="col-sm-1 text-right font-weight-600"><?php echo get_phrases(['code']) ?> : </label>
                    <div class="col-sm-2">
                        <div id="recipeDetails_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-1 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-2">
                        <div id="recipeDetails_date"></div>                        
                    </div>
                </div>

                <div id="item_details_preview"></div>


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
        $('#item_productionList').DataTable().ajax.reload();
    }

    function reset_table(){
        $('#filter_item_id').val('').trigger('change');
        $('#filter_voucher_no').val('').trigger('change');
        $('#filter_date').val('');
        $('#item_productionList').DataTable().ajax.reload();
    }

    var showCallBackData = function () {
        $('#id').val('');        
        //$('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#recipeDetails-modal').modal('hide');
        $('#item_productionList').DataTable().ajax.reload(null, false);
    }
    var po_showCallBackData = function () {
        $('#po_id').val('');        
        $('#po_action').val('add');        
        $('.po_ajaxForm')[0].reset();        
        $('#item_production-modal').modal('hide');
        $('#item_productionList').DataTable().ajax.reload(null, false);
    }

    function reload_max_id(){
        getMAXID('wh_recipe','id','po_voucher_no','PO-');
    }

    function first_item_row(){
        var item_list = $('#item_list').html();

        var item_counter = 1;

        var html = '<tr><td><select name="material_id[]" id="material_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td><td><div class="input-group"><input type="text" name="qty[]" class="form-control text-right onlyNumber po_qty" id="po_qty'+item_counter+'" autocomplete="off" required><div class="input-group-append"><div class="input-group-text">%</div></div></div></td><td><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button><input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0"></td></tr>';      
        $("#po_item_div").html(html); 
        $("#po_item_counter").val(item_counter);
        total_cal();
        $('select').select2({
            placeholder: '<?php echo get_phrases(['select','item']);?>'                
        });
    }

    function total_cal(){             
        var item_counter = parseInt($("#po_item_counter").val()); 
        var total = 0;
        for(var i=1; i<=item_counter; i++){
            total += ($("#po_qty"+i).val() =='')?0:parseFloat($("#po_qty"+i).val());
        }
        $("#total_show").text(total);
    }

   $(document).on('keyup', '.po_qty', function() {              
       total_cal();
    });

    $(document).ready(function() { 
       "use strict";

       $('#item_list').hide();

       get_item_list(0);

       $('.po_modal_action').on('click', function(e) {
            if( !check_total() ){
                e.preventDefault();
            }
        });


       $('#reload_max_id').on('click', function() {
            reload_max_id();
        });

        $('body').on('click', '.addRow', function() {
            var item_counter = parseInt($("#po_item_counter").val()); 
            item_counter += 1;
            $("#po_item_counter").val(item_counter);
            var item_list = $('#item_list').html();

            var html = '<tr><td><select name="material_id[]" id="material_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td><td><div class="input-group"><input type="text" name="qty[]" class="form-control text-right onlyNumber po_qty" id="po_qty'+item_counter+'" autocomplete="off" required><div class="input-group-append"><div class="input-group-text">%</div></div></div></td><td><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button><input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0"></td></tr>';

            $("#po_item_div").append(html); 
            total_cal();
            $('select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });
        });


        $('body').on('click', '.removeRow', function() {
            var rowCount = $('#production_table >tbody >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
                po_calculation_total();
            }else{
                toastr.warning('<?php echo get_notify("There_only_one_row_you_can_not_delete."); ?>');
            } 
        });

        $('.addShowModal').on('click', function(){
            $('.po_ajaxForm').removeClass('was-validated');
            $('#po_id').val('');
            $('#po_action').val('add');

            $('#po_item_id').val('').trigger('change');

            getMAXID('wh_recipe','id','po_voucher_no','RCP-');
            var item_list = $('#item_list').html();

            var item_counter = 1;

            var html = '<tr><td><select name="material_id[]" id="material_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td><td><div class="input-group"><input type="text" name="qty[]" class="form-control text-right onlyNumber po_qty" id="po_qty'+item_counter+'" autocomplete="off" required><div class="input-group-append"><div class="input-group-text">%</div></div></div></td><td><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button><input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0"></td></tr>';      
            $("#po_item_div").html(html); 

            $("#po_item_counter").val(item_counter);
            total_cal();
            $('select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });

            $('#item_productionModalLabel').text('<?php echo get_phrases(['production', 'recipe']);?>');
            $('.po_modal_action').text('<?php echo get_phrases(['add']);?>');
            $('.po_modal_action').prop('disabled', false);
            $('#item_production-modal').modal('show');
        });


        $('#item_productionList').on('click', '.actionPreview', function(e){
             e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'production/recipe/getProductionRecipeDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#recipeDetails-modal').modal('show');
                    $('#recipeDetailsModalLabel').text('<?php echo get_phrases(['production','recipe','details']);?>');

                    $('#recipeDetails_item_id').text(data.item_name);

                    $('#recipeDetails_voucher_no').text(data.voucher_no);
                    $('#recipeDetails_date').text(data.date);                      
                    $('#recipeDetails_sub_total').text(data.sub_total);
                    $('#recipeDetails_grand_total').text(data.grand_total);
                    $('#recipeDetails_vat').text(data.vat);

                    $('#approve').hide();
                    $('#id').val('');

                    get_item_details(id);

                },error: function() {

                }
            });  

        });

        var title = $("#testtitle").html();
        $('#item_productionList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [4,5,6] },
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
                    title : 'ProductionRecipe_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'ProductionRecipe_List-<?php echo date('Y-m-d');?>',
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
               'url': _baseURL + 'production/recipe/getProductionRecipe',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.item_id = $('#filter_item_id').val();
                        d.voucher_no = $('#filter_voucher_no').val();
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
             { data: 'item_name' },
             { data: 'status'},
             { data: 'approval_status'},
             { data: 'button'},
          ],
        });

        $('#item_productionList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        
        $('#item_productionList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'production/recipe/getProductionRecipeDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    //console.log(data);
                    if(data.received == 1){
                        toastr.warning('<?php echo get_phrases(['already','received']); ?>');
                    } else if(data != null){
                        $('#item_production-modal').modal('show');
                        $('#item_productionModalLabel').text('<?php echo get_phrases(['production','recipe','update']);?>');

                        $('#po_item_id').val(data.item_id);
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

        $('#item_productionList').on('click', '.actionApprove', function(e){
             e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'production/recipe/getProductionRecipeDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#recipeDetails-modal').modal('show');
                    $('#recipeDetailsModalLabel').text('<?php echo get_phrases(['production','recipe','pending','approval']);?>');

                    $('#recipeDetails_item_id').text(data.item_name);

                    $('#recipeDetails_voucher_no').text(data.voucher_no);
                    $('#recipeDetails_date').text(data.date);                      
                    $('#recipeDetails_vat').text(data.vat);                      
                    $('#recipeDetails_sub_total').text(data.sub_total);
                    $('#recipeDetails_grand_total').text(data.grand_total);

                    $('#id').val(id);
                    $('#action').val('approve');
                    $('#approve').show();
                    $('#approve').text('Approve');
                    $('#approve').prop('disabled', false);

                    get_item_details(id);

                },error: function() {

                }
            });  

        });

        $('#item_productionList').on('click', '.actionActive', function(e){
             e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'production/recipe/getProductionRecipeDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#recipeDetails-modal').modal('show');
                    $('#recipeDetailsModalLabel').text('<?php echo get_phrases(['production','recipe','pending','approval']);?>');

                    $('#recipeDetails_item_id').text(data.item_name);

                    $('#recipeDetails_voucher_no').text(data.voucher_no);
                    $('#recipeDetails_date').text(data.date);                      
                    $('#recipeDetails_vat').text(data.vat);                      
                    $('#recipeDetails_sub_total').text(data.sub_total);
                    $('#recipeDetails_grand_total').text(data.grand_total);

                    $('#id').val(id);
                    $('#action').val('active');
                    $('#approve').show();
                    $('#approve').text('Active');
                    $('#approve').prop('disabled', false);

                    get_item_details(id);

                },error: function() {

                }
            });  

        });
        // delete item_production
        $('#item_productionList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"production/recipe/deleteProductionRecipe/"+id;
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
                            $('#item_productionList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, '<?php echo get_phrases(["record"])?>');
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });


    function get_item_details_edit(recipe_id){

        if(recipe_id !='' ){
            var submit_url = _baseURL+'production/recipe/getProductionRecipeItemDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'json',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'recipe_id':recipe_id},
                success: function(data) {
                    //console.log(data);return;
                    if(data != null){
                        var item_counter = 1;
                        $("#po_item_div").html(''); 
                        $.each(data, function(index, value){
                            add_item_row(item_counter);

                            $("#material_id"+item_counter).val(value.material_id).trigger('change');
                            
                            $("#wastage"+item_counter).val(value.wastage);
                            $("#po_qty"+item_counter).val(value.qty);   

                            $("#unit_name"+item_counter).val(value.unit_name);   

                            $("#po_item_counter").val(item_counter);
                            item_counter += 1;
                        });
                        //$('#po_item_div').html(data);
                    } 
                }
            });
        } else {
            $('#po_item_div').html('');
        }
    }

    function add_item_row(item_counter){
        //var item_counter = parseInt($("#po_item_counter").val()); 
        //item_counter += 1;
        //$("#po_item_counter").val(item_counter);
        var item_list = $('#item_list').html();

        var button_color='success';
        var button_type='plus';
        var button_class='addRow';
        if( item_counter > 1){
            var button_color='danger';
            var button_type='minus';
            var button_class='removeRow';
        }
        var html = '<tr><td><select name="material_id[]" id="material_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td><td><div class="input-group"><input type="text" name="qty[]" class="form-control text-right onlyNumber po_qty" id="po_qty'+item_counter+'" autocomplete="off" required><div class="input-group-append"><div class="input-group-text">%</div></div></div></td><td><button type="button" class="btn btn-'+button_color+' '+button_class+'" ><i class="fa fa-'+button_type+'"></i></button><input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0"></td></tr>';

        $("#po_item_div").append(html); 
        total_cal();
        $('select').select2({
            placeholder: '<?php echo get_phrases(['select','item']);?>'                
        });
    }

    function get_item_details(recipe_id){

        if(recipe_id !='' ){
            var submit_url = _baseURL+'production/recipe/getProductionRecipePricingDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'recipe_id':recipe_id, 'quantity':0 },
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
            url: _baseURL+"production/recipe/get_item_list",
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

    function po_item_info(material_id,sl){
        if(material_id >0 ){
            var item_counter = parseInt($("#po_item_counter").val()); 
            var material_id_each = 0;
            for(var i=1; i<=item_counter; i++){
                material_id_each = $("#material_id"+i).val();
                if(material_id_each >0 && material_id == material_id_each &&  i!=sl){
                    toastr.warning('<?php echo get_notify('Same_item_can_not_be_added'); ?>');
                    $("#material_id"+sl).val('').trigger('change');
                    return false;
                }
            }
        }
        var action = $('#po_action').val();
        var existing = $('#existing'+sl).val();

        $.ajax({
            url: _baseURL+"production/recipe/getSupplierItemDetailsById",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val,material_id: material_id},
            dataType:"JSON",
            async: false,
            success: function (data) {
                //console.log(data);
                if(data != null && ( action=='add' || (action=='update' && existing=='0'))){
                    if(data.unit_name !=null){
                        $('#unit_name'+sl).text(data.unit_name);

                    } else {
                        $('#unit_name'+sl).text('');                  
                    }
                    $('#po_qty'+sl).val('');
                    $("#po_qty"+sl).prop('readonly', false);

                    //bag_cal(sl);
                }
            }
        });
    }

    function po_carton_cal(sl){
        
        var carton = ($("#po_carton"+sl).val()=='')?0:parseInt($("#po_carton"+sl).val());
        var carton_qty = ($("#carton_qty"+sl).val()=='')?0:parseInt($("#carton_qty"+sl).val());
        //var po_box = ($("#po_box"+sl).val()=='')?0:$("#po_box"+sl).val();
        var box_qty = ($("#box_qty"+sl).val()=='')?0:parseFloat($("#box_qty"+sl).val());

        if( carton > 0 && carton_qty >0 && box_qty >0 ){
            var total_qty = carton * carton_qty * box_qty;
            $("#po_qty"+sl).val(total_qty);
            $("#po_qty"+sl).prop('readonly', true);
            $("#po_box"+sl).val(carton_qty);
            $("#po_box"+sl).prop('readonly', true);

        } else if( carton_qty >0 && box_qty >0 ){
            var total_qty = carton_qty * box_qty;
            $("#po_qty"+sl).val(total_qty);
            $("#po_qty"+sl).prop('readonly', true);
            $("#po_box"+sl).val(carton_qty);
            $("#po_box"+sl).prop('readonly', false);

        } else {
            $("#po_qty"+sl).val('');
            $("#po_qty"+sl).prop('readonly', false);
            $("#po_box"+sl).val('');
            $("#po_box"+sl).prop('readonly', false);

        }
    }

    function bag_cal(sl){
        
        var bag_size = ($("#bag_size"+sl).val()=='')?0:parseFloat($("#bag_size"+sl).val());
        var input_kg = ($("#input_kg"+sl).val()=='')?0:parseFloat($("#input_kg"+sl).val());
        var old_feed = ($("#old_feed"+sl).val()=='')?0:parseFloat($("#old_feed"+sl).val());
        var wastage = ($("#wastage"+sl).val()=='')?0:parseFloat($("#wastage"+sl).val());
        var act_bags = ($("#act_bags"+sl).val()=='')?0:parseFloat($("#act_bags"+sl).val());
        var po_qty = ($("#po_qty"+sl).val()=='')?0:parseFloat($("#po_qty"+sl).val());

        if( bag_size == 0 && (input_kg >0 || old_feed >0 || wastage >0 || act_bags >0 || po_qty >0)){
            toastr.warning('<?php echo get_notify('Enter_bag_size'); ?>');
            $("#act_bags"+sl).val('');
            $("#po_qty"+sl).val('');
            $("#po_qty"+sl).prop('readonly', false);
            $("#loss_kg_show"+sl).text('');
            $("#loss_kg"+sl).val('');
            $("#prod_percent_show"+sl).text('');
            $("#prod_percent"+sl).val('');
            $("#loss_percent_show"+sl).text('');
            $("#loss_percent"+sl).val('');
            return false;
        }
        if( input_kg == 0  && (old_feed >0 || wastage >0 || act_bags >0 || po_qty >0)){
            toastr.warning('<?php echo get_notify('Enter_input_kg'); ?>');
            $("#act_bags"+sl).val('');
            $("#po_qty"+sl).val('');
            $("#po_qty"+sl).prop('readonly', false);
            $("#loss_kg_show"+sl).text('');
            $("#loss_kg"+sl).val('');
            $("#prod_percent_show"+sl).text('');
            $("#prod_percent"+sl).val('');
            $("#loss_percent_show"+sl).text('');
            $("#loss_percent"+sl).val('');
            return false;
        }
        if( act_bags == 0 && po_qty >0){
            toastr.warning('<?php echo get_notify('Enter_bags'); ?>');
            $("#po_qty"+sl).val('');
            $("#po_qty"+sl).prop('readonly', false);
            $("#loss_kg_show"+sl).text('');
            $("#loss_kg"+sl).val('');
            $("#prod_percent_show"+sl).text('');
            $("#prod_percent"+sl).val('');
            $("#loss_percent_show"+sl).text('');
            $("#loss_percent"+sl).val('');
            return false;
        }
        if( act_bags > 0 ){
            var total_qty = act_bags * bag_size;
            $("#po_qty"+sl).val(total_qty.toFixed(2));
            $("#po_qty"+sl).prop('readonly', true);

            var in_qty = input_kg+old_feed;
            var out_qty = wastage+total_qty;

            var prod_percent = (out_qty/in_qty) * 100;

            var loss_kg = 0;
            var loss_percent = 0;
            if(in_qty > out_qty){
                loss_kg = in_qty - out_qty;
                loss_percent = (loss_kg/in_qty) * 100;
            }

            $("#loss_kg_show"+sl).text(loss_kg.toFixed(2));
            $("#loss_kg"+sl).val(loss_kg.toFixed(2));

            $("#prod_percent_show"+sl).text(prod_percent.toFixed(2));
            $("#prod_percent"+sl).val(prod_percent.toFixed(2));

            $("#loss_percent_show"+sl).text(loss_percent.toFixed(2));
            $("#loss_percent"+sl).val(loss_percent.toFixed(2));

        }

    }

    function check_total(){
        var item_counter = $("#po_item_counter").val();
        var total = 0;
        var qty = 0;
        for(var i=1; i<=item_counter; i++){
            qty = ($('#po_qty'+i).val() =='')?0:parseFloat($('#po_qty'+i).val());
            total += qty;
        }
        if(total < 100 || total > 100){
            toastr.warning('<?php echo get_notify("Total_percentage_should_be_100"); ?>');
            return false;
        }        
        return true;
    }

</script>