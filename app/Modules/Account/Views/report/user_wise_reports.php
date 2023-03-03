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
                      
                    </div>
                </div>
            </div>
            <div class="card-body" id="printC">
                <?php echo form_open_multipart('account/reports/getUserReports', 'id="userRForm"');?>
                <div class="row no-print">
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
                            <input type="text" name="date_range" id="reportrange" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1"><?php echo get_phrases(['filter']);?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close();?>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <h4><center><?php echo esc($dates);?></center></h4>
                        <div class="table-responsive">
                            <table class="table table-stripped table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="10%"><?php echo get_phrases(['account', 'code']);?></th>
                                         <th class="text-center" width="15%"><?php echo get_phrases(['name']);?></th>
                                        <th class="text-center" width="28%"><?php echo get_phrases(['description']);?></th>
                                       
                                        <th class="text-right" width="10%"><?php echo get_phrases(['debit']);?></th>
                                        <th class="text-right" width="10%"><?php echo get_phrases(['credit']);?></th>
                                        <th class="text-center" width="12%"><?php echo get_phrases(['created', 'date']);?></th>
                                        <th class="text-center" width="15%"><?php echo get_phrases(['created', 'by']);?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $debitT = 0;
                                    $creditT = 0;
                                    if(!empty($reports)){
                                        foreach ($reports as $value) { 
                                            $debitT += $value->Debit;
                                            $creditT += $value->Credit;
                                        ?>
                                            <tr>
                                                <td><?php echo esc($value->COAID);?></td>
                                                <td><?php echo esc($value->HeadName);?></td>
                                                <td><?php echo esc($value->Narration);?></td>
                                                <td class="text-right"><?php echo esc(number_format($value->Debit, 2));?></td>
                                                <td class="text-right"><?php echo esc(number_format($value->Credit, 2));?></td>
                                                <td><?php echo esc($value->CreateDate);?></td>
                                                <td><?php echo esc($value->created_by);?></td>
                                            </tr>
                                    <?php } }else{ ?>
                                        <tr>
                                            <th colspan="7" class="text-center"><?php echo get_notify('data_is_not_available');?></th>
                                        </tr>
                                    <?php }?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th class="text-right" id="debitT"><?php echo number_format($debitT, 2);?></th>
                                        <th class="text-right" id="creditT"><?php echo number_format($creditT, 2);?></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer no-print">
                    <?php if($permission->method('user_wise_report', 'create')->access()){ ?>
                    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
                    <?php }?>
            </div>
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
    $(document).ready(function(){
        "use strict";
        $('option:first-child').val('').trigger('change');

        // search employee
        $('#employee_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'user']);?>',
            minimumInputLength: 1,
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