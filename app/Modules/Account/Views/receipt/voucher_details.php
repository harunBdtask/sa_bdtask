<div class="row">
    <div class="col-sm-12">
        <?php 
        $phrases = json_decode($phrases->phrases, true);
        if($permission->method('receipt_voucher', 'read')->access()){ ?>
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
                        <?php if($permission->method('receipt_voucher', 'create')->access()){ ?>
                        <a href="<?php echo base_url('account/accounts/receipt_voucher');?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'new', 'voucher']);?></a>
                        <?php }?>
                        <a href="<?php echo base_url('account/accounts/receiptVList');?>" class="btn btn-info btn-sm mr-1"><i class="fas fa-list mr-1"></i><?php echo get_phrases(['receipt', 'voucher', 'list']);?></a>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            
            <div class="card-body" id="printC">
                <div class="row">
                    <div class="col-12 text-center">
                        <img src="<?php echo base_url().$setting->logo;?>" alt="..." class="img-responsive mb-1">
                        <h4 class="mb-0 font-weight-bold"><?php echo esc($setting->title);?></h4>
                        (<?php echo esc($setting->nameA);?>)
                        <p class="text-muted mb-5 fs-20"><?php echo $phrases[$lang]['voucher_title'];?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600"><?php echo $phrases[$lang]['voucher_to'];?></h6>
                        <p class="text-muted mb-4">
                            <strong class="text-body fs-16"><?php echo esc($vouchers->nameE.' '.$vouchers->nameA);?></strong> <br>
                            <?php echo $phrases[$lang]['file_no'];?> - <?php echo esc($vouchers->file_no);?> <br>
                            <?php echo $phrases[$lang]['gender'];?> - <?php echo esc($vouchers->gender);?><br>
                            <?php echo $phrases[$lang]['mobile_no'];?> - <?php echo esc($vouchers->mobile);?><br>
                            <?php echo $phrases[$lang]['nid_no'];?> - <?php echo esc($vouchers->nid_no);?><br>
                            <?php echo $phrases[$lang]['age'];?> :  <?php echo esc(!empty($vouchers->age)?$vouchers->age:0);?><br>
                            <?php if(!empty($vouchers->isCredit)){ ?>
                                <a href="<?php echo base_url('account/services/detailsInvoice/'.$vouchers->isCredit)?>"><?php echo $phrases[$lang]['reference_credit_invoice_id'].' - '.esc($vouchers->isCredit)?></a>
                                
                            <?php }?>
                        </p>
                    </div>
                    <div class="col-6 text-right">
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"><?php echo $phrases[$lang]['voucher_no'];?></h6>
                        <?php echo esc($vouchers->id);?>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo $phrases[$lang]['doctor_name'];?></h6>
                        <?php echo esc($vouchers->doctor);?>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo $phrases[$lang]['created_date'];?></h6>
                        <time datetime=""><?php echo esc(date('F j, Y h:i A', strtotime($vouchers->created_date)));?></time>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo get_phrases(['branch', 'name']);;?></h6>
                        <?php echo esc($vouchers->branch_name);?>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo $phrases[$lang]['vat_no'];?></h6>
                        <?php echo esc($vouchers->vat_no);?>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            <?php echo $phrases[$lang]['code_no'];?>
                                        </th>
                                        <th>
                                            <?php echo $phrases[$lang]['description'];?>
                                        </th>
                                        <th class="text-right">
                                           <?php echo $phrases[$lang]['qty'];?>
                                        </th>
                                        <th class="text-right">
                                            <?php echo $phrases[$lang]['price'];?>
                                        </th>
                                        <th class="text-right">
                                            <?php echo $phrases[$lang]['discount'];?>
                                        </th>
                                        <th class="text-right">
                                            <?php echo $phrases[$lang]['vat'];?>
                                        </th>
                                        <th class="text-right">
                                            <?php echo $phrases[$lang]['amount'];?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $Total =0;
                                    $grandT =0;
                                    $disT =0;
                                    $vatT =0;
                                    if(!empty($vouchers->details)){
                                        foreach ($vouchers->details as $value) { 
                                            $Total  +=$value->price*$value->qty;
                                            $grandT +=$value->amount;
                                            $disT   +=$value->discount;
                                            $vatT   +=$value->vat;
                                            ?>
                                        <tr>
                                            <td><?php echo esc($value->code_no);?></td>
                                            <td><?php echo esc($value->nameE);?></td>
                                            <td class="text-right"><?php echo esc($value->qty);?></td>
                                            <td class="text-right"><?php echo esc(getPriceFormat($value->price));?></td>
                                            <td class="text-right"><?php echo esc(getPriceFormat($value->discount));?></td>
                                            <td class="text-right"><?php echo esc(getPriceFormat($value->vat));?></td>
                                            <td class="text-right"><?php echo esc(getPriceFormat($value->amount));?></td>
                                        </tr>
                                        <?php }
                                    }else { 
                                        $Total = $vouchers->total;
                                        $grandT = $vouchers->grand_total;
                                        $disT =0;
                                        $vatT = $vouchers->vat;
                                    ?>
                                        <tr>
                                            <td colspan="7" class="text-center"><?php echo get_phrases(['service', 'not', 'available']);?></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <?php echo $phrases[$lang]['payment_method'];?>
                                                </th>
                                                <th class="text-right">
                                                   <?php echo $phrases[$lang]['amount'];?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $payTotal = 0;
                                            if(!empty($vouchers->payment)){
                                                foreach ($vouchers->payment as $value) { $payTotal +=$value->amount;?>
                                                <tr>
                                                    <td>
                                                        <?php echo esc($value->nameE);?><br>

                                                        <?php 
                                                        if($value->pay_method_id  !='120'){
                                                            if($value->card_or_cheque_no !=''){echo '<b>'.get_phrases(['card', 'number']).':</b> '.$value->card_or_cheque_no;}
                                                            if($value->expiry_date !=0000-00-00){echo '<b>'.get_phrases(['expiry', 'date']).':</b> '.date('d/m/Y', strtotime(esc($value->expiry_date))).'<br>';}
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-right"><?php echo esc(getPriceFormat($value->amount));?></td>
                                                </tr>
                                                <?php }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <b><?php echo get_phrases(['notes']);?> : </b> <?php echo esc($vouchers->update_notes);?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <center>
                                    <p class="fs-20">
                                    <?php 
                                        if($vouchers->isPaid==1){
                                            echo '<span class="text-success font-weight-bold">'.get_phrases(['paid']).'</span>';
                                        }
                                    ?>
                                    </p>
                                </center>
                            </div>
                            <div class="col-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <tbody>
                                            <tr>
                                                <th class="text-right"><?php echo $phrases[$lang]['sub_total'];?></th>
                                                <th class="text-right"><?php echo esc(getPriceFormat($Total));?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-right"><?php echo $phrases[$lang]['total_discount'];?></th>
                                                <th class="text-right"><?php echo esc(getPriceFormat($disT));?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-right"><?php echo $phrases[$lang]['total_net'];?></th>
                                                <th class="text-right"><?php echo esc(getPriceFormat($Total - $disT));?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-right"><?php echo $phrases[$lang]['total_vat'];?></th>
                                                <th class="text-right"><?php echo esc(getPriceFormat($vatT));?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-right"><?php echo $phrases[$lang]['grand_total'];?></th>
                                                <th class="text-right"><?php echo esc(getPriceFormat($grandT));?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-right"><?php echo $phrases[$lang]['total_paid'];?></th>
                                                <th class="text-right"><?php echo esc(getPriceFormat($vouchers->receipt));?></th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr class="mb-4">
                        <div class="row">
                            <div class="col-4">
                                <div class="text-muted"><?php echo esc($vouchers->created_by);?></div>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-4">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="font-weight-bold fs-13"><?php echo $phrases[$lang]['signature_by_cash'];?> </div>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-4">
                                <div class="font-weight-bold fs-13"><?php echo $phrases[$lang]['depositor_signature'];?> </div>
                            </div>
                        </div>
                        <p class="text-muted mb-0">
                            <?php 
                                echo $phrases[$lang]['item_return_policy'];
                            ?>
                        </p>
                        <p class="text-right no-print">
                            <button type="button" class="btn btn-purple mr-2" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']); ?></button>
                            <?php if($permission->method('journal_voucher', 'read')->access()){ ?>
                            <button type="button" class="btn btn-info viewJV" data-id="RV-<?php echo esc($vouchers->id);?>"><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']); ?></button>
                            <?php }?>
                        </p>
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

    });
</script>