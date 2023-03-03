<script src="<?php echo base_url('application/modules/accounts/assets/js/general_ledger_report_script.js'); ?>" type="text/javascript"></script>
<div class="row">
     <div class="col-sm-12 col-md-12">
            <div class="card ">

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
                       <a href="<?php echo base_url('account/reports/GeneralLForm'); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>

                    <div class="card-body"> 
                    <?php echo form_open_multipart('account/reports/generalLedgerReportdata', 'id="glForm"');?>
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            
                            <select class="form-control" name="cmbCode" required="required">
                                <option></option>
                                <?php if($general_ledger){
                                    foreach($general_ledger as $head){?>
                                    <option value="<?php echo $head->HeadCode?>" <?php echo  $head->HeadCode == $cmbCode  ? 'selected' : '' ;?>><?php echo $head->HeadName?></option>
                                    <?php }}?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                        <input type="text" name="dtpFromDate" value="<?php echo   isset($dtpFromDate)? $dtpFromDate : date('Y-m-d'); ?>" placeholder="<?php echo get_phrases(['date']) ?>" class="datepickerui form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                        <input type="text"  name="dtpToDate" value="<?php echo  isset($dtpToDate)? $dtpToDate : date('Y-m-d'); ?>" placeholder="<?php echo get_phrases(['date']) ?>" class="datepickerui form-control" autocomplete="off">
                        </div>
                    </div>
                   
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1"><?php echo get_phrases(['filter']);?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close();?>
                    </div> 
             </div>
       </div>
 </div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card ">
            
         <div class="card-body" id="printArea">
            <p style="font-size:10px"><i>Print Date: <?php echo date('Y-m-d h:i:s')?></i></p>
            <table width="99%"  class="datatable table table-bordered table-hover general_ledger_report_tble"> 
                <thead>
                <tr align="center">
                    <td colspan="9"><font size="+1"  class="general_ledger_report_fontfamily"> <strong><?php echo get_phrases(['general','ledger','of']).' '.$ledger->HeadName.' on '.date('d-m-Y', strtotime($dtpFromDate)). ' To '  .date('d-m-Y', strtotime($dtpToDate));?></strong></font><strong></th></strong>
                </tr>
                <tr>
                    <td height="25" width="5%" style="background: #abbff9!important"><strong><?php echo get_phrases(['sl']);?></strong></td>
                    <td  width="10%" style="background: #abbff9!important"><strong><?php echo get_phrases(['transdate']);?></strong></td>
                    <td width="10%" style="background: #abbff9!important"><strong><?php echo get_phrases(['voucher','no']);?></strong></td>
                    <td width="8%" style="background: #abbff9!important"><strong><?php echo get_phrases(['voucher','type']);?></strong></td>
                    
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
                    $style = $k % 2 ? '#efefef!important' : ''; ?>
                <tr>
                    <td height="25" style="background:<?php echo $style; ?>"><?php echo (++$key + $openid) ;?></td>
                    <td style="background:<?php echo $style; ?>"><?php echo date('d-m-Y', strtotime($data->VDate)); ?></td>
                    <td style="background:<?php echo $style; ?>"><a href="javascript:void(0)" onclick="voucherDetails('<?php echo $data->VNo; ?>')"><?php echo $data->VNo; ?></a></td>
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
                    <?php echo get_phrases(['opening','balance'])?> : <?php echo number_format($prebalance,2,'.',','); ?>
                    <br /> <?php echo get_phrases(['closing','balance'])?> : <?php echo number_format($CurBalance,2,'.',','); ?>
                </h4>
       </table>
    </div>
    <div class="text-center pt-4 pb-3" >
    <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printContent('printArea');"/>
  </div>
 </div>
 <div class="modal fade bd-example-modal-lg" id="voucherdetails-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="detailsModalLabel"><?php echo get_phrases(['view', 'voucher', 'details']); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="voucherviewDetails">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']); ?></button>
            </div>
        </div>
    </div>
</div>
</div></div>

<script type="text/javascript">
    function voucherDetails(VNo) {
        $('#voucherviewDetails').html('');
           $('#voucherdetails-modal').modal('show');
            var submit_url = _baseURL + "account/vouchers/getVoucherDetails/" + VNo;
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {
                    'csrf_stream_name': csrf_val
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#voucherviewDetails').html('');
                    $('#voucherviewDetails').html(response.data);
                }
            });
    }
</script>