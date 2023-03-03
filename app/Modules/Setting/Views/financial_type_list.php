<?php if(!empty($list)){
foreach ($list as $key => $value) {
?>
    <tr>
        <td><?php echo esc($value->branch_name);?></td>
        <td style="color: <?php echo $value->color;?>"><?php echo esc($value->nameE);?></td>
        <td style="color: <?php echo $value->color;?>"><?php echo esc($value->nameA);?></td>
        <td><?php echo number_format(esc($value->start_amount), 2).' - '.number_format(esc($value->end_amount), 2);?></td>
        <td>
            <a href="javascript:void(0);" class="btn btn-success-soft btn-sm btnC mr-1 typeEditAction" data-id="<?php echo esc($value->id);?>"><i class="fa fa-edit"></i></a>
            <a href="javascript:void(0);" class="btn btn-danger-soft btn-sm btnC mr-1 typeDeleteAction" data-id="<?php echo esc($value->id);?>"><i class="fa fa-trash-alt"></i></a>
        </td>
    </tr>
<?php } }else{ ?>
    <tr>
        <td colspan="4" class="text-danger text-center font-weight-600"><?php echo get_notify('data_is_not_available');?></td>
    </tr>
<?php }?>