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
                    
                    <div class="col-sm-3">
                        <div class="form-group">
                            <strong><?php echo get_phrases(['store']);?></strong> <i class="text-danger">*</i>
                        </div>
                        <div class="form-group">
                            <select name="store" id="store" class="form-control custom-select">
                                <option value="">Select</option>
                                <?php foreach($store_list as $value){ ?>
                                <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                               <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <strong><?php echo get_phrases(['sub','store']);?></strong> <i class="text-danger">*</i>
                        </div>
                        <div class="form-group">
                            <select name="sub_store" id="sub_store" class="form-control custom-select" >
                                <option value="">Select</option>
                                <?php foreach($sub_store_list as $value){ ?>
                                <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                               <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <strong><?php echo get_phrases(['item']);?></strong> <i class="text-danger">*</i>
                        </div>
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
                            <strong><?php echo get_phrases(['page','size']);?></strong> <i class="text-danger">*</i>
                        </div>
                        <div class="form-group">
                            <select name="page_size" id="page_size" class="form-control custom-select" required>
                                <option value=""><?php echo get_phrases(['select','size']);?></option>
                                <option value="20" selected>20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <br>
                        <div class="form-group">
                            <input type="hidden" name="pageNumber" id="pageNumber" value="1">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1 userIBtn"><?php echo get_phrases(['filter']);?></button>
                        </div>
                    </div>
                </div>
                <div class="row pagination">
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-sm btn-success rounded-pill mt-1 prevBtn " onclick="get_data('prev')"><?php echo get_phrases(['prev']);?></button>
                    </div>
                    <div class="col-sm-8 text-center">
                        <div class="info"></div>
                    </div>
                    <div class="col-sm-2 text-right">
                        <button type="button" class="btn btn-sm btn-success rounded-pill mt-1 nextBtn " onclick="get_data('next')"><?php echo get_phrases(['next']);?></button>
                    </div>
                </div>
                <br>

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

    function get_item_list(supplier_id){
         $('#item_id').val('').trigger('change');
         $('#item_id').html('');

         $.ajax({
            url: _baseURL+"inventory/get_item_list",
            type: 'POST',
            data: {'csrf_stream_name':csrf_val, supplier_id: supplier_id},
            dataType:"html",
            success: function (data) {
                $('#item_id').html(data);
                //first_item_row();
            }
        });
    }

    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');

         // get patient info by ID
        $('#store').on('select2:select', function(e){
            $('#sub_store').val('').trigger('change');
            $('#results').html('');
            $('#pageNumber').val('1');
            $('.pagination').hide();
        });
        $('#sub_store').on('select2:select', function(e){
            $('#store').val('').trigger('change');
            $('#results').html('');
            $('#pageNumber').val('1');
            $('.pagination').hide();
        });
        $('#item_id').on('select2:select', function(e){
            $('#results').html('');
            $('#pageNumber').val('1');
            $('.pagination').hide();
        });
        $('#page_size').on('select2:select', function(e){
            $('#results').html('');
            $('#pageNumber').val('1');
            $('.pagination').hide();
        });

        $('.userIBtn').on('click', function(e){
            e.preventDefault();

            //var branch_id = $('#branch_id').val();
            //var store_id = $('#store_id').val();
            get_data('first');
        });

        $('.pagination').hide();

    });

    function get_data(dir){
        $('.pagination').hide();
        $('.prevBtn').prop('disabled', true);
        $('.nextBtn').prop('disabled', true);

        var pageNumber = parseInt($('#pageNumber').val());
        if(dir == 'next'){
            pageNumber += 1;
        } else if(dir == 'prev' && pageNumber >1 ){
            pageNumber -= 1;
        } 
        var store = $('#store').val();
        var sub_store = $('#sub_store').val();
        if(store =='' && sub_store =='' ){
            toastr.warning('<?php echo get_notify('store_is_required');?>');
            return false;
        }      
        var item_id = $('#item_id').val();
        if(item_id =='' ){
            toastr.warning('<?php echo get_notify('item_is_required');?>');
            return false;
        }      
        var page_size = $('#page_size').val();
        if(page_size =='' ){
            toastr.warning('<?php echo get_notify('page_size_is_required');?>');
            return false;
        }           
        var item_data = $('#item_id').select2('data');
        if(store !=''){
            var store_data = $('#store').select2('data');
        } else {
            var store_data = $('#sub_store').select2('data');
        }
        //$('#results').html('<h1>Loading...</h1>');    
        $('.userIBtn').prop('disabled', true);
        var submit_url = _baseURL+"reports/inventory/get_item_stock_history/"+pageNumber; 
        preloader_ajax();
        $.ajax({
            type: 'POST',
            url: submit_url,
            data: {'csrf_stream_name':csrf_val, store:store, sub_store:sub_store, item_id:item_id, page_size:page_size},
            dataType: 'JSON',
            success: function(res) {
                $('#results').html(res.data);
                $('#title').text('<?php echo get_phrases(['stock', 'reflection','of','item']); ?>: '+item_data[0].text+', Store: '+store_data[0].text);
                $('.userIBtn').prop('disabled', false);

                //$('#contactList > tbody').html(res.data);
                $('#pageNumber').val(res.pageNumber);

                $('.pagination').show();
                var range_start = ((res.pageNumber-1)*res.page_size)+1;
                var range_end = (res.total < res.page_size)?res.total:res.pageNumber*res.page_size;

                $('.info').html("Page "+res.pageNumber+", Showing "+range_start+" - "+range_end+" of "+res.total);
                if( res.pageNumber > 1  ){
                    $('.prevBtn').prop('disabled', false);
                } else {
                    $('.prevBtn').prop('disabled', true);
                }
                if( (res.pageNumber*res.page_size) < res.total ){
                    $('.nextBtn').prop('disabled', false);
                } else {
                    $('.nextBtn').prop('disabled', true);
                }
            }
        });  
    }

    function preview(obj){
            var item_id = $(obj).attr('data-id');
            //var store_id = $(obj).attr('warehouse-id');
            var submit_url = _baseURL+'reports/inventory/getItemStockDetailsAllById';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val,'item_id':item_id},
                success: function(data) {
                    $('#itemReceiveDetails-modal').modal('show');
                    $('#itemReceiveDetailsModalLabel').text('<?php echo get_phrases(['item','stock','details']);?>');

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