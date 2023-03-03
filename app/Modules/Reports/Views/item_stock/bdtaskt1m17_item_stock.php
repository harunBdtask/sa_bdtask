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
                    <div class="col-sm-1 text-right">
                        <div class="form-group">
                            <strong><?php echo get_phrases(['branch']);?></strong> <i class="text-danger">*</i>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <?php echo form_dropdown('branch_id','','','class="custom-select" id="branch_id"');?>
                        </div>
                    </div>
                    <div class="col-sm-1 text-right">
                        <div class="form-group">
                            <strong><?php echo get_phrases(['store']);?></strong> <i class="text-danger">*</i>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <select name="store_id" id="store_id" class="form-control custom-select" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1 text-right">
                        <div class="form-group">
                            <strong><?php echo get_phrases(['item']);?></strong>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
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

                <div id="item_details_preview"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
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

         // get patient info by ID
        $('#branch_id').on('change', function(e){
            var branch_id = $(this).val();
            var result = change_top_branch(branch_id);
            if(result == true){
                get_store_list(branch_id);
            }
        });

        $('.userIBtn').on('click', function(e){
            e.preventDefault();

            var branch_id = $('#branch_id').val();
            var store_id = $('#store_id').val();
            var item_id = $('#item_id').val();
            if(branch_id =='' ){
                toastr.warning('<?php echo get_notify('Select_branch'); ?>');
                return
            }
            if(store_id =='' ){
                toastr.warning('<?php echo get_notify('Select_store'); ?>');
                return
            }
            if(item_id =='' ){
                item_id = 0;
            }
            var warehouse_data = $('#store_id').select2('data');
            
            var submit_url = _baseURL+"reports/inventory/get_item_stock"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, store_id:store_id, item_id:item_id},
                dataType: 'JSON',
                success: function(res) {
                    $('#results').html('');
                    $('#results').html(res.data);
                    $('#title').text(warehouse_data[0].text+' <?php echo get_phrases(['available', 'stock']).', Date: '.date('d M, Y');?>');
                    
                }
            });  
        });



    });

    function preview(obj){
            var item_id = $(obj).attr('data-id');
            var store_id = $(obj).attr('warehouse-id');
            var submit_url = _baseURL+'reports/inventory/getItemReceiveDetailsById';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val,'item_id':item_id,'store_id':store_id},
                success: function(data) {
                    $('#itemReceiveDetails-modal').modal('show');
                    $('#itemReceiveDetailsModalLabel').text('<?php echo get_phrases(['stock','details']);?>');

                    $('#item_details_preview').html(data.html);

                    ///get_item_details(purchase_id);


                },error: function() {

                }
            });  

    }

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