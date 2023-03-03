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
                <table id="item_purchaseList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['ID']);?></th>
                            <th><?php echo get_phrases(['voucher','no']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
                            <th><?php echo get_phrases(['PO']);?></th>
                            <th><?php echo get_phrases(['supplier']);?></th>
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
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="40px" ><br><br>
                        <h6><?php echo $settings_info->title.' ( '.$settings_info->nameA.' )'; ?></h6>
                        <h5><?php echo get_phrases(['received', 'item', 'invoice']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['from','supplier']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_supplier_id" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['to','store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_store_id"></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_receive_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['MR','date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_receive_date"></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['PO', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_po_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['PO','date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_po_date"></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['driver', 'name']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_driver_name" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['driver', 'mobile']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_driver_mobile" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['load','truck']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_load_truck" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['unload','truck']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_unload_truck" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['truck','no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_truck_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['QC', 'attachment']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_qc_filePreview" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['chalan', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_chalan_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['chalan', 'attachment']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_chalan_filePreview" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['GR', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_gr_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['scale', 'attachment']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_scale_filePreview" ></div>                        
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
                
                <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                <button type="button" class="btn btn-info" onclick="makePdf('printContent')"><?php echo get_phrases(['download']);?></button>
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
                        <h5><?php echo get_phrases(['supplier','return', 'invoice']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['from','store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReturnDetails_store_id"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['to','supplier']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReturnDetails_supplier_id" ></div>                        
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
                <?php if($permission->method('journal_voucher', 'read')->access()){ ?>
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

<!-- gate_pass -->
<div class="modal fade bd-example-modal-xl" id="item_purchase-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="item_purchaseModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('purchase/gatepass', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <input type="hidden" name="id" id="id" />
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="po_voucher" class="font-weight-600"><?php echo get_phrases(['PO','no'])?></label>
                            <input name="po_voucher" type="text" class="form-control" id="po_voucher" autocomplete="off" readonly />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="r_voucher" class="font-weight-600"><?php echo get_phrases(['receive','no'])?></label>
                            <input name="r_voucher" type="text" class="form-control" id="r_voucher" autocomplete="off" readonly />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="truck_no" class="font-weight-600"><?php echo get_phrases(['truck','no'])?> <i class="text-danger">*</i></label>
                            <input name="truck_no" type="text" class="form-control" id="truck_no" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="driver_name" class="font-weight-600"><?php echo get_phrases(['driver','name'])?></label>
                            <input name="driver_name" type="text" class="form-control" id="driver_name" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="driver_mobile" class="font-weight-600"><?php echo get_phrases(['driver','mobile'])?></label>
                            <input name="driver_mobile" type="text" class="form-control" id="driver_mobile" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="load_truck" class="font-weight-600"><?php echo get_phrases(['load','truck']) ?> <i class="text-danger">*</i></label>
                            <input type="number" name="load_truck" id="load_truck" class="form-control" autocomplete="off" required />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="unload_truck" class="font-weight-600"><?php echo get_phrases(['unload','truck']) ?> <i class="text-danger">*</i></label>
                            <input type="number" name="unload_truck" id="unload_truck" class="form-control" autocomplete="off" required />
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success modal_action actionBtn"></button>
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
    $('#preloader-wrapper').removeClass('hidden');
    
    function reload_table(){
        $('#item_purchaseList').DataTable().ajax.reload();
    }

    function reset_table(){
        $('#filter_store_id').val('').trigger('change');
        $('#filter_supplier_id').val('').trigger('change');
        $('#filter_voucher_no').val('');
        $('#filter_date').val('');
        $('#item_purchaseList').DataTable().ajax.reload();
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

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('#item_purchase-modal').modal('hide');        
        $('.ajaxForm')[0].reset();        
        $('#item_purchaseList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";

        $('.viewJV').on('click', function(e){
            e.preventDefault();
            var VNo = $(this).attr('data-id');
            $('#jv-modal').modal('show');
            var submit_url = _baseURL+"account/vouchers/getVoucherDetails/"+VNo; 
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                async: true,
                success: function(response) {
                    $('#viewDetails').html('');
                    $('#viewDetails').html(response.data);
                }
            });  
        });

        var title = $("#testtitle").html();
        $('#item_purchaseList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [7] },
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
                    title : 'ItemPurchase_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'ItemPurchase_List-<?php echo date('Y-m-d');?>',
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
               'url': _baseURL + 'purchase/getItemPurchase',
               'data': function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.store_id = $('#filter_store_id').val();
                        d.supplier_id = $('#filter_supplier_id').val();
                        d.voucher_no = $('#filter_voucher_no').val();
                        d.date = $('#filter_date').val();
                        d.type = 'null';
                    }
            },
          'columns': [
             { data: 'sl' },
             { data: 'id' },
             { data: 'voucher_no' },
             { data: 'date' },
             { data: 'po' },
             { data: 'supplier_name' },
            //  { data: 'grand_total', className: 'text-right' },
             { data: 'status'},
             { data: 'button'},
          ],
        });        

        $('#item_purchaseList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'purchase/getMaterialReceiveDetailsById/'+id;
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: true,
                success: function(data) {
                    $('#itemReceiveDetails-modal').modal('show');
                    $('#itemReceiveDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

                    $('#itemReceiveDetails_supplier_id').text(data.supplier_name);
                    $('#itemReceiveDetails_store_id').text(data.store_name);
                    $('#journal_voucher').attr('data-id', data.receive_voucher_no);
                    $('#itemReceiveDetails_receive_voucher_no').text(data.voucher_no);
                    $('#itemReceiveDetails_po_voucher_no').text(data.po_voucher);
                    $('#itemReceiveDetails_po_date').text(data.po_date);
                    $('#itemReceiveDetails_truck_no').text(data.truck_no);
                    $('#itemReceiveDetails_chalan_no').text(data.chalan_no);
                    $('#itemReceiveDetails_gr_no').text(data.gr_no);
                    $('#itemReceiveDetails_driver_name').text(data.driver_name);
                    $('#itemReceiveDetails_driver_mobile').text(data.driver_mobile);
                    $('#itemReceiveDetails_load_truck').text(data.load_truck);
                    $('#itemReceiveDetails_unload_truck').text(data.unload_truck);
                    $('#itemReceiveDetails_chalan_filePreview').html('<a href="<?php echo base_url() ?>' + data.chalan_attachment + ' " target="_blank" rel="noopener noreferrer" class="btn btn-success"><i class="fa fa-download"></i> </a>');
                    $('#itemReceiveDetails_scale_filePreview').html('<a href="<?php echo base_url() ?>' + data.scale_attachment + ' " target="_blank" rel="noopener noreferrer" class="btn btn-primary"><i class="fa fa-download"></i> </a>');
                    $('#itemReceiveDetails_qc_filePreview').html('<a href="<?php echo base_url() ?>' + data.qc_attachment + ' " target="_blank" rel="noopener noreferrer" class="btn btn-info"><i class="fa fa-download"></i> </a>');

                    $('#itemReceiveDetails_receive_date').text(data.receive_date);         
                    $('#itemReceiveDetails_receipt').text(data.receipt);                    

                    get_item_details(id);

                },error: function() {

                }
            });  

        });

        $('#item_purchaseList').on('click', '.actionPreviewReturn', function(e){
             e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#return_item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'purchase/getReturnDetailsById/'+id;
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: true,
                success: function(data) {
                    $('#itemReturnDetails-modal').modal('show');
                    $('#itemReturnDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

                    $('#itemReturnDetails_supplier_id').text(data.supplier_name);
                    $('#itemReturnDetails_store_id').text(data.store_name);

                    $('#journal_voucher_return').attr('data-id', data.return_voucher_no);
                    $('#itemReturnDetails_return_voucher_no').text(data.return_voucher_no);
                    $('#itemReturnDetails_return_date').text(data.return_date);   
                    //$('#itemReturnDetails_due').text(data.due);                    
                    //$('#itemReturnDetails_receipt').text(data.receipt);                    
                    //$('#itemReturnDetails_payment_method').text(data.payment_method);                    
                    $('#itemReturnDetail_return_sub_total').text((data.return_sub_total)?data.return_sub_total:0);
                    $('#itemReturnDetails_return_vat').text(data.return_vat);                    
                    $('#itemReturnDetails_return_grand_total').text((data.return_grand_total)?data.return_grand_total:0);

                    get_return_item_details(id);

                },error: function() {

                }
            });  

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
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'purchase/getMaterialReceiveDetailsById/'+id;
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: true,
                success: function(response) {
                    $('#item_purchase-modal').modal('show');
                    $('#id').val(id);
                    $('#truck_no').val(response.truck_no);
                    $('#driver_name').val(response.driver_name);
                    $('#driver_mobile').val(response.driver_mobile);
                    $('#r_voucher').val(response.r_voucher);
                    $('#po_voucher').val(response.po_voucher);
                    $('#action').val('update');
                    $('#item_purchaseModalLabel').text('<?php echo get_phrases(['update', 'purchase']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');

                    
                }
            }); 
        });
        // delete item_purchase
        $('#item_purchaseList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"purchase/deleteItemPurchase/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){
                preloader_ajax();  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    async: true,
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

    function get_item_details(purchase_id){

        if(purchase_id !='' ){
            var submit_url = _baseURL+'purchase/getItemPricingDetailsById';
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: true,
                data: {'csrf_stream_name':csrf_val, 'purchase_id':purchase_id },
                success: function(data) {
                    $('#item_details_preview').html(data);
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    function get_return_item_details(purchase_id){

        if(purchase_id !='' ){
            var submit_url = _baseURL+'purchase/getReturnItemDetailsById';
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: true,
                data: {'csrf_stream_name':csrf_val, 'purchase_id':purchase_id },
                success: function(data) {
                    $('#return_item_details_preview').html(data);
                }
            });
        } else {
            $('#return_item_details_preview').html('');
        }
    }


</script>