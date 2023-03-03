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
                        <?php if($permission->method('payment_voucher', 'read')->access()){ ?>
                        <a href="<?php echo base_url('account/accounts/paymentVList');?>" class="btn btn-info btn-sm mr-1"><i class="fas fa-list mr-1"></i><?php echo get_phrases(['payment', 'voucher', 'list']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <?php if($permission->method('payment_voucher', 'create')->access()){ ?>
                    <?php echo form_open_multipart('account/accounts/savePayVoucher', 'id="voucherForm"');?>
                        <div class="row">
                            <div class="col-md-5 col-sm-12">
                                <input type="hidden" name="action" id="action" value="add">
                                <div class="form-group">
                                    <?php echo form_dropdown('voucher_id','','','class="custom-select" id="voucher_id"');?>
                                </div>
                                <input type="hidden" name="acc_head" id="acc_head">
                                <input type="hidden" name="acc_balance" id="acc_balance">
                                <input type="hidden" name="v_remain_amount" id="v_remain_amount">
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
                                                    <label for="nameA" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['receipt', 'voucher']) ?></label>
                                                    <input type="text" name="receipt_voucher" id="receipt_voucher" class="form-control form-control-small" readonly>
                                                </td>
                                                <td>
                                                    <label for="voucher_date" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['voucher', 'date']) ?></label>
                                                    <input type="text" name="voucher_date" id="voucher_date" class="form-control form-control-small" readonly>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7 col-sm-12">
                                <div class="form-group">
                                    <input type="hidden" name="packId" id="packId">
                                    <?php echo form_dropdown('package_id','','','class="custom-select" id="package_id"');?>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-12">
                                <div class="form-group">
                                    <input type="hidden" name="doctorId" id="doctorId">
                                    <?php echo form_dropdown('doctor_id','','','class="custom-select" id="doctor_id"');?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-5 col-sm-12">
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
                            
                            <div class="col-md-7 col-sm-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-sm mb-1">
                                            <thead>
                                                <tr>
                                                    <th width="21" class="text-right"><?php  echo get_phrases(['balance']);?></th>
                                                    <th width="7" class="text-right"><?php  echo get_phrases(['vat']);?></th>
                                                    <th width="25" class="text-right"><?php  echo get_phrases(['total', 'balance']);?></th>
                                                    <th width="20" class="text-right"><?php  echo get_phrases(['remaining', 'balance']);?></th>
                                                    <th width="7" class="text-right"><?php  echo get_phrases(['pay', 'vat']);?></th>
                                                    <th width="20" class="text-right"><?php  echo get_phrases(['pay', 'amount']);?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" name="total_price" id="total_price" class="form-control form-control-small text-right" value="0"  readonly></td>
                                                    <td><input type="text" name="total_vat" id="total_vat" class="form-control form-control-small text-right" value="0"  readonly></td>
                                                    <td><input type="text" name="remaining_receipt" id="remaining_receipt" class="form-control form-control-small text-right" value="0" readonly></td>
                                                    <td><input type="text" name="remaining" id="remaining" class="form-control form-control-small text-right" value="0" readonly></td>
                                                    <td><input type="text" name="pay_vat" id="pay_vat" class="form-control form-control-small text-right" value="0" readonly></td>
                                                    <td><input type="text" name="payable_amount" id="payable_amount" class="form-control form-control-small text-right" value="0"></td>
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
                                <button type="submit" class="btn btn-success" id="createPVoucher"><?php echo get_phrases(['create', 'voucher']);?></button>
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
    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#invoices-modal').modal('hide');
        $('#invoicesList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');

        // voucher submit by click
        $("#createPVoucher").one('click', function (event) {  
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

        // doctor list
        $.ajax({
            type:'GET',
            url: _baseURL+'auth/allPackList',
            dataType: 'json',
        }).done(function(data) {
            $("#package_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'package', 'name']);?>',
                data: data
            });
        });
    
        // search patient appointment
        $('#voucher_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'receipt', 'voucher', 'no']);?>',
            minimumInputLength: 1,
                ajax: {
                    url: _baseURL+'auth/searchRVoucher',
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

         // get service list by appointment Id
        $('#voucher_id').on('change', function(e){
            e.preventDefault();

            var id = $(this).val();
            var submit_url = _baseURL+"account/accounts/rvForPayment/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    var bl;
                    if(res.balance >=0){
                        bl = '<b class="fs-18"><?php echo get_phrases(['current', 'balance']);?></b> : <b class="text-success">'+parseFloat(res.balance).toFixed(2)+'</b>';
                    }else{
                        bl = '<b class="fs-18"><?php echo get_phrases(['current', 'balance']);?></b> : <b class="text-danger">'+parseFloat(res.balance).toFixed(2)+'</b>';
                    }
                    $('#BL').html(bl);
                    var Payment = res.payDetails.TotalPayment !=null?res.payDetails.TotalPayment:0.00;
                    var Vat = res.payDetails.TotalVat !=null?res.TotalVat.TotalPayment:0.00;
                    var TVat = parseFloat(res.vat - Vat);
                    var total = parseFloat(res.receipt - Payment);
                    var TotalPayable = parseFloat(total - TVat);
                    $('#patient_id').val(res.patient_id);
                    $('#nameE').val(res.nameE);
                    $('#nameA').val(res.nameA);
                    $('#file_no').val(res.file_no);
                    $('#nid_no').val(res.nid_no);
                    $('#voucher_date').val(res.voucher_date);
                    $('#receipt_voucher').val(res.id);
                    $('#doctorId').val(res.doctor_id);
                    $('#doctor_id').val(res.doctor_id).trigger('change');
                    $('#package_id').val(res.package_id).trigger('change');
                    $('#packId').val(res.package_id).trigger('change');
                    $("#doctor_id").select2({disabled:'readonly'});
                    $("#package_id").select2({disabled:'readonly'});
                    $('#total_price').val(TotalPayable.toFixed(2));
                    $('#total_vat').val(TVat.toFixed(2));
                    $('#v_remain_amount').val(res.remaining_balance);
                    $('#remaining_receipt').val(res.remaining_balance);
                    $('#vat_percent').val(res.vat_percent);
                    $('.pay_amount').val(0.00);
                    $('#acc_head').val(res.acc_head);
                    $('#acc_balance').val(res.balance);
                    $('#payable_amount').val(0).trigger('change')
                    $('#pay_vat').val(0.00);
                    $('#remaining').val(0.00);
                }
            });  
        });

        var defaultPay = $('#remaining_receipt').val();
        var paymentMHTML = '<tr>'+
                       '<td><select name="pm_name[]" id="pm_name" class="custom-select form-control-small pm_name"></select><div class="others"></div></td>'+
                       '<td><input type="text" name="pay_amount[]" id="pay_amount" class="form-control form-control-small text-right pay_amount onlyNumber" value="'+defaultPay+'" autocomplete="off"></td>'+
                       '<td><a href="javascript:void(0);" class="text-danger addPayBtn text-center"><i class="fa fa-minus fs-22"></i></a></td>'+
                   '</tr>';
        $('#payMethodTr').append(paymentMHTML);
        var countPayId =1;
        $('body').on('click', '.addPayM', function() {
            var total = parseFloat($('#payable_amount').val()) + parseFloat($('#pay_vat').val());
            var remaining = parseFloat($('#remaining_receipt').val());
            //total   
            var pay_amount = 0.00;
            var rest;
            $('.pay_amount').each(function(){ 
                pay_amount  += parseFloat($(this).val());
                
                if(total>pay_amount){
                    rest = total - pay_amount;
                    $('#remaining').val(remaining - pay_amount);
                }else if(total==pay_amount){
                    rest = 0;
                    $('#remaining').val(remaining - pay_amount);
                }else{
                    rest = 0;
                    $('#remaining').val(remaining - total);
                }
            });
            $("#payMethodTr").append('<tr>'+
                       '<td><select name="pm_name[]" id="pm_name_'+countPayId+'" class="custom-select form-control-small pm_name"></select><div class="others"></div></td>'+
                       '<td><input type="text" name="pay_amount[]" id="pay_amount_'+countPayId+'" value="'+rest+'" class="form-control form-control-small text-right pay_amount onlyNumber" autocomplete="off"></td>'+
                       '<td><a href="javascript:void(0);" class="text-danger addPayBtn text-center"><i class="fa fa-minus fs-22"></i></a></td>'+
                   '</tr>'); 
            // payment method list
            $.ajax({
                type:'GET',
                url: _baseURL+'auth/select2List/list_payment_method/127-368',
                dataType: 'json',
                data:{'csrf_stream_name':csrf_val},
            }).done(function(data) {
                $(".pm_name").select2({
                    placeholder: '<?php echo get_phrases(['select', 'payment', 'method']);?>',
                    data: data
                });
            });
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

        $(document).on('keyup', '.pay_amount', function(e){
            e.preventDefault();
            var total_vat = parseFloat($('#total_vat').val());
            var total_price = parseFloat($('#total_price').val());
            var total = parseFloat($('#total_price').val()) + total_vat;
            var remaining = parseFloat($('#remaining_receipt').val());
            if(total > 0){
                if($(this).val()){
                    //total   
                    var pay_amount = 0.00;
                    $('.pay_amount').each(function(){ 
                        pay_amount  += parseFloat($(this).val());
                        var payVat = total_vat*pay_amount/remaining;
                        var payable_amount = pay_amount - payVat;
                        $('#pay_vat').val(payVat.toFixed(2));
                        $('#payable_amount').val(payable_amount.toFixed(2));

                        if(total>pay_amount){
                            var rest = total - pay_amount;
                            $('#remaining').val(rest);
                        }else if(total==pay_amount){
                            $('#remaining').val(0.00);
                        }else{
                            var ref = pay_amount - parseFloat($(this).val());
                            var payVat = total_vat*ref/remaining;
                            $('#pay_vat').val(payVat.toFixed(2));
                            $('#payable_amount').val(ref);
                            $('#remaining').val(0.00);
                            toastr.warning("<?php echo get_notify('Pay_amount_must_be_equal_or_less_from_balance');?>");
                            $(this).val(0);
                        }
                    }); 
                }
            }else{
                toastr.warning("<?php echo get_phrases(['please', 'enter', 'pay', 'amount']);?>");
                $('#pay_amount').val(0);
            }
        });

        $(document).on('keyup', '#payable_amount', function(e){
            e.preventDefault();
            var total = parseFloat($('#total_price').val());
            var remaining = parseFloat($('#remaining_receipt').val());
            if($(this).val()){
                var vat = parseFloat($('#vat_percent').val());
                var pay_amount = parseFloat($(this).val());
                //total   
                var vatCal = (pay_amount*vat)/100;
                $('#pay_vat').val(vatCal);
                if(total>pay_amount){
                    var pay = pay_amount+vatCal;
                    var rest = remaining - pay;
                    $('#remaining').val(rest);
                    $('#pay_amount').val(pay);
                }else if(total==pay_amount){
                    var pay = pay_amount+vatCal;
                    $('#remaining').val(0);
                    $('#pay_amount').val(pay);
                }else{
                    toastr.warning("<?php echo get_notify('Pay_amount_must_be_equal_or_less_from_balance');?>");
                    $('#pay_vat').val(0);
                    $('#remaining').val(0);
                    $('#pay_amount').val(0);
                }
            }else{
                $('#pay_vat').val(0);
                $('#remaining').val(0);
                $('#pay_amount').val(0);
            }
        });


        $('body').on('click', '.addPayBtn', function() {
            var rowCount = $('#paymentTbl >tbody >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
                var payable = parseFloat($('#payable_amount').val()) + parseFloat($('#pay_vat').val());
                //total   
                var pay_amount = 0;
                $('.pay_amount').each(function(){ 
                    pay_amount  += parseFloat($(this).val());
                    var net = payable - pay_amount;
                    $(this).parent().parent().find('.pay_amount').val(isNaN(net)?0.00:net.toFixed(2));
                }); 
            }else{
                alert("There only one row you can't delete.");
            } 
        });

    });
</script>