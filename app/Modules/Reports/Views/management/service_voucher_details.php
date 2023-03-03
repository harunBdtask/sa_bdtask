<h4><center id="title"></center></h4>
<div class="row">
  <div class="col-12 col-md-5">
      <h6 class="text-uppercase text-muted fs-12 font-weight-600"><?php echo get_phrases(['voucher', 'to']);?></h6>
      <p class="text-muted mb-4">
          <strong class="text-body fs-16"><a class="un-none" href="<?php echo base_url('patient/view_patient_profile/'.$results->patient_id);?>" target="_blank"><?php echo esc($results->patient_name);?></a></strong> <br>
          <?php echo get_phrases(['file', 'no']);?> - <?php echo esc($results->file_no);?><br>
          <?php echo get_phrases(['gender']);?> - <?php echo esc($results->gender);?><br>
          <?php echo get_phrases(['mobile', 'no']);?> - <?php echo esc($results->mobile);?><br>
          <?php echo get_phrases(['age']);?> :  <?php echo esc(!empty($results->age)?$results->age:0);?>
      </p>
  </div>
  <div class="col-12 col-md-2 text-md-center font-weight-600 fs-20">
    <?php 
    echo $results->type;
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
      <table class="table table-stripped table-sm">
          <thead>
              <tr>
                  <th width="7%"><?php echo get_phrases(['code', 'no']);?></th>
                  <th width="30%"><?php echo get_phrases(['service', 'name']);?></th>
                  <th class="text-center" width="5%"><?php echo get_phrases(['qty']);?></th>
                  <th class="text-right" width="8%"><?php echo get_phrases(['price']);?></th>
                  <th class="text-right" width="8%"><?php echo get_phrases(['gross', 'total'])?></th>
                  <th class="text-right" width="10%"><?php echo get_phrases(['doctor', 'discount'])?></th>
                  <th class="text-right" width="9%"><?php echo get_phrases(['offer', 'discount'])?></th>
                  <th class="text-right" width="9%"><?php echo get_phrases(['total', 'net'])?></th>
                  <th class="text-right" width="7%"><?php echo get_phrases(['vat']);?></th>
                  <th class="text-right" width="8%"><?php echo get_phrases(['total']);?></th>
              </tr>
          </thead>
          <tbody>
            <?php 
              $totalDis = 0;
              $totalPrice = 0;
              $credited = 0;
              $totalAmt = 0;
              if(!empty($results->details)) {
              foreach ($results->details as $value) {
                $doctor = $value->doctor_discount + $value->over_limit_discount;
                $disCount = $value->offer_discount + $doctor;   
                $totalDis += $disCount;
                $ctotal = $value->price*$value->qty;
                $totalPrice += $ctotal;
                $afterPrice = $ctotal - $disCount;
                $credited +=$value->creditAmt;
                $totalAmt +=$value->amount;
                ?>
                <tr>
                  <td><?php echo esc($value->code_no);?></td>
                  <td><?php echo esc($value->nameE);?></td>
                  <td class="text-center"><?php echo esc($value->qty);?></td>
                  <td class="text-right"><?php echo number_format(esc($value->price), 2);?></td>
                  <td class="text-right"><?php echo number_format(esc($ctotal)); ?></td>
                  <td class="text-right"><?php echo number_format(esc($doctor)); ?></td>
                  <td class="text-right"><?php echo number_format(esc($value->offer_discount)); ?></td>
                  <td class="text-right"><?php echo number_format(esc($afterPrice)); ?></td>
                  <td class="text-right"><?php echo number_format(esc($value->vat), 2);?></td>
                  <td class="text-right"><?php echo number_format(esc($value->amount), 2);?></td>
                </tr>
             <?php } }?>
          </tbody>
      </table>
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
     
  </div>
  <div class="col-md-4">
     <center class="mt-5">
          <?php 
          if($results->isPaid==1){
              echo '<b class="fs-18 text-success">'.get_phrases(['paid']).'</b>';
          }else if($results->isPaid==2){
              echo '<b class="fs-18 text-primary">'.get_phrases(['credit']).'</b>';
          }else{
              echo '<b class="s-18 text-danger">'.get_phrases(['unpaid']).'</b>';
          }
          ?>
      </center>
  </div>
  <div class="col-md-4">
       <table class="table table-bordered" cellspacing="0" width="100%">
          <tfoot>
                <td class="text-right"><b><?php echo get_phrases(['sub', 'total'])?></b></td>
                <th  class="text-right"><?php echo getPriceFormat($totalPrice);?></th>
              </tr>
               <tr>
                  <td class="text-right"><b><?php echo get_phrases(['total', 'discount'])?></b></td>
                  <th  class="text-right"><?php echo getPriceFormat($totalDis);?></th>
              </tr>
              <tr>
                  <td class="text-right"><b><?php $net = $totalPrice - $totalDis; echo get_phrases(['total', 'net'])?></b></td>
                  <th  class="text-right"><?php echo getPriceFormat($net);?></th>
              </tr>
              <tr>
                  <td class="text-right border-left-0"><b><?php echo get_phrases(['total', 'vat'])?></b></td>
                  <th  class="text-right"><?php echo getPriceFormat($results->vat);?></th>
              </tr>
              <tr>
                  <td class="text-right"><b><?php echo get_phrases(['grand','total'])?></b></td>
                  <th  class="text-right"><?php echo getPriceFormat($results->grand_total);?></th> 
              </tr>
              <tr>
                  <td class="text-right"><b><?php echo get_phrases(['total', 'paid'])?></b></td>
                  <th class="text-right"><?php echo getPriceFormat($totalAmt - $credited);?></th>
              </tr>
              <tr>
                 <td class="text-right">
                      <b><?php echo get_phrases(['total', 'due'])?></b></td>
                  <th  class="text-right"><?php $totalDue = !empty($credited)?$credited:$results->due; echo getPriceFormat($totalDue);?></th>
              </tr>
            </tfoot>
      </table>
  </div>
</div>