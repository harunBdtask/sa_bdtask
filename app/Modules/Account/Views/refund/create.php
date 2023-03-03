<div class="row">
    <div class="col-sm-12">
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
                        <?php if($permission->method('refund_voucher', 'read')->access()){ ?>
                        <a href="<?php echo base_url('account/accounts/refundVList');?>" class="btn btn-info btn-sm mr-1"><i class="fas fa-list mr-1"></i><?php echo get_phrases(['refund', 'voucher', 'list']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <?php if($permission->method('refund_voucher', 'create')->access()){ ?>
                    <?php echo form_open_multipart('account/accounts/saveRefundV', 'id="voucherForm"');?>
                        <div class="row">
                            <div class="col-md-5 col-sm-12">
                                <input type="hidden" name="action" id="action" value="add">
                                <div class="form-group">
                                    <?php echo form_dropdown('invoice_id','','','class="custom-select" id="invoice_id"');?>
                                </div>
                                <input type="hidden" name="acc_head" id="acc_head">
                                <input type="hidden" name="acc_balance" id="acc_balance">
                                <input type="hidden" name="vat_percent" id="vat_percent">
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?php echo !empty($branchInfo)?$branchInfo->nameE:'';?>" readonly="">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12" id="BL">
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label for="file_no" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['file', 'no']) ?></label>
                                                    <input type="hidden" name="patient_id" id="patient_id">
                                                    <input type="text" name="file_no" id="file_no" class="form-control form-control-small" placeholder="<?php echo get_phrases(['file', 'no']);?>" readonly>
                                                </td>
                                                <td>
                                                    <label for="nameE" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['name', 'english']) ?></label>
                                                    <input type="text" name="nameE" id="nameE" class="form-control form-control-small" placeholder="<?php echo get_phrases(['name', 'english']);?>" readonly>
                                                </td>
                                                <td>
                                                    <label for="nameA" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['name', 'arabic']) ?></label>
                                                    <input type="text" name="nameA" id="nameA" class="form-control form-control-small" placeholder="<?php echo get_phrases(['name', 'arabic']);?>" readonly>
                                                </td>
                                                 <td>
                                                    <label for="nid_no" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['id', 'no']) ?></label>
                                                    <input type="text" name="nid_no" id="nid_no" class="form-control form-control-small" placeholder="<?php echo get_phrases(['id', ' no']);?>" readonly>
                                                </td>
                                                <td>
                                                    <label for="invoice_no" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['invoice', 'no']) ?></label>
                                                    <input type="text" id="invoice_no" class="form-control form-control-small" readonly>
                                                </td>
                                                <td>
                                                    <label for="invoice_date" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['invoice', 'date']) ?></label>
                                                    <input type="text" name="invoice_date" id="invoice_date" class="form-control form-control-small" readonly>
                                                </td>
                                            </tr>
                                             <tr>
                                                <td colspan="4">
                                                    <div class="mt-2">
                                                        <input type="hidden" name="doctorId" id="doctorId">
                                                        <?php echo form_dropdown('doctor_id','','','class="custom-select" id="doctor_id"');?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <label for="voucher_no" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['return', 'voucher', 'no']) ?></label>
                                                    <input type="text" name="voucher_no" id="voucher_no" class="form-control form-control-small" value="<?php echo getMAXID('payment_vouchers', 'id');?>" readonly>
                                                </td>
                                                <td>
                                                    <label for="voucher_date" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['voucher', 'date']) ?></label>
                                                    <input type="text" name="voucher_date" id="voucher_date" class="form-control form-control-small" value="<?php echo date('Y-m-d');?>" readonly>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-stripped table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="8%"><?php echo get_phrases(['code', 'no']);?></th>
                                                <th class="text-center" width="28%"><?php echo get_phrases(['description']);?></th>
                                                <th class="text-center" width="5%"><?php echo get_phrases(['qty']);?></th>
                                                <th class="text-right" width="8%"><?php echo get_phrases(['price']);?></th>
                                                <th class="text-right" width="8%"><?php echo get_phrases(['gross', 'total']);?></th>
                                                <th class="text-right" width="8%"><?php echo get_phrases(['discount']);?></th>
                                                <th class="text-right" width="10%"><?php echo get_phrases(['total', 'net']);?></th>
                                                <th class="text-right" width="7%"><?php echo get_phrases(['vat']);?></th>
                                                <th class="text-right" width="9%"><?php echo get_phrases(['total']);?></th>
                                                <th class="text-right" width="10%"><?php echo get_phrases(['refund', 'amount']);?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="service_div">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <table class="table table-bordered table-sm" id="paymentTbl">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="62%"><?php echo get_phrases(['payment', 'method']);?></th>
                                            <th class="text-center" width="30%"><?php echo get_phrases(['amount']);?></th>
                                            <th class="text-center" width="8%"><i class="fa fa-minus"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="payMethodTr">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3">
                                                <a href="javascript:void(0);" class="btn btn-sm btn-info addPayM"><i class="fa fa-plus"></i> <?php echo get_phrases(['add', 'more']);?></a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-sm mb-1">
                                            <thead>
                                                <tr>
                                                    <th class="text-right"><?php  echo get_phrases(['total', 'price']);?></th>
                                                    <th class="text-right"><?php  echo get_phrases(['total', 'discount']);?></th>
                                                    <th class="text-right"><?php  echo get_phrases(['after', 'discount']);?></th>
                                                    <th class="text-right"><?php  echo get_phrases(['total', 'vat']);?></th>
                                                    <th class="text-right"><?php  echo get_phrases(['total', 'receipt']);?></th>
                                                    <th class="text-right"><?php  echo get_phrases(['remaining', 'amount']);?></th>
                                                    <th class="text-right"><?php  echo get_phrases(['pay', 'vat']);?></th>
                                                    <th class="text-right"><?php  echo get_phrases(['total', 'refund']);?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" name="total_price" id="total_price" class="form-control form-control-small text-right" value="0"  readonly></td>
                                                    <td><input type="text" name="total_discount" id="total_discount" class="form-control form-control-small text-right" value="0"  readonly></td>
                                                    <td><input type="text" name="after_discount" id="after_discount" class="form-control form-control-small text-right" value="0"  readonly></td>
                                                    <td><input type="text" name="total_vat" id="total_vat" class="form-control form-control-small text-right" value="0"  readonly></td>
                                                    <td><input type="text" name="total_receipt" id="total_receipt" class="form-control form-control-small text-right" value="0"  readonly></td>
                                                    <td><input type="text" name="remaining" id="remaining" class="form-control form-control-small text-right" value="0" readonly></td>
                                                    <td><input type="text" name="pay_vat" id="pay_vat" class="form-control form-control-small text-right" value="0" readonly></td>
                                                    <td><input type="text" name="deduction" id="deduction" class="form-control form-control-small text-right onlyNumber" value="0" readonly></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="<?php echo get_phrases(['notes']);?>"></textarea>
                                    </div>
                                     <div class="col-md-4">
                                        <input type="file" name="attach_file" class="form-control" id="attach_file" accept=".png, .jpg, .jpeg, .pdf">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php echo form_close();?>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-success" id="createRefund" disabled=""><?php echo get_phrases(['create', 'voucher']);?></button>
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
                    <div class="row">
                        <div class="col-md-12">
                            <strong class="fs-20 text-danger"><?php echo get_phrases(['you_do_not_have_permission_to_access_please_contact_with_administrator']);?></strong>
                        </div>
                    </div>
                <?php }?>
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');

         // voucher submit by click
        $("#createRefund").one('click', function (event) {  
           event.preventDefault();
           $(this).prop('disabled', true);
           $('#voucherForm').submit();
        });

        // payment method list
        $.ajax({
            type:'GET',
            url: _baseURL+'auth/select2List/list_payment_method/127-130-150-368',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#pm_name").select2({
                placeholder: '<?php echo get_phrases(['select', 'payment', 'method']);?>',
                data: data
            });
        });

        // doctor list
        $.ajax({
            type:'GET',
            url: _baseURL+'auth/doctorList',
            dataType: 'json',
        }).done(function(data) {
            $("#doctor_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'doctor', 'name']);?>',
                data: data
            });
        });
    
        // search patient appointment 
        $('#invoice_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'invoice', 'no']);?>',
            minimumInputLength: 1,
                ajax: {
                    url: _baseURL+'auth/searchInvoiceNo',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                      return {
                        results:  $.map(data, function (item) {
                              return {
                                  text: item.text,
                                  id: item.id
                              }
                          })
                      };
                    },
                    cache: true
               }
        });

         // get invoice info for refund Id
        $('#invoice_id').on('change', function(e){
            e.preventDefault();

            var id = $(this).val();
            var submit_url = _baseURL+"account/accounts/invInfoForRefund/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    //console.log(res);
                    var bl;
                    if(res.balance >=0){
                        bl = '<b class="fs-18"><?php echo get_phrases(['current', 'balance']);?></b> : <b class="text-success">'+parseFloat(res.balance).toFixed(2)+'</b>';
                    }else{
                        bl = '<b class="fs-18"><?php echo get_phrases(['current', 'balance']);?></b> : <b class="text-danger">'+parseFloat(res.balance).toFixed(2)+'</b>';
                    }
                    $('#BL').html(bl);
                    $('#patient_id').val(res.patient_id);
                    $('#nameE').val(res.nameE);
                    $('#nameA').val(res.nameA);
                    $('#file_no').val(res.file_no);
                    $('#nid_no').val(res.nid_no);
                    $('#invoice_date').val(res.invoice_date);
                    $('#invoice_no').val(res.id);
                    $('#doctorId').val(res.doctor_id);
                    $('#doctor_id').val(res.doctor_id).trigger('change');
                    $("#doctor_id").select2({disabled:'readonly'});
                    $('#total_price').val(0);
                    $('#total_receipt').val(0);
                    $('.pay_amount').val(0);
                    $('#total_vat').val(0);
                    $('#pay_vat').val(0);
                    $('#acc_head').val(res.acc_head);
                    $('#acc_balance').val(res.balance);
                    $('#vat_percent').val(res.vat_percent);
                    var list ='';
                    var totalDis = 0;
                    var afterDis = 0;
                    var Count =1;
                    $.each(res.services, function (key, value) {
                        var gross = parseFloat(value.qty*parseFloat(value.price));
                        var isRefund = value.isRefund==1?'disabled checked':'';
                        var color = value.isRefund==1?'text-success':'';
                        var dis = parseFloat(value.offer_discount) + parseFloat(value.doctor_discount);
                        var disPrice = gross - parseFloat(dis);
                        var vat = parseFloat(value.vat);
                        var amount = parseFloat(value.amount);
                        totalDis += dis;
                        afterDis += disPrice;
                        list += '<tr class="clickable-row">'+
                                   '<td class="text-center '+color+'"><input type="checkbox" name="ID['+Count+']" id="RowId-'+value.app_service_id+'" class="RowId" value="'+value.app_service_id+'" '+isRefund+'> '+value.code_no+'</td>'+
                                   '<td class="text-center '+color+'">'+value.nameE+'</td>'+
                                   '<td class="text-center"><input type="hidden" name="qty['+Count+']" class="qty" value="'+value.qty+'">'+value.qty+'</td>'+
                                   '<td class="text-right"><input type="hidden" name="price['+Count+']" class="price" value="'+value.price+'">'+value.price+'</td>'+
                                   '<td class="text-right"><input type="hidden" name="gross['+Count+']" class="gross" value="'+gross+'">'+gross.toFixed(2)+'</td>'+
                                   '<td class="text-right"><input type="hidden" name="dis['+Count+']" class="dis" value="'+dis+'">'+dis.toFixed(2)+'</td>'+
                                   '<td class="text-right"><input type="hidden" name="disPrice['+Count+']" class="disPrice" value="'+disPrice+'">'+disPrice.toFixed(2)+'</td>'+
                                   '<td class="text-right"><input type="hidden" name="vat['+Count+']" class="vat" value="'+value.vat+'"><input type="hidden" name="vat_deduct['+Count+']" class="vat_deduct" value="0">'+vat.toFixed(2)+'</td>'+
                                   '<td class="text-right"><input type="hidden" name="amount['+Count+']" class="amount" value="'+value.amount+'">'+amount.toFixed(2)+'</td>'+
                                   '<td><input type="text" name="refund_amoun['+Count+']" class="form-control form-control-small onlyNumber refundAmount text-right" value="0.00" autocomplete="off"></td>'+
                               '</tr>';
                        Count++;
                    });
                    $('#total_discount').val(0);
                    $('#after_discount').val(0);
                    $('#deduction').val(0);
                    $('#service_div').html(list);
                }
            });  
        });

        // checked unchecked services
        $(document).on('click', '#service_div tr', function(event) {
            if (event.target.type !== 'checkbox') {
                $(':checkbox', this).trigger('click');
            }
        });

        $(document).on('change', '.RowId', function() {
            var total= $('input:checkbox:checked').length;
            if(total > 0){
                $('#createRefund').attr('disabled', false);
            }else{
                $('#createRefund').attr('disabled', true);
            }
            calculator1(this);
        });

        //selected service calculation
        $(document).on('keyup', '.refundAmount', function() {
            var refund = parseFloat($(this).val());
            var amt = parseFloat($(this).parent().parent().find(".disPrice").val());
            
            if(refund && !isNaN(refund)){
                if(amt >= refund){
                    calculator(this);
                }else{
                    if(confirm('<?php echo get_notify('Refund_amount_can_not_exceed_after_discount_amount');?>')){
                         $(this).val(amt)
                        calculator(this);
                    }else{
                        $(this).val(0)
                        calculator(this);
                    }
                }
            }
        });

        function calculator(event){
            var refund = 0;
            var deductvat  = 0;
            var rf_amt = parseFloat($(event).val());
            var vt = parseFloat($(event).parent().parent().find(".vat").val());
            var afdis = parseFloat($(event).parent().parent().find(".disPrice").val());
            var totVatDeduct = (vt*rf_amt)/afdis;
            parseFloat($(event).parent().parent().find(".vat_deduct").val(totVatDeduct));
            //alert('amt= '+rf_amt+'vt= '+vt+'afdis= '+afdis+'totVatDeduct= '+totVatDeduct);
            $('.refundAmount').each(function(){ 
                refund  += parseFloat($(this).val());
                deductvat += parseFloat($(this).parent().parent().find(".vat_deduct").val());
            });
            var total = deductvat+refund;
            var totalPay = total > 0?total:0;

            $('.pay_amount').val(totalPay.toFixed(2));
            $('#pay_vat').val(deductvat.toFixed(2));
            $('#deduction').val(refund.toFixed(2));
        }

        function calculator1(event){
            var rf_amt = parseFloat($(event).parent().parent().find(".refundAmount").val());
            var price = parseFloat($(event).parent().parent().find(".price").val());
            var gross = parseFloat($(event).parent().parent().find(".gross").val());
            var amt = parseFloat($(event).parent().parent().find(".amount").val());
            var dis = parseFloat($(event).parent().parent().find(".dis").val());
            var disPrice = parseFloat($(event).parent().parent().find(".disPrice").val());
            var deduct = parseFloat($(event).parent().parent().find(".vat_deduct").val());
            var vat = parseFloat($(event).parent().parent().find(".vat").val());
            var pay  = parseFloat($('#pay_amount').val());

            if($(event).prop("checked") == true){
                var total =pay + deduct + rf_amt;
                rf_amt  += parseFloat($('#deduction').val());
                gross += parseFloat($('#total_price').val());
                amt += parseFloat($('#total_receipt').val());
                dis += parseFloat($('#total_discount').val());
                disPrice += parseFloat($('#after_discount').val());
                deduct += parseFloat($('#pay_vat').val());
                vat += parseFloat($('#total_vat').val());
            }
            if($(event).prop("checked") == false){
                var total =pay - (deduct + rf_amt);
                rf_amt  = parseFloat($('#deduction').val()) - rf_amt;
                gross = parseFloat($('#total_price').val()) - gross;
                amt = parseFloat($('#total_receipt').val()) - amt;
                dis = parseFloat($('#total_discount').val()) - dis;
                disPrice = parseFloat($('#after_discount').val()) - disPrice;
                deduct = parseFloat($('#pay_vat').val()) - deduct;
                vat = parseFloat($('#total_vat').val()) - vat;
            }
            total = total > 0?total:0;
            deduct = deduct > 0?deduct:0;
            $('#total_price').val(gross.toFixed(2));
            $('#total_receipt').val(amt.toFixed(2));
            $('#total_vat').val(vat.toFixed(2));
            $('#total_discount').val(dis.toFixed(2));
            $('#after_discount').val(disPrice.toFixed(2));
            $('#pay_vat').val(deduct.toFixed(2));
            $('#pay_amount').val(total.toFixed(2))
            $('#deduction').val(rf_amt.toFixed(2));
        }

        //qty total summation
        $(document).on('keyup', '.pay_amount', function() {
            var amt = 0;
            $('.pay_amount').each(function(){ 
                amt  += parseFloat($(this).val());
            });

            var refund = parseFloat($('#deduction').val());
            var vat  = parseFloat($('#pay_vat').val());
            var total =refund + vat;
            
            if(amt){
                if(amt>total){
                    alert("<?php echo get_notify('Can_not_exceed_the_total_pay_amount');?>");
                    $(this).val(0);
                    var tt = 0;
                    $('.pay_amount').each(function(){ 
                        tt  += parseFloat($(this).val());
                    });
                    var restT = total - tt;
                    var rest = restT > 0?restT:0;
                    $(this).val(rest.toFixed(2));
                }
            }
        });

        var defaultPay = $('#deduction').val();
        var paymentMHTML = '<tr>'+
                       '<td><select name="pm_name[]" id="pm_name" class="custom-select form-control-small pm_name"></select><div class="others"></div></td>'+
                       '<td><input type="text" name="pay_amount[]" id="pay_amount" class="form-control form-control-small text-right pay_amount onlyNumber" value="'+defaultPay+'" autocomplete="off"></td>'+
                       '<td><a href="javascript:void(0);" class="text-danger addPayBtn text-center"><i class="fa fa-minus fs-22"></i></a></td>'+
                   '</tr>';
        $('#payMethodTr').append(paymentMHTML);
        var countPayId =1;
        $('body').on('click', '.addPayM', function() {
            $("#payMethodTr").append('<tr>'+
                       '<td><select name="pm_name[]" id="pm_name_'+countPayId+'" class="custom-select form-control-small pm_name"></select><div class="others"></div></td>'+
                       '<td><input type="text" name="pay_amount[]" id="pay_amount_'+countPayId+'" class="form-control form-control-small text-right pay_amount onlyNumber" autocomplete="off"></td>'+
                       '<td><a href="javascript:void(0);" class="text-danger addPayBtn text-center"><i class="fa fa-minus fs-22"></i></a></td>'+
                   '</tr>'); 
            // payment method list
            $.ajax({
                type:'GET',
                url: _baseURL+'auth/select2List/list_payment_method/127-130-150-368',
                dataType: 'json',
                data:{'csrf_stream_name':csrf_val},
            }).done(function(data) {
                $(".pm_name").select2({
                    placeholder: '<?php echo get_phrases(['select', 'payment', 'method']);?>',
                    data: data
                });
            });
            $('#due_total').val(0);
            countPayId++;
        });
        var payNoDate = '<div class="row"><div class="col-md-6 mt-1"><input type="text" name="ac_no[]" class="form-control form-control-small onlyNumber" placeholder="<?php echo get_phrases(['card', 'number']);?>"></div><div class="col-md-6 mt-1"><input type="text" name="edate[]" class="form-control form-control-small onlyD"></div></div>';
        var payNoName = '<div class="row"><div class="col-md-6 mt-1"><input type="text" name="ac_no[]" class="form-control form-control-small onlyNumber" placeholder="<?php echo get_phrases(['account', 'no']);?>"></div><div class="col-md-6 mt-1"><input type="text" name="bank_name[]" class="form-control form-control-small" placeholder="<?php echo get_phrases(['bank', 'name']);?>"></div></div>';
        // get service list by appointment Id
        $(document).on('change', '.pm_name', function(e){
            e.preventDefault();
            var id = $(this).val();
            if(id=='122'){
                $(this).closest('td').find('.others').html(payNoDate);
            }else if(id=='121'){
                $(this).closest('td').find('.others').html(payNoDate);
            }else if(id=='123'){
                $(this).closest('td').find('.others').html(payNoDate);
            }else if(id=='124'){
               $(this).closest('td').find('.others').html(payNoDate);
            }else if(id=='125'){
                $(this).closest('td').find('.others').html(payNoName);
            }else if(id=='126'){
                $(this).closest('td').find('.others').html(payNoDate);
            }else if(id=='129'){
                var vat = $('#pay_vat').val();
                var deduction = $('#deduction').val();
                var total = parseFloat(vat+deduction);
                $(this).parent().parent().nextAll('tr').remove();
                $(this).parent().parent().prevAll('tr').remove();
                $(this).parent().parent().find('td .pay_amount').val(total.toFixed(2));
                // $(this).parent().parent().find('td .pay_amount').attr('readonly', true);
                // $('.addPayM').attr('disabled', true);
            }else{
                $(this).closest('td').find('.others').html('');
            }
            //Single Date Picker
            $('.onlyD').daterangepicker({
                singleDatePicker: true,
                locale : {
                    format : 'YYYY-MM-DD'
                }
            });
        });


        $('body').on('click', '.addPayBtn', function() {
            var rowCount = $('#paymentTbl >tbody >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
                var payable = parseFloat($('#deduction').val());
                //total   
                var pay_amount = 0;
                $('.pay_amount').each(function(){ 
                    pay_amount  += parseFloat($(this).val());
                    var net = payable - pay_amount;
                    $('#due_total').val(isNaN(net)?0.00:net.toFixed(2));
                }); 
            }else{
                alert("There only one row you can't delete.");
            } 
        });

    });
</script>