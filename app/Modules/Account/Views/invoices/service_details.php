<div class="row">
    <div class="col-sm-12">
        <?php 
        $phrases = json_decode($phrases->phrases, true);
        if($permission->method('service_invoices', 'read')->access()){ ?>
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
                        <a href="<?php echo base_url('account/services/generatePDF/'.$details->id);?>" class="btn btn-primary btn-sm mr-1"><i class="fas fa-download mr-1"></i><?php echo get_phrases(['download', 'invoice']);?></a>
                        <a href="<?php echo base_url('account/services/addInvoice');?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['new', 'invoice']);?></a>
                        <a href="<?php echo base_url('account/services/invoices');?>" class="btn btn-info btn-sm mr-1"><i class="fas fa-list mr-1"></i><?php echo get_phrases(['invoice', 'list']);?></a>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i>Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="printDiv">
                <div class="row">
                    <div class="col-6">
                        <img src="<?php echo base_url().$website->logo;?>" class="img-responsive" alt="<?php echo $website->title; ?>"></br>
                        <?php echo get_phrases(['phone']) ?>: <?php echo $website->phone; ?></br>
                        <?php echo get_phrases(['email']) ?>: <?php echo $website->email; ?>
                    </div>
                    <div class="col-6 text-right">
                         <address>
                            <strong><?php echo $website->title; ?></strong><br>
                            (<?php echo esc($website->nameA);?>)<br>
                            <?php echo $website->address; ?>
                        </address>
                    </div>
                </div> <hr>

                <div class="row">
                    <div class="col-6">
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600"><?php echo $phrases[$lang]['invoice_to']; ?></h6>
                        <p class="text-muted mb-4">
                            <strong class="text-success fs-16"><a class="un-none" href="<?php echo base_url('patient/view_patient_profile/'.$details->patient_id);?>" target="_blank"><?php echo esc($details->patient_name);?></a></strong> <br>
                            <?php echo $phrases[$lang]['file_no'];?> - <?php echo esc($details->file_no);?><br>
                            <?php echo $phrases[$lang]['gender'];?> - <?php echo esc($details->gender);?><br>
                            <?php echo $phrases[$lang]['mobile_no'];?> - <?php echo esc($details->mobile);?><br>
                            <?php echo $phrases[$lang]['nid_no'];?> - <?php echo esc($details->nid_no);?><br>
                            <?php echo $phrases[$lang]['age'];?> :  <?php echo esc(!empty($details->age)?$details->age:0);?>
                        </p>
                    </div>
                    <div class="col-6 text-right">
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"><?php echo $phrases[$lang]['invoice_no'];?></h6>
                        <?php echo esc($details->id);?>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo $phrases[$lang]['invoice_date'];?></h6>
                        <?php echo esc(date('d/m/Y', strtotime($details->invoice_date)));?>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo $phrases[$lang]['doctor_name'];?></h6>
                        <?php echo esc($details->doctor);?>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo $phrases[$lang]['created_date'];?></h6>
                        <time datetime=""><?php echo esc(date('F j, Y h:i A', strtotime($details->created_date)));?></time>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo get_phrases(['branch', 'name']);;?></h6>
                        <?php echo esc($details->branch_name);?>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo $phrases[$lang]['vat_no'];?></h6>
                        <?php echo esc($details->vat_no);?>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" cellspacing="0" width="100%" >
                            <thead>
                                <tr>
                                    <th class="text-center"><?php echo $phrases[$lang]['code_no']?></th>
                                    <th class="text-center"><?php echo $phrases[$lang]['item_name'];?></th>
                                    <th class="text-center"><?php echo $phrases[$lang]['qty'];?></th>
                                    <th class="text-right"><?php echo $phrases[$lang]['price'];?></th>
                                    <th class="text-right"><?php echo $phrases[$lang]['gross_total'];?></th>
                                    <!-- <th class="text-right"><?php //echo $phrases[$lang]['total_discount_%'];?></th> -->
                                    <th class="text-right"><?php echo $phrases[$lang]['doctor_discount'];?></th>
                                    <th class="text-right"><?php echo $phrases[$lang]['offer_discount'];?></th>
                                    <th class="text-right"><?php echo $phrases[$lang]['total_net'];?></th>
                                    <th class="text-right"><?php echo $phrases[$lang]['vat'];?>(<?php echo esc($details->vat_percent)?>%)</th>
                                    <th class="text-right"><?php echo $phrases[$lang]['total'];?></th>

                                </tr>
                            </thead>
                            <tbody>
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
                                <tr>
                                      <td class="text-center"><?php echo esc($row->code_no); ?></td>
                                      <td class="text-center"><?php echo esc($row->nameE); ?></td>
                                      <td class="text-center"><?php echo esc($row->qty); ?></td>
                                      <td class="text-right"><?php echo getPriceFormat(esc($row->price)); ?></td>
                                      <td class="text-right"><?php echo getPriceFormat(esc($ctotal)); ?></td>
                                      <!-- <td class="text-right"><?php //echo esc($row->totalDiscount); ?>%</td> -->
                                      <td class="text-right"><?php echo getPriceFormat(esc($doctor)); ?></td>
                                      <td class="text-right"><?php echo getPriceFormat(esc($row->offer_discount)); ?></td>
                                      <td class="text-right"><?php echo getPriceFormat(esc($afterPrice)); ?></td>
                                      <td class="text-right"><?php echo getPriceFormat(esc($row->vat)); ?></td>
                                      <td class="text-right"><?php echo getPriceFormat(esc($row->amount)); ?></td>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>  <!-- /.table-responsive --> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        <table class="table table-bordered" cellspacing="0" width="100%" >
                            <tbody>
                                <tr>
                                    <th colspan="2" class="text-center font-weight-600"><h5 class="mb-0"><?php echo $phrases[$lang]['payment_method'];?></h5></th>
                                </tr>
                                <?php  
                                $totalPay = 0;
                                if(!empty($details->payments)){
                                foreach($details->payments as $pay){ $totalPay +=$pay->amount; ?>
                                <tr>
                                    <th>
                                        <?php echo esc($pay->nameE);?><br>
                                        <?php
                                        if($pay->payment_name =='120' || $pay->payment_name =='127' || $pay->payment_name =='130' || $pay->payment_name =='150'){
                                        }else{
                                            if($pay->card_or_cheque_no !=null){echo '<b>'.get_phrases(['card', 'number']).':</b> '.$pay->card_or_cheque_no;}
                                            if($pay->expiry_date !=0000-00-00){echo '<b class="ml-3">'.get_phrases(['expiry', 'date']).':</b> '.date('d/m/Y', strtotime(esc($pay->expiry_date))).'<br>';}
                                        }
                                        ?>
                                    </th>
                                    <td class="text-right"><?php echo number_format(esc($pay->amount), 2);?></td>
                                </tr>
                                <?php } }?>
                                <tr>
                                    <th colspan="2" class="text-right"><?php echo getPriceFormat(esc($totalPay));?></th>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <img class=" ml-0 mb-0" src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=<?php echo base_url('auth/servInvoice/'.$details->id); ?>" alt="Invoice QR code">
                            </div>
                            <div class="col-md-12 service-qr-text"><?php echo get_phrases(['Scan','this','QR','code']);?></div>
                        </div>
                       
                    </div>
                    <div class="col-4">
                       <center>
                            <?php 
                              if($details->isPaid==1){
                                  echo '<b class="fs-18 text-success">'.get_phrases(['paid']).'</b>';
                              }else if($details->isPaid==2){
                                  echo '<b class="fs-18 text-primary">'.get_phrases(['credit']).'</b>';
                              }else{
                                  echo '<b class="fs-18 text-danger">'.get_phrases(['unpaid']).'</b>';
                              }
                            ?>
                        </center>
                    </div>
                    <?php
                        $disPer = !empty($total)?(100*$totalDis)/$total:0;
                    ?>
                    <div class="col-4">
                         <table class="table table-bordered" cellspacing="0" width="100%">
                            <tfoot>
                                <tr>
                                  <td class="text-right"><b><?php echo $phrases[$lang]['gross_total'];?></b></td>
                                  <th  class="text-right"><?php echo getPriceFormat($total);?></th>
                                </tr>
                                 <tr>
                                    <td class="text-right"><b><?php echo $phrases[$lang]['total_discount']?></b></td>
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
                                    <td class="text-right border-left-0"><b><?php echo $phrases[$lang]['total_vat'];?></b></td>
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
                              </tfoot>
                        </table>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-4">
                        <div class="my_sign">
                            <?php echo esc($details->createdBy);?><br>
                            <span>___________________________</span><br>
                            <b><?php echo $phrases[$lang]['signature_by_cash']; ?></b>
                        </div>
                    </div>
                    <div class="col-4">
                        
                    </div>
                    <div class="col-4">
                        <div class="my_sign">
                            <span>___________________________</span><br>
                            
                            <b><?php echo $phrases[$lang]['patient_signature']; ?></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <?php echo $phrases[$lang]['item_return_policy'];?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-purple mr-2" onclick="printContent('printDiv')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']); ?></button>
                <?php if($permission->method('journal_voucher', 'read')->access()){ ?>
                <button type="button" class="btn btn-info viewJV mr-2" data-id="SINV-<?php echo esc($details->id);?>"><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']); ?></button>
                <?php }?>
                <button type="button" class="btn btn-primary viewConsumed" data-id="<?php echo esc($details->id);?>"><span class="fa fa-list"></span> <?php echo get_phrases(['consumption', 'list']); ?></button>
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
<!-- view voucher details -->
<div class="modal fade bd-example-modal-xl" id="jv-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="detailsModalLabel"><?php echo get_phrases(['view', 'voucher', 'details']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printJV">
                <div class="row">
                    <div class="col-md-12" id="viewDetails">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" class="btn btn-purple" onclick="printContent('printJV')"><i class="fa fa-print"></i> <?php echo get_phrases(['print']);?></button>
            </div>
        </div>
    </div>
</div>

<!-- view voucher details -->
<div class="modal fade" id="consListModal" tabindex="-1" role="dialog" aria-labelledby="consListModalLabel3" aria-hidden="true">
    <div class="modal-dialog custom-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="consListModalLabel"><?php echo get_phrases(['consumption', 'list']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-sm table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo get_phrases(['serial']);?></th>
                                    <th><?php echo get_phrases(['voucher', 'no']);?></th>
                                    <th><?php echo get_phrases(['date']);?></th>
                                    <th><?php echo get_phrases(['department', 'name']);?></th>
                                    <th><?php echo get_phrases(['store', 'name']);?></th>
                                    <th><?php echo get_phrases(['doctor', 'name']);?></th>
                                    <th><?php echo get_phrases(['file', 'no']);?></th>
                                    <th><?php echo get_phrases(['requested', 'by']);?></th>
                                    <th><?php echo get_phrases(['status']);?></th>
                                </tr>
                            </thead>
                            <tbody id="viewConsResults">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
        </div>
    </div>
</div>

<!-- item request modal button -->
<div class="modal fade bd-example-modal-xl" id="itemRequestDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemRequestDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printContent">
                <input type="hidden" name="id2" id="id2" />
                <input type="hidden" name="action2" id="action2" value="return" />

                <div class="row printing_info">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="40px" ><br><br>
                        <h6><?php echo $settings_info->title.' ( '.$settings_info->nameA.' )'; ?></h6>
                        <h5><?php echo get_phrases(['item', 'consumption','voucher']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>

                <div class="row printing_info_return">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="40px" ><br><br>
                        <h6><?php echo $settings_info->title.' ( '.$settings_info->nameA.' )'; ?></h6>
                        <h5><?php echo get_phrases(['item', 'return','voucher']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                    <hr>
                </div>
                
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['department']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_from_department_id"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['from','store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_sub_store_id" ></div>                        
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_date"></div>                        
                    </div>
                </div>
                
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['consumed','by']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_consumed_by" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['request','by']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_request_by" ></div>                        
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['notes']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_notes" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['status']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_status" ></div>                        
                    </div>
                </div>

                <div class="row consumed_service_info" >
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['patient']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_patient" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['doctor']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_doctor" ></div>                        
                    </div>
                </div>

                <div class="row consumed_service_info">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['invoice','no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_invoice_id"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['service']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_service" ></div>                        
                    </div>
                </div>

                <div class="row printing_info_return">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['return','voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_return_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['returned','date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_return_date"></div>                        
                    </div>
                </div>

                <div class="form-group row return_input">
                    <label for="return_voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['voucher','no'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="return_voucher_no" type="text" class="form-control" id="return_voucher_no" placeholder="<?php echo get_phrases(['voucher','no'])?>" autocomplete="off" readonly >
                    </div>
                    <label for="return_date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="return_date" type="text" class="form-control" id="return_date" value="<?php echo date('d/m/Y')?>" autocomplete="off" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-12">
                        <div id="item_details_preview"></div>
                    </div>
                </div>
               
                <div class="row printing_info">
                    <div class="col-sm-6 ">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['received', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                    <div class="col-sm-6 text-right">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['authorized', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                </div>

                <div class="row printing_info_return">
                    <div class="col-sm-6 ">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['returned', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                    <div class="col-sm-6 text-right">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['authorized', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <?php if($permission->method('journal_voucher', 'read')->access()){ ?>
                    <button type="button" class="btn btn-info viewJV" id="journal_voucher" data-id=""><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']); ?></button>
                <?php } ?>
                <button type="button" id="print" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() { 
        "use strict";

        $('.viewJV').on('click', function(e){
            e.preventDefault();
            var VNo = $(this).attr('data-id');
            $('#jv-modal').modal('show');
            var submit_url = _baseURL+"account/vouchers/getVoucherDetails/"+VNo; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(response) {
                    $('#viewDetails').html('');
                    $('#viewDetails').html(response.data);
                }
            });  
        });
        
        $('.viewConsumed').on('click', function(e){
            e.preventDefault();
            $('#consListModal').modal('show');
            var invoice_id = $(this).attr('data-id');
            var submit_url = _baseURL+"account/services/invoiceConsumptions/"+invoice_id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, approved:0, service_id:null},
                dataType: 'JSON',
                success: function(response) {
                    $('#viewConsResults').html('');
                    $('#viewConsResults').html(response.data);
                }
            });  
        });

         $(document).on('click', '.actionCost', function(e){
            e.preventDefault();
            onclick_change_bg('#viewConsResults', this, 'cyan');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'inventory/consumption/getItemRequestDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#itemRequestDetails-modal').modal('show');
                    $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['consumption','request','details']);?>');

                    $('#itemRequestDetails_sub_store_id').text(data.sub_store_name);
                    $('#itemRequestDetails_date').text(data.date);
                    $('#itemRequestDetails_voucher_no').text(data.voucher_no);
                    $('#itemRequestDetails_from_department_id').text(data.department_name);
                    $('#itemRequestDetails_notes').text(data.notes);
                    $('#itemRequestDetails_status').text(data.status_text+' '+data.status_collected+' '+data.status_returned);
                    $('#itemRequestDetails_request_by').text(data.request_by_name);
                    $('#itemRequestDetails_consumed_by').text(data.consumed_by);
                    if(data.consumed_by == 'service'){
                        $('.consumed_service_info').show();
                        $('#itemRequestDetails_invoice_id').html('SINV-'+data.invoice_id);
                        $('#itemRequestDetails_invoice_id').attr('data-id','SINV-'+data.invoice_id);
                        $('#itemRequestDetails_service').text(data.service);
                        $('#itemRequestDetails_patient').text(data.patient);
                        $('#itemRequestDetails_doctor').text(data.doctor_name);
                    } else {
                        $('.consumed_service_info').hide();
                    }
                    
                    $('#return').hide();
                    $('.return_input').hide();
                    $('.printing_info').hide();
                    $('.printing_info_return').hide();
                    $('#journal_voucher').hide();
                    $('#journal_voucher').attr('data-id', '');
                    
                    get_item_details(id);

                },error: function() {

                }
            });   

        });

    });

    function get_item_details(request_id){
        if(request_id !='' ){
            var submit_url = _baseURL+'inventory/consumption/getItemRequestQuantityDetailsById';
            var action = $('#action2').val();

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'request_id':request_id, 'action':action },
                success: function(data) {
                    $('#item_details_preview').html(data);
                    if(action == 'print'){
                        $('.return_info').hide();
                    } else {
                        $('.return_info').show();
                    }
                    if(action == 'print_return'){
                        $('.consume_info').hide();
                    } else {
                        $('.consume_info').show();
                    }

                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }
</script>
