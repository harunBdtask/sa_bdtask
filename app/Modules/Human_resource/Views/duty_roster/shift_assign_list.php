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
                        <?php if($hasReadAccess){ ?>
                            <a href="<?php echo base_url("human_resources/duty_roster/shiftRosterList") ?>"><button type="button" class="btn btn-success btn-sm mr-1"><i class="ti-align-justify mr-1" aria-hidden="true"></i><?php echo get_phrases(['assign', 'roster','list']);?></button></a>
                        <?php } ?>
                        <?php if($hasCreateAccess){ ?>
                            <a href="<?php echo base_url("human_resources/duty_roster/roster_shift_assign") ?>"><button type="button" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['assign', 'roster']);?></button></a>
                        <?php } ?>
                       <a href="javascript:void(0);" onclick="goBackOnePage()" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="EmpRosterWorkList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['assigned','employee']);?></th>
                            <th><?php echo get_phrases(['shift', 'name']);?></th>
                            <th><?php echo get_phrases(['shift','start']);?></th>
                            <th><?php echo get_phrases(['shift','end']);?></th>
                            <th><?php echo get_phrases(['action']);?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($sftasn_datalist)) { ?>

                            <?php 

                                $sl = 1;

                            ?>

                            <?php foreach ($sftasn_datalist as $row) {

                            ?>
                            <tr class="">
                                <td><?php echo $sl; ?></td>
                                <td><?php echo $row->first_name.' '.$row->last_name; ?></td>
                                <td><?php echo $row->shift_name; ?></td>
                                <td><?php echo $row->roster_start; ?></td>
                                <td><?php echo $row->roster_end; ?></td>
                              
                                <td class="center">

                                    <?php if ($hasDeleteAccess) : ?>
                                   <!--  <a href="<?php echo base_url("human_resources/duty_roster/delete_shiftassign/". $row->roster_id.'/'. $row->emp_id) ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('<?php echo get_phrases(['are','you','sure']);?>')"
                                        title="Delete "><i class="ti-trash" aria-hidden="true"></i>
                                    </a> -->
                                    <a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="<?php echo get_phrases(['delete']);?>" data-roster-id="<?php echo $row->roster_id;?>" data-emp-id="<?php echo $row->emp_id;?>" ><i class="far fa-trash-alt"></i></a>
                                    <?php endif; ?>

                                </td>

                            </tr>
                            <?php $sl++; ?>

                            <?php }; ?>

                        <?php } ?>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function() { 
       "use strict";

       $('#EmpRosterWorkList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 1, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [3] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    className: 'btn-light',
                    title : 'Roster_Assign_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                <?php } if($hasExportAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    className: 'btn-light',
                    title : 'Roster_Assign_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    className: 'btn-light',
                    title : 'Roster_Assign_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    className: 'btn-light',
                    title : 'Roster_Assign_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    className: 'btn-light',
                    title : 'Roster_Assign_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    }
                }
                <?php } ?>
          ],

        });


       // Delete EmpRosterWork shift reocrds

        $('#EmpRosterWorkList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var roster_id = $(this).attr('data-roster-id');
            var emp_id = $(this).attr('data-emp-id');

            // console.log('roster_id: '+roster_id+' , emp_id'+emp_id);
            
            var submit_url = _baseURL+"human_resources/duty_roster/delete_shiftassign";

            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){

                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {
                        'csrf_stream_name':csrf_val,
                        'roster_id':roster_id,
                        'emp_id':emp_id,
                    },
                    dataType: 'JSON',
                    success: function(res) {

                        // console.log(res);

                        if(res.success==true){
                            toastr.success(res.message, 'Roster Assign');
                            location.reload();
                        }else{
                            toastr.error(res.message, 'Roster Assign');
                        }
                        
                    },error: function() {

                    }
                });

            }

        });

    });

</script>