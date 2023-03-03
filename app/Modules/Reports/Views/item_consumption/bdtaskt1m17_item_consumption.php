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
                            <label class="font-weight-600"><?php echo get_phrases(['branch']);?> <i class="text-danger">*</i> </label>
                            <?php echo form_dropdown('branch_id','','','class="custom-select" id="branch_id"');?>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['sub','store']);?> <i class="text-danger">*</i> </label>
                            <select name="sub_store_id" id="sub_store_id" class="form-control custom-select" multiple>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['request','date']);?>  </label>
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['doctor']);?>  </label>
                            <select name="doctor_id" id="doctor_id" class="form-control custom-select">
                                <option value="">Select</option>
                                <?php foreach($doctor_list as $value){?>
                                <option value="<?php echo $value->emp_id;?>"><?php echo $value->short_name.'-'.$value->nameE;?></option>
                               <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['invoice','date']);?> </label>
                            <input type="text" name="invoice_date" id="invoice_date" class="form-control reportrange1" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>" required>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['sorting']);?></label>
                            <select name="sorting" id="sorting" class="form-control custom-select">
                                <option value=""><?php echo get_phrases(['sorting','by']);?></option>
                                <option value="default" selected><?php echo get_phrases(['default']);?></option>
                                <option value="company_code"><?php echo get_phrases(['company','code']);?></option>
                                <option value="item_name"><?php echo get_phrases(['item','name']);?></option>
                                <option value="approved_date"><?php echo get_phrases(['approved','date']);?></option>
                                <option value="store_name"><?php echo get_phrases(['store','name']);?></option>
                                <option value="dept_name"><?php echo get_phrases(['department','name']);?></option>
                                <option value="doctor_name"><?php echo get_phrases(['doctor','name']);?></option>
                                <option value="code_no"><?php echo get_phrases(['code','no']);?></option>
                                <option value="file_no"><?php echo get_phrases(['file','no']);?></option>
                                <option value="voucher_no"><?php echo get_phrases(['voucher','no']);?></option>
                                <option value="quantity"><?php echo get_phrases(['quantity']);?></option>
                                <option value="price"><?php echo get_phrases(['unit','price']);?></option>
                                <option value="total"><?php echo get_phrases(['total','price']);?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['direction']);?></label>
                            <select name="direction" id="direction" class="form-control custom-select">
                                <option value=""><?php echo get_phrases(['direction']);?></option>
                                <option value="ASC" selected><?php echo get_phrases(['ascending']);?></option>
                                <option value="DESC"><?php echo get_phrases(['descending']);?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-4 userIBtn"><?php echo get_phrases(['filter']);?></button>
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


<!-- item request modal button -->
<div class="modal fade bd-example-modal-xl" id="itemRequestDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemRequestDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printContent">

                <div class="row printing_info">
                    <div class="col-sm-12 text-center">
                        <h5><?php echo get_phrases(['item', 'consumption','voucher']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>
                
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['from','department']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_from_department_id"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['to','store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_sub_store_id" ></div>                        
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_date"></div>                        
                    </div>
                </div>
                
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['consumed','by']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_consumed_by" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['request','by']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_request_by" ></div>                        
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['notes']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_notes" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['status']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_status" ></div>                        
                    </div>
                </div>

                <div class="row consumed_service_info" >
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['patient']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_patient" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['doctor']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_doctor" ></div>                        
                    </div>
                </div>

                <div class="row consumed_service_info">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['service']) ?> : </label>
                    <div class="col-sm-10">
                        <div id="itemRequestDetails_service" ></div>                        
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-12">
                        <div id="item_details_preview"></div>
                    </div>
                </div>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" id="print" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
            </div>
            
        </div>
    </div>
</div>

