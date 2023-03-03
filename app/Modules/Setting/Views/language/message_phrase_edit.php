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
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="languageList" style="width:100%">
                    <thead>
                        <tr>
                            <th><i class="fa fa-th-list"></i></th>
                            <th><?php echo get_phrases(['phrase']);?></th>
                            <th><?php echo ucfirst($language);?></th> 
                            <th><?php echo get_phrases(['action']);?></th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($phrases)){ 
                            $i=1;
                            foreach ($phrases as $key => $value) {
                            ?>
                            <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo ucfirst(str_replace('_', ' ', $key)); ?>
                                </td>
                                <td><input type="text" name="update_data" id="phrase-<?php echo $key; ?>" class="form-control" value="<?php echo esc($value);?>" ></td>
                                <td><button type="button" class="btn btn-success" id="btn-<?php echo $key; ?>" onclick="updatePhrase('<?php echo $key; ?>')"><?php echo get_phrases(['update']);?></button></td>
                            </tr>
                        <?php  $i++;} }?>
                    </tbody>
                </table>
                <input type="hidden" name="label" id="label_name" value="<?php echo $label;?>">
                <input type="hidden" name="language_name" id="language_name" value="<?php echo $language;?>">
            </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>

 <script>
    function updatePhrase(key){
        var updatedValue = $('#phrase-'+key).val();
        var language     = $('#language_name').val();
        var label_name   = $('#label_name').val();
        if(updatedValue){
            $('#btn-'+key).text('...');
            $.ajax({
                type : "POST",
                url  : _baseURL + 'settings/updateMsgPhrase',
                data : {'csrf_stream_name':csrf_val, updatedValue : updatedValue, language : language, key : key, label:label_name},
                dataType: 'JSON',
                success : function(response) {
                    if(response.success==true){
                        $('#btn-'+key).html('<i class = "fa fa-check-circle"></i>').addClass('btn-success').removeClass('btn-danger');
                        toastr.success(response.message, response.title);
                    }else{
                        toastr.error(response.message, response.title);
                    }
                }
            });
        }else{
            toastr.warning('<?php echo get_phrases(['language', 'data', 'empty']);?>', '<?php echo get_phrases(['required']);?>');
            $('#btn-'+key).addClass('btn-danger').removeClass('btn-success');
            $('#phrase-'+key).focus();
        }
    }

    $(document).ready(function() { 
       "use strict";
       var language = $('#language').val();

        $('#languageList').DataTable();
    });
</script>