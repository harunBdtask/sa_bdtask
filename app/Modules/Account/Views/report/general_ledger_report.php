<div class="row">
    <div class="col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb" class="order-sm-last p-0">
                        <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                            <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle;?></a></li>
                            <li class="breadcrumb-item active"><?php echo $title;?></li>
                        </ol>
                    </nav>
                </div>
                <div class="text-right">
                   <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                </div>
            </div>
        </div>
    <div class="card-body">
         <div id="printArea">
            <div class="row">
              <div class="col-6">
                  <img src="<?php echo base_url().$settings_info->logo;?>" class="img-responsive" alt="logo"></br>
                  <?php echo get_phrases(['phone']) ?>: <?php echo esc($settings_info->phone); ?></br>
                  <?php echo get_phrases(['email']) ?>: <?php echo esc($settings_info->email); ?>
                  <br>
              </div>
              <div class="col-6 text-right">
                   <address>
                      <strong><?php echo esc($settings_info->title); ?></strong><br>
                      (<?php echo $settings_info->nameA; ?>)<br>
                      <?php echo esc($settings_info->address); ?>
                  </address>
              </div>
            </div> <hr>
            
            <table class="table" width="99%" align="center"  cellpadding="5" cellspacing="5" border="2"> 

                <thead>
                <tr align="center" class="">

                    <td colspan="7"><font size="+1"> <strong ><?php echo get_phrases(['general','ledger','of']).'- '.$ledger->HeadName.' ('.get_phrases(['on'])?> <span class="text-"><?php echo $dateRange ?></span>)</strong></font><strong></th></strong>
                </tr>

                <tr>
                    <td height="25" align="center" style="background: #abbff9!important"><strong><?php echo get_phrases(['sl']);?></strong></td>
                    <td align="center" style="background: #abbff9!important"><strong><?php echo "Transaction Date";?></strong></td>
                    <td align="center" style="background: #abbff9!important"><strong><?php echo !empty($Trans)?"Transaction Date":"Head Code";?></strong></td>
                    
                    <?php
                    if($chkIsTransction){
                        ?>
                        <td align="center" style="background: #abbff9!important"><strong><?php echo get_phrases(['particulars'])?></strong></td>
                    <?php
                    }
                    ?>
                    <td align="right" style="background: #abbff9!important"><strong><?php echo get_phrases(['debit']);?></strong></td>
                    <td align="right" style="background: #abbff9!important"><strong><?php echo get_phrases(['credit']);?></strong></td>
                    <td align="right" style="background: #abbff9!important"><strong><?php echo get_phrases(['balance']);?></strong></td>
                </tr>
                </thead>
                <tbody>

                <?php
                if((!empty($error)?$error:'')){
                    ?>

                    <tr>
                        <td height="25"></td>
                        <td></td>
                        <td><?php echo get_phrases(['no','record','found'])?>.</td>
                        <?php
                        if($chkIsTransction){
                            ?>
                            <td></td>
                            <?php
                        }
                        ?>

                        <td align="right"></td>
                        <td align="right"></td>
                        <td align="right"></td>
                    </tr>

                    <?php
                }
                else{
                $TotalCredit=0;
                $TotalDebit=0;
                $CurBalance =$prebalance;
                foreach($HeadName2 as $key=>$data) {
                    ?>
                    <tr>
                        <td height="25" align="center"><?php echo ++$key;?></td>
                        <td align="center"><?php echo $data->VDate; ?></td>
                        <td align="center"><?php echo $data->COAID; ?></td>
                        
                        <?php
                        if($chkIsTransction){
                            ?>
                            <td align="center"><?php echo $data->Narration; ?></td>
                            <?php
                        }
                        ?>

                        <td align="right"><?php echo  number_format($data->Debit,2,'.',','); ?></td>
                        <td align="right"><?php echo  number_format($data->Credit,2,'.',','); ?></td>
                        <?php
                        $TotalDebit += $data->Debit;
                        $CurBalance += $data->Debit;

                        $TotalCredit += $data->Credit;
                        $CurBalance -= $data->Credit;
                        ?>
                        <td align="right"><?php echo  number_format($CurBalance,2,'.',','); ?></td>
                        
                    </tr>
                <?php } ?>

                <tfoot>
                <tr class="table_data">
                    <?php
                        if($chkIsTransction)
                            $colspan=4;
                        else
                            $colspan=3;
                            ?>
                    <td colspan="<?php echo $colspan;?>" align="right" style="background: #abbff9!important"><strong><?php echo get_phrases(['total'])?></strong></td>                    
                    <td align="right" style="background: #abbff9!important"><strong><?php echo number_format($TotalDebit,2,'.',','); ?></strong></td>
                    <td align="right" style="background: #abbff9!important"><strong><?php echo number_format($TotalCredit,2,'.',','); ?></strong></td>
                    <td align="right" style="background: #abbff9!important"><strong><?php echo number_format($CurBalance,2,'.',','); ?></strong></td>
                </tr>
                </tfoot>
                <?php
                }
                ?>
                </tbody>
               
                <h4 class="prbalance">
                    <?php echo get_phrases(['pre','balance'])?> : <?php echo number_format($prebalance,2,'.',','); ?>
                    <br /> <?php echo get_phrases(['current','balance'])?> : <?php echo number_format($CurBalance,2,'.',','); ?>
                </h4>
             
            </table>
        </div>
            <div class="text-center" id="print">
                <?php if($permission->method('gl_reports', 'print')->access()){ ?>
                <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printContent('printArea');"/>
                <?php }?>
            </div>  
        </div>
    </div>
</div>
           
</div>

                      

