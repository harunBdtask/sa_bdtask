<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- <meta charset="utf-8" /> -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Patient Service Invoice</title>

    <!-- Favicon -->
    <link rel="icon" href="./images/favicon.png" type="image/x-icon" />

    <!-- Invoice styling -->
    <style>
      body {
        font-family: 'DejaVu Sans', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif !important;
        text-align: center;
        color: #777;
      }

      body h1 {
        font-weight: 300;
        margin-bottom: 0px;
        padding-bottom: 0px;
        color: #000;
      }

      body h3 {
        font-weight: 300;
        margin-top: 10px;
        margin-bottom: 20px;
        font-style: italic;
        color: #555;
      }

      body a {
        color: #06f;
      }
      .text-left {
        text-align: left !important;
      }
      .text-right {
        text-align: right !important;
      }
      .text-center {
        text-align: center !important;
      }

      .text-success {
        color: #37a000;
      }

      .text-primary {
        color: blue;
      }
      .text-danger {
        color: red;
      }

      .invoice-box {
        max-width: 900px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        font-size: 14px;
        line-height: 24px;
        color: #555;
      }

      .invoice-box table {
        width: 100%;
        line-height: 100%;
        text-align: left;
        border-collapse: collapse;
      }

      .invoice-box table td {
        padding: 2px;
        vertical-align: top;
      }
      .invoice-box table th {
        font-size: bold;
      }

      .invoice-box table tr td:nth-child(2) {
        text-align: right;
      }

      .invoice-box table tr.top table td {
        padding-bottom: 20px;
      }

      .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
      }

      .invoice-box table tr.information table td {
        padding-bottom: 20px;
      }

      .invoice-box table tr.info {
        padding-bottom: 20px;
        padding-top: 10px;
      }

      .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
      }

      .invoice-box table tr.details td {
        padding-bottom: 20px;
      }

      .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
      }

      .invoice-box table tr.item.last td {
        border-bottom: none;
      }

      .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
      }

      @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
          width: 100%;
          display: block;
          text-align: center;
        }

        .invoice-box table tr.information table td {
          width: 100%;
          display: block;
          text-align: center;
        }
      }
      .table-bordered td, .table-bordered th {
          border: 1px solid #e4e5e7;
      }

      tr.table-bordered-buttom {
          border-bottom: 1px solid #e4e5e7;
      }
      .fs-18{
        font-weight: 600;
        font-size: 20px;
      }
      .clearfix{
        margin-top: 30 px !important;
      }
    </style>
  </head>

  <body>
    
    <div class="invoice-box">
      <table>
        <tr class="top">
          <td colspan="10">
            <table>
              <tr>
                <td class="title">
                  <?php
                  $phrases = json_decode($phrases->phrases, true);
                  $path = base_url(!empty($website->logo)?$website->logo:"/assets/frontend/img/no-image.png");
                  $type = pathinfo($path, PATHINFO_EXTENSION);
                  $data = file_get_contents($path);
                  $base64img = 'data:image/' . $type . ';base64,' . base64_encode($data);

                ?>
                  <img src="<?php echo $base64img;?>" alt="Company logo" style="height: 100px" />
                </td>

                <td>
                   <?php echo $phrases[$lang]['invoice_no'];?> : <?php echo esc($details->id);?><br>
                        <?php echo $phrases[$lang]['doctor_name'];?><br> <?php echo esc($details->doctor);?><br>
                        <?php echo $phrases[$lang]['created_date'];?> <br><?php echo esc(date('F j, Y h:i A', strtotime($details->created_date)));?><br>
                        <?php echo $phrases[$lang]['vat_no'];?> <br><?php echo esc($details->vat_no);?>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr class="information">
          <td colspan="10">
            <table>
              <tr>
                <td>
                 <strong><?php echo esc($details->patient_name);?></strong> <br>
                  <?php echo $phrases[$lang]['file_no'];?> - <?php echo esc($details->file_no);?><br>
                  <?php echo $phrases[$lang]['gender'];?> - <?php echo esc($details->gender);?><br>
                  <?php echo $phrases[$lang]['mobile_no'];?> - <?php echo esc($details->mobile);?><br>
                  <?php echo $phrases[$lang]['nid_no'];?> - <?php echo esc($details->nid_no);?><br>
                  <?php echo $phrases[$lang]['age'];?> :  <?php echo esc(!empty($details->age)?$details->age:0);?>
                </td>

                <td>
                  <strong><?php echo $website->title; ?></strong><br>
                    <?php echo $website->address; ?><br>
                    <?php echo $website->phone; ?></br>
                    <?php echo $website->email; ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr class="heading table-bordered">
          <th class="text-center"><?php echo $phrases[$lang]['code_no'];?></th>
          <th class="text-center"><?php echo $phrases[$lang]['item_name'];?></th>
          <th class="text-center"><?php echo $phrases[$lang]['qty'];?></th>
          <th class="text-right"><?php echo $phrases[$lang]['price'];?></th>
          <th class="text-right"><?php echo $phrases[$lang]['gross_total'];?></th>
          <th class="text-right"><?php echo $phrases[$lang]['doctor_discount'];?></th>
          <th class="text-right"><?php echo $phrases[$lang]['offer_discount'];?></th>
          <th class="text-right"><?php echo $phrases[$lang]['total_net'];?></th>
          <th class="text-right"><?php echo $phrases[$lang]['vat'];?></th>
          <th class="text-right"><?php echo $phrases[$lang]['total'];?></th>
        </tr>

        <?php 
          $total = 0; $totalDis = 0; $credited = 0;
          $totalAmt = 0;
          foreach($details->items as $row){ 
              $doctor = $row->doctor_discount + $row->over_limit_discount;
              $disCount = $row->offer_discount + $doctor + $row->redeem_discount;
              $totalDis += $disCount;
              $ctotal = $row->price*$row->qty;
              $total += $ctotal;
              $afterPrice = $ctotal - $disCount;
              $credited +=$row->creditAmt;
              $totalAmt +=$row->amount;
          ?>
          <tr class="table-bordered">
                <td class="text-center"><?php echo esc($row->code_no); ?></td>
                <td class="text-center"><?php echo esc($row->nameE); ?></td>
                <td class="text-center"><?php echo esc($row->qty); ?></td>
                <td class="text-right"><?php echo esc($row->price); ?></td>
                <td class="text-right"><?php echo esc($ctotal); ?></td>
                <td class="text-right"><?php echo esc($doctor); ?></td>
                <td class="text-right"><?php echo esc($row->offer_discount); ?></td>
                <td class="text-right"><?php echo esc($afterPrice); ?></td>
                <td class="text-right"><?php echo esc($row->vat); ?></td>
                <td class="text-right"><?php echo esc($row->amount); ?></td>
          </tr>
      <?php }?>

        <tr class="info">
          <td colspan="4">
            <br>
            <table class="table-bordered">
              <tr>
                  <th colspan="2" class="text-center"><?php echo $phrases[$lang]['payment_method'];?></th>
              </tr>
              <?php  
              $disPer = !empty($total)?(100*$totalDis)/$total:0;
              $totalPay = 0;
              if(!empty($details->payments)){
              foreach($details->payments as $pay){ $totalPay +=$pay->amount; ?>
              <tr>
                  <th><?php echo esc($pay->nameE);?></th>
                  <td class="text-right"><?php echo getPriceFormat(esc($pay->amount));?></td>
              </tr>
              <?php } }?>
              <tr>
                  <th colspan="2" class="text-right"><?php echo getPriceFormat(esc($totalPay));?></th>
              </tr>
            </table>
          </td>
          <td>
            </td>
          <td colspan="5">
            <br>
            <table class="table-bordered">
              <tr>
                <td class="text-right"><b><?php echo $phrases[$lang]['gross_total'];?></b></td>
                <th  class="text-right"><?php echo getPriceFormat($total);?></th>
              </tr>
               <tr>
                  <td class="text-right"><b><?php echo $phrases[$lang]['total_discount'];?></b></td>
                  <th  class="text-right"><?php echo getPriceFormat($totalDis);?></th>
              </tr>
               <tr>
                  <td class="text-right"><b><?php echo $phrases[$lang]['total_discount_%'];?></b></td>
                  <th  class="text-right"><?php echo number_format($disPer, 2);?>%</th>
              </tr>
              <tr>
                  <td class="text-right"><b><?php $net = $total - $totalDis; echo $phrases[$lang]['total_net'];?></b></td>
                  <th  class="text-right"><?php echo getPriceFormat($net);?></th>
              </tr>
              <tr>
                  <td class="text-right"><b><?php echo $phrases[$lang]['total_vat'];?></b></td>
                  <th  class="text-right"><?php echo getPriceFormat($details->vat);?></th>
              </tr>
              <tr>
                  <td class="text-right"><b><?php echo $phrases[$lang]['grand_total'];?></b></td>
                  <th  class="text-right"><?php echo getPriceFormat($details->grand_total);?></th> 
              </tr>
              <tr>
                  <td class="text-right"><b><?php echo $phrases[$lang]['total_paid'];?></b></td>
                  <th class="text-right"><?php echo getPriceFormat($totalAmt - $credited);?></th>
              </tr>
              <tr>
                 <td class="text-right">
                      <b><?php echo $phrases[$lang]['total_due'];?></b></td>
                  <th  class="text-right"><?php $totalDue = !empty($credited)?$credited:$details->due; echo getPriceFormat($totalDue);?></th>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <center>
          <?php 
            if($details->isPaid==1){
                echo '<b class="fs-18 text-success">'.get_phrases(['paid']).'</b>';
            }else if($details->isPaid==2){
                echo '<b class="fs-18 text-primary">'.get_phrases(['credit']).'</b>';
            }else{
                echo '<b class="s-18 text-danger">'.get_phrases(['unpaid']).'</b>';
            }
          ?>
      </center>
      <div class="clearfix"></div>
      <table width="100%">
        <tr>
          <td style="text-align: left;">
            <?php echo esc($details->createdBy);?><br>
              <span>___________________________</span><br>
              <b><?php echo $phrases[$lang]['signature_by_cash']; ?></b>
          </td>
          <td style="text-align: right;">
            <span>___________________________</span><br>
            
            <b><?php echo $phrases[$lang]['patient_signature']; ?></b>
          </td>
        </tr>
      </table>
      
      <div class="row">
          <div class="col-12">
              <?php echo $phrases[$lang]['item_return_policy'];?>
          </div>
      </div>
    </div>
  </body>
</html>
