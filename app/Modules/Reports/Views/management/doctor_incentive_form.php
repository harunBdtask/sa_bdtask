<link href="<?php echo base_url()?>/assets/plugins/print/print.min.css" rel="stylesheet">
<div class="row">
    <div class="col-sm-12">
        <?php if($permission->method('aldara_incentive_report', 'create')->access() || $permission->method('aldara_incentive_report', 'read')->access()){ ?>
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
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <?php echo form_dropdown('doctor_id','','','class="custom-select" id="doctor_id"');?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php echo form_dropdown('service_id[]','','','class="form-control custom-select" id="service_id" multiple');?>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>" required>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-12">
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

<!-- view all invoices modal -->
<div class="modal fade bd-example-modal-xl" id="viewVoucherModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="viewVoucherModalLabel"><?php echo get_phrases(['view', 'all', 'invoice']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
               <div class="row">
                   <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo get_phrases(['voucher', 'no']);?></th>
                                        <th><?php echo get_phrases(['patient', 'name']);?></th>
                                        <th><?php echo get_phrases(['total', 'amount']);?></th>
                                        <th><?php echo get_phrases(['total', 'discount']);?>%</th>
                                        <th><?php echo get_phrases(['discount', 'amount']);?></th>
                                        <th><?php echo get_phrases(['total', 'vat']);?></th>
                                        <th><?php echo get_phrases(['total', 'paid']);?></th>
                                        <th><?php echo get_phrases(['total', 'due']);?></th>
                                        <th><?php echo get_phrases(['created', 'by']);?></th>
                                        <th><?php echo get_phrases(['created', 'date']);?></th>
                                    </tr>
                                </thead>
                                <tbody id="viewAllResult">
                                    
                                </tbody>
                            </table>
                        </div>
                   </div>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
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

<!-- view voucher details -->
<div class="modal fade" id="consListModal" tabindex="-1" role="dialog" aria-labelledby="consListModalLabel3" aria-hidden="true">
    <div class="modal-dialog custom-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="consListModalLabel"><?php echo get_phrases(['consumption', 'list']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-sm table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo get_phrases(['serial']);?></th>
                                    <th><?php echo get_phrases(['voucher', 'no']);?></th>
                                    <th><?php echo get_phrases(['date']);?></th>
                                    <th><?php echo get_phrases(['department', 'name']);?></th>
                                    <th><?php echo get_phrases(['store', 'name']);?></th>
                                    <th><?php echo get_phrases(['doctor', 'name']);?></th>
                                    <th><?php echo get_phrases(['file', 'no']);?></th>
                                    <th><?php echo get_phrases(['requested', 'by']);?></th>
                                    <th><?php echo get_phrases(['status']);?></th>
                                </tr>
                            </thead>
                            <tbody id="viewConsResults">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
        </div>
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
                <input type="hidden" name="id2" id="id2" />
                <input type="hidden" name="action2" id="action2" value="return" />

                <div class="row printing_info">
                    <div class="col-sm-12 text-center">
                        <h5><?php echo get_phrases(['item', 'consumption','voucher']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>

                <div class="row printing_info_return">
                    <div class="col-sm-12 text-center">
                        <h5><?php echo get_phrases(['item', 'return','voucher']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                    <hr>
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

                <div class="row printing_info_return">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['return','voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_return_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['returned','date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_return_date"></div>                        
                    </div>
                </div>

                <div class="form-group row return_input">
                    <label for="return_voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['voucher','no'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="return_voucher_no" type="text" class="form-control" id="return_voucher_no" placeholder="<?php echo get_phrases(['voucher','no'])?>" autocomplete="off" required >
                    </div>
                    <label for="return_date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="return_date" type="text" class="form-control" id="return_date" value="<?php echo date('d/m/Y')?>" autocomplete="off" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-12">
                        <div id="item_details_preview"></div>
                    </div>
                </div>
               
                <div class="row printing_info">
                    <hr>
                    <div class="col-sm-12 text-right">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['received', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                </div>

                <div class="row printing_info_return">
                    <hr>
                    <div class="col-sm-12 text-right">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['returned', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" id="print" class="btn btn-success printConsumption"><?php echo get_phrases(['print']);?></button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url()?>/assets/plugins/print/print.min.js?v=1.3" type="text/javascript"></script>

<script type="text/javascript">
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
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');

        $('.printConsumption').on('click', function(e){
            printJS({ printable: 'printContent', type: 'html'})
        });
    
        // search doctor
        $('#doctor_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'doctor', 'or', 'service', 'provider']);?>',
            minimumInputLength: 2,
            ajax: {
                url: _baseURL+'auth/searchDoctor',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                  return {
                    results:  $.map(data, function (item) {
                          return {
                              text: item.text,
                              id: item.id
                          }
                      })
                  };
                },
                cache: true
           }
        });

        // search service 
        $.ajax({
            type:'GET',
            url: _baseURL+'business/serviceList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#service_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'services']);?>',
                data: data
            });
        });
        // $('#service_id').select2({
        //     placeholder: '<?php //echo get_phrases(['search', 'services']);?>',
        //     minimumInputLength: 2,
        //     ajax: {
        //         url: _baseURL+'reports/management/searchServices',
        //         dataType: 'json',
        //         delay: 250,
        //         processResults: function (data) {
        //           return {
        //             results:  $.map(data, function (item) {
        //                   return {
        //                       text: item.text,
        //                       id: item.id
        //                   }
        //               })
        //           };
        //         },
        //         cache: true
        //    }
        // });

        // get patient info by ID
        $('.userIBtn').on('click', function(e){
            e.preventDefault();
            var doctor_id  = $('#doctor_id').val();
            var service_id = $('#service_id').val();
            var date       = $('#reportrange1').val();
            var submit_url = _baseURL+"reports/management/getDoctorIncentives"; 
            if(date){
                preloader_ajax();
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, doctor_id:doctor_id, service_id:service_id, date_range:date},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#results').html('');
                        $('#results').html(res.data);
                        $('#title').text('');
                        $('#title').text(date);
                        $('.custool').tooltip(); 
                    }
                });  
            }else{
                alert('Please select the date range!');
            }
        });

        // data export
        $(document).on('click', '.export', function(e){
            e.preventDefault();
            var doctor_id  = $('#doctor_id').val();
            var service_id = $('#service_id').val();
            var date       = $('#reportrange1').val();
            var submit_url = _baseURL+"reports/management/incentiveExportExcel"; 
            if(date){
                preloader_ajax();
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, doctor_id:doctor_id, service_id:service_id, date_range:date},
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

        // preview cost consumption item
        $(document).on('click', '.actionCost', function(e){
            e.preventDefault();
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'appointment/inventory/getItemRequestDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#itemRequestDetails-modal').modal('show');
                    $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

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
                    
                    $('#return').hide();
                    $('#print').show();
                    $('.return_input').hide();
                    $('.printing_info').show();
                    $('.printing_info_return').hide();

                    get_item_details(id);

                },error: function() {

                }
            });   

        });

        /// view all invoices info
        $(document).on('click', '.allInvoices', function(e){
            e.preventDefault();
            $('#viewVoucherModal').modal('show');
            var date = $('#reportrange1').val();
            var doctorId = $(this).attr('data-doctor');
            var type = $(this).attr('data-type');
            
            if(date && doctorId){
                var submit_url = _baseURL+"reports/management/getReportInvoices"; 
                preloader_ajax();
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, doctor_id:doctorId, date_range:date, type:type},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#viewAllResult').html('');
                        $('#viewAllResult').html(res.data);
                    }
                });  
            }else{
                alert('Wrong Invoice!')
            }
        });

        /// view all invoices info
        $(document).on('click', '.invoicesIds', function(e){
            e.preventDefault();
            $('#viewVoucherModal').modal('show');
            var ids = $(this).attr('data-ids');
            if(ids){
                var submit_url = _baseURL+"reports/management/getCommInvoices"; 
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, ids:ids},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#viewAllResult').html('');
                        $('#viewAllResult').html(res.data);
                    }
                });  
            }else{
                alert('Wrong Invoice!')
            }
        });

        /// Get consumed services invoice
        $(document).on('click', '.consumedList', function(e){
            e.preventDefault();
            $('#consListModal').modal('show');
            var invoice_id = $(this).attr('data-ids');
            var service_id = $(this).attr('data-service');
            var submit_url = _baseURL+"account/services/invoiceConsumptions/"+invoice_id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, approved:1, service_id:service_id},
                dataType: 'JSON',
                success: function(response) {
                    $('#viewConsResults').html('');
                    $('#viewConsResults').html(response.data);
                }
            });  
        });

        // view details invoice info
        $(document).on('click', '.viewDetails', function(e){
            e.preventDefault();
            $('#viewDModal').modal('show');
            var Id = $(this).attr('data-id');
            var type = $(this).attr('data-type');
            var typeId = type+'-'+Id;
            if(Id && type){
                var submit_url = _baseURL+"reports/management/getVoucherDetails"; 
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, voucherId:typeId},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#viewDetails').html('');
                        $('#viewDetails').html(res.data);
                    }
                });  
            }else{
                alert('Wrong Invoice!')
            }
        });

        $(document).on('click', '.clickable-row', function(e){
            onclick_change_bg('.table', this, 'cyan');
        });

    });
</script>