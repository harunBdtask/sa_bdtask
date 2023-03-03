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
                    <div class="col-sm-2">
                        <label for="filter_production_voucher_no" class="font-weight-600"><?php echo get_phrases(['plan','number']) ?>  </label>
                        <select name="filter_production_voucher_no" id="filter_production_voucher_no" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($production_list)){ ?>
                                <?php foreach ($production_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->voucher_no; ?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="filter_voucher_no" class="font-weight-600"><?php echo get_phrases(['production','voucher']) ?> </label>
                        <select name="filter_voucher_no" id="filter_voucher_no" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($receive_list)){ ?>
                                <?php foreach ($receive_list as $key => $value) {?>
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
                <table id="item_purchaseList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['plan','no']);?></th>
                            <th><?php echo get_phrases(['production','voucher']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
                            <th><?php echo get_phrases(['store']);?></th>
                            <th><?php echo get_phrases(['plant']);?></th>
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


<!-- item receive modal button -->
<div class="modal fade bd-example-modal-xl" id="itemReceiveDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemReceiveDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printContent">
                <div class="row">
                    <div class="col-sm-2">
                        <img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="100px" >
                    </div>
                    <div class="col-sm-8 text-center">
                        <h1><?php echo $settings_info->title; ?></h1>
                        <h6><?php echo $settings_info->address; ?></h6>
                        <h5 class="bg-secondary text-white rounded"><?php echo get_phrases(['finished','goods', 'production', 'voucher']) ?></h5>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['plant']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_machine_id" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_store_id"></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_receive_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_receive_date"></div>                        
                    </div>
                </div>

                <div id="item_details_preview"></div>

                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['received', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                    <div class="col-sm-6 text-right">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['authorized', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <?php if($permission->method('journal_voucher', 'read')->access() === 3213){ ?>
                    <button type="button" class="btn btn-info viewJV" id="journal_voucher" data-id=""><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']); ?></button>
                <?php } ?>
                <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                
            </div>
            
        </div>
    </div>
</div>


<!-- item receive modal button -->
<div class="modal fade bd-example-modal-xl" id="itemReturnDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemReturnDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printContent">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="40px" ><br><br>
                        <h6><?php echo $settings_info->title.' ( '.$settings_info->nameA.' )'; ?></h6>
                        <h5><?php echo get_phrases(['plant','return', 'invoice']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['from','store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReturnDetails_store_id"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['to','plant']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReturnDetails_machine_id" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReturnDetails_return_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReturnDetails_return_date"></div>                        
                    </div>
                </div>

                <div id="return_item_details_preview"></div>

                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['sub', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReturnDetail_return_sub_total" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['vat']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReturnDetails_return_vat" ></div>                        
                    </div>
                </div>
                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['grand', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReturnDetails_return_grand_total" ></div>                        
                    </div>
                </div>
                
                <!--<div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php //echo get_phrases(['total', 'paid']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReturnDetails_receipt" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php //echo get_phrases(['total', 'due']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReturnDetails_due" ></div>                        
                    </div>
                </div>
                 <div class="row form-group">
                    <label class="col-sm-10 text-right font-weight-600"><?php //echo get_phrases(['payment','method']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReturnDetails_payment_method" ></div>
                        
                    </div>
                </div> -->

                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['returned', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                    <div class="col-sm-6 text-right">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['authorized', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <?php if($permission->method('journal_voucher', 'read')->access() === 423423){ ?>
                    <button type="button" class="btn btn-info viewJV" id="journal_voucher_return" data-id=""><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']); ?></button>
                <?php } ?>
                <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                
            </div>
            
        </div>
    </div>
</div>

<!-- view voucher details -->
<div class="modal fade bd-example-modal-lg" id="jv-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="detailsModalLabel"><?php echo get_phrases(['view', 'voucher', 'details']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="viewDetails">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function reload_table(){
        $('#item_purchaseList').DataTable().ajax.reload();
    }

    function reset_table(){
        $('#filter_machine_id').val('').trigger('change');
        $('#filter_store_id').val('').trigger('change');
        $('#filter_production_voucher_no').val('').trigger('change');
        $('#filter_voucher_no').val('').trigger('change');
        $('#filter_date').val('');
        $('#item_purchaseList').DataTable().ajax.reload();
    }

    $(document).ready(function() { 
        "use strict";

        $('.viewJV').on('click', function(e){
            e.preventDefault();
            var VNo = $(this).attr('data-id');
            $('#jv-modal').modal('show');
            var submit_url = _baseURL+"account/vouchers/getVoucherDetails/"+VNo; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                async: false,
                success: function(response) {
                    $('#viewDetails').html('');
                    $('#viewDetails').html(response.data);
                }
            });  
        });

    });

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#item_purchase-modal').modal('hide');
        $('#item_purchaseList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
        

        $('#item_purchaseList').on('click', '.actionPreview', function(e){
             e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'production/getItemReceiveDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemReceiveDetails-modal').modal('show');
                    $('#itemReceiveDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

                    $('#itemReceiveDetails_machine_id').text(data.machine_name);
                    $('#itemReceiveDetails_store_id').text(data.store_name);

                    $('#journal_voucher').attr('data-id', data.receive_voucher_no);
                    $('#itemReceiveDetails_receive_voucher_no').text(data.receive_voucher_no);
                    $('#itemReceiveDetails_receive_date').text(data.receive_date);    

                    get_item_details(id);

                },error: function() {

                }
            });  

        });


        $('#item_purchaseList').DataTable({ 
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
                    title : 'ProductionVoucher_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                <?php } if($hasExportAccess){ ?>{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'ProductionVoucher_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'ProductionVoucher_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'ProductionVoucher_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'ProductionVoucher_List-<?php echo date('Y-m-d');?>',
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
               'url': _baseURL + 'production/getProductionVoucher',
               'data': function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.store_id = $('#filter_store_id').val();
                        d.machine_id = $('#filter_machine_id').val();
                        d.production_voucher_no = $('#filter_production_voucher_no').val();
                        d.voucher_no = $('#filter_voucher_no').val();
                        d.date = $('#filter_date').val();
                    }
            },
          'columns': [
             { data: 'id' },
             { data: 'plan_no' },
             { data: 'voucher_no' },
             { data: 'date' },
             { data: 'store_name' },
             { data: 'machine_name' },
             { data: 'status'},
             { data: 'received_status'},
             { data: 'button'},
          ],
        });

        $('#item_purchaseList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        
        $('#picture').on('change', function () {
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
            imgPreview($(this), fileExtension);
        }); 

        $('#item_purchaseList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'production/getProductionVoucherById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#item_purchase-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('update');
                    $('#item_purchaseModalLabel').text('<?php echo get_phrases(['update', 'purchase']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');

                    $('#nameE').val(data.nameE);
                    $('#nameA').val(data.nameA);
                    $('#cat_id').val(data.cat_id).trigger('change');
                    $('#unit_id').val(data.unit_id).trigger('change');
                    $('#price').val(data.price);

                    

                },error: function() {

                }
            });   

        });
        // delete item_purchase
        $('#item_purchaseList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"production/deleteProductionVoucher/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    async: false,
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, '<?php echo get_phrases(["record"])?>');
                            $('#item_purchaseList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, '<?php echo get_phrases(["record"])?>');
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });


    function get_item_details(production_id){

        if(production_id !='' ){
            var submit_url = _baseURL+'production/getItemPricingDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'production_id':production_id },
                success: function(data) {
                    $('#item_details_preview').html(data);
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

</script>