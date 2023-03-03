<div class="row">
    <div class="col-sm-12">
        <?php if($permission->method('activity_log_report', 'create')->access() || $permission->method('activity_log_report', 'read')->access()){ ?>
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
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="user_id" class="col-form-label font-weight-600"><?php echo get_phrases(['select', 'user']) ?></label>
                            <?php echo form_dropdown('user_id','','','class="custom-select" id="user_id"');?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="reportrange1" class="col-form-label font-weight-600"><?php echo get_phrases(['select', 'date']) ?></label>
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>" required>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <label for="page_size" class="col-form-label font-weight-600"><?php echo get_phrases(['page', 'size']) ?></label>
                            <?php 
                                $psize = array(''=>'', '20'=>'20', '50'=>'50', '100'=>'100', '250'=>'250');
                                echo form_dropdown('page_size',$psize,'20','class="form-control custom-select" id="page_size"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group mt-4 py-3">
                            <input type="hidden" name="pageNumber" id="pageNumber" value="1">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill filtering"><?php echo get_phrases(['filter', 'user', 'logs']);?></button>
                        </div>
                    </div>
                </div>

                <div class="row" id="printC">
                    <div class="col-md-12">
                        <div id="resultData"></div>
                    </div>
                </div>
                <div class="row hidden" id="showNextPrev">
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-sm btn-success rounded-pill mt-1 prevBtn " onclick="get_data('prev')"><?php echo get_phrases(['prev']);?></button>
                    </div>
                    <div class="col-sm-4">
                        <div id="info"></div>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button type="button" class="btn btn-sm btn-success rounded-pill mt-1 nextBtn " onclick="get_data('next')"><?php echo get_phrases(['next']);?></button>
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

<script type="text/javascript">

    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');
    
        // search doctor
        $('#user_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'user', 'name']);?>',
            minimumInputLength: 2,
            ajax: {
                url: _baseURL+'auth/searchEmployee',
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

        // get patient info by ID
        $('.filtering').on('click', function(e){
            e.preventDefault();
            get_data('first');
        });

        // data export
        $(document).on('click', '.export', function(e){
            e.preventDefault();
            var user_id  = $('#user_id').val();
            var date       = $('#reportrange1').val();
            var submit_url = _baseURL+"reports/management/exportActivityLogs"; 
            if(date){
                preloader_ajax();
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, user_id:user_id, date_range:date},
                    dataType: 'JSON',
                    success: function(response) {
                        window.open(response.url, '_self');
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
    });
     function get_data(dir){
        $('#showNextPrev').removeClass('hidden');
        $('.filtering').prop('disabled', true);
        $('.prevBtn').prop('disabled', true);
        $('.nextBtn').prop('disabled', true);

        var pageNumber = parseInt($('#pageNumber').val());
        if(dir == 'next'){
            pageNumber += 1;
        } else if(dir == 'prev' && pageNumber >1 ){
            pageNumber -= 1;
        }
           
        var user_id = $('#user_id').val();
        var date_range = $('#reportrange1').val();
        var pageSize= $('#page_size').val();
       
        var submit_url = _baseURL+"reports/management/activityLogs";
        if(date_range !=''){
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, user_id:user_id, date_range:date_range, pageNumber:pageNumber, page_size:pageSize},
                dataType: 'JSON',
                success: function(res) {
                    $('#resultData').html(res.info);

                    $('#pageNumber').val(res.pageNumber);

                    $('#info').html("Page "+res.pageNumber+", Showing "+(((res.pageNumber-1)*res.page_size)+1)+" - "+(res.pageNumber*res.page_size)+" of "+res.total);
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
                    $('.filtering').prop('disabled', false);
                },error: function() {

                }
            });
        }else{
            $('.filtering').prop('disabled', true);
            toastr.warning('<?php echo get_notify('Please_fillup_all_fields');?>', '<?php echo get_phrases(['required', 'missing']);?>');
        }
    }
</script>