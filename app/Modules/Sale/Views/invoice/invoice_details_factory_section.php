<div class="row">
    <div class="col-sm-12">
       <div class="card">
         <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="40px" ><br><br>
                        <h5><?php echo $settings_info->title; ?></h5>
                        <h6><?php echo 'CHALLAN NO :'. ($do_main?$do_main->challan_no:''); ?></h6>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>
                <div class="row">
                   <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['dealer','name']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="dealer_name" ><?php echo ($do_main?$do_main->dealer_name:'')?></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="mobile_no"><?php echo ($do_main?$do_main->do_date:'')?> </div>                        
                    </div>

                     <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['dO','no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="dealer_name" ><?php echo ($do_main?$do_main->vouhcer_no:'')?> </div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['time']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="time"><?php echo ($do_main?date("h:i a", strtotime($do_main->created_date)):'')?> </div>                        
                    </div>
                </div>
          

                 

                <table class="table table-bordered">
                    <thead>
                            <tr>
                                <th class="text-center"><?php echo get_phrases(['sl','no']);?></th>
                                <th class="text-center"><?php echo get_phrases(['item','name']);?></th>
                                <th class="text-center"><?php echo get_phrases(['quantity']).'('.get_phrases(['bag']).')';?></th>
                                

                            </tr>
                        </thead>
                        <tbody>
                           <?php if($do_details){
                              $sl = 1;
                              foreach($do_details as $details){?>
                           <tr>
                              <td class="text-center"><?php echo $sl++;?></td>
                              <td class="text-center"><?php echo $details->item_name?></td>
                              <td class="text-center"><?php echo $details->quantity?></td>

                           </tr>
                        <?php }}?>
                        </tbody>
                      
                </table>

                    <div class="form-group row">
               <?php if($do_main->sls_admin_signature){?>
               <label for="name" class="col-sm-2 col-form-label text-center"> <img src="<?php echo base_url($do_main->sls_admin_signature)?>" height="70px;" width="80px;"><div class="border-top"><?php echo get_phrases(['sales','admin'])?></div></label>
               <?php }?>

         <?php if($do_main->accountant_sig){?>
                <label for="name" class="col-sm-2 col-form-label text-center"> <img src="<?php echo base_url($do_main->accountant_sig)?>" height="70px;" width="80px;"><div class="border-top"><?php echo get_phrases(['accountant','signature'])?></div></label>
                <?php }?>

           


           <?php if($do_main->dl_s_sig){?>
                <label for="name" class="col-sm-3 col-form-label text-center"> <img src="<?php echo base_url($do_main->dl_s_sig)?>" height="70px;" width="80px;"><div class="border-top"><?php echo get_phrases(['delivery','section','admin','signature'])?></div></label>
                <?php }?> 

                <?php if($do_main->str_s_sig){?>
                <label for="name" class="col-sm-3 col-form-label text-center"> <img src="<?php echo base_url($do_main->str_s_sig)?>" height="70px;" width="80px;"><div class="border-top"><?php echo get_phrases(['store','admin','signature'])?></div></label>
                <?php }?>   
                
                <?php if($do_main->fc_m_sig){?>
                <label for="name" class="col-sm-2 col-form-label text-center"> <img src="<?php echo base_url($do_main->fc_m_sig)?>" height="70px;" width="80px;"><div class="border-top"><?php echo get_phrases(['factory','manager'])?></div></label>
                <?php }?> 
            </div>

         </div>
       </div>
     </div>
</div> 
  