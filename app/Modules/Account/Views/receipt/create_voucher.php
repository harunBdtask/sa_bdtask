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
                        <?php if($permission->method('receipt_voucher', 'read')->access()){ ?>
                        <a href="<?php echo base_url('account/accounts/receiptVList');?>" class="btn btn-info btn-sm mr-1"><i class="fas fa-list mr-1"></i><?php echo get_phrases(['receipt', 'voucher', 'list']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if($permission->method('receipt_voucher', 'create')->access()){ ?>
                    <?php echo form_open_multipart('account/accounts/saveRVoucher', 'id="voucherForm"');?>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <input type="hidden" name="action" id="action" value="add">
                                <div class="form-group">
                                    <?php echo form_dropdown('patient_id','','','class="custom-select" id="patient_id"');?>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <?php echo form_dropdown('invoice_id','','','class="custom-select" id="invoice_id"');?>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <center id="BL"></center>
                            </div>
                            <input type="hidden" name="acc_head" id="acc_head">
                            <input type="hidden" name="acc_balance" id="acc_balance">
                            <input type="hidden" name="nationality" id="nationality">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label for="file_no" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['file', 'no']) ?></label>
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
                                                    <label for="nameA" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?></label>
                                                    <input type="text" name="voucher_no" id="voucher_no" class="form-control form-control-small" value="<?php echo getMAXID('vouchers', 'id');?>" readonly>
                                                </td>
                                                <td>
                                                    <label for="voucher_date" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['voucher', 'date']) ?></label>
                                                    <input type="text" name="voucher_date" id="voucher_date" class="form-control form-control-small" value="<?php echo date('d/m/Y');?>" readonly>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 col-sm-12">
                                <div class="form-group">
                                    <?php echo form_dropdown('package_id','','','class="custom-select" id="package_id"');?>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <?php echo form_dropdown('doctor_id','','','class="custom-select" id="doctor_id", required="required"');?>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?php echo !empty($branchInfo)?$branchInfo->nameE:'';?>" readonly="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <input type="hidden" name="vat_percent" id="vat_percent">
                                <input type="hidden" name="default_vat" id="default_vat" value="<?php echo get_setting('default_vat');?>">
                                <div class="table-responsive">
                                    <table class="table table-stripped table-sm">
                                        <thead>
                                            <tr class="bg-info text-white">
                                                <th class="text-center" width="10%"><?php echo get_phrases(['code', 'no']);?><i class="text-danger">*</i></th>
                                                <th class="text-center" width="38%"><?php echo get_phrases(['description']);?></th>
                                                <th class="text-center" width="5%"><?php echo get_phrases(['qty']);?></th>
                                                <th class="text-center" width="10%"><?php echo get_phrases(['price']);?></th>
                                                <th class="text-center" width="10%"><?php echo get_phrases(['discount']);?></th>
                                                <th class="text-center" width="10%"><?php echo get_phrases(['after', 'discount']);?></th>
                                                <th class="text-center" width="7%"><?php echo get_phrases(['vat']);?>%</th>
                                                <th class="text-center" width="10%"><?php echo get_phrases(['net', 'amount']);?></th>
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
                                            <th class="text-right" width="30%"><?php echo get_phrases(['amount']);?></th>
                                            <th class="text-center" width="8%"><i class="fa fa-minus"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="payMethodTr">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3">
                                                <a href="javascript:void(0);" class="btn btn-sm btn-info addPayM"><i class="fa fa-plus"></i> <?php echo get_phrases(['add', 'more']);?></a>
                                                <span id="invoiceLink">
                                                    
                                                </span>
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
                                                    <th class="text-right"><?php  echo get_phrases(['total','discount']);?></th>
                                                    <th class="text-right"><?php  echo get_phrases(['amount','without', 'vat']);?></th>
                                                    <th class="text-right"><?php  echo get_phrases(['total', 'vat']);?></th>
                                                    <th class="text-right"><?php  echo get_phrases(['total', 'due']);?></th>
                                                    <th class="text-right"><?php  echo get_phrases(['grand', 'total']);?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" name="total_price" id="total_price" class="form-control form-control-small text-right" value="0"  readonly></td>
                                                    <td><input type="text" name="total_discount" id="total_discount" class="form-control form-control-small text-right" value="0" readonly></td>
                                                    <td><input type="text" name="amount_without_vat" id="amount_without_vat" class="form-control form-control-small text-right onlyNumber" value="0" autocomplete="off"></td>
                                                    <td><input type="text" name="sub_vat" id="sub_vat" class="form-control form-control-small text-right" value="0" readonly></td>
                                                    <td><input type="text" name="due_total" id="due_total" class="form-control form-control-small text-right" value="0" readonly></td>
                                                    <td><input type="text" name="payable" id="payable" class="form-control form-control-small text-right" value="0" readonly></td>
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
                                <button type="submit" class="btn btn-success" id="createVoucher"><?php echo get_phrases(['create', 'voucher']);?></button>
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

<!-- view voucher details modal -->
<div class="modal fade bd-example-modal-xl" id="viewDModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="viewDModalLabel"><?php echo get_phrases(['view', 'details']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
               <div class="row">
                   <div class="col-md-12" id="viewDetails">
                        
                   </div>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('#invoices-modal').modal('hide');
        $('.ajaxForm')[0].reset();        
        $('#invoicesList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');

        // voucher submit by click
        $("#createVoucher").one('click', function (event) {  
           event.preventDefault();
           $(this).prop('disabled', true);
           $('#voucherForm').submit();
        });

        // payment method list
        $.ajax({
            type:'GET',
            url: _baseURL+'auth/select2List/list_payment_method/127-129-130-150-368',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#pm_name").select2({
                placeholder: '<?php echo get_phrases(['select', 'payment', 'method']);?>',
                data: data
            });
        });

        // packages list
        // $.ajax({
        //     type:'GET',
        //     url: _baseURL+'auth/allPackList',
        //     dataType: 'json',
        // }).done(function(data) {
        //     $("#package_id").select2({
        //         placeholder: '<?php echo get_phrases(['select', 'package', 'name']);?>',
        //         data: data
        //     });
        // });

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
        $('#patient_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'patient']);?>',
            minimumInputLength: 2,
                ajax: {
                    url: _baseURL+'auth/searchPntWithFile',
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

       
        $("#invoice_id").on('select2:open', function (e) {
            var p = $('#patient_id').val();
            if(p===''){
                if(confirm('Please select the patient first!')){
                    $(this).select2('close');
                }
            }
        });

        // search credit invoice
        $('#invoice_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'credit', 'invoice', 'id']);?>',
            minimumInputLength: 2,
                ajax: {
                    url: _baseURL+'account/accounts/searchCreditInvoice',
                    data: function (params) {
                      var query = {
                        search: params.term,
                        patient_id: $('#patient_id').val()
                      }
                      return query;
                    },
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
        $('#package_id').on('change', function(e){
            e.preventDefault();

            var id = $(this).val();
            var ids = id.split('_');
            var patient_id = $('#patient_id').val();
            var submit_url = _baseURL+"account/services/servicesByPackId/"+ids[1]; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, patient_id:patient_id, appoint_id:ids[0]},
                dataType: 'JSON',
                success: function(res) {
                    console.log(res);
                    var options = new Option('', '', true, true);
                    $('#invoice_id').append(options).trigger('change');
                     $('#doctor_id').val(res.doctor_id).trigger('change');
                    var nid = $('#nid_no').val();
                    var nationality = $('#nationality').val();
        
                    var list ='';
                    var subTotal = 0;
                    var afterDis = 0;
                    var totalVat = 0;
                    var totalPrice = 0;
                    var totalDiscount = 0;
                    var firstDigi = nid.substring(0,1);
                    if(nationality.indexOf("Saudi") > -1 && firstDigi=="1"){
                        var vat = 0;
                        $('#vat_percent').val(vat);
                    }else{
                        var vat = parseFloat($('#default_vat').val());
                        $('#vat_percent').val(vat);
                    }

                    $.each(res.services, function (key, value) {
                        var totalPr = parseFloat(value.price*value.qty);
                        var dis = (totalPr*value.packDis)/100;
                        var disPrice = totalPr - parseFloat(dis);
                        var vatCal = (disPrice*vat)/100;
                        var netTotal = parseFloat(disPrice) + parseFloat(vatCal);
                        subTotal += netTotal;
                        afterDis += parseFloat(vatCal);
                        totalVat += parseFloat(vatCal);
                        totalPrice += parseFloat(totalPr);
                        totalDiscount += parseFloat(dis);
                        list += '<tr>'+
                                   '<td><input type="hidden" name="id[]" value="'+value.id+'"><input type="hidden" name="service_id[]" value="'+value.service_id+'">'+value.code_no+'</td>'+
                                   '<td>'+value.nameE+'</td>'+
                                   '<td><input type="text" name="qty[]" class="form-control form-control-small onlyNumber qty-calc" value="'+value.qty+'" readonly></td>'+
                                   '<td><input type="text" name="price[]" class="form-control form-control-small text-right" value="'+value.price+'" readonly></td>'+
                                   '<td><input type="text" name="discount[]" class="form-control form-control-small text-right" value="'+dis.toFixed(2)+'" readonly></td>'+
                                   '<td><input type="text" name="after_dis[]" class="form-control form-control-small text-right" value="'+disPrice.toFixed(2)+'" readonly></td>'+
                                   '<td><input type="text" name="vat[]" class="form-control form-control-small vat" value="'+vatCal+'" readonly></td>'+
                                   '<td><input type="hidden" class="amount" value="'+netTotal.toFixed(2)+'"><input type="text" name="subtotal[]" class="form-control form-control-small onlyNumber subtotal text-right" value="'+netTotal.toFixed(2)+'" readonly></td>'+
                               '</tr>';
                    });
                    var afterDisAmt = totalPrice - totalDiscount;
                    $('#service_div').html(list);
                    $('#total_price').val(totalPrice.toFixed(2));
                    $('#sub_vat').val(totalVat.toFixed(2));
                    $('#total_discount').val(totalDiscount.toFixed(2));
                    $('#payable').val(subTotal.toFixed(2));
                    $('#due_total').val(0.00);
                    $('#pay_amount').val(subTotal.toFixed(2));
                    $('#amount_without_vat').val(afterDisAmt.toFixed(2));
                    $('#amount_without_vat').prop("readonly", true);
                    $('#invoiceLink').html('');
                }
            });  
        });

        // get service list by credit invoice ID
        $('#invoice_id').on('change', function(e){
            e.preventDefault();
            
            var id = $(this).val();
            var submit_url = _baseURL+"account/accounts/getCreditInvoiceById/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    var options = new Option('', '', true, true);
                    $('#package_id').append(options).trigger('change');
                    $('#doctor_id').val(res.doctor_id).trigger('change');
                    $('#doctor_id').prop("readonly", true);;
                    var list ='';
                    var subTotal = 0;
                    var totalVat = 0;
                    var totalPrice = 0;
                    var totalDiscount = 0;

                    $.each(res.details, function (key, value) {
                        var dis = parseFloat(value.doctor_discount) + parseFloat(value.offer_discount) + parseFloat(value.over_limit_discount);
                        var disPrice = (value.qty*parseFloat(value.price)) - parseFloat(dis);
                        var vatCal = parseFloat(value.vat);
                        var netTotal = parseFloat(value.amount);
                        subTotal += parseFloat(value.creditAmt);
                        totalVat += parseFloat(vatCal);
                        totalPrice += value.qty*parseFloat(value.price);
                        totalDiscount += parseFloat(dis);
                        list += '<tr>'+
                                   '<td><input type="hidden" name="service_id[]" value="'+value.app_service_id+'">'+value.code_no+'</td>'+
                                   '<td>'+value.nameE+'</td>'+
                                   '<td><input type="text" name="qty[]" class="form-control form-control-small onlyNumber qty-calc text-center" value="'+value.qty+'" readonly></td>'+
                                   '<td><input type="text" name="price[]" class="form-control form-control-small text-right" value="'+value.price+'" readonly></td>'+
                                   '<td><input type="text" name="discount[]" class="form-control form-control-small text-right" value="'+dis.toFixed(2)+'" readonly></td>'+
                                   '<td><input type="text" name="after_dis[]" class="form-control form-control-small text-right" value="'+disPrice.toFixed(2)+'" readonly></td>'+
                                   '<td><input type="text" name="vat[]" class="form-control form-control-small vat text-right" value="'+vatCal+'" readonly></td>'+
                                   '<td><input type="hidden" class="amount" value="'+netTotal.toFixed(2)+'"><input type="text" name="subtotal[]" class="form-control form-control-small onlyNumber subtotal text-right" value="'+netTotal.toFixed(2)+'" readonly></td>'+
                               '</tr>';
                    });
                    var afterDis = totalPrice - totalDiscount;
                    $('#service_div').html(list);
                    $('#total_price').val(totalPrice.toFixed(2));
                    $('#sub_vat').val(totalVat.toFixed(2));
                    $('#total_discount').val(totalDiscount.toFixed(2));
                    $('#payable').val(subTotal.toFixed(2));
                    $('#due_total').val(0.00);
                    $('#pay_amount').val(subTotal.toFixed(2));
                    $('#amount_without_vat').val(afterDis.toFixed(2));
                    $('#amount_without_vat').prop("readonly", true);
                    $('#invoiceLink').html('<button type="button" class="btn btn-primary viewDetails" data-id="SINV-'+res.id+'"><i class="fa fa-eye"></i><?php echo get_phrases(['view', 'invoice', 'details']);?></button>');
                }
            });  
        });

        // packages list by patient ID
        function getPatientPackages(pid){
            $.ajax({
                type: 'POST',
                url: _baseURL+'account/accounts/getPackagesByPid',
                data: {'csrf_stream_name':csrf_val, patient_id:pid},
                dataType: 'JSON',
                success: function(data) {
                    $("#package_id").empty()
                    $("#package_id").select2({
                        placeholder: '<?php echo get_phrases(['select', 'package', 'name']);?>',
                        data: data
                    });
                    var option = new Option('', '', true, true);
                    $("#package_id").append(option).trigger('change');
                }
            });  
        }

        // get patient info by Id
        $('#patient_id').on('change', function(e){
            e.preventDefault();
            $('#invoiceLink').html('');
            var id = $(this).val();
            
            var submit_url = _baseURL+"account/accounts/pntInfoById/"+id; 
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
                    // var nid = res.nid_no;
                    // var firstDigi = nid.substring(0,1);
                    // if(res.nationality=='Saudi' && firstDigi=="1"){
                    //     $('#sub_vat').attr('readonly', true);
                    // }else{
                    //     $('#sub_vat').attr('readonly', false);
                    // }

                    $('#BL').html(bl);
                    $('#patient_id').val(res.patient_id);
                    $('#nameE').val(res.nameE);
                    $('#nameA').val(res.nameA);
                    $('#file_no').val(res.file_no);
                    $('#nid_no').val(res.nid_no);
                    $('#acc_head').val(res.acc_head);
                    $('#acc_balance').val(res.balance);
                    $('#nationality').val(res.nationality);
                    getPatientPackages(id);
                }
            });  
        });

        function findWord(word, str) {
          return str.split(' ').some(function(w){return w === word})
        }

        //qty total summation
        $(document).on('keyup', '#amount_without_vat', function() {
            var amt = parseFloat($(this).val());
            
            var checkTotal = isNaN(amt)?0:amt.toFixed(2);
            var vat = parseFloat($('#default_vat').val());
            var patient_id = $('#patient_id').val();
            var nid = $('#nid_no').val();
            var nationality = $('#nationality').val();
            var firstDigi = nid.substring(0,1);
            
            if(patient_id !=''){
                if(checkTotal > 0){
                    if(nationality.indexOf("Saudi") > -1 && firstDigi=="1"){
                        $('#vat_percent').val(0);
                        $('#sub_vat').val(0);
                        $('#total_price').val(checkTotal);
                        $('#pay_amount').val(checkTotal);
                        $('#payable').val(checkTotal);
                    }else{
                        $('#vat_percent').val(vat);
                        var vatCal = (checkTotal*vat)/100;
                        var total = amt + parseFloat(vatCal);
                        $('#sub_vat').val(vatCal.toFixed(2));
                        $('#total_price').val(checkTotal);
                        $('#pay_amount').val(total.toFixed(2));
                        $('#payable').val(total.toFixed(2));
                    }
                }else{
                    $('#total_price').val(0);
                    $('#pay_amount').val(0);
                    $('#payable').val(0);
                }
            }else{
                toastr.warning('<?php echo get_phrases(['please', 'select', 'patient', 'name']);?>!');
                $('#patient_id').focus();
            }
            $('#total_discount').val(0);
            $('#due_total').val(0);
        });

        // vat calculation
        // $(document).on('keyup', '#sub_vat', function() {
        //     var vat = parseFloat($(this).val());
        //     var price = parseFloat($('#total_price').val());
        //     if(price > 0 && vat > 0){
        //         var total = vat + price;
        //         var checkTotal = isNaN(total)?0:total.toFixed(2);
        //         $('#pay_amount').val(checkTotal);
        //         $('#payable').val(checkTotal);
        //     }else{
        //         alert('Please add payable amount!');
        //         $('#payable').focus();
        //     }
        // });

        //due calculation
        $(document).on('keyup', '.pay_amount', function() {
            var packId = $('#package_id').val();
            var pay_amount = 0;
            if(packId){
                var payable = parseFloat($('#payable').val());
                //total   
                $('.pay_amount').each(function(){ 
                    pay_amount  += parseFloat($(this).val());
                    if(pay_amount > payable){
                        $(this).val(0);
                        toastr.warning('<?php echo get_notify('payment_amount_is_not_equal_to_payable_amount');?>');
                    }else{
                        var net = payable - pay_amount;
                        $('#due_total').val(isNaN(net)?0.00:net.toFixed(2));
                    }
                }); 
            }else{
                //total   
                $('.pay_amount').each(function(){ 
                    pay_amount  += parseFloat($(this).val());
                }); 
                var checkTotal = isNaN(pay_amount)?0.00:pay_amount.toFixed(2);
                var vat = parseFloat($('#default_vat').val());
                var nid = $('#nid_no').val();
                var nationality = $('#nationality').val();
                var firstDigi = nid.substring(0,1);
                if(nationality.indexOf("Saudi") > -1 && firstDigi=="1"){
                    $('#sub_vat').val(0);
                    $('#total_price').val(checkTotal);
                    $('#payable').val(checkTotal);
                    $('#amount_without_vat').val(checkTotal.toFixed(2));
                }else{
                    var vatStand = parseFloat(vat+100);
                    var vatCal = (checkTotal*vat)/vatStand;
                    var total = checkTotal - parseFloat(vatCal);
                    $('#sub_vat').val(vatCal.toFixed(2));
                    $('#total_price').val(total.toFixed(2));
                    $('#payable').val(checkTotal);
                    $('#amount_without_vat').val(total.toFixed(2));
                }
                
                $('#total_discount').val(0);
                $('#due_total').val(0);
            }
        });
        var defaultPay = $('#payable').val();
        var paymentMHTML = '<tr>'+
                       '<td><select name="pm_name[]" id="pm_name" class="custom-select form-control-small pm_name"></select><div class="others"></div></td>'+
                       '<td><input type="text" name="pay_amount[]" id="pay_amount" class="form-control form-control-small text-right pay_amount onlyNumber" value="'+defaultPay+'" autocomplete="off"></td>'+
                       '<td><a href="javascript:void(0);" class="text-danger addPayBtn text-center"><i class="fa fa-minus fs-22"></i></a></td>'+
                   '</tr>';
        $('#payMethodTr').append(paymentMHTML);
        var countPayId =1;
        $('body').on('click', '.addPayM', function() {
            var restDue = $('#due_total').val();
            $("#payMethodTr").append('<tr>'+
                       '<td><select name="pm_name[]" id="pm_name_'+countPayId+'" class="custom-select form-control-small pm_name"></select><div class="others"></div></td>'+
                       '<td><input type="text" name="pay_amount[]" id="pay_amount_'+countPayId+'" class="form-control form-control-small text-right pay_amount onlyNumber" value="'+restDue+'" autocomplete="off"></td>'+
                       '<td><a href="javascript:void(0);" class="text-danger addPayBtn text-center"><i class="fa fa-minus fs-22"></i></a></td>'+
                   '</tr>'); 
            // payment method list
            $.ajax({
                type:'GET',
                url: _baseURL+'auth/select2List/list_payment_method/127-129-130-150-368',
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
            if(id=='120'){
                $(this).closest('td').find('.others').html('<input type="hidden" name="ac_no[]"><input type="hidden" name="edate[]">');
            }else if(id=='122'){
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


        $('body').on('click', '.addPayBtn', function() {
            var rowCount = $('#paymentTbl >tbody >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
                var payable = parseFloat($('#payable').val());
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

        // view details invoice info
        $(document).on('click', '.viewDetails', function(e){
            e.preventDefault();
            $('#viewDModal').modal('show');
            var typeId = $(this).attr('data-id');
            if(typeId){
                var submit_url = _baseURL+"reports/management/getVoucherDetails"; 
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, voucherId:typeId},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#viewDetails').html('');
                        $('#viewDetails').html(res.data);
                    }
                });  
            }else{
                alert('Wrong Invoice!')
            }
        });

    });
</script>