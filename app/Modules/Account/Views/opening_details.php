<?php 
use App\Libraries\Numberconverter;
$numberToword = new Numberconverter();
?>
<div class="col-md-12" id="printArea">
<div class="row">
               <div class="col-md-3">
                  <img src="<?php echo base_url() . $settings_info->logo; ?>" alt="Logo" height="40px"><br><br>
               </div>
               <div class="col-md-6 text-center">
                    <h6><?php echo $settings_info->title; ?></h6>
                   
                  <strong><u class="pt-4"><?php echo 'Opening Details'; ?></u></strong>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-12">
                <div class="float-right">
         
            <label class="font-weight-600 mb-0"><?php echo get_phrases(['voucher','date']);?></label> : <?php echo esc(date('d/m/Y', strtotime($results->openDate)));?>
            </div>
                </div>
            </div>
           
    <table class="table table-bordered table-sm mt-2">
        
        <thead>
            <tr>
                <th class="text-center"><?php echo get_phrases(['particulars']);?></th>
                <th class="text-center"><?php echo get_phrases(['debit']);?></th>
                <th class="text-center"><?php echo get_phrases(['credit']);?></th>
               
            </tr>
           
            
        </thead>
        <tbody>
        	<?php
        	$Debit = 0;
        	$Credit = 0;
            if(!empty($results)){
            	foreach($results->details as $row){ 
            		$Debit += $row->Debit;
            		$Credit += $row->Credit;
            	?>
            	<tr>
            		<td><strong style="font-size: 15px;"><?php echo $row->HeadName.($row->subType != 1?'('.$row->subname.')':'');?></strong>
                   
                </td>
            		<td class="text-right"><?php echo esc($row->Debit);?></td>
            		<td class="text-right"><?php echo esc($row->Credit);?></td>
            	</tr>
        	<?php } }else{ ?>
                <tr>
                    <td colspan="3" class="text-center text-danger"><?php echo get_notify('data_is_not_available');?></td>
                </tr>
            <?php }?>
           
        </tbody>
        <tfoot>
        	<tr>
        		<th class="text-right"><?php echo get_phrases(['total']);?></th>
        		<th class="text-right"><?php echo number_format($Credit, 2); ?></th>
        		<th class="text-right"><?php echo number_format($Credit, 2); ?></th>
        	</tr>
            <tr>
        		
        		<th class="" colspan="3"><?php echo get_phrases(['in','words']);?> : <?php echo $numberToword->AmountInWords(($Credit?$Credit:$Debit)); ?></th>
        		
        	</tr>
           
        </tfoot>
    </table>
    <div class="form-group row mt-5">
    <label for="name" class="col-sm-3 col-form-label text-center"> <div class="border-top"><?php echo get_phrases(['prepared','by'])?></div></label>
    <label for="name" class="col-sm-3 col-form-label text-center"> <div class="border-top"><?php echo get_phrases(['checked','by'])?></div></label>
    <label for="name" class="col-sm-3 col-form-label text-center"> <div class="border-top"><?php echo get_phrases(['authorised','by'])?></div></label>
    <label for="name" class="col-sm-3 col-form-label text-center"> <div class="border-top"><?php echo get_phrases(['received','by'])?></div></label>
               
    </div>
</div>
