
<div class="row">
     <div class="col-md-12 col-lg-12">

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
                       <?php if($permission->method('add_benefits','create')->access()){?>
                       <a href="<?php echo base_url('human_resources/payroll/add_benefits')?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add','benefits'])?></a>
                     <?php }?>
                      
                    </div>
                </div>
            </div>

             <div class="card-body">

                  <div class="table-responsive">
                    <table id="BenefitList" class="table display datatable table-bordered table-striped table-hover compact" id="example" width="100%">
                     
                      <thead>
                        <tr>
                          <th><?php echo get_phrases(['sl'])?></th>
                          <th class="text-center"><?php echo get_phrases(['benefit','name'])?></th>
                          <th class="text-center"><?php echo get_phrases(['benefit','type'])?></th>
                          <th class="text-center"><?php echo get_phrases(['status'])?></th>
                          <th class="text-center"><?php echo get_phrases(['action'])?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        if($list){
                          $sl = 1;
                        foreach($list as $data){?>
                        <tr>
                          <td><?php echo$sl++;?></td>
                          <td class="text-center"><?php echo $data['benefit_name']?></td>
                          <td class="text-center"><?php echo ($data['benefit_type'] == 1?get_phrases(['add']):get_phrases(['deduct']))?></td>
                          <td class="text-center"><?php echo ($data['status'] ==1?get_phrases(['active']):get_phrases(['inactive']))?></td>
                          <td class="text-center">
                             <?php if($permission->method('benefit_list','update')->access()){?>
                            <a href="<?php echo base_url('human_resources/payroll/edit_benefit/'.$data['id'])?>" class="btn btn-info-soft"><i class="fas fa-edit" aria-hidden="true"></i></a>
                          <?php }?>
                          <?php if($permission->method('benefit_list','delete')->access()){?>
                            <a onclick="return confirm('Are You Sure?')" href="<?php echo base_url('human_resources/payroll/delete_benefit/'.$data['id'])?>" class="btn btn-danger-soft"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>
                          <?php }?>
                          </td>
                        </tr>
                      <?php }}?>
                      </tbody>
                    </table>
                  </div>
            </div>
            
        </div>
    </div>
</div>

                 

<script type="text/javascript">
    
    $(document).ready(function() { 
       "use strict";

       $('#BenefitList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [4] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    className: 'btn-light',
                    title : 'Benefit_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    className: 'btn-light',
                    title : 'Benefit_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    className: 'btn-light',
                    title : 'Benefit_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2]
                    }
                }
                
          ],

        });


    });

</script>