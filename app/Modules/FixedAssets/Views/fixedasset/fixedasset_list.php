    <?php $assetmodel = new \App\Modules\FixedAssets\Models\Fixedasset_model();?>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card ">
            <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo get_phrases(['fixedasset','list'])?></h6>
                                </div>
                                <div class="text-right">
                                  <?php if($permission->method('add_expense_item','create')->access()){?>  
                                   <a href="<?php echo base_url('expense/add_expense_item')?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add','expense','item'])?></a>
                                 <?php }?>
                                 
                                </div>
                            </div>
                        </div>
            <div class="card-body">

                <div class="table">
                    <table class="table display table-bordered table-striped table-hover custom-table" id="example">
                        <thead>
                            <tr>
                            <th><?php echo get_phrases(['sl','no']) ?></th>
                             <th><?php echo get_phrases(['parent','asset']) ?></th>
                            <th><?php echo get_phrases(['asset','name']) ?></th>
                            <th><?php echo get_phrases(['action']) ?>
                              
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                          $sl = 1;
                           if($assets){?>
                            <?php foreach($assets as $item){
                               $childheads =  $assetmodel->findAll_childassets($item->HeadName);
                                ?>
                            <tr>
                              <td><?php echo $sl++;?></td>
                              <td><?php echo $item->PHeadName;?></td>
                              <td><?php echo $item->HeadName;?></td>
                           
                              <td>
                                <?php if($permission->method('fixedasset_list','update')->access()){?>  
                                <a href="<?php echo base_url().'/fixedasset/edit_fixedasset/'.$item->HeadCode?>" class="btn btn-success-soft btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fas fa-edit" aria-hidden="true"></i></a>
                              <?php }?>
                               <?php if($permission->method('fixedasset_list','delete')->access()){?> 
                               <a href="<?php echo base_url().'/fixedasset/delete_fixedasset/'.$item->HeadCode?>" onclick="return confirm('Are You Sure?')" class="btn btn-danger-soft btn-sm" data-toggle="tooltip" data-placement="left" title="Delete"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                             <?php }?>
                              </td>
                            </tr>

                            <?php if($childheads){
                                foreach($childheads as $childs){?>
                            <tr>
                              <td><?php echo $sl++;?></td>
                              <td><?php echo $childs->PHeadName;?></td>
                              <td><?php echo $childs->HeadName;?></td>
                              <td>
                                <?php if($permission->method('fixedasset_list','update')->access()){?>  
                                <a href="<?php echo base_url().'/fixedasset/edit_fixedasset/'.$childs->HeadCode?>" class="btn btn-success-soft btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fas fa-edit" aria-hidden="true"></i></a>
                              <?php }?>
                               <?php if($permission->method('fixedasset_list','delete')->access()){?> 
                               <a href="<?php echo base_url().'/fixedasset/delete_fixedasset/'.$childs->HeadCode?>" onclick="return confirm('Are You Sure?')" class="btn btn-danger-soft btn-sm" data-toggle="tooltip" data-placement="left" title="Delete"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                             <?php }?>
                              </td>
                            </tr>
                            <?php }}?>
                          <?php }?>
                          <?php }else{?>
                   <tr><td colspan="3" class="text-center"><b>No Data Found</b></td></tr>
                          <?php }?>
                        </tbody>
                         
                    </table>
                    
                </div>
            </div> 
        </div>
    </div>
</div>

  