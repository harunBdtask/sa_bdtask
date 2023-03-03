<div class="row">
    <div class="col-sm-12">
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
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']) ?></a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="printDiv">
                <div class="row">
                  <div class="col-md-6">
                      <img src="<?php echo base_url().$setting->logo;?>" class="img-responsive" alt=""></br>
                      <?php echo get_phrases(['phone']) ?>: <?php echo $setting->phone; ?></br>
                      <?php echo get_phrases(['email']) ?>: <?php echo $setting->email; ?>
                      <br>
                  </div>
                  <div class="col-md-6 text-right">
                       <address>
                          <strong><?php echo $setting->title; ?></strong><br>
                          (<?php echo $setting->nameA; ?>)<br>
                          <?php echo $setting->address; ?>
                      </address>
                  </div>
                </div> <hr>
                <table width="100%" class="table table-bordered table-sm table-hover" cellpadding="0" cellspacing="0">
                  <tr>
                      <td colspan="3" align="center"><b><?php echo get_phrases(['cash_flow_statement']);?>  </b></td>
                  </tr>
                  <tr class="table_head">
                      <td colspan="3" align="center"><b><?php echo esc($dateRange); ?></b></td>
                  </tr>
                  <tr class="table_head">
                      <td width="73%" height="29" align="center" class="cashflowparticular"><b><?php echo get_phrases(['particulars']);?></b></td>
                      <td width="2%">&nbsp;</td>
                      <td width="30%" align="right" class="cashflowamount"><b><?php echo get_phrases(['amount']);?></b></td>
                  </tr>
                   <tr class="table_head">
                    <td colspan="3" class="paddingleft10px"><strong><?php echo get_notify('opening_cash_and_equivalent');?>:</strong></td>
                  </tr>
                  <?php
                    use App\Modules\Account\Models\Bdtaskt1m8ReportModel;
                    $accountModel  = new Bdtaskt1m8ReportModel();
                    $db = db_connect();
                    $sql=$accountModel->cashflow_firstquery();

                    $sql = $db->query($sql);
                    $oResultAsset = $sql->getResult();
            
                    $TotalOpening=0;
                    $transIdss = '';
                    for($i=0;$i<count($oResultAsset);$i++)
                    {
                      $COAID=$oResultAsset[$i]->HeadCode;
                      $sql=$accountModel->cashflow_secondquery($branch_id, $dtpFromDate,$dtpToDate,$COAID); 

                      $sql1 = $db->query($sql);
                      $oResultAmountPre = $sql1->getRow();
                      if($oResultAmountPre->Amount!=0)
                      {
                      $transIds = str_replace(',', '-', $oResultAmountPre->Ids);
                      $transIdss .=$transIds.'-';
                  ?>
                    <tr class="clickable-row viewTrans" data-ids="<?php echo $transIds;?>">
                        <td align="left" class="paddingleft10px"><?php echo esc($oResultAsset[$i]->HeadName); ?></td>
                        <td>&nbsp;</td>
                        <td align="right" class="cashflowamnt <?php if($TotalOpening==0) echo 'footersignature' ?>" >
                            <?php 
                                $Total=$oResultAmountPre->Amount;
                                echo number_format($Total, 2);
                        
                                $TotalOpening+=$Total; 
                            ?>
                        </td>
                    </tr>
                          <?php
                      }
                    }
                  ?>
                  <tr>
                    <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td class="footersignature">&nbsp;</td>
                  </tr>
                  <tr class="clickable-row viewTrans" data-ids="<?php echo $transIdss;?>">
                   <td align="left" class="paddingleftright10"><strong>Total Opening Cash & Cash Equivalent</strong></td>
                    <td>&nbsp;</td>
                     <td align="right" class="totalopeninig"><strong><?php echo number_format($TotalOpening); ?></strong></td>
                  </tr>
                  <tr class="table_head">
                      <td colspan="3" class="padddingwithunderline"><b>Cashflow from Operating Activities</b></td>
                  </tr>
                  <?php
                      $TotalCurrAsset=0;
                      $transIdss1 = '';
                      $sql=$accountModel->cashflow_thirdquery();

                      $sql2 = $db->query($sql);
                      $oResultCurrAsset = $sql2->getResult();
                      //echo "<pre>";print_r($oResultCurrAsset);die();
                      if(!empty($oResultCurrAsset)){
                        for($s=0;$s<count($oResultCurrAsset);$s++)
                        {
                          $COAID=$oResultCurrAsset[$s]->HeadCode;
                          $sql= $accountModel->cashflow_forthquery($branch_id,$dtpFromDate,$dtpToDate,$COAID);

                          $sql3 = $db->query($sql);
                          $oResultAmount = $sql3->getRow();

                          if($oResultAmount->Amount!=0)
                          {
                            $transIds1 = str_replace(',', '-', $oResultAmount->Ids);
                            $transIdss1 .= $transIds1.'-';
                            ?>
                            <tr class="clickable-row viewTrans" data-ids="<?php echo $transIds1;?>">
                                <td align="left" class="paddingleft10px"><?php echo esc($oResultCurrAsset[$s]->HeadName); ?></td>
                                <td>&nbsp;</td>
                                <td align="right" class="cashflowamnt <?php if($TotalCurrAsset==0) echo 'footersignature' ?>">
                                    <?php 
                                        $Total=$oResultAmount->Amount;
                                        echo number_format($Total, 2);
                                        $TotalCurrAsset+=$Total; 
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                      }
                       $sql=$accountModel->cashflow_fifthquery($branch_id,$dtpFromDate,$dtpToDate,$COAID);

                        $sql4 = $db->query($sql);
                        $oResultAmount = $sql4->getRow();

                        if($oResultAmount->Amount!=0)
                        {
                          $transIds2 = str_replace(',', '-', $oResultAmount->Ids);
                          $transIdss1 .= $transIds2.'-';
                          ?>
                         <tr class="clickable-row viewTrans" data-ids="<?php echo $transIds2;?>">
                            <td align="left" class="paddingleft10px">Payment for Other Operating Activities</td>
                            <td>&nbsp;</td>
                            <td align="right"  class="cashflowamnt">
                                <?php 
                                    $Total=$oResultAmount->Amount;
                                    echo number_format($Total,2);
                                    $TotalCurrAsset+=$Total; 
                                ?>
                            </td>
                        </tr>
                        <?php
                      }
                    }
                ?>
                   <tr >
                      <td >&nbsp;</td>
                       <td>&nbsp;</td>
                     <td class="footersignature">&nbsp;</td>
                  </tr>
                  <tr class="clickable-row viewTrans" data-ids="<?php echo $transIdss1;?>">
                      <td align="left" class="paddingleftright10"><strong>Cash generated from Operating Activites before Changing in Opereating Assets &amp; Liabilities</strong></td>
                       <td>&nbsp;</td>
                     <td align="right" class="totalopeninig"><strong><?php echo number_format($TotalCurrAsset, 2); ?></strong></td>
                  </tr>
                  
                  <tr class="table_head">
                      <td colspan="3" class="padddingwithunderline"><b>Cashflow from Non Operating Activities</b></td>
                  </tr>
                  <?php
                    $TotalCurrAssetNon=0;
                    $transIdss3 = '';
                    $sql=$accountModel->cashflow_sixthquery();

                    $sql5 = $db->query($sql);
                    $oResultCurrAsset = $sql5->getResult();

                    for($s=0;$s<count($oResultCurrAsset);$s++)
                    {
                    $COAID=$oResultCurrAsset[$s]->HeadCode;
                    $sql=$accountModel->cashflow_seventhquery($branch_id,$dtpFromDate,$dtpToDate,$COAID);

                    $sql6 = $db->query($sql);
                    $oResultAmount = $sql6->getRow();

                    if($oResultAmount->Amount!=0)
                    {
                    $transIds3 = str_replace(',', '-', $oResultAmount->Ids);
                    $transIdss3 .= $transIds3.'-';
                  ?>
                    <tr class="clickable-row viewTrans" data-ids="<?php echo $transIds3;?>">
                        <td align="left" class="paddingleft10px"><?php echo esc($oResultCurrAsset[$s]->HeadName); ?></td>
                        <td>&nbsp;</td>
                        <td align="right" class="cashflowamnt <?php if($TotalCurrAssetNon==0) echo 'footersignature' ?>">
                    <?php 
                        $Total=$oResultAmount->Amount;
                        echo number_format($Total, 2);
                        $TotalCurrAssetNon+=$Total; 
                    ?>
                        </td>
                    </tr>
                  <?php
                      }
                    }
                  ?>
                   <tr >
                      <td >&nbsp;</td>
                       <td>&nbsp;</td>
                     <td class="footersignature">&nbsp;</td>
                  </tr>
                  <tr class="clickable-row viewTrans" data-ids="<?php echo $transIdss3;?>">
                      <td align="left" class="paddingleftright10"><strong>Cash generated from Non Operating Activites before Changing in Opereating Assets &amp; Liabilities</strong></td>
                       <td>&nbsp;</td>
                     <td align="right" class="totalopeninig"><strong><?php echo number_format($TotalCurrAssetNon); ?></strong></td>
                  </tr>
                  <tr >
                      <td >&nbsp;</td>
                       <td>&nbsp;</td>
                     <td >&nbsp;</td>
                  </tr>
                   <tr class="table_head">
                      <td align="left" class="paddingleftright10"><strong>Increase/Decrease in Operating Assets &amp; Liabilites</strong></td>
                     <td>&nbsp;</td>
                     <td align="right" class="pddingright10">&nbsp;</td>
                </tr>
                  
                <?php
                $TotalCurrLiab=0;
                $transIdss4 = '';
                $sql="SELECT * FROM acc_coa WHERE IsGL=1 AND HeadCode LIKE '20101%' AND HeadCode!=20101 AND IsActive=1";

                $sql6 = $db->query($sql);
                $oResultLiab = $sql6->getResult();

                for($t=0;$t<count($oResultLiab);$t++)
                {
                $COAID=$oResultLiab[$t]->HeadCode;

                $sql="SELECT SUM(acc_transaction.Credit)-SUM(acc_transaction.Debit) AS Amount, GROUP_CONCAT(acc_transaction.ID) as Ids FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND acc_transaction.VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND acc_transaction.COAID LIKE '$COAID%' AND acc_transaction.BranchID ='$branch_id' AND acc_transaction.VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1211%' AND BranchID ='$branch_id')";
                $oResultAmount=$oAccount->SqlQuery($sql);

                $sql7 = $db->query($sql);
                $oResultAmount = $sql7->getRow();

                if($oResultAmount->Amount!=0)
                {
                  $transIds4 = str_replace(',', '-', $oResultAmount->Ids);
                  $transIdss4 .= $transIds4.'-';
                  ?>
                  <tr class="clickable-row viewTrans" data-ids="<?php echo $transIds4;?>">
                      <td align="left" class="paddingleft10px"><?php echo esc($oResultLiab[$t]->HeadName); ?></td>
                      <td>&nbsp;</td>
                      <td align="right" class="cashflowamnt <?php if($TotalCurrLiab==0) echo 'footersignature' ?>">
                          <?php 
                              $Total=$oResultAmount->Amount;
                              echo number_format($Total, 2);
                              $TotalCurrLiab+=$Total;
                          ?>
                      </td>
                  </tr>
                  <?php
              }
                    }
                  ?>
                   <tr >
                      <td >&nbsp;</td>
                       <td>&nbsp;</td>
                     <td class="footersignature">&nbsp;</td>
                  </tr>
                  <tr class="clickable-row viewTrans" data-ids="<?php echo $transIdss4;?>">
                      <td align="left" class="paddingleftright10"><strong>Total Increase/Decrease</strong></td>
                       <td>&nbsp;</td>
                     <td align="right" class="totalopeninig"><strong><?php echo number_format($TotalCurrLiab, 2); ?></strong></td>
                  </tr>
                 <tr >
                      <td >&nbsp;</td>
                       <td>&nbsp;</td>
                     <td >&nbsp;</td>
                  </tr>
                  <tr>
                      <td align="left" class="paddingleftright10"><strong>Net Cash From Operating/Non Operating Activities</strong></td>
                      <td>&nbsp;</td>
                      <td align="right" class="totalopeninig"><strong><?php echo number_format(($TotalCurrAsset+$TotalCurrAssetNon+$TotalCurrLiab), 2); ?></strong></td>
                  </tr>
                   <tr >
                      <td >&nbsp;</td>
                       <td>&nbsp;</td>
                     <td >&nbsp;</td>
                  </tr>
                  <tr class="table_head">
                      <td colspan="3" class="padddingwithunderline"><b>Cash Flow from Investing Activities</b></td>
                  </tr>
                  <?php
                  $TotalNonCurrAsset=0;
                  $transIdss5 = '';
                  $sql="SELECT * FROM acc_coa WHERE IsGL=1 AND HeadCode LIKE '11%' AND HeadCode!=101 AND IsActive=1";

                  $sql9 = $db->query($sql);
                  $oResultNonCurrAsset = $sql9->getResult();

                  for($t=0;$t<count($oResultNonCurrAsset);$t++)
                  {
                  $COAID=$oResultNonCurrAsset[$t]->HeadCode;

                  $sql="SELECT SUM(acc_transaction.Debit)-SUM(acc_transaction.Credit) AS Amount, GROUP_CONCAT(acc_transaction.ID) as Ids FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND acc_transaction.VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND acc_transaction.COAID LIKE '$COAID%' AND acc_transaction.BranchID ='$branch_id' AND acc_transaction.VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1211%' AND BranchID ='$branch_id')";

                  $sql8 = $db->query($sql);
                  $oResultAmount = $sql8->getRow();

                  if($oResultAmount->Amount!=0)
                  {
                    $transIds5 = str_replace(',', '-', $oResultAmount->Ids);
                    $transIdss5 .= $transIds5.'-';
                  ?>
                  <tr class="clickable-row viewTrans" data-ids="<?php echo $transIds5;?>">
                      <td align="left" class="paddingleft10px"><?php echo $oResultNonCurrAsset[$t]->HeadName; ?></td>
                      <td>&nbsp;</td>
                      <td align="right" class="cashflowamnt <?php if($TotalNonCurrAsset==0) echo 'footersignature' ?>">
                          <?php 
                              $Total=$oResultAmount->Amount;
                              echo number_format($Total,2);
                              $TotalNonCurrAsset+=$Total;
                          ?>
                      </td>
                  </tr>
                  <?php
              }
                    }
                  ?>
                   <tr >
                      <td >&nbsp;</td>
                       <td>&nbsp;</td>
                     <td class="footersignature">&nbsp;</td>
                  </tr>
                  <tr class="clickable-row viewTrans" data-ids="<?php echo $transIdss5;?>">
                      <td align="left" class="paddingleftright10"><strong>Net Cash Used Investing Activities</strong></td>
                      <td>&nbsp;</td>
                      <td align="right" class="noncurcss"><strong><?php echo number_format($TotalNonCurrAsset, 2); ?></strong></td>
                  </tr>
                   <tr >
                      <td >&nbsp;</td>
                       <td>&nbsp;</td>
                     <td >&nbsp;</td>
                  </tr>
                 
                  <tr class="table_head">
                      <td colspan="3" class="padddingwithunderline"><b>Cash Flow from Financing Activities</b></td>
                  </tr>
                  <?php
                  $TotalNonCurrLiab=0;
                  $sql="SELECT * FROM acc_coa WHERE IsGL=1 AND HeadCode LIKE '20102%' AND IsActive=1";

                  $sql10 = $db->query($sql);
                  $oResultNonCurrLiab = $sql10->getResult();

                  for($t=0;$t<count($oResultNonCurrLiab);$t++)
                  {
                  $COAID=$oResultNonCurrLiab[$t]->HeadCode;

                  $sql="SELECT SUM(acc_transaction.Credit)-SUM(acc_transaction.Debit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND acc_transaction.VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND acc_transaction.COAID LIKE '$COAID%' AND acc_transaction.BranchID ='$branch_id' AND acc_transaction.VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1211%' AND BranchID ='$branch_id')";

                  $sql11 = $db->query($sql);
                  $oResultAmount = $sql11->getRow();

                  if($oResultAmount->Amount!=0)
                  {
                      ?>
                  <tr >
                      <td align="left" class="paddingleft10px"><?php echo $oResultNonCurrLiab[$t]->HeadName; ?></td>
                      <td>&nbsp;</td>
                      <td align="right" class="cashflowamnt <?php if($TotalNonCurrLiab==0) echo 'footersignature' ?>">
                          <?php 
                              $Total=$oResultAmount->Amount;
                              echo number_format($Total);
                              $TotalNonCurrLiab+=$Total;
                          ?>
                      </td>
                  </tr>
                  <?php
                    }
                  }
                  ?>
                  <?php
                  $TotalFund=0;
                  $sql="SELECT * FROM acc_coa WHERE IsGL=1 AND HeadCode LIKE '202%' AND IsActive=1";

                  $sql12 = $db->query($sql);
                  $oResultFund = $sql12->getResult();


                  for($t=0;$t<count($oResultFund);$t++)
                  {
                  $COAID=$oResultFund[$t]->HeadCode;

                  $sql="SELECT SUM(acc_transaction.Credit)-SUM(acc_transaction.Debit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND acc_transaction.VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND acc_transaction.COAID LIKE '$COAID%' AND acc_transaction.BranchID ='$branch_id' AND acc_transaction.VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1211%' AND BranchID ='$branch_id')";

                  $sql13 = $db->query($sql);
                  $oResultAmount = $sql13->getRow();

                  if($oResultAmount->Amount!=0)
                  {
                  ?>
                  <tr >
                      <td align="left" class="paddingleft10px"><?php echo esc($oResultFund[$t]->HeadName); ?></td>
                      <td>&nbsp;</td>
                      <td align="right" class="cashflowamnt">
                          <?php 
                              $Total=$oResultAmount->Amount;
                              echo number_format($Total,2);
                              $TotalFund+=$Total;
                          ?>
                      </td>
                  </tr>
                  <?php
              }
                    }
                  ?>
                   <tr >
                      <td >&nbsp;</td>
                       <td>&nbsp;</td>
                     <td class="footersignature">&nbsp;</td>
                  </tr>
                  <tr >
                      <td align="left" class="paddingleftright10"><strong>Net  Cash Used Financing Activities</strong></td>
                      <td>&nbsp;</td>
                      <td align="right" class="cashflowamnt"><strong><?php echo number_format($TotalFund+$TotalNonCurrLiab, 2); ?></strong></td>
                  </tr>
                   <tr >
                      <td >&nbsp;</td>
                       <td>&nbsp;</td>
                     <td >&nbsp;</td>
                  </tr>
                  <tr >
                      <td align="left" class="paddingleft10px"><strong>Net Cash Inflow/Outflow (Profit Loss <?php echo number_format(($TotalCurrAsset+$TotalCurrAssetNon+$TotalCurrLiab+$TotalNonCurrAsset+$TotalFund+$TotalNonCurrLiab), 2); ?>)</strong></td>
                      <td>&nbsp;</td>
                      <td align="right" class="totalopeninig"><strong><?php echo number_format(($TotalCurrAsset+$TotalCurrAssetNon+$TotalCurrLiab+$TotalNonCurrAsset+$TotalFund+$TotalNonCurrLiab+$TotalOpening),2); ?></strong></td>
                  </tr>
                   <tr >
                      <td >&nbsp;</td>
                       <td>&nbsp;</td>
                     <td >&nbsp;</td>
                  </tr>
                
                <tr class="table_head">
                    <td colspan="3" class="paddingleft10px"><strong>Closing Cash & Cash Equivalent:</strong></td>
                  </tr>
                <?php
                  $sql="SELECT * FROM acc_coa WHERE IsTransaction=1 AND HeadType='A' AND IsActive=1 AND HeadCode LIKE '121100001%' ";

                  $sql14 = $db->query($sql);
                  $oResultAsset = $sql14->getResult();

                  $TotalAsset=0;
                  $transIdss6 = '';
                  for($i=0;$i<count($oResultAsset);$i++)
                  {
                    $COAID=$oResultAsset[$i]->HeadCode;
                    $sql="SELECT SUM(acc_transaction.Debit)- SUM(acc_transaction.Credit) AS Amount, GROUP_CONCAT(acc_transaction.ID) as Ids FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove =1 AND acc_transaction.VDate BETWEEN  '".$dtpFromDate."' AND '".$dtpToDate."' AND acc_transaction.COAID LIKE '$COAID%' AND acc_transaction.BranchID ='$branch_id'";

                    $sql15 = $db->query($sql);
                    $oResultAmount = $sql15->getRow();

                    if($oResultAmount->Amount!=0)
                    {
                    $transIds6 = str_replace(',', '-', $oResultAmount->Ids);
                    $transIdss6 .= $transIds6.'-';
                ?>
                  <tr class="clickable-row viewTrans" data-ids="<?php echo $transIds6;?>">
                      <td align="left" class="paddingleft10px"><?php echo $oResultAsset[$i]->HeadName; ?></td>
                      <td>&nbsp;</td>
                      <td align="right" class="cashflowamnt <?php if($TotalAsset==0) echo 'footersignature' ?>">
                          <?php 
                              $Total=$oResultAmount->Amount;
                              echo number_format($Total, 2);
                                  $TotalAsset+=$Total; 
                          ?>
                      </td>
                  </tr>
                        <?php
                    }
                  }
                ?>
                  <tr>
                    <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td class="footersignature">&nbsp;</td>
                  </tr>
                  <tr class="clickable-row viewTrans" data-ids="<?php echo $transIdss6;?>">
                   <td align="left" class="paddingleftright10"><strong>Total Closing Cash & Cash Equivalent</strong></td>
                    <td>&nbsp;</td>
                     <td align="right" class="totalopeninig"><strong><?php echo number_format($TotalAsset, 2); ?></strong></td>
                  </tr>
                 
              </table>
              <br>
              <br><br>
              <table width="100%" cellpadding="1" cellspacing="20">
                      <tr>
                        <td width="20%" class="footersignature" align="center"><?php echo get_phrases(['prepared','by'])?></td>
                          <td width="20%" class="footersignature" align="center"><?php echo get_phrases(['accounts'])?></td>
                          <td width="20%" class="footersignature" align="center"><?php echo get_phrases(['authorized','signature'])?></td>
                          <td  width="20%" class="footersignature" align='center'><?php echo get_phrases(['chairman'])?></td>
                      </tr>
              </table>
              <div class="row no-print">
                <div class="col-sm-12">
                  <?php if($permission->method('cash_flow', 'print')->access()){ ?>
                  <button type="button" class="btn btn-info" onclick="printContent('printDiv')"><i class="fa fa-print"></i> <?php echo get_phrases(['print']);?></button>
                  <?php }?>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

<!-- view all transactions modal -->
<div class="modal fade bd-example-modal-xl" id="viewVoucherModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="viewVoucherModalLabel"><?php echo get_phrases(['view', 'all', 'transaction']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
               <div class="row">
                   <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo get_phrases(['id', 'no']);?></th>
                                        <th><?php echo get_phrases(['voucher', 'no']);?></th>
                                        <th><?php echo get_phrases(['voucher', 'type']);?></th>
                                        <th><?php echo get_phrases(['account', 'name']);?></th>
                                        <th><?php echo get_phrases(['account', 'number']);?></th>
                                        <th><?php echo get_phrases(['created', 'by']);?></th>
                                        <th><?php echo get_phrases(['created', 'date']);?></th>
                                        <th><?php echo get_phrases(['debit']);?></th>
                                        <th><?php echo get_phrases(['credit']);?></th>
                                        <th><?php echo get_phrases(['balance']);?></th>
                                    </tr>
                                </thead>
                                <tbody id="viewAllResult">
                                    
                                </tbody>
                            </table>
                        </div>
                   </div>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
            
        </div>
    </div>
</div>
<script type="text/javascript">
    function get_item_details(request_id){

        if(request_id !='' ){
            var submit_url = _baseURL+'appointment/inventory/getItemRequestQuantityDetailsById';
            var action = 'print';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'request_id':request_id, 'action':action },
                success: function(data) {
                    $('#item_details_preview').html(data);
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    $(document).ready(function() { 
       "use strict";
        
        var branch_id = '<?php echo $branch_id;?>';
        /// view all invoices info
        $(document).on('click', '.viewTrans', function(e){
            e.preventDefault();
            $('#viewVoucherModal').modal('show');
            var ids = $(this).attr('data-ids');
            
            if(ids){
                var submit_url = _baseURL+"account/reports/viewTransByIds"; 
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, branch_id:branch_id, ids:ids},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#viewAllResult').html('');
                        $('#viewAllResult').html(res.data);
                    }
                });  
            }else{
                alert('Empty transactions!')
            }
        });

    });
</script>