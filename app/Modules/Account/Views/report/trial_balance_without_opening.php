<?php
$hasPrintAccess  = $permission->method('trial_balance', 'print')->access();
$hasExportAccess = $permission->method('trial_balance', 'export')->access();
$this->db = db_connect();
?>
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
            <div class="card-body" id="printDiv">
               <center>
                <h3 class="mb-0"><?php echo get_notify('trial_balance_on');?></h3>
                <h5 class="mt-0"><?php echo $dateRange; ?></h5>
              </center>
               <table id="balanceList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                          <th width="20%"><?php echo get_phrases(['account', 'code']);?></th>
                          <th width="25%"><?php echo get_phrases(['account','name']);?></th>
                          <th width="15%" class="text-right"><?php echo get_phrases(['opening','balance']);?></th>
                          <th width="15%" class="text-right"><?php echo get_phrases(['debit']);?></th>
                          <th width="15%" class="text-right"><?php echo get_phrases(['credit']);?></th>
                          <th width="10%" class="text-right"><?php echo get_phrases(['closing']);?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                <div class="row no-print">
                  <div class="col-sm-12">
                    <?php if($hasExportAccess){ ?>
                    <a href="<?php echo base_url('account/reports/exportTrialBalance?branch_id='.$branch_id.'&from_date='.$dtpFromDate.'&to_date='.$dtpToDate);?>" class="btn btn-purple"><span class="fa fa-download"></span> <?php echo get_phrases(['export','excel']);?></a>
                    <?php }?>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        "use strict";

        $('#balanceList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "lengthMenu": [ [15, 30, 50, 100, 500, 1000], [15, 30, 50, 100, 500, 1000] ],
             "aaSorting": [[ 0, "ASC" ]],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
              <?php if($hasPrintAccess){ ?>
              {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'Trial_Balance_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                <?php } if($hasExportAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Trial_Balance_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Trial_Balance_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Trial_Balance_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Trial_Balance_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                }
              <?php }?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'account/reports/trialBalanceData',
               'type':'POST',
               'data': function ( d ) {
                    d.csrf_stream_name = csrf_val;
                    d.from_date = '<?php echo $dtpFromDate;?>';
                    d.to_date = '<?php echo $dtpToDate;?>';
                    d.branch_id = '<?php echo $branch_id;?>';
                }
            },
          'columns': [
             { data: 'HeadCode' },
             { data: 'HeadName' },
             { data: 'opening', className: "text-right" },
             { data: 'debit' , className: "text-right"},
             { data: 'credit', className: "text-right" },
             { data: 'closing', className: "text-right" }
          ],
        });

    });
</script>