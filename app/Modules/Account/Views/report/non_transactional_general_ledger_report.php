
        <div class="row">
             <div class="col-md-12 col-lg-12">
                <div class="card">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo get_phrases(['general','ledger'])?></h6>
                </div>
                <div class="text-right">
                       
              <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printDiv('printArea');"/>       
                  
                </div>
            </div>
        </div>
                 <div class="card-body">
  
          <?php echo  form_open_multipart('account/reports/non_transactional_general_ledger','id="general_ledger_non_transactional"') ?>
          <div class="row">
          <div class="col-md-6"><div class="form-group row">
                        <label for="general_head" class="col-sm-2 col-form-label"><?php echo get_phrases(['general','head'])?><i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                             <select class="form-control select2" name="cmbGLCode" required="">
                                    <option >Select Head</option>
                                    <?php
                                    foreach($general_ledger as $g_data){
                                        ?>
                                        <option value="<?php echo $g_data->HeadCode;?>" <?php if($glcode == $g_data->HeadCode){echo 'selected';}?>><?php echo $g_data->HeadName;?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                        </div>
                         <label for="from_date" class="col-sm-2 col-form-label"><?php echo get_phrases(['from','date'])?></label>
                        <div class="col-sm-4">
                         <input type="text" name="dtpFromDate" value="<?php echo $dtpFromDate?>" placeholder="<?php echo get_phrases(['date']) ?>" class="datepicker form-control" required>
                        </div>
                    </div> </div>
          <div class="col-md-6"><div class="form-group row">
                        <label for="to_date" class="col-sm-2 col-form-label"><?php echo get_phrases(['to','date'])?><i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                         <input type="text"  name="dtpToDate" value="<?php echo $dtpToDate?>" placeholder="<?php echo get_phrases(['date']) ?>" class="datepicker form-control" required>
                        </div>
                        
                        <div class="col-sm-4">
                          <input type="submit" id="add_receive" class="btn btn-success btn-large form-control" name="search" value="<?php echo get_phrases(['search']) ?>" />
                        </div>
                    </div> </div>
      </div>
                     
                   
  
                      
                  <?php echo form_close() ?>

                  <?php if($glcode){?>
                    <div class="row" id="printArea">
                             <table class="table" width="99%" align="center"  cellpadding="5" cellspacing="5" border="2"> 

                <thead>
                <tr align="center" class="">

                    <td colspan="7"><font size="+1"> <strong ><?php echo get_phrases(['general','ledger','of']).'- '.$ledger[0]['HeadName'].' ('.get_phrases(['on'])?> <span class="text-"><?php echo $dtpFromDate ?></span> <?php echo get_phrases(['to'])?>  <span class="text"> <?php echo $dtpToDate;?></span>)</strong></font><strong></th></strong>
                </tr>

                <tr>
                    <td height="25" align="center"><strong><?php echo get_phrases(['sl']);?></strong></td>
                    <td align="center"><strong><?php echo "Transaction Date";?></strong></td>
                    <td align="center"><strong><?php echo !empty($Trans)?"Transaction Date":"Head Name";?></strong></td>
                    
                    <?php
                    if($chkIsTransction){
                        ?>
                        <td align="center"><strong><?php echo get_phrases(['particulars'])?></strong></td>
                    <?php
                    }
                    ?>
                    <td align="right"><strong><?php echo get_phrases(['debit']);?></strong></td>
                    <td align="right"><strong><?php echo get_phrases(['credit']);?></strong></td>
                    <td align="right"><strong><?php echo get_phrases(['balance']);?></strong></td>
                </tr>
                </thead>
                <tbody>

            <tr>
                <td class="text-center">1</td>
                <td class="text-center"><?php echo $dtpFromDate?></td>

                <td colspan="" class="text-center">Opening Balance</td>
                <td></td>
                <td class="text-right"><?php echo number_format($prebalance,2,'.',','); ?></td>
                <td></td>
                <td class="text-right"><?php echo number_format($prebalance,2,'.',','); ?></td>



            </tr>
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
                $sl = 1;
                foreach($HeadName2 as $key=>$data) {
                    ?>
                    <tr>
                        <td height="25" align="center"><?php echo ++$sl;?></td>
                        <td align="center"><?php echo $data->VDate; ?></td>
                        <td align="center"><?php echo $data->HeadName; ?></td>
                        
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
                    <td colspan="<?php echo $colspan;?>" align="right"><strong><?php echo get_phrases(['total'])?></strong></td>                    
                    <td align="right"><strong><?php echo number_format($TotalDebit,2,'.',','); ?></strong></td>
                    <td align="right"><strong><?php echo number_format($TotalCredit,2,'.',','); ?></strong></td>
                    <td align="right"><strong><?php echo number_format($CurBalance,2,'.',','); ?></strong></td>
                </tr>
                <tr>
                    <td colspan="6" class="text-right"><b>Opening Balance for <?php echo date('Y-m-d', strtotime("+1 day", strtotime($dtpToDate)));?></b> </td>
                    <td class="text-right"><strong><?php echo number_format($CurBalance,2,'.',','); ?></strong></td>
                  

                </tr>
                </tfoot>
                <?php
                }
                ?>
                </tbody>
               
                   
               
            </table>
        </div>

                  <?php }?>
                    
                    </div>
                    </div>
                    </div>
           

                    </div>

      

