<div class="row">
  <div class="col-12 col-md-5">
      <h6 class="text-uppercase text-muted fs-12 font-weight-600"><?php echo get_phrases(['voucher', 'to']);?></h6>
      <p class="text-muted mb-4">
          <strong class="text-body fs-16"><?php echo esc($results->patient_name);?></strong> <br>
          <?php echo get_phrases(['file', 'no']);?> - <?php echo esc($results->file_no);?><br>
          <?php echo get_phrases(['gender']);?> - <?php echo esc($results->gender);?><br>
          <?php echo get_phrases(['mobile', 'no']);?> - <?php echo esc($results->mobile);?><br>
          <?php echo get_phrases(['age']);?> :  <?php echo esc(!empty($results->age)?$results->age:0);?><br>
          <?php echo get_phrases(['reference', 'invoice', 'no']);?> :  <a href="<?php echo base_url('account/services/detailsInvoice/'.$results->ref_voucher);?>"><?php echo esc($results->ref_voucher);?> </a>
      </p>
  </div>
  <div class="col-12 col-md-2 text-md-center font-weight-600 fs-20">
    <?php if($results->vtype=='PV'){
      echo get_phrases(['payment', 'voucher']);
    }else{
      echo get_phrases(['refund', 'voucher']);
    }
    ?>
  </div>
  <div class="col-12 col-md-5 text-md-right">
      <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"><?php echo get_phrases(['voucher', 'no']);?></h6>
      <?php echo esc($results->id);?>
      <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo get_phrases(['doctor', 'name']);?></h6>
      <?php echo esc($results->doctor);?>
      <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo get_phrases(['created', 'date']);?></h6>
      <p><time datetime=""><?php echo esc(date('F j, Y H:i A', strtotime($results->created_date)));?></time></p>
  </div>
</div> 

<div class="row">
  <div class="col-md-12">
    <div class="table-responsive">
      <div class="table-responsive">
          <table class="table table-sm">
              <?php 
              $tSVat = 0;
              $tSInvAmt = 0;
              $tSAmt = 0;
              if($results->vtype=='PV'){ ?>
                  <thead>
                    <tr>
                        <th>
                            <?php echo get_phrases(['description']);?>
                        </th>
                        <th class="text-right">
                            <?php echo get_phrases(['receipt', 'amount']);?>
                        </th>
                         <th class="text-right">
                            <?php echo get_phrases(['pay', 'vat']);?>
                        </th>
                        <th class="text-right">
                            <?php echo get_phrases(['pay', 'amount']);?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $tSVat    += $results->vat;
                        $tSInvAmt += $results->payment;
                        $tSAmt    += $results->payment;
                    ?>
                    <tr>
                        <td ><?php echo esc($results->remarks);?></td>
                        <td class="text-right"><?php echo esc(getPriceFormat($results->payment));?></td>
                        <td class="text-right"><?php echo esc(getPriceFormat($results->vat));?></td>
                        <td class="text-right"><?php echo esc(getPriceFormat($results->payment));?></td>
                    </tr>
                </tbody>
              <?php }else{ ?>
              <thead>
                  <tr>
                      <th>
                          <?php echo get_phrases(['service', 'code']);?>
                      </th>
                      <th>
                          <?php echo get_phrases(['service', 'name']);?>
                      </th>
                      <th class="text-right">
                          <?php echo get_phrases(['receipt', 'amount']);?>
                      </th>
                       <th class="text-right">
                          <?php echo get_phrases(['return', 'vat']);?>
                      </th>
                      <th class="text-right">
                          <?php echo get_phrases(['return', 'amount']);?>
                      </th>
                  </tr>
              </thead>
              <tbody>
                  <?php 
                  if(!empty($results->details)){
                      foreach ($results->details as $key => $row) {
                          $tSVat    += $row->vat;
                          $tSInvAmt += $row->invoice_amount;
                          $tSAmt    += $row->amount + $row->vat;
                  ?>
                  <tr>
                      <td><?php echo esc($row->code_no);?></td>
                      <td ><?php echo esc($row->nameE);?></td>
                      <td class="text-right"><?php echo esc(getPriceFormat($row->invoice_amount));?></td>
                      <td class="text-right"><?php echo esc(getPriceFormat($row->vat));?></td>
                      <td class="text-right"><?php echo esc(getPriceFormat($row->amount));?></td>
                  </tr>
              <?php } }?>
              </tbody>
              <?php }?>
          </table>
      </div>
  </div>
  </div>
</div>
<div class="row">
  <div class="col-md-4">
      <table class="table table-bordered" cellspacing="0" width="100%" >
          <tbody>
              <tr>
                  <th colspan="2" class="text-center font-weight-600"><h5 class="mb-0"><?php echo get_phrases(['payment', 'method'])?></h5></th>
              </tr>
              <?php  
              $totalPay = 0;
              if(!empty($results->payments)){
              foreach($results->payments as $pay){ $totalPay +=$pay->amount; ?>
              <tr>
                  <th><?php echo esc($pay->nameE);?></th>
                  <td class="text-right"><?php echo getPriceFormat(esc($pay->amount));?></td>
              </tr>
              <?php } }?>
              <tr>
                  <th colspan="2" class="text-right"><?php echo getPriceFormat(esc($totalPay));?></th>
              </tr>
          </tbody>
      </table>
       <p>
          <b><?php echo get_phrases(['notes']);?> : </b><?php echo esc($results->remarks);?>
      </p>
  </div>
  <div class="col-md-4">
    
  </div>
  <div class="col-md-4">
    <div class="table-responsive">
        <table class="table table-bordered" cellspacing="0" width="100%">
            <tfoot>
                <tr>
                  <td class="text-right"><b><?php echo get_phrases(['sub', 'total'])?></b></td>
                  <th  class="text-right"><?php echo getPriceFormat($tSAmt);?></th>
                </tr>
                <tr>
                    <td class="text-right"><b><?php echo get_phrases(['total', 'vat'])?></b></td>
                    <th  class="text-right"><?php echo getPriceFormat($tSVat);?></th>
                </tr>
                <tr>
                    <td class="text-right"><b><?php echo get_phrases(['total', 'return', 'amount'])?></b></td>
                    <th  class="text-right"><?php echo getPriceFormat($tSAmt);?></th> 
                </tr>
              </tfoot>
        </table>
    </div>
  </div>
</div>