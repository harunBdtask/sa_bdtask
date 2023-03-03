<div class="row">
    <div class="col-sm-12">
        <?php if($permission->method('vat_reports', 'create')->access() || $permission->method('vat_reports', 'read')->access()){ ?>
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
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php echo form_dropdown('branch_id','','','class="custom-select" id="branch_id"');?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date', 'range']);?>" required>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1 userIBtn"><?php echo get_phrases(['filter']);?></button>
                        </div>
                    </div>
                </div>

                <div class="row" id="printC">
                    <div class="col-md-12">
                        <div id="results"></div>
                    </div>
                </div>
            </div>

        </div>
        <?php }else{ ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong class="fs-20 text-danger"><?php echo get_phrases(['you_do_not_have_permission_to_access_please_contact_with_administrator']);?></strong>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
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
                        <span class="fs-20"><?php echo get_phrases(['received', 'item', 'invoice']) ?></span><br>
                        <b class="fs-18"><?php echo $settings_info->title;?></b><br>
                        (<b class="fs-17"><?php echo $settings_info->nameA;?></b>)<br>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['supplier']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_supplier_id" ></div>                        
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

                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['sub', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receive_sub_total" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['vat']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_vat" ></div>                        
                    </div>
                </div>
                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['grand', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receive_grand_total" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['total', 'paid']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receipt" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['total', 'due']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_due" ></div>                        
                    </div>
                </div>
                <!-- <div class="row form-group">
                    <label class="col-sm-10 text-right font-weight-600"><?php //echo get_phrases(['payment','method']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_payment_method" ></div>
                        
                    </div>
                </div> -->

                <hr>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['received', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" class="btn btn-purple" onclick="printContent('printContent')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
                
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
            <div class="modal-body" id="printContent1">
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
                
                <button type="button" class="btn btn-success" onclick="printContent('printContent1')"><?php echo get_phrases(['print']);?></button>
                
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() { 
       "use strict";
       $('option:first-child').val('').trigger('change');

       // branch list
        $.ajax({
            type:'POST',
            url: _baseURL+'auth/branchList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#branch_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'branch', 'name']);?>',
                data: data
            });
        });

        // get patient info by ID
        $('.userIBtn').on('click', function(e){
            e.preventDefault();
            var date = $('#reportrange1').val();
            var branch_id = $('#branch_id').val();
            if(date){
                preloader_ajax();
                var submit_url = _baseURL+"reports/vat/getTnventoryStockVat"; 
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, branch_id:branch_id, date_range:date},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#results').html('');
                        $('#results').html(res.data);
                        $('#title').text('');
                        $('#title').text(date);
                    }
                });  
            }else{
                alert('<?php echo get_phrases(['please', 'select', 'date', 'range']);?>');
            }
        });

        // view details invoice info
        $(document).on('click', '.viewVoucher', function(e){
             e.preventDefault();
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'inventory/getItemReceiveDetailsById/'+id;
           
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#itemReceiveDetails-modal').modal('show');
                    $('#itemReceiveDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

                    $('#itemReceiveDetails_supplier_id').text(data.supplier_name);
                    $('#itemReceiveDetails_store_id').text(data.store_name);

                    $('#itemReceiveDetails_receive_voucher_no').text(data.receive_voucher_no);
                    $('#itemReceiveDetails_receive_date').text(data.receive_date);                    
                    $('#itemReceiveDetails_vat').text(data.vat);                    
                    $('#itemReceiveDetails_due').text(data.due);                    
                    $('#itemReceiveDetails_receipt').text(data.receipt);                    
                    //$('#itemReceiveDetails_payment_method').text(data.payment_method);                    
                    $('#itemReceiveDetails_receive_sub_total').text((data.receive_sub_total)?data.receive_sub_total:0);
                    $('#itemReceiveDetails_receive_grand_total').text((data.receive_grand_total)?data.receive_grand_total:0);

                    get_item_details(id);

                },error: function() {

                }
            });  
        });

        // view details invoice info
        $(document).on('click', '.viewReturn', function(e){
             e.preventDefault();
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'inventory/getReturnDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#itemReturnDetails-modal').modal('show');
                    $('#itemReturnDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

                    $('#itemReturnDetails_supplier_id').text(data.supplier_name);
                    $('#itemReturnDetails_store_id').text(data.store_name);

                    $('#journal_voucher_return').attr('data-id', data.return_voucher_no);
                    $('#itemReturnDetails_return_voucher_no').text(data.return_voucher_no);
                    $('#itemReturnDetails_return_date').text(data.return_date);   
                                        
                    $('#itemReturnDetail_return_sub_total').text((data.return_sub_total)?data.return_sub_total:0);
                    $('#itemReturnDetails_return_vat').text(data.return_vat);                    
                    $('#itemReturnDetails_return_grand_total').text((data.return_grand_total)?data.return_grand_total:0);

                    get_return_item_details(id);

                },error: function() {

                }
            });
        });

        function get_item_details(purchase_id){

            if(purchase_id !='' ){
                var submit_url = _baseURL+'inventory/getItemPricingDetailsById';

                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    dataType : 'html',
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
                var submit_url = _baseURL+'inventory/getReturnItemDetailsById';

                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    dataType : 'html',
                    data: {'csrf_stream_name':csrf_val, 'purchase_id':purchase_id },
                    success: function(data) {
                        $('#return_item_details_preview').html(data);
                    }
                });
            } else {
                $('#return_item_details_preview').html('');
            }
        }


        // purchase data export
        $(document).on('click', '.export', function(e){
            e.preventDefault();

            var date       = $('#reportrange1').val();
            var branch_id  = $('#branch_id').val();
            var submit_url = _baseURL+"reports/vat/exportPurchaseVat"; 
            if(date){
                preloader_ajax();
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, branch_id:branch_id, type:1, date_range:date},
                    dataType: 'JSON',
                    success: function(response) {
                        window.open(response.url, '_self');
                    }
                });  
            }else{
                alert('Please select the date range!');
            }
        });


    });
</script>