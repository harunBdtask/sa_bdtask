<?php
$total = 0;
$totalDebit = 0;
$totalCredit = 0;
if(!empty($results)){ 
	foreach ($results as $value) {
		$totalDebit += $value->Debit;
        $totalCredit += $value->Credit;
		$balance = $value->Debit - $value->Credit;
        $total += $balance;
	?>
	<tr>
		<td ><?php echo esc($value->ID);?></td>
		<td><?php echo esc($value->VNo);?></td>
		<td><?php echo esc($value->typeName);?></td>
		<td><?php echo esc($value->HeadName);?></td>
		<td><?php echo esc($value->COAID);?></td>
		<td><?php echo esc($value->employeeName);?></td>
		<td><?php echo esc(date('d/m/Y H:i:s', strtotime($value->CreateDate)));?></td>
		<td><?php echo esc($value->Debit);?></td>
		<td><?php echo esc($value->Credit);?></td>
		<td><?php echo esc($total);?></td>
	</tr>
<?php } ?>
    <tr>
		<th colspan="7" class="text-right"><?php echo get_phrases(['total']);?></th>
		<th><?php echo esc(number_format($totalDebit, 2));?></th>
		<th><?php echo esc(number_format($totalCredit, 2));?></th>
		<th><?php echo esc(number_format($total, 2));?></th>
	</tr>
<?php }else{ ?>
	<tr>
		<td colspan="10" class="text-danger"><center><?php echo get_notify('data_is_not_available');?></center></td>
	</tr>
<?php }?>