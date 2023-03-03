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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'bag']);?></button>
                        <?php } ?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">                    
                    <!-- filter part -->
                </div>
                <table id="itemsList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th width="5%"><?php echo get_phrases(['sl']);?></th>
                            <th width="10%"><?php echo get_phrases(['name']);?></th>
                            <th width="5%"><?php echo get_phrases(['monthly', 'consumption']);?></th>
                            <th width="5%"><?php echo get_phrases(['unit']);?></th>
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
<div class="modal fade bd-example-modal-xl" id="items-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('bag/add_items', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                <div class="row form-group">
                    <label for="nameE" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['name']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="nameE" placeholder="<?php echo get_phrases(['enter', 'name']);?>" class="form-control" id="nameE" required>                        
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
                    <label for="item_code" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['ID']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="item_code" placeholder="<?php echo get_phrases(['enter', 'item', 'code']);?>" class="form-control" id="item_code" readonly>                        
                    </div>
                    <label for="company_code" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['code']) ?> </label>
                    <div class="col-sm-4">
                        <input type="text" name="company_code" placeholder="<?php echo get_phrases(['enter', 'company', 'code']);?>" class="form-control" id="company_code">                        
                    </div>
                    <!-- <label for="where_use" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['where','use']) ?> </label>
                    <div class="col-sm-4">
                        <select name="where_use" id="where_use" class="custom-select form-control" >
                            <option value=""><?php echo get_phrases(['where','use']) ?></option>
                            <?php
                                if(!empty($where_use)){ ?>
                                <?php foreach ($where_use as $key => $value) {?>
                                    <option value="<?php echo $value->id; ?>"><?php echo $value->nameE; ?></option>
                                <?php }?>
                            <?php } ?>
                        </select>                        
                    </div> -->
                </div>
                <div class="row form-group">
                    <label for="alert_qty" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['alert','quantity']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="alert_qty" placeholder="<?php echo get_phrases(['enter','quantity']);?>" class="form-control onlyNumber" id="alert_qty" required>                        
                    </div>
                    <label for="vat_applicable" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['vat', 'applicable']) ?> </label>
                    <div class="col-sm-1 mt-2">
                        <input type="checkbox" name="vat_applicable" id="vat_applicable" value="1">                        
                    </div>
                    <!-- <label for="country_origin" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['country','origin']) ?> </label>
                    <div class="col-sm-4">
                        <select name="country_origin" id="country_origin" class="custom-select form-control" >
                            <option value=""><?php echo get_phrases(['country','origin']) ?></option>
                            <?php
                                $country_origin = countries(); 
                                if(!empty($country_origin)){ ?>
                                <?php foreach ($country_origin as $key => $value) {  ?>
                                    <option value="<?php echo $value->code; ?>"><?php echo $value->name; ?></option>
                                <?php }?>
                            <?php }?>
                        </select>                        
                    </div> -->
                </div>
                <div class="row form-group">
                    <!-- <label for="aprox_monthly_consumption" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['monthly','consumption']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="aprox_monthly_consumption" placeholder="<?php echo get_phrases(['enter','quantity']);?>" class="form-control onlyNumber" id="aprox_monthly_consumption" required>                        
                    </div> -->
                    
                </div>
                <div class="row form-group">
                    <!-- <label for="price" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['price']) ?> </label>
                    <div class="col-sm-4">
                        <input type="text" name="price" placeholder="<?php echo get_phrases(['price']);?>" class="form-control onlyNumber" id="price">                        
                    </div> -->
                    <label for="finish_goods" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['finish','goods']) ?> </label>
                    <div class="col-sm-4">
                        <select name="finish_goods" id="finish_goods" class="custom-select form-control" >
                            <?php
                                if(!empty($finish_goods)){ ?>
                                <?php foreach ($finish_goods as $key => $value) {?>
                                    <option value="<?php echo $value->id; ?>"><?php echo $value->nameE; ?></option>
                                <?php }?>
                            <?php } ?>
                        </select>                        
                    </div>
                </div>
                <div class="row form-group">
                    <label for="bag_size" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['bag','size']) ?> </label>
                    <div class="col-sm-4">
                        <input type="text" name="bag_size" placeholder="<?php echo get_phrases(['bag','size']);?>" class="form-control" id="bag_size">                        
                    </div>
                    <label for="specification" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['specification']) ?> </label>
                    <div class="col-sm-4">
                        <select name="specification" id="specification" class="custom-select form-control" >
                            <?php
                                if(!empty($specification)){ ?>
                                <?php foreach ($specification as $key => $value) {?>
                                    <option value="<?php echo $value->id; ?>"><?php echo $value->nameE; ?></option>
                                <?php }?>
                            <?php } ?>
                        </select>                        
                    </div>
                </div>
                <div class="row form-group">
                    <label for="liner_size" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['liner','size']) ?> </label>
                    <div class="col-sm-4">
                        <input type="text" name="liner_size" placeholder="<?php echo get_phrases(['liner','size']);?>" class="form-control" id="liner_size">                        
                    </div>
                    <label for="bag_weight" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['bag','weight']) ?> </label>
                    <div class="col-sm-4">
                        <input type="number" name="bag_weight" placeholder="<?php echo get_phrases(['bag','weight']);?>" class="form-control" id="bag_weight">                        
                    </div>
                </div>
                <div class="row form-group">
                    <label for="liner_weight" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['liner','weight']) ?> </label>
                    <div class="col-sm-4">
                        <input type="number" name="liner_weight" placeholder="<?php echo get_phrases(['liner','weight']);?>" class="form-control" id="liner_weight">                        
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
            <div class="modal-body" id="printContent">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="<?php echo base_url() . $setting->logo; ?>" class="img-responsive" alt=""><strong><?php echo $setting->title; ?></strong><br>
                        <?php echo $setting->address; ?>
                    </div>
                </div>
                <hr>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['item', 'name']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_nameE" ></div>                        
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['item', 'code']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_item_code"></div>
                        
                    </div>
                    <!-- <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['where','use']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_where_use"></div>
                        
                    </div> -->
                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['item', 'unit']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_unit_id"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['finish', 'goods']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_finish_goods" ></div>                        
                    </div>
                </div>
                <div class="row form-group">
                    <!-- <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['price']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_price" ></div>                        
                    </div> -->
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
                    <!-- <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['country', 'origin']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_country_origin"></div>                        
                    </div> -->
                </div>

                <div class="row form-group">               
                    <!-- <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['monthly', 'consumption']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_aprox_monthly_consumption"></div>                        
                    </div> -->
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['vat', 'applicable']) ?> : </label>
                    <div class="col-sm-1">
                        <div id="itemDetails_vat_applicable"></div>                        
                    </div>
                </div>

                <div class="row form-group">               
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['specification']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_specification"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['bag', 'size']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_bag_size"></div>                        
                    </div>
                </div>

                <div class="row form-group">               
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['liner', 'size']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_liner_size"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['bag','weight']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_bag_weight"></div>                        
                    </div>
                </div>

                <div class="row form-group"> 
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['liner','weight']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_liner_weight"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['description']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemDetails_description" ></div>                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                <button type="button" class="btn btn-info" onclick="makePdf('printContent')"><?php echo get_phrases(['download']);?></button>
            </div>
            
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
    $(document).ready(function() { 
       "use strict";
        
        //single details
        $('#itemsList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'bag/getItemDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#itemDetails-modal').modal('show');
                    $('#itemDetailsModalLabel').text('<?php echo get_phrases(['item','details']);?>');
                    $('#itemDetails_nameE').text(data.item.nameE);
                    $('#itemDetails_store').text(data.item.store_name);
                    $('#itemDetails_item_code').text(data.item.item_code);
                    $('#itemDetails_finish_goods').text(data.item.c_name);
                    $('#itemDetails_unit_id').text(data.item.unit_name);
                    $('#itemDetails_alert_qty').text(data.item.alert_qty);
                    $('#itemDetails_aprox_monthly_consumption').text(data.item.aprox_monthly_consumption);
                    $('#itemDetails_where_use').text(data.item.w_use);
                    $('#itemDetails_price').text(data.item.price);
                    $('#itemDetails_expire').text(data.item.expire);
                    $('#itemDetails_country_origin').text(data.item.country_name);
                    $('#itemDetails_description').text(data.item.description);
                    $('#itemDetails_specification').text(data.item.specification);
                    $('#itemDetails_bag_size').text(data.item.bag_size);
                    $('#itemDetails_liner_size').text(data.item.liner_size);
                    $('#itemDetails_bag_weight').text(data.item.bag_weight);
                    $('#itemDetails_liner_weight').text(data.item.liner_weight);
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

        //list table
        var title = $("#testtitle").html();
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
                    title: '',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title: '',
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
               'url': _baseURL + 'bag/getItems',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.cat_id = $('#filter_cat_id').val();
                    }
            },
          'columns': [
             { data: 'id' },
             { data: 'nameE' },
             { data: 'aprox_monthly_consumption' },
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
            $('#aprox_monthly_consumption').val('');
            $('#price').val('');
            $('#description').val('');
            $('#country_origin').val('BD').trigger('change');
            $('#where_use').val('').trigger('change');
            $('#finish_goods').val('').trigger('change');
            $('#unit_id').val('34').trigger('change');
            $('#vat_applicable').prop('checked', false);

            $('#company_code').val('');            
            $('#alert_qty').val('');     
            $('#company_code').val('');     
            $('#bag_size').val('');     
            $('#liner_size').val('');     
            $('#bag_weight').val('');     
            $('#liner_weight').val('');     
            $('#specification').val('').trigger('change');
            $('#item_type').val('').trigger('change');
            $('#consumed_by').val('').trigger('change');
            $('#consumed_by').prop('required', false);
            $('#sell_price_container').hide();

            getMAXID('wh_bag','id','item_code','BAG-');
            
            $('#itemsModalLabel').text('<?php echo get_phrases(['add', 'bag']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('#items-modal').modal('show');

        });

        //edit
        $('#itemsList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'bag/getItemsById/'+id;

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
                    $('#item_code').val(data.item.item_code);
                    $('#alert_qty').val(data.item.alert_qty);
                    $('#company_code').val(data.item.company_code);
                    $('#aprox_monthly_consumption').val(data.item.aprox_monthly_consumption);
                    $('#unit_id').val(data.item.unit_id).trigger('change');
                    $('#specification').val(data.item.specification).trigger('change');
                    $('#bag_size').val(data.item.bag_size);
                    $('#liner_size').val(data.item.liner_size);
                    $('#bag_weight').val(data.item.bag_weight);
                    $('#liner_weight').val(data.item.liner_weight);
                    $('#price').val(data.item.price);
                    $('#description').val(data.item.description);
                    $('#finish_goods').val(data.item.finish_goods).trigger('change');
                    $('#where_use').val(data.item.where_use).trigger('change');
                    $('#country_origin').val(data.item.country_origin).trigger('change');
                    if(data.item.vat_applicable == '1'){
                        $('#vat_applicable').prop('checked', true);  
                    } else {
                        $('#vat_applicable').prop('checked', false);  
                    }    

                },error: function() {

                }
            });   

        });
        // delete items
        $('#itemsList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"bag/deleteItems/"+id;
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