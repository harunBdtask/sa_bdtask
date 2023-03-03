 <div class="row">
        <div class="col-sm-12">
            <div class="card card-bd lobidrag">
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
                           <a href="<?php echo base_url('permission/roles');?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-list mr-1"></i><?php echo get_phrases(['role', 'list']);?></a>
                           <a href="<?php echo previous_url();?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                        </div>
                    </div>
                </div>
                <?php echo form_open('permission/roles/save', 'class="ajaxForm needs-validation" novalidate="" data="showCallBackData"') ?>
                <div class="card-body">
                     <div class="form-group row">
                        <label for="type" class="col-sm-2 col-form-label"><?php echo get_phrases(['role', 'name']) ?> <i class="fa fa-info-circle text-info custool" title="Max 100 Character"></i> <i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="role_id" id="type" maxlength="100" placeholder="<?php echo get_phrases(['enter', 'role','name']) ?>" required />
                        </div>
                        <label for="type" class="col-sm-1 col-form-label"><?php echo get_phrases(['role', 'title']) ?> <i class="fa fa-info-circle text-info custool" title="Max 100 Character"></i></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="title" id="title" maxlength="100" placeholder="<?php echo get_phrases(['enter', 'role','title']) ?>" />
                        </div>
                    </div>
             <?php
            $m=0;
            foreach($accounts as $key=> $value) {?>
                <table class="table table-bordered text-center">
                    <h2 class=""><?php echo $value['nameE'];?></h2>
                    <thead>
                    <tr>
                        <th><b>#</b></th>
                        <th><?php echo get_phrases(['module', 'menu']);?></th>
                        <th><?php echo get_phrases(['select', 'all']);?> </th>
                        <th><?php echo get_phrases(['create']);?> </th>
                        <th><?php echo get_phrases(['read']);?></th>
                        <th><?php echo get_phrases(['update']);?> </th>
                        <th><?php echo get_phrases(['delete']);?></th>
                        <th><?php echo get_phrases(['print']);?></th>
                        <th><?php echo get_phrases(['export']);?></th>
                    </tr>
                    </thead>
                    <?php

                     $sl = 0 ?>
                    <?php if (!empty($value['subModule'])) { ?>
                        <?php foreach ($value['subModule'] as $key1 => $module_name) { ?>

                            <?php
                            $createID = 'id="create'.$m.''.$sl.'" class="create'.$m.'"';
                            $readID   = 'id="read'.$m.''.$sl.'" class="read'.$m.'"';
                            $updateID = 'id="update'.$m.''.$sl.'" class="edit'.$m.'"';
                            $deleteID = 'id="delete'.$m.''.$sl.'" class="delete'.$m.'"';
                            $printID = 'id="print'.$m.''.$sl.'" class="print'.$m.'"';
                            $exportID = 'id="export'.$m.''.$sl.'" class="export'.$m.'"';
                            $class = 'all'.$m.$sl;
                            ?>
                            <tbody>
                            <tr>
                                <td><?php echo ($sl+1) ?></td>
                                <td>
                                    <?php echo $module_name->nameE;?>
                                    <input type="hidden" name="fk_module_id[<?php echo $m?>][<?php echo $sl?>][]"  value="<?php echo $module_name->id ?>" id="id_<?php echo $module_name->id ?>">
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                    <input type="checkbox" name="all[<?php echo $m?>][<?php echo $sl?>][]" value="<?php echo $m.$sl;?>" id="<?php echo $class;?>" class="select_all">  
                                    <label for="<?php echo $class ?>"></label>
                                </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                         <input type="checkbox" name="create[<?php echo $m?>][<?php echo $sl?>][]" value="1" class="<?php echo $class;?>" <?php echo $createID?>>  
                                        <label for="create<?php echo $m ?><?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                         <input type="checkbox" name="read[<?php echo $m?>][<?php echo $sl?>][]" value="1" class="<?php echo $class;?>" <?php echo $readID?>>
                                        <label for="read<?php echo $m ?><?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                        <input type="checkbox" name="update[<?php echo $m?>][<?php echo $sl?>][]" value="1" class="<?php echo $class;?>" <?php echo $updateID?>>
                                        <label for="update<?php echo $m ?><?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                         <input type="checkbox" name="delete[<?php echo $m?>][<?php echo $sl?>][]" value="1" class="<?php echo $class;?>" <?php echo $deleteID?>>
                                        <label for="delete<?php echo $m ?><?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                         <input type="checkbox" name="print[<?php echo $m?>][<?php echo $sl?>][]" value="1" class="<?php echo $class;?>" <?php echo $printID?>>
                                        <label for="print<?php echo $m ?><?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                         <input type="checkbox" name="export[<?php echo $m?>][<?php echo $sl?>][]" value="1" class="<?php echo $class;?>" <?php echo $exportID?>>
                                        <label for="export<?php echo $m ?><?php echo $sl ?>"></label>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            <?php $sl++ ?>
                        <?php } ?>
                    <?php } //endif ?>
                </table>
                <?php $m++; } ?>
                <div class="form-group text-right">
                    <button type="reset" class="btn btn-primary w-md m-b-5"><?php echo get_phrases(['reset']) ?></button>
                    <button type="submit" class="btn btn-success w-md m-b-5 modal_action actionBtn"><?php echo get_phrases(['save']) ?></button>
                </div>
            <?php echo form_close() ?>
            </div>
           
        </div>
    </div>
</div>
<script>
var showCallBackData = function(){
    $('.ajaxForm')[0].reset();
    $('.ajaxForm').removeClass('was-validated');
}

$(document).ready(function(){


    $(".select_all").on("change",function(){  
        var id = $(this).val();
        $(".all"+id).prop("checked", $(this).prop("checked"));
    });
})</script>