<hr>
<?php 
$phrases = json_decode($results->phrases, true);
echo form_hidden('id',$results->id) ?>
<table class="table table-bordered table-striped table-sm table-hover" width="100%">
    <thead>
        <th><?php echo get_phrases(['section']) ?></th>
        <th><?php echo get_notify('english') ?></th>
        <th><?php echo get_notify('arabic') ?></th>
        
    </thead>
    <tbody>
    <?php 
    if(!empty($phrases['english'])){
        foreach($phrases['english'] as $key => $phrase){ ?>
            <tr class="phase_keys">
                <td class="text-center"><?php echo ucfirst(str_replace('_', ' ', $key)) ?> <input type="hidden" name="phase_keys[]" value="<?php echo $key; ?>"></td>
                <td>
                    <?php if($key=='item_return_policy'){ ?>
                    <textarea name="english[]" class="form-control" required="" rows="3"><?php echo $phrase; ?></textarea>
                    <?php  } else { ?>
                    <input name="english[]" type="text" class="form-control" required="" value="<?php echo $phrase ?>">
                    <?php  }  ?>
                </td>
                <td>
                    <?php if($key=='item_return_policy'){ ?>
                    <textarea name="arabic[]" class="form-control" required="" rows="3"><?php echo $phrases['arabic'][$key]; ?></textarea>
                    <?php  } else { ?>
                        <input name="arabic[]" type="text" class="form-control" required="" value="<?php echo $phrases['arabic'][$key]; ?>">
                    <?php  }  ?>
                </td>
                <!-- <td><button type="button" class="removeSBtn btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button></td> -->
            </tr>
    <?php } } ?>
    </tbody>
</table>

<div class="form-group text-center">
    <button type="submit" class="btn btn-success w-md m-b-5"><?php echo get_phrases(['update', 'phrase']) ?></button>
    <!-- <button type="button" class="btn btn-success addNew">ADD NEW</button> -->
</div>
<script type="text/javascript">
    $(document).ready(function(){

        var addHtml = '<tr><td><input type="text" class="form-control" name="phase_keys[]"></td><td><input type="text" name="english[]" class="form-control"></td><td><input type="text" name="arabic[]" class="form-control"></td><td><button type="button" class="btn btn-sm btn-danger removeSBtn"><i class="far fa-trash-alt"></i></button></td></tr>';
        $('.addNew').on('click', function(){
            $('#loadSettings >table >tbody').append(addHtml);
        });
        $('.phase_keys').on('click', function(){
            onclick_change_bg('#loadSettings', this, 'cyan');
        });
        //remove row
        $('body').on('click','.removeSBtn' ,function() {
            var rowCount = $('#loadSettings >table >tbody >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
            }else{
                alert("There only one row you can't delete.");
            } 
        });
    });
</script>
       