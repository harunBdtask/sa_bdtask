<div class="row">
    <div class="col-sm-12">
        <?php if($permission->method('user_wise_report', 'create')->access() || $permission->method('user_wise_report', 'read')->access()){ ?>
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
                <?php echo form_open_multipart('account/reports/getUserReports', 'id="userRForm"');?>
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <?php echo form_dropdown('branch_id','','','class="custom-select" id="branch_id" required="required"');?>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <?php echo form_dropdown('employee_id','','','class="custom-select" id="employee_id" required="required"');?>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1"><?php echo get_phrases(['filter']);?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close();?>
            </div>
      
        </div>
        <?php } else if( session('branchId') == '' || session('branchId') == 0 ){ ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong class="fs-20 text-danger"><?php echo get_notify('you_have_to_switch_to_a_specific_branch');?></strong>
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

        // search employee
        $('#employee_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'user']);?>',
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

    });
</script>