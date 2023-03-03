<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <a class="btn btn-info text-white" href="<?php echo base_url("settings/languages") ?>"> <i class="fa fa-list"></i>  <?php echo get_phrases(['language', 'list']);?></a> 
                </div>
            </div>
            <div class="card-body"> 
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td colspan="2">
                                <?php echo  form_open('settings/add_phrase', ' class="form-inline" ') ?> 
                                    <div class="form-group">
                                        <label class="sr-only" for="addphrase"> <?php echo get_phrases(['phrase', 'name']);?></label>
                                        <input name="phrase" type="text" class="form-control" id="addphrase" placeholder="<?php echo get_phrases(['phrase', 'name']);?>">
                                    </div>
                                    <div class="form-group m-r-10">
                                        <button type="submit" class=" btn btn-success"><?php echo get_phrases(['save']);?></button>
                                    </div>
                                <?php echo  form_close(); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-th-list"></i></th>
                            <th><?php echo get_phrases(['phrase']);?></th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($phrases)) {?>
                            <?php $sl = 1 ?>
                            <?php foreach ($phrases as $value) {?>
                            <tr>
                                <td><?php echo  $sl++ ?></td>
                                <td><?php echo  $value['phrase'] ?></td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                  </table>  
                <div class="d-flex justify-content-end .dataTables_wrapper">

                    <?= $pager->makeLinks((!empty($page_num)?$page_num:1), 10, (!empty($total_phrase->total)?$total_phrase->total:0), 'default_full') ?>
                </div>
            </div>
         

        </div>
    </div>
</div>