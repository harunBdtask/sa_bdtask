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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['new', 'plan']);?></button>  
                       <?php } ?> 
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">     
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
                    <div class="col-sm-3">
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
                    <div class="col-sm-2">
                        <label for="filter_voucher_no" class="font-weight-600"><?php echo get_phrases(['plan','no']) ?> </label>
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
                <table id="item_productionList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['plan','no']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
                            <th><?php echo get_phrases(['plant']);?></th>
                            <th><?php echo get_phrases(['item','name']);?></th>
                            <th><?php echo get_phrases(['item','code']);?></th>
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
<div class="modal fade bd-example-modal-xl" id="item_production-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl " >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="item_productionModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('production/add_item_production', 'class="po_ajaxForm needs-validation" id="po_ajaxForm" novalidate="" data="po_showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="po_id" />
                <input type="hidden" name="store_id" id="po_store_id" value="1" /> 
                <input type="hidden" name="action" id="po_action" value="add" />
                    <div class="row">
                        <label for="po_machine_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['plant'])?><i class="text-danger">*</i></label>
                        <div class="col-sm-3">
                            <select name="machine_id" id="po_machine_id" class="custom-select" required>
                                <?php foreach($machine_list as $value){?>
                                <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                               <?php }?>
                            </select>
                        </div>
                        <label for="po_voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['plan','no'])?> <i class="text-danger">*</i></label>
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
                                    <th width="30%" class="text-center"><?php echo get_phrases(['finished', 'goods'])?><i class="text-danger">*</i></th>
                                    <th width="15%" class="text-center"><?php echo get_phrases(['recipe'])?></th>
                                    <th width="15%" class="text-center"><?php echo get_phrases(['bag','size'])?> KG</th>
                                    <th width="15%" class="text-center"><?php echo get_phrases(['bags'])?></th>
                                    <th width="15%" class="text-center"><?php echo get_phrases(['output'])?> KG</th>
                                    <th width="10%"><?php echo get_phrases(['action'])?></th>

                                </tr>
                            </thead>
                            <tbody id="po_item_div">
                                
                            </tbody>
                            <tfoot>

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
<div class="modal fade bd-example-modal-xl" id="itemProductionDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemProductionDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('production/item_production_approve', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
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
                    <label class="col-sm-1 text-right font-weight-600"><?php echo get_phrases(['plant']) ?> : </label>
                    <div class="col-sm-3">
                        <div id="itemProductionDetails_machine_id" ></div>
                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['plan', 'no']) ?> : </label>
                    <div class="col-sm-2">
                        <div id="itemProductionDetails_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-2">
                        <div id="itemProductionDetails_date"></div>                        
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
            <?php echo form_open_multipart('production/recipe/item_production_approve', 'class="ajaxForm needs-validation" id="recipeDetailsAjaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body" >

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['item']) ?> : </label>
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

                <div id="recipe_item_details_preview"></div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                
            </div>
            <?php echo form_close();?>
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
        $('#item_productionList').DataTable().ajax.reload();
    }

    function reset_table(){
        $('#filter_machine_id').val('').trigger('change');
        $('#filter_item_id').val('').trigger('change');
        $('#filter_voucher_no').val('').trigger('change');
        $('#filter_date').val('');
        $('#item_productionList').DataTable().ajax.reload();
    }

    var showCallBackData = function () {
        $('#id').val('');        
        //$('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#itemProductionDetails-modal').modal('hide');
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
        getMAXID('wh_production','id','po_voucher_no','PO-');
    }

    function first_item_row(){
        var item_list = $('#item_list').html();

        var item_counter = 1;

        var html = '<tr><td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td><td align="center"><input type="hidden" name="recipe_id[]" id="recipe_id'+item_counter+'"><span id="recipe_show'+item_counter+'"></span></td><td><input type="text" name="bag_size[]" id="bag_size'+item_counter+'" class="form-control text-right onlyNumber" autocomplete="off" onkeyup="bag_cal('+item_counter+')" required></td><td><input type="text" name="act_bags[]" id="act_bags'+item_counter+'" class="form-control text-right onlyNumber" autocomplete="off" onkeyup="bag_cal('+item_counter+')" required></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty'+item_counter+'" autocomplete="off" onkeyup="bag_cal('+item_counter+')" required></td><td><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button><input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0"></td></tr>';      
        $("#po_item_div").html(html); 
        $("#po_item_counter").val(item_counter);
        
        $('select').select2({
            placeholder: '<?php echo get_phrases(['select','item']);?>'                
        });
    }

    $(document).ready(function() { 
       "use strict";

       $('#item_list').hide();

       get_item_list(0);


       $('#reload_max_id').on('click', function() {
            reload_max_id();
        });

        $('body').on('click', '.addRow', function() {
            var item_counter = parseInt($("#po_item_counter").val()); 
            item_counter += 1;
            $("#po_item_counter").val(item_counter);
            var item_list = $('#item_list').html();

            var html = '<tr><td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td><td align="center"><input type="hidden" name="recipe_id[]" id="recipe_id'+item_counter+'"><span id="recipe_show'+item_counter+'"></span></td><td><input type="text" name="bag_size[]" id="bag_size'+item_counter+'" class="form-control text-right onlyNumber" autocomplete="off" onkeyup="bag_cal('+item_counter+')" required></td><td><input type="text" name="act_bags[]" id="act_bags'+item_counter+'" class="form-control text-right onlyNumber" autocomplete="off" onkeyup="bag_cal('+item_counter+')" required></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty'+item_counter+'" autocomplete="off" onkeyup="bag_cal('+item_counter+')" required></td><td><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button><input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0"></td></tr>';

            $("#po_item_div").append(html); 
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

            $('#po_machine_id').val('').trigger('change');
            select2_readonly_off('po_machine_id');
            $('#po_store_id').val('1');

            getMAXID('wh_production','id','po_voucher_no','PLAN-');
            var item_list = $('#item_list').html();

            var item_counter = 1;

            var html = '<tr><td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td><td align="center"><input type="hidden" name="recipe_id[]" id="recipe_id'+item_counter+'"><span id="recipe_show'+item_counter+'"></span></td><td><input type="text" name="bag_size[]" id="bag_size'+item_counter+'" class="form-control text-right onlyNumber" autocomplete="off" onkeyup="bag_cal('+item_counter+')" required></td><td><input type="text" name="act_bags[]" id="act_bags'+item_counter+'" class="form-control text-right onlyNumber" autocomplete="off" onkeyup="bag_cal('+item_counter+')" required></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty'+item_counter+'" autocomplete="off" onkeyup="bag_cal('+item_counter+')" required></td><td><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button><input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0"></td></tr>';      
            $("#po_item_div").html(html); 

            $("#po_item_counter").val(item_counter);

            $('select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });

            $('#item_productionModalLabel').text('<?php echo get_phrases(['production','plan']);?>');
            $('.po_modal_action').text('<?php echo get_phrases(['add']);?>');
            $('.po_modal_action').prop('disabled', false);
            $('#item_production-modal').modal('show');
        });


        $('#item_productionList').on('click', '.actionPreview', function(e){
             e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'production/getItemProductionDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemProductionDetails-modal').modal('show');
                    $('#itemProductionDetailsModalLabel').text('<?php echo get_phrases(['production','plan','details']);?>');

                    $('#itemProductionDetails_machine_id').text(data.machine_name);
                    $('#itemProductionDetails_store_id').text(data.store_name);

                    $('#itemProductionDetails_voucher_no').text(data.voucher_no);
                    $('#itemProductionDetails_date').text(data.date);                      
                    $('#itemProductionDetails_sub_total').text(data.sub_total);
                    $('#itemProductionDetails_grand_total').text(data.grand_total);
                    $('#itemProductionDetails_vat').text(data.vat);

                    $('#approve').hide();
                    $('#id').val('');

                    get_item_details(id, data.machine_id);

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
                { "bSortable": false, "aTargets": [6,7,8] },
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
                    title : 'ItemProduction_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'ItemProduction_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'production/getItemProduction',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        //d.store_id = $('#filter_store_id').val();
                        d.machine_id = $('#filter_machine_id').val();
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
             { data: 'machine_name' },
             { data: 'item_name' },
             { data: 'item_codes' },
             { data: 'status'},
             { data: 'received_status'},
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
            var submit_url = _baseURL+'production/getItemProductionDetailsById/'+id;

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
                        $('#item_productionModalLabel').text('<?php echo get_phrases(['production','plan','update']);?>');

                        $('#po_store_id').val(data.store_id);
                        $('#po_machine_id').val(data.machine_id).trigger('change');
                        select2_readonly('po_machine_id',data.machine_id);
                        $('#po_voucher_no').val(data.voucher_no);
                        $('#po_date').val(data.date);      

                        $('#po_id').val(id);
                        $('#po_action').val('update');

                        $('.po_modal_action').text('<?php echo get_phrases(['update']);?>');
                        $('.po_modal_action').prop('disabled', false);

                        get_item_details_edit(id, data.machine_id);
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
            var submit_url = _baseURL+'production/getItemProductionDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemProductionDetails-modal').modal('show');
                    $('#itemProductionDetailsModalLabel').text('<?php echo get_phrases(['production','plan','approve']);?>');

                    $('#itemProductionDetails_machine_id').text(data.machine_name);
                    $('#itemProductionDetails_store_id').text(data.store_name);

                    $('#itemProductionDetails_voucher_no').text(data.voucher_no);
                    $('#itemProductionDetails_date').text(data.date);                      
                    $('#itemProductionDetails_vat').text(data.vat);                      
                    $('#itemProductionDetails_sub_total').text(data.sub_total);
                    $('#itemProductionDetails_grand_total').text(data.grand_total);

                    $('#id').val(id);
                    $('#approve').show();
                    $('#approve').prop('disabled', false);

                    get_item_details(id, data.machine_id);

                },error: function() {

                }
            });  

        });
        // delete item_production
        $('#item_productionList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"production/deleteItemProduction/"+id;
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

    $(document).on('click', '.recipePreview', function(e){
         e.preventDefault();
        $('.ajaxForm').removeClass('was-validated');
        //$('#thumbpic').next('span').text('');
        
        var id = $(this).attr('data-id');
        var quantity = $(this).attr('data-quantity');
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

                get_recipe_item_details(id, quantity);

            },error: function() {

            }
        });  

    });

    function get_recipe_item_details(recipe_id, quantity){

        if(recipe_id !='' ){
            var submit_url = _baseURL+'production/recipe/getProductionRecipePricingDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'recipe_id':recipe_id, 'quantity':quantity },
                success: function(data) {
                    $('#recipe_item_details_preview').html(data);
                }
            });
        } else {
            $('#recipe_item_details_preview').html('');
        }
    }


    function get_item_details_edit(production_id, machine_id){

        if(production_id !='' ){
            var submit_url = _baseURL+'production/getItemProductionItemDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'json',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'production_id':production_id, 'machine_id':machine_id },
                success: function(data) {
                    //console.log(data);return;
                    if(data != null){
                        var item_counter = 1;
                        $("#po_item_div").html(''); 
                        $.each(data, function(index, value){
                            add_item_row(item_counter);

                            $("#item_id"+item_counter).val(value.item_id).trigger('change');
                            select2_readonly('item_id'+item_counter,value.item_id);
                            $("#recipe_id"+item_counter).val(value.recipe_id).trigger('change');
                            
                            $("#bag_size"+item_counter).val(value.bag_size);
                            $("#act_bags"+item_counter).val(value.act_bags);
                            $("#po_qty"+item_counter).val(value.qty);   
                            if( value.act_bags >0 && value.bag_size >0 ){
                                $("#po_qty"+item_counter).prop('readonly', true);
                            } else {
                                $("#po_qty"+item_counter).prop('readonly', false);
                            }

                            item_counter += 1;
                            $("#po_item_counter").val(item_counter);
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
        var html = '<tr><td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="po_item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td><td align="center"><input type="hidden" name="recipe_id[]" id="recipe_id'+item_counter+'"><span id="recipe_show'+item_counter+'"></span></td><td><input type="text" name="bag_size[]" id="bag_size'+item_counter+'" class="form-control text-right onlyNumber" autocomplete="off" onkeyup="bag_cal('+item_counter+')" required></td><td><input type="text" name="act_bags[]" id="act_bags'+item_counter+'" class="form-control text-right onlyNumber" autocomplete="off" onkeyup="bag_cal('+item_counter+')" required></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="po_qty'+item_counter+'" autocomplete="off" onkeyup="bag_cal('+item_counter+')" required></td><td><button type="button" class="btn btn-'+button_color+' '+button_class+'" ><i class="fa fa-'+button_type+'"></i></button><input type="hidden" name="existing[]" id="existing'+item_counter+'" value="0"></td></tr>';

        $("#po_item_div").append(html); 
        $('select').select2({
            placeholder: '<?php echo get_phrases(['select','item']);?>'                
        });
    }

    function get_item_details(production_id, machine_id){

        if(production_id !='' ){
            var submit_url = _baseURL+'production/getItemProductionPricingDetailsById';

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

    function get_item_list(machine_id){
         $.ajax({
            url: _baseURL+"production/get_item_list",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val, machine_id: machine_id},
            dataType:"html",
            async: false,
            success: function (data) {
                $('#item_list').html(data);
                first_item_row();
            }
        });
    }

    function po_item_info(item_id,sl){
        $('#bag_size'+sl).val('');
        $("#bag_size"+sl).prop('readonly', false); 
        $('#po_qty'+sl).val('');
        $("#po_qty"+sl).prop('readonly', false);
        
        if( item_id >0 ){
            var item_counter = parseInt($("#po_item_counter").val()); 
            var item_id_each = 0;
            for(var i=1; i<=item_counter; i++){
                item_id_each = $("#item_id"+i).val();
                if( item_id_each >0 && item_id == item_id_each &&  i!=sl){
                    toastr.warning('<?php echo get_notify('Same_item_can_not_be_added'); ?>');
                    $("#item_id"+sl).val('').trigger('change');
                    return false;
                }
            }
        }
        var machine_id = $('#machine_id').val();
        var action = $('#po_action').val();
        var existing = $('#existing'+sl).val();

        $.ajax({
            url: _baseURL+"production/getSupplierItemDetailsById",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val,item_id: item_id,machine_id:machine_id},
            dataType:"JSON",
            async: false,
            success: function (data) {
                //console.log(data);
                if(data != null && ( action=='add' || (action=='update' && existing=='0'))){

                    $('#recipe_show'+sl).html(data.recipe);
                    $('#recipe_id'+sl).val(data.recipe_id);

                    if(item_id >0 && data.recipe_id ==''){
                        toastr.warning('<?php echo get_notify('recipe_not_found'); ?>');
                        $("#item_id"+sl).val('').trigger('change');
                    } else if(data.bag_weight !=null){
                        $('#bag_size'+sl).val(data.bag_weight);
                        $("#bag_size"+sl).prop('readonly', true);

                    } else {
                        $('#bag_size'+sl).val('');
                        $("#bag_size"+sl).prop('readonly', false);                        
                    }

                    bag_cal(sl);
                }
            }
        });
    }

    function bag_cal(sl){
        
        var bag_size = ($("#bag_size"+sl).val()=='')?0:parseFloat($("#bag_size"+sl).val());
        var act_bags = ($("#act_bags"+sl).val()=='')?0:parseFloat($("#act_bags"+sl).val());
        var po_qty = ($("#po_qty"+sl).val()=='')?0:parseFloat($("#po_qty"+sl).val());

        if( bag_size == 0 && (act_bags >0 || po_qty >0)){
            toastr.warning('<?php echo get_notify('Enter_bag_size'); ?>');
            $("#act_bags"+sl).val('');
            $("#po_qty"+sl).val('');
            $("#po_qty"+sl).prop('readonly', false);
            
            return false;
        }
        if( act_bags == 0 && po_qty >0){
            toastr.warning('<?php echo get_notify('Enter_bags'); ?>');
            $("#po_qty"+sl).val('');
            $("#po_qty"+sl).prop('readonly', false);
            
            return false;
        }
        if( act_bags > 0 ){
            var total_qty = act_bags * bag_size;
            $("#po_qty"+sl).val(total_qty.toFixed(2));
            $("#po_qty"+sl).prop('readonly', true);

        }

    }


</script>