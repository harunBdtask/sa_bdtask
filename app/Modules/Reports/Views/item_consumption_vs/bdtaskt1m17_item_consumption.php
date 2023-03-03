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
                            <label class="font-weight-600"><?php echo get_phrases(['doctor']);?> </label>
                            <select name="doctor_id" id="doctor_id" class="form-control custom-select" multiple>
                                <option value="">Select</option>
                                <?php foreach($doctor_list as $value){?>
                                <option value="<?php echo $value->emp_id;?>"><?php echo $value->short_name.'-'.$value->nameE;?></option>
                               <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['service']);?> </label>
                            <select name="service_id" id="service_id" class="form-control custom-select" multiple>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['status']);?></label>
                            <select name="status" id="status" class="form-control custom-select">
                                <option value=""><?php echo get_phrases(['status']);?></option>
                                <option value="short"><?php echo get_phrases(['short']);?></option>
                                <option value="same"><?php echo get_phrases(['same']);?></option>
                                <option value="excess"><?php echo get_phrases(['excess']);?></option>
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
                            <label class="font-weight-600"><?php echo get_phrases(['page','size']);?></label>
                            <select name="page_size" id="page_size" class="form-control custom-select" required>
                                <option value=""><?php echo get_phrases(['select','size']);?></option>
                                <option value="20" selected>20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                            <input type="hidden" name="pageNumber" id="pageNumber" value="1">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-4 userIBtn"><?php echo get_phrases(['filter']);?></button>
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
        if(date =='' ){
            toastr.warning('<?php echo get_notify('Select_date_range'); ?>');
            return
        }

        var submit_url = _baseURL+"reports/inventory/get_item_consumption_excel"; 
        if(date){
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, sub_store_id:sub_store_id, item_id:item_id, doctor_id:doctor_id, date_range:date, sorting:sorting, direction:direction},
                dataType: 'JSON',
                success: function(response) {
                    window.open(response.url, '_self');
                    //deleteExportExcel();
                }
            });  
        }else{
            alert('Please select the date range!');
        }
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
        $('.pagination').hide();

        $('#doctor_id').on('change', function(e){
            var doctor_id = $(this).val();
            get_service_list(doctor_id);
        });

         // get patient info by ID
        $('.userIBtn').on('click', function(e){
            e.preventDefault();
            get_data('first');
        });

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
        var doctor_id = $('#doctor_id').val();
        var service_id = $('#service_id').val();
        var status = $('#status').val();
        var date = $('#reportrange1').val();

        /*if(doctor_id =='' ){
            toastr.warning('<?php //echo get_notify('Select_doctor'); ?>');
            return
        }*/
        /*if(service_id =='' ){
            toastr.warning('<?php //echo get_notify('Select_service'); ?>');
            return
        }*/
        if(date =='' ){
            toastr.warning('<?php echo get_notify('Select_date'); ?>');
            return
        }      
        var page_size = $('#page_size').val();
        if(page_size =='' ){
            toastr.warning('<?php echo get_notify('page_size_is_required');?>');
            return false;
        } 
        var doctor_data = $('#doctor_id').select2('data');

        var submit_url = _baseURL+"reports/inventory/get_item_consumption_vs/"+pageNumber; 
        preloader_ajax();
        $('.userIBtn').prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: submit_url,
            data: {'csrf_stream_name':csrf_val, doctor_id:doctor_id, service_id:service_id, date_range:date, status:status, page_size:page_size},
            dataType: 'JSON',
            success: function(res) {
                $('#results').html('');
                $('#results').html(res.data);
                $('#title').text('');
                $('#title').text(date);

                $('.userIBtn').prop('disabled', false);
                $('#pageNumber').val(res.pageNumber);

                $('.pagination').show();
                $('.info').html("Page "+res.pageNumber+", Showing "+(((res.pageNumber-1)*res.page_size)+1)+" - "+(res.pageNumber*res.page_size)+" of "+res.total);
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

    function get_service_list(doctor_id){
        $('#service_id').val('').trigger('change');
        $('#service_id').html('');

        if(doctor_id !='' ){
            var submit_url = _baseURL+'reports/inventory/getServiceListByDoctorId';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'doctor_id':doctor_id },
                success: function(data) {
                    $('#service_id').html(data);
                }
            });
        } else {
            $('#service_id').html('');
        }
    }
</script>