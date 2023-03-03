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
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
             <div class="card-body">

                <!-- language -->  
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><i class="fa fa-th-list"></i></th>
                            <th><?php echo get_phrases(['language']);?></th>
                            <th><i class="fa fa-cogs"></i></th>
                        </tr>
                    </thead>


                    <tbody>
                      <?php if (!empty($languages)) {?>
                            <?php $sl = 1 ?>
                            <?php foreach ($languages as $key => $language) {?>
                            <tr>
                                <td><?php echo  $sl++ ?></td>
                                <td><?php echo  ucfirst($language) ?></td>
                                <td>
                                    <a href="<?php echo  base_url("settings/edit_phrase/$language") ?>" class="btn-icon btn-success-soft btn-sm mr-1"><i class="fa fa-edit"> <?php echo get_phrases(['label']);?></i></a> 
                                    <a href="<?php echo  base_url("settings/edit_message_phrase/notify/$language") ?>" class="btn-icon btn-info-soft btn-sm mr-1"><i class="fa fa-edit"> <?php echo get_phrases(['message']);?></i></a>  
                                    <a href="<?php echo  base_url("settings/edit_message_phrase/api/$language") ?>" class="btn-icon btn-purple btn-sm"><i class="fa fa-edit"> <?php echo get_phrases(['api', 'message']);?></i></a>  
                                </td> 
                            </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody> 
                </table>  
  
            </div>
        </div>
    </div>
</div>

