
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-bd lobidrag">
                     <div class="card-header">
                     <div class="d-flex justify-content-between align-items-center"> 
                     <?php echo $title;?> 
                     </div>
                    </div>
                   
                    <div class="card-body">
                         <?php echo form_open("role/update_role") ?>
                          <div class="form-group row">
                                <label for="type" class="col-sm-3 col-form-label"><?php echo get_phrases(['role_name']) ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-6">
                                    <input type="text" value="<?php echo  $role['0']->type;?>" tabindex="2" class="form-control" name="role_id" id="type" placeholder="<?php echo get_phrases(['role_name']) ?>" required />
                                </div>
                            </div>
                            <input type="hidden" name="rid" value="<?php echo $role['0']->id?>">

                          <?php
                          $this->db = db_connect();
                          $m=0;
            foreach($modules as $key=>$value) {
                 $account_sub = $this->db->table('sub_module')
                        ->select("*")
                        ->where('mid', $value->id)
                        ->get()
                        ->getResult();

                ?>
                <br>
                <table class="table table-bordered hidetable">
                    <h4 class="hidetable"><?php echo get_phrases([$value->name]);?></h4>
                    <thead>
                    <tr>
                        <th><?php echo get_phrases(['sl_no']);?></th>
                        <th><?php echo get_phrases(['module_name']);?></th>
                        <th><?php echo get_phrases(['create']);?></th>
                        <th><?php echo get_phrases(['read']);?></th>
                        <th><?php echo get_phrases(['update']);?></th>
                        <th><?php echo get_phrases(['delete']);?></th>
                    </tr>
                    </thead>
                    <?php $sl = 0 ?>
                    <?php if (!empty($account_sub)) { ?>
                        <?php
                        foreach ($account_sub as $key1 => $module_name){
                        $ck_data = $this->db->table('role_permission')
                        ->select("*")
                        ->where('fk_module_id',$module_name->id)
                        ->where('role_id',$role['0']->id)
                        ->get()
                        ->getRow();
                            ?>
                            <?php
                            $createID = 'id="create'.$m.''.$sl.'"';
                            $readID   = 'id="read'.$m.''.$sl.'"';
                            $updateID = 'id="update'.$m.''.$sl.'"';
                            $deleteID = 'id="delete'.$m.''.$sl.'"';
                            ?>
                            <tbody>
                            <tr>
                                <td><?php echo ($sl+1) ?></td>
                                <td>
                                    <?php echo get_phrases([$module_name->name])?>
                                    <input type="hidden" name="fk_module_id[<?php echo $m?>][<?php echo $sl?>][]" value="<?php echo $module_name->id ?>" id="id_<?php echo $module_name->id ?>">
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                        <input type="checkbox" name="create[<?php echo $m?>][<?php echo $sl ?>][]" value="1" <?php echo ((@$ck_data->create==1)?"checked":null) ?> id="create[<?php echo $m?>]<?php echo $sl?>">
                                        <label for="create[<?php echo $m ?>]<?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                        <input type="checkbox" name="read[<?php echo $m?>][<?php echo $sl ?>][]" value="1" <?php echo ((@$ck_data->read==1)?"checked":null) ?> id="read[<?php echo $m?>]<?php echo $sl?>">
                                        <label for="read[<?php echo $m ?>]<?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                        <input type="checkbox" name="update[<?php echo $m?>][<?php echo $sl ?>][]" value="1" <?php echo ((@$ck_data->update==1)?"checked":null) ?> id="update[<?php echo $m?>]<?php echo $sl?>">
                                        <label for="update[<?php echo $m ?>]<?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                        <input type="checkbox" name="delete[<?php echo $m?>][<?php echo $sl ?>][]" value="1" <?php echo ((@$ck_data->delete==1)?"checked":null) ?> id="delete[<?php echo $m?>]<?php echo $sl?>">
                                        <label for="delete[<?php echo $m ?>]<?php echo $sl ?>"></label>
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

    


