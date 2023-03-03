<link href="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<div class="row">
      <div class="col-sm-12 col-md-12">
            <div class="card ">

                  <div class="card-header py-2">
                        <div class="d-flex justify-content-between align-items-center">
                              <div>
                                    <nav aria-label="breadcrumb" class="order-sm-last p-0">
                                          <ol
                                                class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                                                <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle;?></a>
                                                </li>
                                                <li class="breadcrumb-item active"><?php echo $title;?></li>
                                          </ol>
                                    </nav>
                              </div>
                              <div class="text-right">
                                    <a href="<?php echo base_url('account/reports/sub_ledger'); ?>"
                                          class="btn btn-warning btn-sm mr-1 ml-2"><i
                                                class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                              </div>
                        </div>
                  </div>

                  <div class="card-body">
                        <?php echo form_open_multipart('account/reports/sub_ledger_report', 'id="glForm"');?>
                        <div class="row" id="">
                              <div class="col-sm-6">
                                    <div class="form-group row">
                                          <label for="date"
                                                class="col-sm-4 col-form-label"><?php echo get_phrases(['subtype'])?></label>
                                          <div class="col-sm-8">
                                                <select class="form-control" name="subtype" id="subtype"
                                                      onchange="showAccountSubhead(this.value);">
                                                      <option></option>
                                                      <?php
                                    foreach($general_ledger as $g_data){
                                        ?>
                                                      <option value="<?php echo $g_data->id;?>"
                                                            <?php echo  $g_data->id == $subtype  ? 'selected' : '' ;?>>
                                                            <?php echo $g_data->subtypeName;?></option>
                                                      <?php
                                    }
                                    ?>
                                                </select>
                                          </div>
                                    </div>
                                    <div class="form-group row">
                                          <label for="date"
                                                class="col-sm-4 col-form-label"><?php  echo get_phrases(['account','head'])?></label>
                                          <div class="col-sm-8">
                                                <?php  if($subtype != 1 ) { ?>
                                                <select name="accounthead" class="form-control" id="accounthead"
                                                      onchange="showTransationSubhead(this.value);">
                                                      <?php
                                    foreach($acchead as $ac){  ?>
                                                      <option value="<?php echo $ac->HeadCode;?>"
                                                            <?php echo  $ac->HeadCode == $accounthead? 'selected' : '' ; ?>>
                                                            <?php echo $ac->HeadName;?></option>
                                                      <?php  } ?>
                                                </select>
                                                <?php  } else { ?>
                                                <select name="accounthead" class="form-control" id="accounthead"
                                                      onchange="showTransationSubhead(this.value);"></select>
                                                <?php } ?>

                                          </div>
                                    </div>
                                    <div class="form-group row">
                                          <label for="date"
                                                class="col-sm-4 col-form-label"><?php  echo get_phrases(['transaction','head'])?></label>
                                          <div class="col-sm-8">
                                                <?php  if($subtype != 1 ) { ?>
                                                <select name="subcode" class="form-control" id="subcode">
                                                      <option value="all">All</option>
                                                      <?php
                                                   foreach($subcodes as $sc){  ?>
                                                      <option value="<?php echo $sc->id;?>"
                                                            <?php echo  $sc->id == $subcode? 'selected' : '' ; ?>>
                                                            <?php echo $sc->name;?></option>
                                                      <?php  } ?>
                                                </select>
                                                <?php  } else { ?>
                                                <select name="subcode" class="form-control" id="subcode"> </select>
                                                <?php } ?>

                                          </div>
                                    </div>

                                    <div class="form-group row">
                                          <label for="date"
                                                class="col-sm-4 col-form-label"><?php echo get_phrases(['from','date']) ?></label>
                                          <div class="col-sm-8">
                                                <input type="text" name="dtpFromDate" value="<?php echo $dtpFromDate;?>"
                                                      placeholder="<?php echo get_phrases(['date']) ?>"
                                                      class="datepicker form-control">
                                          </div>
                                    </div>

                                    <div class="form-group row">
                                          <label for="date"
                                                class="col-sm-4 col-form-label"><?php echo get_phrases(['to','date']) ?></label>
                                          <div class="col-sm-8">
                                                <input type="text" name="dtpToDate" value="<?php echo $dtpToDate;?>"
                                                      placeholder="<?php echo get_phrases(['date']) ?>"
                                                      class="datepicker form-control">
                                          </div>
                                    </div>
                                    <div class="form-group text-right">
                                          <button type="submit"
                                                class="btn btn-success w-md m-b-5"><?php echo get_phrases(['find']) ?></button>
                                    </div>
                              </div>
                        </div>
                        <?php echo form_close();?>
                  </div>
            </div>
      </div>
