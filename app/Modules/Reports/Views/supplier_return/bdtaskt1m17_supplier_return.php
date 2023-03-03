<div class="row">
    <div class="col-sm-12">
        <?php //if($permission->method('user_income_reports', 'create')->access()){ ?>
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
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i>Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['branch']);?>  <i class="text-danger">*</i></label>
                            <?php echo form_dropdown('branch_id','','','class="custom-select" id="branch_id"');?>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['store']);?>  <i class="text-danger">*</i></label>
                            <select name="store_id" id="store_id" class="form-control custom-select" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['supplier']);?>  </label>
                            <select name="supplier_id" id="supplier_id" class="form-control custom-select" required>
                                <option value="">Select</option>
                                <?php foreach($supplier_list as $value){?>
                                <option value="<?php echo $value->id;?>"><?php echo $value->code_no.'-'.$value->nameE;?></option>
                               <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['item']);?>  </label>
                            <select name="item_id" id="item_id" class="form-control custom-select">
                                <option value="">Select</option>
                                <?php foreach($item_list as $value){?>
                                <option value="<?php echo $value->id;?>"><?php echo $value->company_code.'-'.$value->nameE;?></option>
                               <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['date']);?>  <i class="text-danger">*</i></label>
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>" required>
                        </div>
                    </div>
                    <div class="col-sm-1">
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
        <?php //}else{ 
        /* <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong class="fs-20 text-danger"><?php echo get_phrases(['you_do_not_have_permission_to_access_please_contact_with_administrator']);?></strong>
                    </div>
                </div>
            </div>
        </div>*/
         //} ?>
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
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#invoices-modal').modal('hide');
        $('#invoicesList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');

        // branch list
        $.ajax({
            type:'GET',
            url: _baseURL+'auth/branchList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#branch_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'branch', 'name']);?>',
                data: data
            });
            $("#branch_id").val('<?php echo session('branchId');?>').trigger('change');
        });

        $('#branch_id').on('change', function(e){
            var branch_id = $(this).val();
            var result = change_top_branch(branch_id);
            if(result == true){
                get_store_list(branch_id);
            }
        });
         // get patient info by ID
        $('.userIBtn').on('click', function(e){
            e.preventDefault();

            var branch_id = $('#branch_id').val();
            var store_id = $('#store_id').val();
            var supplier_id = $('#supplier_id').val();
            var item_id = $('#item_id').val();
            var date = $('#reportrange1').val();
            if(branch_id =='' ){
                toastr.warning('<?php echo get_notify('Select_branch'); ?>');
                return
            }
            if(store_id =='' ){
                toastr.warning('<?php echo get_notify('Select_store'); ?>');
                return
            }
            if(date =='' ){
                toastr.warning('<?php echo get_notify('Select_date_range'); ?>');
                return
            }
    
            var submit_url = _baseURL+"reports/inventory/get_supplier_return"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, store_id:store_id, supplier_id:supplier_id, item_id:item_id, date_range:date},
                dataType: 'JSON',
                success: function(res) {
                    $('#results').html('');
                    $('#results').html(res.data);
                    $('#title').text('');
                    $('#title').text(date);
                }
            });  
        });

    });


    function preview(obj){
            //e.preventDefault();
            //$('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var purchase_id = $(obj).attr('purchase-id');
            var submit_url = _baseURL+'inventory/getReturnDetailsById/'+purchase_id;

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

                    $('#itemReturnDetails_return_voucher_no').text(data.return_voucher_no);
                    $('#itemReturnDetails_return_date').text(data.return_date);   
                    //$('#itemReturnDetails_due').text(data.due);                    
                    //$('#itemReturnDetails_receipt').text(data.receipt);                    
                    //$('#itemReturnDetails_payment_method').text(data.payment_method);                    
                    $('#itemReturnDetail_return_sub_total').text((data.return_sub_total)?data.return_sub_total:0);
                    $('#itemReturnDetails_return_vat').text(data.return_vat);                    
                    $('#itemReturnDetails_return_grand_total').text((data.return_grand_total)?data.return_grand_total:0);

                    get_return_item_details(purchase_id);

                },error: function() {

                }
            });  

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

    function get_store_list(branch_id){

        if(branch_id !='' ){
            var submit_url = _baseURL+'reports/inventory/getWarehouseListByBranchId';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'json',
                data: {'csrf_stream_name':csrf_val, 'branch_id':branch_id },
                success: function(data) {
                    $('#store_id').html(data.store);
                }
            });
        } else {
            $('#store_id').html('');
        }
    }
</script>