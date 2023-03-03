<?php
use App\Modules\Account\Models\Bdtaskt1m8ReportModel;
$this->accountModel  = new Bdtaskt1m8ReportModel();
$this->db = db_connect();
?>
<div class="row">
    <div class="col-sm-12">
        <?php 
        $hasPrintAccess  = $permission->method('balance_sheet', 'print')->access();
        $hasExportAccess = $permission->method('balance_sheet', 'export')->access();
        if($permission->method('balance_sheet', 'create')->access()){ ?>
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
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i>Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php echo form_open_multipart('account/reports/balanceSheetForm', 'id="userRForm"');?>
                <div class="row">
                    <div class="col-md-5 col-sm-12">
                        <div class="form-group">
                            <input type="text" name="date_range" id="reportrange" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>">
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1"><?php echo get_phrases(['filter']);?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close();?>

                <div class="row" id="printDiv">
                    <div class="col-md-12">
                        <h5><center><?php echo $dateRange;?></center></h5>
                        <table width="100%" class="table table-bordered table-sm" cellpadding="0" cellspacing="0">
                            <tr class="table_head">
                                <td width="73%" height="29" align="center" class="cashflowparticular"><b><?php echo get_phrases(['particulars']);?></b></td>
                               
                                <td width="30%" align="right" class="cashflowamount"><b><?php echo get_phrases(['amount']);?></b></td>
                            </tr>
                            <?php 

                            foreach($fixed_assets as $assets){
                            $total_assets = 0;
                            $head_data = $this->accountModel->bdtaskt1m8_14_assets_info($assets['HeadName']);
                            //echo "<pre>";print_r($head_data);die();
                            ?>
                            <tr >
                                  <td align="left" class="paddingleft10px <?php if($assets['HeadName'] =='CurrentAssets' || $assets['HeadName'] =='FixedAssets'){echo 'balancesheet_head';}?>"><?php echo $assets['HeadName']; ?></td>
                                
                                  <td align="right" class="cashflowamnt" >
                                     
                                  </td>
                              </tr>
                              <?php
                             
                               foreach($head_data as $assets_head){
                                  
                               $ass_balance = $this->accountModel->bdtaskt1m8_15_assets_balance($assets_head['HeadCode'],$from_date,$to_date);?>
                            <?php if($assets_head['PHeadName'] == 'CurrentAssets'){
                            $child_head_current = $this->accountModel->bdtaskt1m8_16_asset_childs($assets_head['HeadName']);
                            //echo "<pre>";print_r($child_head_current);die();
                            ?>
                            <tr>
                                  <td align="left" class="paddingleft10px"><?php echo esc($assets_head['HeadName']); ?></td>
                                
                                  <td align="right" class="cashflowamnt" >
                                    
                                  </td>
                              </tr>  

                              <?php foreach($child_head_current as $cchead){
                                $cur_ass_balance = $this->accountModel->bdtaskt1m8_15_assets_balance($cchead['HeadCode'],$from_date,$to_date);
                                 $schild_head_current = $this->accountModel->bdtaskt1m8_16_asset_childs($cchead['HeadName']);

                                ?>
                                <?php if($cur_ass_balance[0]['balance'] <> 0){?>
                            <tr>
                                  <td align="left" class="paddingleft10px"><?php echo esc($cchead['HeadName']); ?></td>
                                
                                  <td align="right" class="cashflowamnt" >
                                    <?php echo esc($cur_ass_balance[0]['balance']);
                                    $total_assets += $cur_ass_balance[0]['balance'];
                                    ?>
                                  </td>
                              </tr> 
                            <?php }?>
                             <!-- $cchead['HeadName'] == 'Patients' -->
                              <?php if($cchead['PHeadName'] == 'CashAssets' || $cchead['HeadName'] == 'Receivables' || $cchead['HeadName'] == 'Loan Receivable'){
                              foreach($schild_head_current as $scchild){
                                $cur_bank_balance = $this->accountModel->bdtaskt1m8_15_assets_balance($scchild['HeadCode'],$from_date,$to_date);
                               ?>
                               <?php if($cur_bank_balance[0]['balance'] <> 0){?>
                                <tr >
                                  <td align="left" class="paddingleft10px"><?php echo esc($scchild['HeadName']); ?></td>
                                
                                  <td align="right" class="cashflowamnt" >
                                    <?php echo esc($cur_bank_balance[0]['balance']);
                                    $total_assets += $cur_bank_balance[0]['balance'];
                                    ?>
                                  </td>
                              </tr> 
                            <?php }?>
                            <?php }}?>
                              <?php }?>
                            <?php }?>

                                <?php if($assets_head['PHeadName'] == 'FixedAssets'){?>
                                <tr >
                                  <td align="left" class="paddingleft10px"><?php echo esc($assets_head['HeadName']); ?></td>
                                
                                  <td align="right" class="cashflowamnt" >
                                    <?php echo esc($ass_balance[0]['balance']);
                                    $total_assets += $ass_balance[0]['balance'];
                                    ?>
                                  </td>
                              </tr>  

                            <?php }?>
                                  
                            <?php }?>

                                 <tr >
                                  <td class="text-right" style="padding-right: 10px;"><b><?php echo get_phrases(['total'])?>  <?php echo $assets['HeadName']; ?></b></td>
                                
                                  <td align="right" class="cashflowamnt" style="border: solid 2px #000;">
                                    <b><?php echo number_format($total_assets,2);?></b>
                                  </td>
                              </tr>
                            <?php }?>



                            <?php

                            foreach($liabilities as $liability){
                            $total_liab = 0;
                            $liab_head_data = $this->accountModel->bdtaskt1m8_10_liabilities_info($liability['HeadName']);
                            ?>
                            <tr >
                                  <td align="left" class="paddingleft10px <?php if($liability['HeadName'] =='Current Liabilities' || $liability['HeadName'] =='Non Current Liabilities'){echo 'balancesheet_head';}?>"><?php echo $liability['HeadName']; ?></td>
                                
                                  <td align="right" class="cashflowamnt" >
                                     
                                  </td>
                              </tr>
                               <?php
                             
                               foreach($liab_head_data as $liab_head){
                              
                                if($liab_head['HeadName'] == 'Tax'){
                                    $child_liability = $this->accountModel->bdtaskt1m8_13_liabilities_info_tax($liab_head['HeadName']);
                                }else{
                                    $child_liability = $this->accountModel->bdtaskt1m8_10_liabilities_info($liab_head['HeadName']);
                                }
                                ?>
                               <?php  if($liab_head['HeadName'] != 'Tax'){?>
                            <tr >
                                  <td align="left" class="paddingleft10px"><?php echo esc($liab_head['HeadName']); ?></td>
                                
                                  <td align="right" class="cashflowamnt" >
                                     <?php 
                                     $total_liab += 0;
                                      ?>
                                  </td>
                              </tr>
                            <?php }?>

                               <?php
                             
                               foreach($child_liability as $chliab_head){
                                $liab_balance = $this->accountModel->bdtaskt1m8_11_liabilities_balance($chliab_head['HeadCode'],$from_date,$to_date);

                                ?>
                                <?php if($liab_balance[0]['balance'] <> 0){?>
                            <tr >
                                  <td align="left" class="paddingleft10px"><?php echo esc($chliab_head['HeadName']); ?></td>
                                
                                  <td align="right" class="cashflowamnt" >
                                     <?php 

                                     echo  esc($liab_balance[0]['balance']);
                                     $total_liab += $liab_balance[0]['balance'];
                                      ?>
                                  </td>
                              </tr>
                            <?php }?>

                            <?php }?>
                            <?php }?>
                            <tr >
                                  <td class="paddingleft10px text-right"  style="padding-right: 10px;"><b><?php echo get_phrases(['total'])?>  <?php echo esc($liability['HeadName']); ?></b></td>
                                
                                  <td align="right" class="cashflowamnt" style="border: solid 2px #000;">
                                    <b><?php echo number_format($total_liab,2);?></b>
                                  </td>
                              </tr>
                            <?php }?>

                              <?php
                             $total_expense = 0;
                             $total_income = 0;
                               foreach($incomes as $incomelable){
                                $inc_head_current = $this->accountModel->bdtaskt1m8_16_asset_childs($incomelable['HeadName']);
                                ?>
                                <tr>
                                  <td align="left" class="paddingleft10px"><?php echo esc($incomelable['HeadName']); ?></td>
                                
                                  <td align="right" class="cashflowamnt" >
                                    
                                  </td>
                              </tr>  
                                <?php 
                                foreach ($inc_head_current as $incChHead) {
                                $income_balance = $this->accountModel->bdtaskt1m8_12_income_balance($incChHead['HeadCode'],$from_date,$to_date);
                                ?>
                                <tr>
                                  <td align="left" class="paddingleft10px balancesheet_head pl-2"><?php echo esc($incChHead['HeadName']); ?></td>
                                
                                  <td align="right" class="cashflowamnt" >
                                     <?php echo esc($income_balance[0]['balance']);
                                     $total_income += $income_balance[0]['balance'];
                                     ?>
                                  </td>
                                </tr>
                                <?php } }?>

                            <tr >
                                  <td class="paddingleft10px text-right"  style="padding-right: 10px;"><b><?php echo get_phrases(['total'])?>  <?php echo get_phrases(['income']); ?></b></td>
                                
                                  <td align="right" class="cashflowamnt" style="border: solid 2px #000;">
                                    <b><?php echo number_format($total_income,2);?></b>
                                  </td>
                              </tr>
                                 <?php
                             
                               foreach($expenses as $expense){
                                $expense_balance = $this->accountModel->bdtaskt1m8_12_income_balance($expense['HeadCode'],$from_date,$to_date);
                                ?>
                                <tr>
                                  <td align="left" class="paddingleft10px balancesheet_head"><?php echo esc($expense['HeadName']); ?></td>
                                
                                  <td align="right" class="cashflowamnt" >
                                     <?php echo $expense_balance[0]['balance'];
                                       $total_expense += $expense_balance[0]['balance'];
                                     ?>
                                  </td>
                                </tr>
                                <?php }?>
                                <tr >
                                  <td class="paddingleft10px text-right"  style="padding-right: 10px;"><b><?php echo get_phrases(['total'])?>  <?php echo get_phrases(['expense']); ?></b></td>
                                
                                  <td align="right" class="cashflowamnt" style="border: solid 2px #000;">
                                    <b><?php echo number_format($total_expense,2);?></b>
                                  </td>
                              </tr>

                            </table>
                    </div>
                </div>
                <div class="row no-print">
                  <div class="col-sm-12">
                    <?php if($hasPrintAccess){ ?>
                    <button type="button" class="btn btn-info" onclick="printContent('printDiv')"><i class="fa fa-print"></i> <?php echo get_phrases(['print']);?></button>
                    <?php }?>
                  </div>
                </div>
            </div>
      
        </div>
        <?php } else if( session('branchId') == '' || session('branchId') == 0 ){ ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong class="fs-20 text-danger"><?php echo get_notify('you_have_to_switch_to_a_specific_branch');?></strong>
                    </div>
                </div>
            </div>
        </div>
        <?php }else{ ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong class="fs-20 text-danger"><?php echo get_phrases(['you_do_not_have_permission_to_access_please_contact_with_administrator']);?></strong>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
    </div>
</div>