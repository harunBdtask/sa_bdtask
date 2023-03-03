<div class="row">
    <div class="col-sm-12">
        <?php if($permission->method('payment_voucher', 'read')->access()){ ?>
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
                        <?php if($permission->method('payment_voucher', 'create')->access()){ ?>
                        <a href="<?php echo base_url('account/accounts/payment_voucher');?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'payment', 'voucher']);?></a>
                        <?php } if($permission->method('payment_voucher', 'read')->access()){?>
                        <a href="<?php echo base_url('account/accounts/paymentVList');?>" class="btn btn-info btn-sm mr-1"><i class="fas fa-list mr-1"></i><?php echo get_phrases(['payment', 'voucher', 'list']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            
            <div class="card-body" id="printC">
                <div class="row">
                    <div class="col-12 text-center">
                        <img src="<?php echo base_url().$setting->logo;?>" alt="..." class="img-responsive mb-1">
                        <h4 class="mb-0 font-weight-bold"><?php echo esc($setting->title);?></h4>
                        (<?php echo esc($setting->nameA);?>)<br>
                        <p class="text-muted mb-5 fs-20"><?php echo get_phrases(['payment', 'voucher']);?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600"><?php echo get_phrases(['voucher', 'to']);?></h6>
                        <p class="text-muted mb-4">
                            <strong class="text-success fs-16"><?php echo esc($vouchers->nameE.' '.$vouchers->nameA);?></strong> <br>
                            <?php echo get_phrases(['file', 'no']);?> - <?php echo esc($vouchers->file_no);?><br> <?php echo get_phrases(['gender']);?> - <?php echo esc($vouchers->gender);?><br>
                            <?php echo get_phrases(['mobile', 'no']);?> - <?php echo esc($vouchers->mobile);?><br>
                            <?php echo get_phrases(['nid', 'no']);?> - <?php echo esc($vouchers->nid_no);?><br>
                            <?php echo get_phrases(['age']);?> :  <?php echo esc(!empty($vouchers->age)?$vouchers->age:0);?><br>
                            <?php echo get_phrases(['reference', 'voucher', 'no']);?> :  <a href="<?php echo base_url('account/accounts/receiptVDetails/'.$vouchers->ref_voucher);?>"><?php echo esc($vouchers->ref_voucher);?></a>
                        </p>
                    </div>
                    <div class="col-6 text-right">
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"><?php echo get_phrases(['voucher', 'no']);?></h6>
                        <?php echo esc($vouchers->id);?>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo get_phrases(['doctor', 'name']);?></h6>
                        <?php echo esc($vouchers->doctor);?>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo get_phrases(['created', 'date']);?></h6>
                        <time datetime=""><?php echo esc(date('F j, Y h:i A', strtotime($vouchers->created_date)));?></time>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo get_phrases(['branch', 'name']);;?></h6>
                        <?php echo esc($vouchers->branch_name);?>
                        <h6 class="text-uppercase text-muted fs-12 font-weight-600 mb-0"> <?php echo get_phrases(['vat', 'no']);?></h6>
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
                                            <?php echo get_phrases(['code', 'no']);?>
                                        </th>
                                        <th>
                                            <?php echo get_phrases(['description']);?>
                                        </th>
                                        <th class="text-right">
                                            <?php echo get_phrases(['total', 'amount']);?>
                                        </th>
                                        <th class="text-right">
                                            <?php echo get_phrases(['remaining', 'amount']);?>
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
                                    <tr>
                                        <td><?php echo esc($vouchers->package_id);?></td>
                                        <td><?php echo esc($vouchers->pack_name);?></td>
                                        <td class="text-right"><?php echo esc(getPriceFormat($vouchers->total));?></td>
                                        <td class="text-right"><?php echo esc(getPriceFormat($vouchers->remaining));?></td>
                                        <td class="text-right"><?php echo esc(getPriceFormat($vouchers->vat));?></td>
                                        <td class="text-right"><?php echo esc(getPriceFormat($vouchers->payment));?></td>
                                    </tr>
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
                                                    <?php echo get_phrases(['payment', 'method']);?>
                                                </th>
                                                <th class="text-right">
                                                   <?php echo get_phrases(['amount']);?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            if(!empty($vouchers->pay)){
                                                foreach ($vouchers->pay as $value) { ?>
                                                <tr>
                                                    <td><?php echo esc($value->nameE);?></td>
                                                    <td class="text-right"><?php echo esc(getPriceFormat($value->amount));?></td>
                                                </tr>
                                                <?php }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-8">
                                
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
                                <div class="font-weight-bold fs-13"><?php echo get_phrases(['cash', 'by']);?> </div>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-4">
                                <div class="font-weight-bold fs-13"><?php echo get_phrases(['depositor', 'signature']);?> </div>
                            </div>
                        </div>
                        <p class="text-muted mb-0">
                           <?php 
                           if(!empty($setting->voucher_notes)){
                                echo esc($setting->voucher_notes);
                           }
                           ?>
                        </p>
                        <p class="text-right no-print">
                            <button type="button" class="btn btn-purple mr-2" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']); ?></button>
                            <?php if($permission->method('journal_voucher', 'read')->access()){ ?>
                            <button type="button" class="btn btn-info viewJV" data-id="PV-<?php echo esc($vouchers->id);?>"><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']); ?></button>
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