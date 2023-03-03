<?php 
if(!empty($results)){ 
	foreach ($results as $key => $value) {
		if($value->total > 0){
			$discount = ($value->totalDisAmt*100)/$value->total;
		}else{
			$discount = 0;
		}
		$paid = $value->totalAmount - $value->totalCredit;
	?>
	<tr>
		<td data-id="<?php echo esc($value->id);?>" data-type="<?php echo esc($value->vtype);?>" class="text-success viewDetails clickable-row"><?php echo esc($value->id);?></td>
		<td><?php echo esc($value->patient);?></td>
		<td data-id="<?php echo esc($value->id);?>" data-type="<?php echo esc($value->vtype);?>" class="text-success viewDetails clickable-row"><?php echo esc($value->total);?></td>
		<td><?php echo number_format($discount, 2);?></td>
		<td><?php echo esc($value->totalDisAmt);?></td>
		<td><?php echo esc($value->vat);?></td>
		<td><?php echo $paid;?></td>
		<td><?php echo esc($value->totalCredit);?></td>
		<td><?php echo esc($value->createdBy);?></td>
		<td><?php echo esc(date('d/m/Y H:i:s', strtotime($value->created_date)));?></td>
	</tr>
<?php } }else{ ?>
	<tr>
		<td colspan="10" class="text-danger"><center><?php echo get_notify('data_is_not_available');?></center></td>
	</tr>
<?php }?>