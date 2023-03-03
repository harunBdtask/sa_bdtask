 <div class="row">
            <div class="col-sm-12">
                <div class="card card-bd lobidrag">
                      <div class="card-header">
                 <div class="d-flex justify-content-between align-items-center"> 
                 <?php echo $title;?> 
                                    </div>
                                    </div>
                    <?php echo form_open("role/save_role") ?>
                    <div class="card-body">
                         <div class="form-group row">
                            <label for="type" class="col-sm-3 col-form-label"><?php echo get_phrases(['role_name']) ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" tabindex="2" class="form-control" name="role_id" id="type" placeholder="<?php echo get_phrases(['role_name']) ?>" required />
                            </div>
                        </div>
             <?php
              $this->db = db_connect();
            $m=0;
            foreach($accounts as $key=>$value) {
                $account_sub = $this->db->table('sub_module')
                        ->select("*")
                        ->where('mid', $value['id'])
                        ->get()
                        ->getResult();
                ?>
                <table class="table table-bordered">
                    <h2 class=""><?php echo get_phrases([$value['name']]);?></h2>
                    <thead>
                    <tr>
                        <th><?php echo get_phrases(['sl_no']);?></th>
                        <th><?php echo get_phrases(['menu_name']);?></th>
                        <th><?php echo get_phrases(['create']);?> </th>
                        <th><?php echo get_phrases(['read']);?></th>
                        <th><?php echo get_phrases(['update']);?> </th>
                        <th><?php echo get_phrases(['delete']);?></th>
                    </tr>
                    </thead>
                    <?php

                     $sl = 0 ?>
                    <?php if (!empty($account_sub)) { ?>
                        <?php foreach ($account_sub as $key1 => $module_name) { ?>

                            <?php
                            $createID = 'id="create'.$m.''.$sl.'" class="create'.$m.'"';
                            $readID   = 'id="read'.$m.''.$sl.'" class="read'.$m.'"';
                            $updateID = 'id="update'.$m.''.$sl.'" class="edit'.$m.'"';
                            $deleteID = 'id="delete'.$m.''.$sl.'" class="delete'.$m.'"';
                            ?>
                            <tbody>
                            <tr>
                                <td><?php echo ($sl+1) ?></td>
                                <td>
                                    <?php echo get_phrases([$module_name->name])?>
                                    <input type="hidden" name="fk_module_id[<?php echo $m?>][<?php echo $sl?>][]"  value="<?php echo $module_name->id ?>" id="id_<?php echo $module_name->id ?>">
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                         <input type="checkbox" name="create[<?php echo $m?>][<?php echo $sl?>][]" value="1"  <?php echo $createID?>>  
                                        <label for="create<?php echo $m ?><?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                         <input type="checkbox" name="read[<?php echo $m?>][<?php echo $sl?>][]" value="1"  <?php echo $readID?>>
                                        <label for="read<?php echo $m ?><?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                        <input type="checkbox" name="update[<?php echo $m?>][<?php echo $sl?>][]" value="1"  <?php echo $updateID?>>
                                        <label for="update<?php echo $m ?><?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                         <input type="checkbox" name="delete[<?php echo $m?>][<?php echo $sl?>][]" value="1"  <?php echo $deleteID?>>
                                        <label for="delete<?php echo $m ?><?php echo $sl ?>"></label>
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
                <button type="submit" class="btn btn-success w-md m-b-5"><?php echo get_phrases(['save']) ?></button>
            </div>
            <?php echo form_close() ?>
                    </div>
                   
                </div>
            </div>
        </div>