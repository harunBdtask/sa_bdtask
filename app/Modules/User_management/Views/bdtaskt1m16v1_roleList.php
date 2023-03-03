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
                       <a href="<?php echo base_url('permission/roles/add');?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'role']);?></a>
                       <a href="<?php echo previous_url();?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th>#<?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['role', 'name']);?></th>
                            <th><?php echo get_phrases(['title']);?></th>
                            <th><?php echo get_phrases(['action']);?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($roles)){ ?>
                            <?php foreach ($roles as $key => $value) {
                               ?>
                               <tr>
                                   <td><?php echo esc($value->id);?></td>
                                   <td><?php echo esc($value->type);?></td>
                                   <td><?php echo esc($value->title);?></td>
                                   <td>
                                       <a href="<?php echo base_url('permission/roles/edit').'/'.$value->id;?>" class="btn btn-info-soft btnC mr-1 custool" title="<?php echo get_phrases(['update']);?>"><i class="far fa-edit"></i></a>
                                       <a href="javascript:void(0);" class="btn btn-danger-soft btnC mr-1 custool deleteAction" data-id="<?php echo $value->id;?>" title="<?php echo get_phrases(['delete']);?>"><i class="fa fa-trash"></i></a>
                                   </td>
                               </tr>
                        <?php }}?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() { 
       "use strict";
    
        // delete department
        $('.deleteAction').on('click', function(e){
            e.preventDefault();
            var tr = $(this).parent().parent();
            var id = $(this).attr('data-id');
            $('.custool').tooltip('hide');

            var submit_url = _baseURL+"permission/roles/delete/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            tr.remove();
                        }else{
                            toastr.error(res.message, res.title);
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });
</script>