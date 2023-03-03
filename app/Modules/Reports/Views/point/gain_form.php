<div class="row">
    <div class="col-sm-12">
        <?php 
        if($permission->method('gain_points_report', 'create')->access() || $permission->method('gain_points_report', 'read')->access()){ ?>
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
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']) ?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2 col-sm-6">
                        <div class="form-group">
                            <label for="branch_id" class="col-form-label font-weight-600"><?php echo get_phrases(['branch', 'name']) ?></label>
                            <?php echo form_dropdown('branch_id','','','class="custom-select" id="branch_id"');?>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="form-group">
                            <label for="reportrange1" class="col-form-label font-weight-600"><?php echo get_phrases(['date', 'range']) ?> <i class="text-danger">*</i></label>
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date', 'range']);?>" required="required">
                        </div>
                    </div>
                     <div class="col-lg-2 col-sm-6">
                        <div class="form-group">
                            <label for="doctor_id" class="col-form-label font-weight-600"><?php echo get_phrases(['doctor', 'name']) ?></label>
                            <?php echo form_dropdown('doctor_id','','','class="custom-select" id="doctor_id"');?>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="form-group">
                            <label for="patient_id" class="col-form-label font-weight-600"><?php echo get_phrases(['patient', 'name']) ?></label>
                            <?php echo form_dropdown('patient_id','','','class="custom-select" id="patient_id"');?>
                        </div>
                    </div>
                    <div class="col-lg-1 col-sm-6">
                        <div class="form-group">
                            <label for="column_name" class="col-form-label font-weight-600"><?php echo get_phrases(['column', 'name']) ?></label>
                            <?php 
                                $column = array(
                                    ''=>'', 
                                    'file.file_no'=> get_phrases(['file', 'no']), 
                                    'sid.invoice_id'=> get_phrases(['invoice', 'no']),
                                    'si.invoice_date'=> get_phrases(['invoice', 'date']), 
                                    'sid.gain_points'=> get_phrases(['gain', 'points'])
                                );
                                echo form_dropdown('column_name',$column,'','class="form-control custom-select" id="column_name"');
                            ?>
                        </div>
                    </div>
                     <div class="col-lg-1 col-sm-6">
                        <div class="form-group">
                            <label for="sorting" class="col-form-label font-weight-600"><?php echo get_phrases(['sorting']) ?></label>
                            <?php 
                                $asc_desc = array(''=>'', 'asc'=> get_phrases(['ascending']), 'desc'=> get_phrases(['descending']));
                                echo form_dropdown('sorting',$asc_desc,'','class="form-control custom-select" id="sorting"');
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-1 col-sm-6">
                        <div class="form-group">
                            <label for="page_size" class="col-form-label font-weight-600"><?php echo get_phrases(['page', 'size']) ?></label>
                            <?php 
                                $psize = array(''=>'', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500', '0'=>'All');
                                echo form_dropdown('page_size',$psize,'20','class="form-control custom-select" id="page_size"');
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-1 col-sm-6">
                        <div class="form-group mt-4 py-3">
                             <input type="hidden" name="pageNumber" id="pageNumber" value="1">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1 filtering"><?php echo get_phrases(['filter']);?></button>
                        </div>
                    </div>
                </div>
                <div id="resultData">
                    
                </div>

                <div class="row hidden" id="showNextPrev">
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-sm btn-success rounded-pill mt-1 prevBtn " onclick="get_data('prev')"><?php echo get_phrases(['prev']);?></button>
                    </div>
                    <div class="col-sm-8">
                        <div id="info"></div>
                    </div>
                    <div class="col-sm-2 text-right">
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

<script type="text/javascript">
    var nurseOrcoor = '<?php echo session('user_role');?>';
    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');


        // search employee 
        $('#doctor_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'doctor', 'name']);?>',
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

         // search patient 
        $('#patient_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'patient', 'name']);?>',
            minimumInputLength: 2,
            ajax: {
                url: _baseURL+'auth/searchAllWithFile',
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

        $('.filtering').on('click', function(e){
            get_data('first');
        });

    });

    function get_data(dir){
        $('.prevBtn').prop('disabled', true);
        $('.nextBtn').prop('disabled', true);

        var pageNumber = parseInt($('#pageNumber').val());
        if(dir == 'next'){
            pageNumber += 1;
        } else if(dir == 'prev' && pageNumber >1 ){
            pageNumber -= 1;
        }
           
        var branch_id = $('#branch_id').val();
        var doctor_id = $('#doctor_id').val();
        var service_id = $('#service_id').val();
        var patient_id = $('#patient_id').val();
        var column_name = $('#column_name').val();
        var sorting = $('#sorting').val();
        var date_range= $('#reportrange1').val();
        var pageSize= $('#page_size').val();
       
        var submit_url = _baseURL+"reports/pointing/gainReports";
        if(date_range !=''){
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, branch_id:branch_id, doctor_id:doctor_id, patient_id:patient_id, service_id:service_id, date_range:date_range, pageNumber:pageNumber, page_size:pageSize, column_name:column_name, sorting:sorting},
                dataType: 'JSON',
                success: function(res) {
                    $('#showNextPrev').removeClass('hidden');
                    $('#resultData').html(res.info);

                    $('#pageNumber').val(res.pageNumber);
                    var show = res.pageNumber*res.page_size;
                    if(show > res.total){
                        var fullShow = res.total;
                    }else{
                        var fullShow = res.pageNumber*res.page_size;
                    }

                    $('#info').html("<center>Page "+res.pageNumber+", Showing "+(((res.pageNumber-1)*res.page_size)+1)+" - "+(fullShow)+" of "+res.total+"</center>");
                    if( res.pageNumber > 1  ){
                        $('.prevBtn').prop('disabled', false);
                    } else {
                        $('.prevBtn').prop('disabled', true);
                    }
                    if( (res.pageNumber*res.page_size) < res.total ){
                        if(res.page_size==0){
                            $('.nextBtn').prop('disabled', true);
                        }else{
                            $('.nextBtn').prop('disabled', false);
                        }
                    } else {
                        $('.nextBtn').prop('disabled', true);
                    }
                    $('.filtering').prop('disabled', false);
                },error: function() {

                }
            });
        }else{
            //$('.filtering').prop('disabled', true);
            toastr.warning('<?php echo get_notify('Please_fillup_all_fields');?>', '<?php echo get_phrases(['required', 'missing']);?>');
        }
    }

</script>