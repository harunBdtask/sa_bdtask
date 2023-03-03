<?php
if(!empty($results)){ 
	$approved    = get_phrases(['approved']);
	$napproved   = get_phrases(['not', 'approved']);
	$collected   = get_phrases(['collected']);
	$ncollected  = get_phrases(['not', 'collected']);
	$rejected    = get_phrases(['rejected']);
	$collected   = get_phrases(['collected']);
	$ncollected  = get_phrases(['not', 'collected']);
	$returned    = get_phrases(['returned']);
	$received    = get_phrases(['received']);
	$nreceived   = get_phrases(['not', 'received']);
	
	foreach ($results as $key => $value) {
		$status = '';
		if($value->isApproved==1){
			$status .= '<div class="badge badge-success-soft mr-1" >'.$approved.'</div>';
			if($value->isCollected==1){
				$status .= '<div class="badge badge-primary-soft mr-1" >'.$collected.'</div>';
				if($value->isReturned==1){
					$status .= ' <div class="badge badge-secondary-soft mr-1" >'.$returned.'</div>';
					if($value->isReceived==1){
						$status .= ' <div class="badge badge-primary-soft" >'.$received.'</div>';
					}else{
						$status .= ' <div class="badge badge-warning-soft text-warning" >'.$nreceived.'</div>';
					}
				}
			}else{
				$status .= '<div class="badge badge-info-soft mr-1" >'.$ncollected.'</div>';
			}
		}else if($value->isApproved==2){
			$status .= '<div class="badge badge-danger-soft" >'.$rejected.'</div>';
		}else{
			$status .= '<div class="badge badge-info-soft" >'.$napproved.'</div>';
		}
	?>
	<tr data-id="<?php echo esc($value->id);?>" class="actionCost clickable-row">
		<td><?php echo esc($value->id);?></td>
		<td><?php echo esc($value->voucher_no);?></td>
		<td><?php echo date('d/m/Y', strtotime(esc($value->date)));?></td>
		<td><?php echo esc($value->department_name);?></td>
		<td><?php echo esc($value->sub_store_name);?></td>
		<td><?php echo esc($value->doctor_name);?></td>
		<td><?php echo esc($value->file_no);?></td>
		<td><?php echo esc($value->request_by_name);?></td>
		<td><?php echo $status;?></td>
	</tr>
<?php } }else{ ?>
	<tr>
		<td colspan="9" class="text-danger"><center><?php echo get_notify('data_is_not_available');?></center></td>
	</tr>
<?php }?>