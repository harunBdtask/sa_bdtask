<link href="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card card-bd lobidrag">
            <div class="card-heading">
                <div class="card-title">
                    <h4>

                    </h4>
                </div>
            </div>
            <div class="card-body">
                <?php echo form_open_multipart('sale/deliver_order/accounts_do_paid', 'id="dealer_receive_form"') ?>
                <div class="row" id="">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="select" class="col-sm-4 col-form-label"><?php echo get_phrases(['dealer', 'name']) ?> :
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="" class="form-control" value="<?php echo $do_main->dealer_name ?>" readonly="">
                                <input type="hidden" name="dealer_code">
                                <!-- <input type="hidden" name="dealer_code" value="<?php //echo $dealer_coa->HeadCode ?>"> -->
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="select" class="col-sm-4 col-form-label"><?php echo get_phrases(['date']) ?> :
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="date" class="form-control" placeholder="date" value="<?php echo date('Y-m-d') ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="select" class="col-sm-4 col-form-label"><?php echo get_phrases(['payment', 'type']) ?> <i class="text-danger">*</i> :
                            </label>
                            <div class="col-sm-8">
                                
                                <select name="payment_method" class="form-control select2" id="payment_method"  required>
                                    <option value="">Select Payment Type</option>
                                    <option value="<?php echo $predhead->cashCode?>">Cash Payment</option>
                                    <option value="1">Bank Payment</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 bank_div">
                        <div class="form-group row">
                            <label for="select" class="col-sm-4 col-form-label bank_div"><?php echo get_phrases(['bank']) ?>:</label>
                            <div class="col-sm-8 bank_div" id="bank_div">

                                <?php echo form_dropdown('bank_id', $bank_list, null, 'class="form-control select2" id="bank_id"') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="do_no" class="col-sm-4 col-form-label"><?php echo get_phrases(['dO', 'no']) ?> :
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="challan_no" class="form-control" value="<?php echo $do_main->vouhcer_no ?>" readonly>
                                <input type="hidden" name="dealer_id" value="<?php echo $do_main->dealer_id ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="grand_total" class="col-sm-4 col-form-label"><?php echo get_phrases(['grand', 'total']) ?> :
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="grand_total" class="form-control" placeholder="grand_total" value="<?php echo $do_main->grand_total ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="payable_amount" class="col-sm-4 col-form-label"><?php echo get_phrases(['payable', 'amount']) ?> :
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="payable_amount" class="form-control" placeholder="payable_amount" value="<?php echo ($do_main->grand_total ? $do_main->grand_total : 0) - ($do_main->paid_amount ? $do_main->paid_amount : 0) ?>" id="payable_amount" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="paid_amount" class="col-sm-4 col-form-label"><?php echo get_phrases(['paid', 'amount']) ?> <i class="text-danger">*</i>:
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="paid_amount" class="form-control" placeholder="<?php echo get_phrases(['paid', 'amount']) ?>" value="" id="paid_amount" autocomplete="off" onkeyup="check_payamount()" onchange="check_payamount()" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="due_amount" class="col-sm-4 col-form-label"><?php echo get_phrases(['due', 'amount']) ?> :
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="due_amount" class="form-control" id="due_amount" placeholder="0.00" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="due_payment_date" class="col-sm-4 col-form-label"><?php echo get_phrases(['due', 'payment', 'date']) ?> :
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="due_payment_date" class="form-control datepic" id="due_payment_date" placeholder="" value="" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="due_payment_date" class="col-sm-4 col-form-label"><?php echo get_phrases(['attachment']) ?> :
                            </label>
                            <div class="col-sm-8">
                                <input type="file" name="attachment" class="form-control " id="attachment" placeholder="" value="">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="description" class="col-sm-2 col-form-label"><?php echo get_phrases(['description']) ?> :
                            </label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="description" placeholder="write description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group row">

                            <div class="col-sm-12 text-right">
                                <a href="javascript:void(0)" class="btn btn-warning mr-2" onclick="full_paid()"><?php echo get_phrases(['full', 'paid']) ?></a><button type="submit" class="btn btn-success"><?php echo get_phrases(['save']) ?></button>
                                <input type="hidden" name="do_id" value="<?php echo $do_main->do_id ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <?php echo form_close() ?>

            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('.datepic').datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $(".select2").select2();

        var elements = document.getElementsByClassName('bank_div');
        for (var i = 0; i < elements.length; i++) {
            elements[i].style.display = 'none';
        }


        // var frm = $("#dealer_receive_form");
        // frm.on('submit', function(e) {
        // e.preventDefault();
        // var paid_amount    = $("#paid_amount").val(); 
        // var payment_method = $("#payment_method").val(); 
        //     if(paid_amount == 0 || paid_amount == ''){
        //         toastr.error('Paid Amount Can not null or 0');
        //         return false;
        //     }

        //     if(payment_method == ' '){
        //        toastr.error('Payment Method field required');
        //         return false;  
        //     }

        //     $.ajax({
        //         url: $(this).attr('action'),
        //         method: $(this).attr('method'),
        //         dataType : 'html',
        //         async: false,
        //         data: frm.serialize(),
        //         success: function(data) {
        //          if(data.status = 1){
        //                 toastr.success(data.message);
        //             }else{
        //              toastr.error(data.message);   
        //             }
        //             location.reload(); 
        //         },
        //         error: function(xhr) {
        //            toastr.error('Please Try Again');
        //         }
        //     });
        // });
    });


    $('body').on('change', '#payment_method', function() {
        var val = this.value;
        if (val == 1) {
            var style = 'block';
        } else {
            var style = 'none';
        }
        var elements = document.getElementsByClassName('bank_div');

        for (var i = 0; i < elements.length; i++) {
            elements[i].style.display = style;
        }
    });
</script>