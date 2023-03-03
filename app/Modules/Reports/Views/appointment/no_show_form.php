<div class="row">
    <div class="col-sm-12">
        <?php 
        if($permission->method('appoint_no_show_report', 'create')->access() || $permission->method('appoint_no_show_report', 'read')->access()){ ?>
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
                    <input type="hidden" id="ex_doctor_id">
                    <input type="hidden" id="ex_date_range">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="reportrange1" class="col-form-label font-weight-600"><?php echo get_phrases(['date', 'range']) ?></label>
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date', 'range']);?>" required="required">
                        </div>
                    </div>
                    <?php if(session('user_role') !=14){ ?>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="doctor_id" class="col-form-label font-weight-600"><?php echo get_phrases(['doctor', 'name']) ?></label>
                            <?php echo form_dropdown('doctor_id[]','','','class="custom-select" id="doctor_id" multiple');?>
                        </div>
                    </div>
                    <?php }?>
                    <div class="col-lg-1 col-md-2 col-sm-12">
                        <div class="form-check mt-4 py-3">
                            <input type="checkbox" class="form-check-input" id="checkbox" >
                            <label class="form-check-label text-muted" for="checkbox"><?php echo get_phrases(['selected', 'all']);?></label>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-12">
                        <div class="form-group mt-4 py-3">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1 filtering"><?php echo get_phrases(['filter']);?></button>
                        </div>
                    </div>
                </div>
                <div id="resultData">
                    
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
    
    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');
        
        // doctor list
        $.ajax({
            type:'POST',
            url: _baseURL+'auth/doctorList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#doctor_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'doctor', 'name']);?>',
                data: data
            });
        });

        $('.filtering').on('click', function(e){
             var doctor_id = $('#doctor_id').val();
        
            var date_range= $('#reportrange1').val();
            
            var submit_url = _baseURL+"reports/appointment/getDetailsNoShow";
            if(date_range !=''){
                preloader_ajax();
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, doctor_id:doctor_id, date_range:date_range},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#resultData').html('');
                        $('#resultData').html(res.info);
                    },error: function() {

                    }
                });
            }else{
                toastr.warning('<?php echo get_notify('Please_fillup_all_fields');?>', '<?php echo get_phrases(['required', 'missing']);?>');
            }
        });

        $(document).on('click', '.noShowTr', function(e){
            e.preventDefault();
            onclick_change_bg('#no_show_details', this, 'cyan');
        });

        $("#checkbox").on('click', function(){
            if($("#checkbox").is(':checked') ){
                var selectedItems = [];
                var allOptions = $("#doctor_id option");
                allOptions.each(function() {
                    if($(this).val() !=''){
                        selectedItems.push($(this).val());
                    }
                });
                $("#doctor_id").val(selectedItems).trigger("change"); 
            } else { //deselect all
                $("#doctor_id").find('option').prop("selected",false);
                $("#doctor_id").trigger('change');
            }
        });

    });

</script>