</div>
<hr>
<div class="row">
      <div class="col-sm-12 col-md-12">
            <div class="card card-bd lobidrag">

                  <div class="card-body">
                        <div id="printArea">
                              <?php if($subcode == 'all'){
                        $reportModel = new \App\Modules\Account\Models\Bdtaskt1m8ReportModel();
                        $subcodes = $reportModel->get_subTypeItems($subtype);
                        $coatype = $reportModel->general_led_report_headname($accounthead); 
                         ?>
                              <table width="99%"
                                    class="datatable table table-striped table-bordered table-hover general_ledger_report_tble">
                                    <thead>
                                          <tr>
                                                <th>SubCode</th>
                                                <th>SubCode Ledger</th>
                                                <th>Payable</th>
                                                <th>Receivable</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php 
                        $total_payable    = 0;
                        $total_receivable = 0;
                        $netbal = 0;
                        if($subcodes){
                              foreach($subcodes as $nsubcode){
                                         
                              $ledger = $reportModel->get_general_ledger_report_subheadSummary($accounthead,$dtpFromDate,$dtpToDate,1,0,$subtype,$nsubcode->id);
                              
                              $openingb = $reportModel->get_opening_balance_subtypenew($accounthead,$dtpFromDate,$dtpToDate,$subtype,$nsubcode->id);
                             

                             

                              if($coatype->HeadType == 'A' || $coatype->HeadType == 'E') {
                                    $balance = (($ledger->total_debit?$ledger->total_debit:0) - ($ledger->total_credit?$ledger->total_credit:0)) + ($openingb?$openingb:0);
                                 } else {
                                    $balance = (($ledger->total_credit?$ledger->total_credit:0) - ($ledger->total_debit?$ledger->total_debit:0)) + ($openingb?$openingb:0);
                                 }

                              ?>
                                          <tr>
                                                <td><?php echo $nsubcode->id;?></td>
                                                <td><?php echo $nsubcode->name?></td>
                                                <td class="text-center"><?php if($coatype->HeadType == 'A' || $coatype->HeadType == 'E') {
                                                echo ($balance < 0?$balance:'');
                                                $total_payable += ($balance < 0?$balance:0);
                                                }else{
                                              echo ($balance > 0?$balance:'');
                                                $total_payable += ($balance > 0?$balance:0);
                                                }?></td>
                                                <td class="text-center"><?php if($coatype->HeadType == 'A' || $coatype->HeadType == 'E') {
                                                echo ($balance > 0?$balance:'');
                                                $total_receivable += ($balance > 0?$balance:0);
                                                }else{
                                              echo ($balance < 0?$balance:'');
                                               $total_receivable += ($balance < 0?$balance:0);
                                                }?></td>
                                          </tr>

                                          <?php 

                              }
                                }
                        
                        
                                    ?>



                                    </tbody>
                                    <tfoot>
                                          <tr>
                                                <th colspan="2" class="text-right">Total</th>
                                                <th class="text-center"><?php echo $total_payable;?></th>
                                                <th class="text-center"><?php echo $total_receivable;?></th>
                                          </tr>
                                          <tr>
                                                <th colspan="3" class="text-right">Net Balance</th>
                                               
                                                <th class=""><?php
                                                if($coatype->HeadType == 'A' || $coatype->HeadType == 'E') {
                                                 $netbal = ($total_receivable?$total_receivable:0) - ($total_payable?$total_payable:0);     
                                                }else{
                                                      $netbal = ($total_payable?$total_payable:0) - ($total_receivable?$total_receivable:0);      
                                                }
                                                 echo $netbal;?></th>
                                          </tr>
                                    </tfoot>
                              </table>


                              <?php }else{?>
                              <table width="99%"
                                    class="datatable table table-striped table-bordered table-hover general_ledger_report_tble">
                                    <thead>
                                          <tr align="center">
                                                <td colspan="9">
                                                      <font size="+1" class="general_ledger_report_fontfamily">
                                                            <strong><?php echo get_phrases(['general','ledger','of']).' '.$ledger->HeadName.' ('.$subLedger->name.') on '.date('d-m-Y', strtotime($dtpFromDate)). ' To '  .date('d-m-Y', strtotime($dtpToDate));?></strong>
                                                      </font><strong></th></strong>
                                          </tr>
                                          <tr>
                                                <td height="25" width="5%">
                                                      <strong><?php echo get_phrases(['sl']);?></strong>
                                                </td>
                                                <td width="10%">
                                                      <strong><?php echo get_phrases(['transaction','date']);?></strong>
                                                </td>
                                                <td width="10%">
                                                      <strong><?php echo get_phrases(['voucher','no']);?></strong>
                                                </td>
                                                <td width="8%">
                                                      <strong><?php echo get_phrases(['voucher','type']);?></strong>
                                                </td>

                                                <td width="12%"><strong><?php echo "Head Name";?></strong></td>

                                                <td width="25%">
                                                      <strong><?php echo get_phrases(['ledger','comment'])?></strong>
                                                </td>

                                                <td width="10%" align="right">
                                                      <strong><?php echo get_phrases(['debit']);?></strong>
                                                </td>
                                                <td width="10%" align="right">
                                                      <strong><?php echo get_phrases(['credit']);?></strong>
                                                </td>
                                                <td width="10%" align="right">
                                                      <strong><?php echo get_phrases(['balance']);?></strong></td>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php              
                $TotalCredit=0;
                $TotalDebit  = 0;
                $CurBalance =$prebalance;
                $openid = 1; ?>
                                          <tr>
                                                <td height="25"><?php echo $openid ;?></td>
                                                <td><?php echo date('d-m-Y', strtotime($dtpFromDate)); ?></td>

                                                <td colspan="4" align="right"> <strong>Opening Balance</strong></td>
                                                <td align="right">
                                                      <?php echo $setting->currency_symbol. ' '. number_format(0,2,'.',','); ?>
                                                </td>
                                                <td align="right">
                                                      <?php echo $setting->currency_symbol. ' '. number_format(0,2,'.',','); ?>
                                                </td>
                                                <td align="right">
                                                      <strong><?php echo $setting->currency_symbol. ' '. number_format($prebalance,2,'.',','); ?></strong>
                                                </td>
                                          </tr>
                                          <?php  
                foreach($HeadName2 as $key=>$data) { ?>
                                          <tr>
                                                <td height="25"><?php echo (++$key + $openid) ;?></td>
                                                <td><?php echo date('d-m-Y', strtotime($data->VDate)); ?></td>
                                                <td><?php echo $data->VNo; ?></td>
                                                <td><?php if($data->Vtype=='DV') {echo 'Debit';} else if($data->Vtype=='CV') { echo 'Credit';} else if ($data->Vtype=='JV') { echo 'Journal';} else { echo 'Contra';} ?>
                                                </td>
                                                <td><?php echo $data->HeadName; ?></td>
                                                <td><?php echo $data->ledgerComment; ?></td>

                                                <td align="right">
                                                      <?php echo $setting->currency_symbol. ' '. number_format($data->Debit,2,'.',','); ?>
                                                </td>
                                                <td align="right">
                                                      <?php echo $setting->currency_symbol. ' '. number_format($data->Credit,2,'.',','); ?>
                                                </td>

                                                <?php 
                     $TotalDebit += $data->Debit;
                     $TotalCredit += $data->Credit;
                     if($ledger->HeadType == 'A' || $ledger->HeadType == 'E') {
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
                                                <td align="right">
                                                      <?php echo $setting->currency_symbol. ' '.  number_format($CurBalance,2,'.',','); ?>
                                                </td>
                                          </tr>
                                          <?php } ?>
                                    <tfoot>
                                          <tr class="table_data">
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td align="center">&nbsp;</td>
                                                <td colspan="3" align="right">
                                                      <strong><?php echo get_phrases(['total'])?></strong>
                                                </td>
                                                <td align="right">
                                                      <strong><?php echo $setting->currency_symbol. ' '. number_format($TotalDebit,2,'.',','); ?></strong>
                                                </td>
                                                <td align="right">
                                                      <strong><?php echo $setting->currency_symbol. ' '. number_format($TotalCredit,2,'.',','); ?></strong>
                                                </td>
                                                <td align="right">
                                                      <strong><?php echo $setting->currency_symbol. ' '. number_format($CurBalance,2,'.',','); ?></strong>
                                                </td>
                                          </tr>
                                    </tfoot>
                                    </tbody>
                                    <h4 style="margin-left: 10px; margin-top: 15px;">
                                          <?php echo get_phrases(['opening','balance'])?> :
                                          <?php echo $setting->currency_symbol. ' '. number_format($prebalance,2,'.',','); ?>
                                          <br /> <?php echo get_phrases(['closing','balance'])?> :
                                          <?php echo $setting->currency_symbol. ' '. number_format($CurBalance,2,'.',','); ?>
                                    </h4>
                              </table>


                              <?php }?>
                        </div>
                        <div class="text-center general_ledger_report_btn" id="print">
                              <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print"
                                    onclick="printContent('printArea');" />
                        </div>
                  </div>
            </div>
      </div>
</div>
<script src="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script>
      "use strict";

      function showTransationSubhead(id) {

            $('#subcode').html('');
            var id = $("#subtype").val();
            var url = _baseURL + "account/reports/getSubcodes/" + id;

            $.ajax({
                  url: url,
                  type: "GET",
                  dataType: "json",
                  success: function (data) {
                        if (data != '') {
                              $('#subcode').html(data);
                        }
                  },
                  error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data for inner');
                  }
            });
      }

      /*get account subhead*/
      "use strict";

      function showAccountSubhead(id) {
            $('#accounthead').html('');
            var url = _baseURL + "account/reports/getSubAccountHead/" + id;
            $.ajax({
                  url: url,
                  type: "GET",
                  dataType: "json",
                  success: function (data) {
                       
                        if (data != '') {

                              $('#accounthead').html(data);
                              showTransationSubhead(id);
                        }
                  },
                  error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                  }
            });
      }

      $(document).ready(function () {
            "use strict";
            $('.datepicker').datepicker({
                  dateFormat: 'yy-mm-dd'
            });

      });
      "use strict";
</script>