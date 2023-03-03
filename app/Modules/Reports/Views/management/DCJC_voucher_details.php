<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th><?php echo get_phrases(['voucher', 'no']);?></th>
                <th><?php echo get_phrases(['voucher', 'type']);?></th>
                <th><?php echo get_phrases(['voucher', 'date']);?></th>
                <th><?php echo get_phrases(['created', 'by']);?></th>
                <th><?php echo get_phrases(['created', 'date']);?></th>
                <th><?php echo get_phrases(['approved', 'by']);?></th>
                <th><?php echo get_phrases(['approved', 'date']);?></th>
            </tr>
            <tr>
                <td><?php echo esc($results->VNo);?></td>
                <td><?php echo esc($results->type);?></td>
                <td><?php echo esc(date('d/m/Y', strtotime($results->VDate)));?></td>
                <td><?php echo esc($results->created_by);?></td>
                <td><?php echo esc(date('d/m/Y H:i:s', strtotime($results->CreateDate)));?></td>
                <td><?php echo esc($results->updated_by);?></td>
                <td><?php echo !empty($results->UpdateDate)?esc(date('d/m/Y H:i:s', strtotime($results->UpdateDate))):'';?></td>
            </tr>
            <tr>
                <th colspan="2"><?php echo get_phrases(['account', 'no']);?></th>
                <th colspan="3"><?php echo get_phrases(['account', 'name']);?></th>
                <th class="text-right"><?php echo get_phrases(['debit']);?></th>
                <th class="text-right"><?php echo get_phrases(['credit']);?></th>
            </tr>
        </thead>
        <tbody>
        	<?php
        	$Debit = 0;
        	$Credit = 0;
        	foreach($results->details as $row){ 
        		$Debit += $row->Debit;
        		$Credit += $row->Credit;
        	?>
        	<tr>
        		<td colspan="2"><?php echo esc($row->COAID);?></td>
        		<td colspan="3"><?php echo esc($row->HeadName);?></td>
        		<td class="text-right"><?php echo esc($row->Debit);?></td>
        		<td class="text-right"><?php echo esc($row->Credit);?></td>
        	</tr>
        	<?php }?>
        </tbody>
        <tfoot>
        	<tr>
        		<th class="text-right" colspan="5"><?php echo get_phrases(['total']);?></th>
        		<th class="text-right"><?php echo number_format($Debit, 2); ?></th>
        		<th class="text-right"><?php echo number_format($Credit, 2); ?></th>
        	</tr>
        </tfoot>
    </table>
</div>