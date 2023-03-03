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
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i>Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5 col-sm-12">
                        <div class="form-group">
                             <label for="reportrange" class="col-form-label font-weight-600"><?php echo get_phrases(['select', 'date']) ?></label>
                            <input type="hidden" name="user_id" value="<?php echo session('id');?>">
                            <input type="text" name="date_range" id="reportrange" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>" required>
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
                    <div class="col-md-5 col-sm-12">
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
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() { 
       "use strict";

         // get patient info by ID
        $('.filtering').on('click', function(e){
            e.preventDefault();
            get_data('first');
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
        var date_range = $('#reportrange').val();
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