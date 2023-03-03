<?php 
use App\Libraries\Numberconverter;
$numberToword = new Numberconverter();
?>
<div class="col-md-12" id="printArea">
    <p style="font-size:10px"><i>Print Date: <?php echo date('Y-m-d h:i:s')?></i></p>
<div class="row">
               <div class="col-md-3">
                  <img src="<?php echo base_url() . $settings_info->logo; ?>" alt="Logo" height="40px"><br><br>
               </div>
               <div class="col-md-6 text-center">
                    <h6><?php echo $settings_info->title; ?></h6>
                   
                  <strong><u class="pt-4"><?php echo 'Contra Voucher'; ?></u></strong>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-12">
                <div class="float-right">
            <label class="font-weight-600 mb-0"><?php echo get_phrases(['voucher','no']);?></label> : <?php echo esc($results->VNo);?><br>
            <label class="font-weight-600 mb-0"><?php echo get_phrases(['voucher','date']);?></label> : <?php echo esc(date('d/m/Y', strtotime($results->VDate)));?>
            </div>
                </div>
            </div>
           
    <table class="table table-bordered table-sm mt-2">
        
        <thead>
            <tr>
                <th class="text-center" style="background: #abbff9!important"><?php echo get_phrases(['particulars']);?></th>
                <th class="text-center" style="background: #abbff9!important"><?php echo get_phrases(['debit']);?></th>
                <th class="text-center" style="background: #abbff9!important"><?php echo get_phrases(['credit']);?></th>
               
            </tr>
           
            
        </thead>
        <tbody>
        	<?php
        	$Debit = 0;
        	$Credit = 0;
            $k = 1;
            if(!empty($results)){
            	foreach($results->details as $row){ 
            		$Debit = $row->Debit + ($row->Debit == '0.00'?$row->Credit:0);
            		$Credit = $row->Credit + ($row->Credit == '0.00'?$row->Debit:0);
                      $k++;
                    $style = $k % 2 ? '#efefef!important' : '';
            	?>
            	<tr>
            		<td style="background:<?php echo $style; ?>"><strong style="font-size: 15px;;"><?php echo $row->HeadName;?></strong><br>
                    <span> <?php echo $row->ledgerComment?></span>
                </td>
            		<td class="text-right" style="background:<?php echo $style; ?>"><?php echo esc($row->Debit);?></td>
            		<td class="text-right" style="background:<?php echo $style; ?>"><?php echo esc($row->Credit);?></td>
            	</tr>
        	<?php } }else{ ?>
                <tr>
                    <td colspan="3" class="text-center text-danger"><?php echo get_notify('data_is_not_available');?></td>
                </tr>
            <?php }?>
            <?php  $k = $k + 1 ;
             $style = $k % 2 ? '#efefef!important' : '';?>
            <tr>
                <td class="text-left" style="background:<?php echo $style; ?>"><strong style="font-size: 15px;"><?php echo $results->dbtcrdHead;?></strong></td>
                <td class="text-right" style="background:<?php echo $style; ?>"><?php echo  ($row->Debit == '0.00'?$row->Credit:0);?></td>
                <td class="text-right" style="background:<?php echo $style; ?>"><?php echo  ($row->Credit == '0.00'?$row->Debit:0);?></td>

            </tr>
        </tbody>
        <tfoot>
             <?php  $k = $k + 1 ;
             $style = $k % 2 ? '#efefef!important' : '';?>
        	<tr>
        		<th class="text-right" style="background:<?php echo $style; ?>"><?php echo get_phrases(['total']);?></th>
        		<th class="text-right" style="background:<?php echo $style; ?>"><?php echo number_format($Debit, 2); ?></th>
        		<th class="text-right" style="background:<?php echo $style; ?>"><?php echo number_format($Credit, 2); ?></th>
        	</tr>
             <?php  $k = $k + 1 ;
             $style = $k % 2 ? '#efefef!important' : '';?>
            <tr>
        		
        		<th class="" colspan="3" style="background:<?php echo $style; ?>"><?php echo get_phrases(['in','words']);?> : <?php
                   $wordsamount = $Debit;
                     echo $numberToword->AmountInWords($wordsamount); ?></th>
        		
        	</tr>
            <tr>
        		
        		
        		<th class="" colspan="3"><?php echo get_phrases(['remarks']);?> : <?php echo $results->Narration; ?></th>
        	</tr>
        </tfoot>
    </table>
    <div class="form-group row mt-5">
    <label for="name" class="col-sm-4 col-form-label text-center"> <div class="border-top"><?php echo get_phrases(['prepared','by'])?></div></label>
    <label for="name" class="col-sm-4 col-form-label text-center"> <div class="border-top"><?php echo get_phrases(['checked','by'])?></div></label>
    <label for="name" class="col-sm-4 col-form-label text-center"> <div class="border-top"><?php echo get_phrases(['authorised','by'])?></div></label>
   
               
    </div>
</div>
