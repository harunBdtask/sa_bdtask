<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th><?php echo get_phrases(['voucher', 'no']);?>11</th>
                <th><?php echo get_phrases(['voucher', 'type']);?></th>
                <th><?php echo get_phrases(['remarks']);?></th>
                <th><?php echo get_phrases(['voucher', 'date']);?></th>
                <th><?php echo get_phrases(['created', 'by']);?></th>
                <th><?php echo get_phrases(['created', 'date']);?></th>
                <th><?php echo get_phrases(['updated', 'by']);?></th>
                <th><?php echo get_phrases(['updated', 'date']);?></th>
                <th><?php echo get_phrases(['status']);?></th>
            </tr>
            <?php 
            if(!empty($results)){ 
                if($results->status==1){
                    $status = '<span class="text-success">'.get_phrases(['approved']).'</span>';
                }else if($results->status==2){
                    $status = '<span class="text-danger">'.get_phrases(['rejected']).'</span>';
                }else{
                    $status = '<span class="text-warning">'.get_phrases(['pending']).'</span>';
                }
                ?>
            <tr>
                <td><?php echo esc($results->vtype.'-'.$results->id);?></td>
                <td><?php echo esc($results->typeName);?></td>
                <td><?php echo esc($results->remarks);?></td>
                <td><?php echo esc(date('d/m/Y', strtotime($results->voucher_date)));?></td>
                <td><?php echo esc($results->created_by);?></td>
                <td><?php echo esc(date('d/m/Y H:i:s', strtotime($results->created_date)));?></td>
                <td><?php echo esc($results->updated_by);?></td>
                <td><?php echo !empty($results->updated_date)?esc(date('d/m/Y H:i:s', strtotime($results->updated_date))):'';?></td>
                <td><?php echo $status;?></td>
            </tr>
            <?php }?>
            <tr>
                <th colspan="3"><?php echo get_phrases(['account', 'no']);?></th>
                <th colspan="4"><?php echo get_phrases(['account', 'name']);?></th>
                <th class="text-right"><?php echo get_phrases(['debit']);?></th>
                <th class="text-right"><?php echo get_phrases(['credit']);?></th>
            </tr>
        </thead>
        <tbody>
        	<?php
        	$Debit = 0;
        	$Credit = 0;
            if(!empty($results)){
            	foreach($results->details as $row){ 
            		$Debit += $row->debit;
            		$Credit += $row->credit;
            	?>
            	<tr>
            		<td colspan="3"><?php echo esc($row->head_code);?></td>
            		<td colspan="4"><?php echo esc($row->HeadName);?></td>
            		<td class="text-right"><?php echo esc($row->debit);?></td>
            		<td class="text-right"><?php echo esc($row->credit);?></td>
            	</tr>
        	<?php } }else{ ?>
                <tr>
                    <td colspan="8" class="text-center text-danger"><?php echo get_notify('data_is_not_available');?></td>
                </tr>
            <?php }?>
        </tbody>
        <tfoot>
        	<tr>
        		<th class="text-right" colspan="7"><?php echo get_phrases(['total']);?></th>
        		<th class="text-right"><?php echo number_format($Debit, 2); ?></th>
        		<th class="text-right"><?php echo number_format($Credit, 2); ?></th>
        	</tr>
        </tfoot>
    </table>
</div>