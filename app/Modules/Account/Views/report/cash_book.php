<link href="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<div class="row">
    <div class="col-sm-12">
        <?php
        $hasExportAccess = $hasPrintAccess = 1;
        if ($permission->module('cash_book')->access()) { ?>
            <div class="card">
                <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <nav aria-label="breadcrumb" class="order-sm-last p-0">
                                <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                                    <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle; ?></a></li>
                                    <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                </ol>
                            </nav>
                        </div>
                        <div class="text-right">
                           
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open('account/reports/cash_book')?>
                    <div class="row form-group">
                        
                        <div class="col-sm-2">
                            <input type="text" name="dtpFromDate" id="search_date" class="form-control datepicker" placeholder="<?php echo get_phrases(['select', 'start', 'date']); ?>" autocomplete="off" value="<?php echo ($dtpFromDate?$dtpFromDate:'')?>">
                        </div>

                        <div class="col-sm-2">
                            <input type="text" name="dtpToDate" id="search_date_end" class="form-control datepicker" placeholder="<?php echo get_phrases(['select', 'end', 'date']); ?>" autocomplete="off" value="<?php echo ($dtpToDate?$dtpToDate:'')?>">
                        </div>
                        <div class="col-sm-2">
                              <button type="submit" class="btn btn-success"><?php echo get_phrases(['search']); ?></button>
                        </div>
                    </div>
                    <?php echo form_close()?>
                    <hr>
                    
<?php if(!empty($dtpFromDate) && !empty($dtpToDate)){?>
                    <div class="prcontent"  id="printArea">
                 <p style="font-size:10px"><i>Print Date: <?php echo date('Y-m-d h:i:s')?></i></p>
                <div>
                    <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <img src="<?php echo base_url((!empty($setting->logo)?$setting->logo:'assets/img/icons/mini-logo.png')) ?>" alt="logo" height="100px" width="100px">
                    </div>
                    <div class="col-sm-4 col-xs-12 text-center">
                         <span class="" style="font-size: 16px;font-weight:bold;">
                           <?php echo $setting->title;?>
                           </span><br>
                           <?php echo $setting->address;?>
                    </div>
                    <div class="col-sm-4 col-xs-12 text-right">
                        <date>
                            <?php echo get_phrases(['date'])?>: <?php
                            echo date('d-M-Y');
                            ?> 
                        </date>
                    </div>
                </div>
                
                   
                <div class="table-responsive">
                <div class="text-center"> <strong> <?php echo get_phrases(['cash','book','report'])?>  (<?php echo get_phrases(['from'])?> <?php echo (!empty($dtpFromDate)?$dtpFromDate:''); ?> <?php echo get_phrases(['to'])?> <?php echo (!empty($dtpToDate)?$dtpToDate:'');?>)</strong></div>
                    <table width="100%" class="table table-stripped" cellpadding="6" cellspacing="1">

                       
                        <thead>
                
                <tr>
                    <td height="25" width="5%" style="background: #abbff9!important"><strong><?php echo get_phrases(['sl']);?></strong></td>
                    <td  width="10%" style="background: #abbff9!important"><strong><?php echo get_phrases(['transdate']);?></strong></td>
                    <td width="10%" style="background: #abbff9!important"><strong><?php echo get_phrases(['voucher_no']);?></strong></td>
                    <td width="8%" style="background: #abbff9!important"><strong><?php echo get_phrases(['voucher_type']);?></strong></td>
                    
                    <td width="12%" style="background: #abbff9!important"><strong><?php echo "Head Name";?></strong></td>
                    
                     <td width="25%" style="background: #abbff9!important"><strong><?php echo get_phrases(['ledger','comment'])?></strong></td>
                   
                    <td width="10%" align="right" style="background: #abbff9!important"><strong><?php echo get_phrases(['debit']);?></strong></td>
                    <td width="10%" align="right" style="background: #abbff9!important"><strong><?php echo get_phrases(['credit']);?></strong></td>
                    <td width="10%" align="right" style="background: #abbff9!important"><strong><?php echo get_phrases(['balance']);?></strong></td>
                </tr>
                </thead>
                <tbody>
                <?php  
                        
                $TotalCredit=0;
                $TotalDebit  = 0;
                $CurBalance =$prebalance;
                $openid = 1; 
                 $k =  2;
                 $style = $k % 2 ? '#efefef!important' : '';
                ?>
               <tr>
                    <td height="25" style="background:<?php echo $style; ?>"><?php echo $openid ;?></td>
                    <td style="background:<?php echo $style; ?>"><?php echo date('d-m-Y', strtotime($dtpFromDate)); ?></td>
                    
                    <td colspan="4"  align="right" style="background:<?php echo $style; ?>"> <strong>Opening Balance</strong></td>
                    <td  align="right" style="background:<?php echo $style; ?>"><?php echo number_format(0,2,'.',','); ?></td>
                    <td  align="right" style="background:<?php echo $style; ?>"><?php echo number_format(0,2,'.',','); ?></td>
                    <td  align="right" style="background:<?php echo $style; ?>"><strong><?php echo number_format($prebalance,2,'.',','); ?></strong></td>
                </tr>
                <?php  
                $k = $k ;     
                foreach($HeadName2 as $key=>$data) { 
                    $k++;
                    $style = $k % 2 ? '#efefef!important' : '';
                    ?>
                <tr>
                    <td height="25" style="background:<?php echo $style; ?>"><?php echo (++$key + $openid) ;?></td>
                    <td style="background:<?php echo $style; ?>"><?php echo date('d-m-Y', strtotime($data->VDate)); ?></td>
                    <td style="background:<?php echo $style; ?>"><?php echo $data->VNo; ?></td>
                    <td style="background:<?php echo $style; ?>"><?php if($data->Vtype=='DV') {echo 'Debit';} else if($data->Vtype=='CV') { echo 'Credit';} else if ($data->Vtype=='JV') { echo 'Journal';} else { echo 'Contra';} ?></td>                        
                    <td style="background:<?php echo $style; ?>"><?php echo $data->HeadName; ?></td>
                    <td style="background:<?php echo $style; ?>"><?php echo $data->ledgerComment; ?></td>  
                   
                    <td align="right" style="background:<?php echo $style; ?>"><?php echo number_format($data->Debit,2,'.',','); ?></td>
                    <td align="right" style="background:<?php echo $style; ?>"><?php echo number_format($data->Credit,2,'.',','); ?></td>  
                                                   
                    <?php 
                     $TotalDebit += $data->Debit;
                     $TotalCredit += $data->Credit;
                     if($HeadName->HeadType == 'A' || $HeadName->HeadType == 'E') {
                          if($data->Debit > 0) {
                            $CurBalance += $data->Debit;
                          }
                          if($data->Credit > 0) {
                            $CurBalance -= $data->Credit;
                          }                          
                      } else {                       
                        if($data->Debit > 0) {
                            $CurBalance -= $data->Debit;
                          }                          
                          if($data->Credit > 0) {
                            $CurBalance += $data->Credit;
                          } 
;                     } ?>
                      <td align="right" style="background:<?php echo $style; ?>"><?php echo  number_format($CurBalance,2,'.',','); ?></td>                       
               </tr>
              <?php } ?>
          <tfoot>
                <tr class="table_data"> 
                    <td style="background: #abbff9!important">&nbsp;</td>
                    <td style="background: #abbff9!important">&nbsp;</td>
                    <td align="center" style="background: #abbff9!important">&nbsp;</td>
                    <td colspan="3" align="right" style="background: #abbff9!important"><strong><?php echo get_phrases(['total'])?></strong></td>
                    <td align="right" style="background: #abbff9!important"><strong><?php echo number_format($TotalDebit,2,'.',','); ?></strong></td>
                    <td align="right" style="background: #abbff9!important"><strong><?php echo number_format($TotalCredit,2,'.',','); ?></strong></td>
                    <td align="right" style="background: #abbff9!important"><strong><?php echo number_format($CurBalance,2,'.',','); ?></strong></td>
                </tr>
           </tfoot>               
           </tbody>
                <h4 style="margin-left: 10px; margin-top: 15px;">
                    <?php echo get_phrases(['opening_balance'])?> : <?php echo number_format($prebalance,2,'.',','); ?>
                    <br /> <?php echo get_phrases(['closing_balance'])?> : <?php echo number_format($CurBalance,2,'.',','); ?>
                </h4>
       </table>

                   </div>
                </div>

            </div>
            <?php }?>
                  
            <div class="text-center" id="print">
                        <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printContent('printArea');"/>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong class="fs-20 text-danger"><?php echo get_phrases(['you_do_not_have_permission_to_access_please_contact_with_administrator']); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>




<script src="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript">
</script>

<script type="text/javascript">
 

    $(document).ready(function() {
        "use strict";
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        });

    });

</script>