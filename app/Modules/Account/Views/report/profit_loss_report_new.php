<div class="row" id="printDiv">
      <div class="col-md-12">
          <center>
             <h5 class="mb-0"><?php echo get_phrases(['profit', 'loss', 'report']);?> </h5>
            <h6><?php echo $dateRange;?></h6>
            </center>
          <table width="100%" class="table table-bordered table-sm table-hover" cellpadding="0" cellspacing="0">
              <tr class="table_head">
                  <td width="60%" height="29"><b><?php echo get_phrases(['account', 'name']);?></b></td>
                  <td width="20%" height="29"><b><?php echo get_phrases(['account', 'head']);?></b></td>
                 
                  <td width="20%" align="right"><b><?php echo get_phrases(['amount']);?></b></td>
              </tr>
              

                <?php
                 $total_expense = 0;
                 $total_income = 0;
                 foreach($incomes as $income){
                  $blc = !empty($income->balance)?$income->balance:0;
                  $total_income +=$blc;
                  if($income->HeadLevel==1){
                    $space = 'font-weight-600';
                  }else if($income->HeadLevel==2){
                    $space = 'ml-2';
                  }else if($income->HeadLevel==3){
                    $space = 'ml-3';
                  }else if($income->HeadLevel==4){
                    $space = 'ml-4';
                  }else{
                    $space = 'ml-5';
                  }
                  ?>
                  <tr >
                    <td><span class="<?php echo $space;?>"><?php echo $income->HeadName;?></span></td>
                    <td><?php echo $income->HeadCode;?></td>
                    <td align="right"><?php echo number_format($blc, 2);?></td>
                  </tr>
                <?php }?>
                  <tr >
                    <td colspan="2" class="text-right"  style="padding-right: 10px;"><b><?php echo get_phrases(['total', 'income'])?> </b></td>
                  
                    <td align="right" class="cashflowamnt" style="border: solid 2px #000;">
                      <b><?php $incm = abs($total_income); echo number_format($incm,2);?></b>
                    </td>
                  </tr>
                   <?php 
                   foreach($expenses as $expense){ 
                      $blce = !empty($expense->balance)?$expense->balance:0;
                      $total_expense +=$blce;
                      if($expense->HeadLevel==1){
                        $space = 'font-weight-600';
                      }else if($expense->HeadLevel==2){
                        $space = 'ml-2';
                      }else if($expense->HeadLevel==3){
                        $space = 'ml-3';
                      }else if($expense->HeadLevel==4){
                        $space = 'ml-4';
                      }else{
                        $space = 'ml-5';
                      }
                    ?>
                    <tr >
                      <td><span class="<?php echo $space;?>"><?php echo $expense->HeadName;?></span></td>
                      <td><?php echo $expense->HeadCode;?></td>
                      <td align="right"><?php echo number_format($blce, 2);?></td>
                    </tr>
                   <?php } ?>
                  <tr >
                    <td colspan="2" class="text-right"  style="padding-right: 10px;"><b><?php echo get_phrases(['total', 'expense'])?></b></td>
                  
                    <td align="right" class="cashflowamnt" style="border: solid 2px #000;">
                      <b><?php $exp = abs($total_expense); echo number_format($exp,2);?></b>
                    </td>
                </tr>

                <tr >
                    <td colspan="2" class="text-right"  style="padding-right: 10px;"><b>
                      <?php 
                        $profitLoss = $incm - $exp;
                        if($profitLoss > 0){
                          $color = 'text-success';
                          $text = 'Profit';
                        }else{
                          $color = 'text-success';
                          $text = 'Loss';
                        }
                      ?>
                      Profit-Loss(<?php echo $text;?>)
                    </b></td>
                  
                    <td align="right" class="cashflowamnt" style="border: solid 2px #000;">
                      <b class="<?php echo $color;?>"><?php echo number_format(abs($profitLoss),2);?></b>
                    </td>
                </tr>

              </table>
      </div>
</div> 
<div class="row no-print">
  <div class="col-sm-12">
    <?php //if($permission->method('profit_loss', 'print')->access()){ ?>
    <button type="button" class="btn btn-info" onclick="printContent('printDiv')"><i class="fa fa-print"></i> <?php echo get_phrases(['print']);?></button>
    <?php //}?>
  </div>
</div>