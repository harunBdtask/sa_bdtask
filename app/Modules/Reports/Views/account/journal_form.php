<div class="row">
    <div class="col-sm-12">
        <?php if($permission->method('journal_report', 'create')->access() || $permission->method('journal_report', 'read')->access()){ ?>
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
                    <div class="col-md-5 col-sm-12">
                        <div class="form-group">
                            <?php echo form_dropdown('user_id','','','class="custom-select" id="user_id"');?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>" required>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1 userIBtn"><?php echo get_phrases(['filter', 'report']);?></button>
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

<!-- view voucher details modal -->
<div class="modal fade bd-example-modal-xl" id="viewVoucherModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="viewVoucherModalLabel"><?php echo get_phrases(['voucher', 'details']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printJV">
               <div class="row">
                   <div class="col-md-12" id="viewVoucherResult"></div>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" class="btn btn-purple" onclick="printContent('printJV')"><i class="fa fa-print"></i> <?php echo get_phrases(['print']);?></button>
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');

        // search employee
        $('#user_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'user', 'name']);?>',
            minimumInputLength: 2,
                ajax: {
                    url: _baseURL+'auth/searchUserName',
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
        // }).on('select2:select', function(e){
        //     var dat = e.params.data;
        //     $('#pname').val(dat.text);
        });

        // transaction head code list
        // $.ajax({
        //     type:'GET',
        //     url: _baseURL+'account/reports/getTransHead',
        //     dataType: 'json',
        //     data:{'csrf_stream_name':csrf_val},
        // }).done(function(data) {
        //     $("#head_code").select2({
        //         placeholder: '<?php echo get_phrases(['select', 'account', 'name']);?>',
        //         data: data
        //     });
        // });

         // get patient info by ID
        $('.userIBtn').on('click', function(e){
            e.preventDefault();

            var userId = $('#user_id').val();
            var date   = $('#reportrange1').val();
            if(date && userId){
                var submit_url = _baseURL+"reports/account/getJournalReport"; 
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, user_id:userId, date_range:date},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#results').html('');
                        $('#results').html(res.data);
                        $('#title').text('');
                        $('#title').text(date);
                    }
                });  
            }else{
                if(date ==''){
                    alert('Please select the date range!');
                }
                if(userId ==''){
                    alert('Please select the user name!');
                }
            }
            
        });

        // income data export
        $(document).on('click', '.export', function(e){
            e.preventDefault();

            var userId = $('#user_id').val();
            var date   = $('#reportrange1').val();
            var submit_url = _baseURL+"reports/account/exportJVReports"; 
            if(date){
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, user_id:userId, date_range:date},
                    dataType: 'JSON',
                    success: function(response) {
                        // window.open(response.url, '_self');
                        window.open(response.url, '_blank');
                    }
                });  
            }else{
                alert('Please select the date range!');
            }
        });

        // view voucher info
        $(document).on('click', '.viewVoucher', function(e){
            e.preventDefault();
            $('#viewVoucherModal').modal('show');
            var Id = $(this).attr('data-id');
    
            if(Id){
                var submit_url = _baseURL+"reports/management/getVoucherDetails"; 
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, voucherId:Id},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#viewVoucherResult').html('');
                        $('#viewVoucherResult').html(res.data);
                    }
                });  
            }else{
                alert('Wrong voucher!')
            }
            
        });

    });
</script>