<!-- view voucher details modal -->
<div class="modal fade bd-example-modal-xl" id="viewDModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="viewDModalLabel"><?php echo get_phrases(['view', 'details']);?></h5>
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

    // data export
    $(document).on('click', '.export', function(e){
        e.preventDefault();

        var branch_id = $('#branch_id').val();
        var sub_store_id = $('#sub_store_id').val();
        var item_id = $('#item_id').val();
        var doctor_id = $('#doctor_id').val();
        var date = $('#reportrange1').val();
        var invoice_date = $('#invoice_date').val();
        var sorting = $('#sorting').val();
        var direction = $('#direction').val();
 
        if(branch_id =='' ){
            toastr.warning('<?php echo get_notify('Select_branch'); ?>');
            return
        }
        if(sub_store_id =='' ){
            toastr.warning('<?php echo get_notify('Select_sub_store'); ?>');
            return
        }
        /*if(date =='' ){
            toastr.warning('<?php //echo get_notify('Select_date_range'); ?>');
            return
        }*/

        var submit_url = _baseURL+"reports/inventory/get_item_consumption_excel"; 
        //if(date){
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, sub_store_id:sub_store_id, item_id:item_id, doctor_id:doctor_id, date_range:date, invoice_date:invoice_date, sorting:sorting, direction:direction},
                dataType: 'JSON',
                success: function(response) {
                    window.open(response.url, '_self');
                    //deleteExportExcel();
                }
            });  
        //}else{
        //    alert('Please select the date range!');
        //}
    });

    // view details invoice info
    $(document).on('click', '.viewDetails', function(e){
        e.preventDefault();
        $('#viewDModal').modal('show');
        var voucherId = $(this).attr('data-id');
        //var Id = $(this).attr('data-id');
        //var type = $(this).attr('data-type');
        //var typeId = type+'-'+Id;
        //if(Id && type){
        $('#viewDetails').html('');
        if(voucherId){
            var submit_url = _baseURL+"reports/management/getVoucherDetails"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, voucherId:voucherId},
                dataType: 'JSON',
                success: function(res) {
                    $('#viewDetails').html('');
                    $('#viewDetails').html(res.data);
                }
            });  
        }else{
            toastr.warning('<?php echo get_notify('Wrong_Invoice!'); ?>');
        }
    });
    
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
            var sub_store_id = $('#sub_store_id').val();
            var item_id = $('#item_id').val();
            var doctor_id = $('#doctor_id').val();
            var date = $('#reportrange1').val();
            var invoice_date = $('#invoice_date').val();
            var sorting = $('#sorting').val();
            var direction = $('#direction').val();

            if(branch_id =='' ){
                toastr.warning('<?php echo get_notify('Select_branch'); ?>');
                return
            }
            if(sub_store_id =='' ){
                toastr.warning('<?php echo get_notify('Select_sub_store'); ?>');
                return
            }
            /*if(date =='' ){
                toastr.warning('<?php //echo get_notify('Select_date_range'); ?>');
                return
            }*/
    
            var submit_url = _baseURL+"reports/inventory/get_item_consumption"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, sub_store_id:sub_store_id, item_id:item_id, doctor_id:doctor_id, date_range:date, invoice_date:invoice_date, sorting:sorting, direction:direction},
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
        
        var id = $(obj).attr('data-id');
        var submit_url = _baseURL+'appointment/inventory/getItemRequestDetailsById/'+id;

        $.ajax({
            type: 'POST',
            url: submit_url,
            dataType : 'JSON',
            data: {'csrf_stream_name':csrf_val},
            success: function(data) {
                $('#itemRequestDetails-modal').modal('show');
                $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['item','consumption','details']);?>');

                $('#itemRequestDetails_sub_store_id').text(data.sub_store_name);
                $('#itemRequestDetails_date').text(data.date);
                $('#itemRequestDetails_voucher_no').text(data.voucher_no);
                $('#itemRequestDetails_from_department_id').text(data.department_name);
                $('#itemRequestDetails_notes').text(data.notes);
                $('#itemRequestDetails_status').text(data.status_text+' '+data.status_collected+' '+data.status_returned);
                $('#itemRequestDetails_request_by').text(data.request_by_name);
                $('#itemRequestDetails_consumed_by').text(data.consumed_by);
                //alert(data.consumed_by);
                if(data.consumed_by == 'service'){
                    $('.consumed_service_info').show();
                    $('#itemRequestDetails_service').text(data.service);
                    $('#itemRequestDetails_patient').text(data.patient);
                    $('#itemRequestDetails_doctor').text(data.doctor_name);
                } else {
                    $('.consumed_service_info').hide();
                }
                
                get_item_details(id);

            },error: function() {

            }
        });   

    }

    function get_item_details(request_id){

        if(request_id !='' ){
            var submit_url = _baseURL+'appointment/inventory/getItemRequestQuantityDetailsById';
            var action = 'print';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'request_id':request_id, 'action':action },
                success: function(data) {
                    $('#item_details_preview').html(data);

                    //$('.return_info').hide();
                    //$('.consume_info').hide();
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    function get_store_list(branch_id){
        $('#sub_store_id').val('').trigger('change');
        $('#sub_store_id').html('');

        if(branch_id !='' ){
            var submit_url = _baseURL+'reports/inventory/getSubWarehouseListByBranchId';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'branch_id':branch_id },
                success: function(data) {
                    $('#sub_store_id').html(data);
                }
            });
        } else {
            $('#sub_store_id').html('');
        }
    }
</script>