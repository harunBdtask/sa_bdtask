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
                        <?php if($permission->method('service_invoices', 'read')->access()){ ?>
                        <a href="<?php echo base_url('account/services/invoices');?>" class="btn btn-info btn-sm mr-1"><i class="fas fa-list mr-1"></i><?php echo get_phrases(['invoice', 'list']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="order" id="order" value="<?php echo !empty($order)?$order:'';?>">
            <input type="hidden" id="pack_appoint_id">
            <div class="card-body">
                <?php if($permission->method('service_invoices', 'create')->access()){ ?>
                    <div class="row">
                        <div class="col-md-4 col-sm-12"></div>
                        <div class="col-md-4 col-sm-12"><div id="bl"></div></div>
                        <div class="col-md-4 col-sm-12"><div id="totalPoint"></div></div>
                    </div>
                    <?php echo form_open_multipart('account/services/createInvoice', 'id="invoiceForm"');?>
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <input type="hidden" name="action" id="action" value="add">
                            <input type="hidden" name="isCredit" id="isCredit">
                            <div class="form-group">
                                <?php echo form_dropdown('patient_id','','','class="custom-select" id="patient_id" required="required"');?>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
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
                                                <label for="nameA" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['invoice', 'no']) ?></label>
                                                <input type="text" name="invoice_no" id="invoice_no" class="form-control form-control-small" placeholder="<?php echo get_phrases(['invoice', 'no']);?>" value="<?php echo getMAXID('service_invoices', 'id');?>" readonly>
                                            </td>
                                            <td>
                                                <label for="invoice_date" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['invoice', 'date']) ?></label>
                                                <input type="text" name="invoice_date" id="invoice_date" class="form-control form-control-small" value="<?php echo date('d/m/Y');?>" readonly>
                                            </td>
                                           
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" name="acc_head" id="acc_head">
                        <input type="hidden" name="acc_balance" id="acc_balance" value="0.00">
                        <input type="hidden" name="nationality" id="nationality">
                        <input type="hidden" name="total_redeem" id="total_redeem">
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <?php echo form_dropdown('package_id','','','class="custom-select" id="package_id"');?>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                           <div class="form-group">
                                <?php echo form_dropdown('order_id','','','class="custom-select" id="order_id"');?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <input type="hidden" name="doctor_id" id="doctor_id">
                                <input type="text" name="doctor_name" id="doctor_name" class="form-control" placeholder="<?php echo get_phrases(['doctor', 'name']);?>" readonly="">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <input type="text" name="branch_name" class="form-control" value="<?php echo !empty($branchInfo)?$branchInfo->nameE:'';?>" readonly="">
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
                                        <tr class="bg-success text-white">
                                            <th class="text-center" width="8%"><?php echo get_phrases(['code', 'no']);?><i class="text-danger">*</i></th>
                                            <th class="text-center" width="20%"><?php echo get_phrases(['description']);?></th>
                                            <th class="text-center" width="7%"><?php echo get_phrases(['remain','qty']);?></th>
                                            <th class="text-center" width="5%"><?php echo get_phrases(['qty']);?></th>
                                            <th class="text-right" width="8%"><?php echo get_phrases(['price']);?></th>
                                            <th class="text-right" width="10%"><?php echo get_phrases(['gross', 'total']);?></th>
                                            <th class="text-right" width="7%"><?php echo get_phrases(['redeem','points']);?></th>
                                            <th class="text-right" width="8%"><?php echo get_phrases(['discount']);?></th>
                                            <th class="text-right" width="10%"><?php echo get_phrases(['total', 'net']);?></th>
                                            <th class="text-right" width="7%"><?php echo get_phrases(['vat']);?></th>
                                            <th class="text-right" width="10%"><?php echo get_phrases(['total']);?></th>
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
                                    <tr class="bg-primary text-white">
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
                                            <button type="button" class="btn btn-sm btn-info addPayM"><i class="fa fa-plus"></i> <?php echo get_phrases(['add', 'more']);?></button>
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
                                            <tr class="bg-info text-white">
                                                <th class="text-right"><?php  echo get_phrases(['total', 'price']);?></th>
                                                <th class="text-right"><?php  echo get_phrases(['discount']);?></th>
                                                <th class="text-right"><?php  echo get_phrases(['total', 'vat']);?></th>
                                                <th class="text-right"><?php  echo get_phrases(['advance']);?></th>
                                                 <th class="text-right"><?php  echo get_phrases(['grand', 'total']);?></th>
                                                <th class="text-right"><?php  echo get_phrases(['total', 'due']);?></th>
                                                <th class="text-right"><?php  echo get_phrases(['payable']);?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="netAmount" id="netAmount" value="0.00">
                                                    <input type="text" name="total_price" id="total_price" class="form-control form-control-small text-right" placeholder="<?php echo get_phrases(['total', 'price']);?>" value="0" readonly></td>
                                                <td><input type="text" name="total_discount" id="total_discount" class="form-control form-control-small text-right" placeholder="<?php echo get_phrases(['discount']);?>" value="0" readonly></td>
                                                <td><input type="text" name="sub_vat" id="sub_vat" class="form-control form-control-small text-right" placeholder="<?php echo get_phrases(['total', 'vat']);?>" value="0" readonly></td>
                                                <td><input type="text" name="total_advance" id="total_advance" class="form-control form-control-small text-right" placeholder="<?php echo get_phrases(['total', 'advance']);?>" value="0" readonly></td>
                                                <td><input type="text" name="grand_total" id="grand_total" class="form-control form-control-small text-right" placeholder="<?php echo get_phrases(['grand', 'total']);?>" value="0" readonly></td>
                                                <td><input type="text" name="due_total" id="due_total" class="form-control form-control-small text-right" placeholder="<?php echo get_phrases(['due']);?>" value="0" readonly></td>
                                                <td><input type="text" name="payable" id="payable" class="form-control form-control-small text-right" placeholder="<?php echo get_phrases(['payable', 'amount']);?>" value="0" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary text-right hidden" id="viewStatement"><?php echo get_phrases(['view', 'patient', 'details']);?></button>
                                </div>
                                <div class="col-md-6">
                                    <input type="file" name="attach_file" class="form-control" id="attach_file" accept=".png, .jpg, .jpeg, .pdf">
                                </div>
                            </div>

                        </div>
                    </div> 
                    <?php echo form_close();?>
                    <div class="row">
                        <div class="col-md-6">
                            
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-success" id="createInvoice" disabled><?php echo get_phrases(['create', 'invoice']);?></button>
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

<!-- Search modal --> 
<div class="modal" id="spaModal" tabindex="-1" aria-labelledby="spaModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="spaModalLabel"><?php echo get_phrases(['patient', 'information']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="viewPatientStatement">
                        
                    </div>
                </div>
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
    $(window).on("load", function () {
        $('#sidebarCollapse').trigger('click');
    });
    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');

        //$('.sidebar, .navbar').toggleClass('active');

        $("#package_id").select2({
            placeholder: '<?php echo get_phrases(['select', 'package', 'name']);?>',
        });

         $("#order_id").select2({
            placeholder: '<?php echo get_phrases(['select', 'unpaid', 'order']);?>',
        });

        // payment method list
        $.ajax({
            type:'GET',
            url: _baseURL+'auth/select2List/list_payment_method/129-368',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#pm_name").select2({
                placeholder: '<?php echo get_phrases(['select', 'payment', 'method']);?>',
                data: data
            });
        });

        // invoice submit by click
        $("#createInvoice").one('click', function (event) {  
           event.preventDefault();
           $(this).prop('disabled', true);
           $('#invoiceForm').submit();
        });

        // doctor list 
        // $.ajax({
        //     type:'GET',
        //     url: _baseURL+'auth/doctorList',
        //     dataType: 'json',
        //     data:{'csrf_stream_name':csrf_val},
        // }).done(function(data) {
        //     $("#doctor_id").select2({
        //         placeholder: '<?php //echo get_phrases(['select', 'doctor', 'name']);?>',
        //         data: data
        //     });
        // });

        // search patient
        $('#patient_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'patient', 'by', 'file', 'no', 'or', 'name']);?>',
            minimumInputLength: 3,
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

        // packages list by patient ID
        function getPatientPackages(pid){
            $.ajax({
                type:'GET',
                url: _baseURL+'account/services/getPacksByPId/'+pid,
                dataType: 'json',
            }).done(function(data) {
                $("#package_id").empty()
                $("#package_id").select2({
                    placeholder: '<?php echo get_phrases(['select', 'package', 'name']);?>',
                    data: data
                });
                var option = new Option('', '', true, true);
                $("#package_id").append(option).trigger('change');
            });
        }

        // get patient info by ID
        $('#patient_id').on('change', function(e){
            e.preventDefault();

            var id = $(this).val();
            getPatientPackages(id);
            var submit_url = _baseURL+"account/services/serviceByPId/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    $("#order_id").empty();
                    $("#order_id").select2({
                        placeholder: '<?php echo get_phrases(['select', 'unpaid', 'order']);?>',
                        data: res.orders
                    });
                    var option = new Option('', '', true, true);
                    $("#order_id").append(option).trigger('change');

                    if(res.balance !=null){
                        var blc = res.balance;
                    }else{
                        var blc = 0;
                    }
                    var bl;
                    if(res.balance >=0){
                        $('#total_advance').val(res.balance);
                        bl = '<b class="fs-18"><?php echo get_phrases(['current', 'balance']);?></b> : <b class="text-success">'+parseFloat(blc).toFixed(2)+'</b>';
                    }else{
                        $('#total_advance').val(0);
                        bl = '<b class="fs-18"><?php echo get_phrases(['current', 'balance']);?></b> : <b class="text-danger">'+parseFloat(blc).toFixed(2)+'</b>';
                    }
                    $('#bl').html(bl);
        
                    var nid = (res.nid_no==null)?'':res.nid_no;
                    $('#patient_id').val(res.patient_id);
                    $('#nameE').val(res.nameE);
                    $('#nameA').val(res.nameA);
                    $('#file_no').val(res.file_no);
                    $('#nid_no').val(nid);
                    $('#acc_head').val(res.acc_head);
                    $('#acc_balance').val(res.balance);
                    $('#nationality').val(res.nationality);
                    
                    $('#total_price').val(0);
                    $('#sub_vat').val(0);
                    $('#total_discount').val(0);
                    $('#grand_total').val(0);
                    $('#payable').val(0);
                    $('#due_total').val(0);
                    $('#pay_amount').val(0);
                    $('#viewStatement').removeClass('hidden').attr('data-id', id);
                    get_total_point(id);
                }
            });  
        });

        function get_total_point(patient_id){
            var date = '<?php echo date('Y-m-d');?>';
            var addDate = moment(date).subtract(29, 'days').format('YYYY-MM-DD');
         
            var submit_url = _baseURL+"account/services/patientAvailablePoints"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, date:addDate, patient_id:patient_id},
                dataType: 'JSON',
                success: function(res) {
                    var points = 0;
                    if(res){
                        if(res.gain_points) {
                            var points = res.gain_points;
                        }
                    }
                    $('#totalPoint').html('<b class="fs-18"><?php echo get_phrases(['current', 'points']);?></b> : <b class="text-success">'+parseFloat(points).toFixed(2)+'</b><input type="hidden" id="tPoints" value="'+points+'">');
                }
            });
        }

        // get services by package id advance
        $('#package_id').on('change', function(e){
            e.preventDefault();
            $('#netAmount').val(0);
            $('#grand_total').val(0);
            $('#payable').val(0);
            $('.pay_amount').val(0);
            $('#total_price').val(0);
            $('#sub_vat').val(0);
            $('#total_discount').val(0);

            var id = $(this).val();
            var ids = id.split('_');
            var patient_id = $('#patient_id').val();

            var submit_url = _baseURL+"account/services/servicesByPackId/"+ids[1]; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, patient_id:patient_id, appoint_id:ids[0]},
                dataType: 'JSON',
                async: false,
                success: function(response) {
                    var option = new Option('', '', true, true);
                    $("#order_id").append(option).trigger('change');
                    var nid = $('#nid_no').val();
                    $('#doctor_id').val(response.doctor_id);
                    $('#doctor_name').val(response.doctor_name);
                    var nationality = $('#nationality').val();
                    // if(response.advance==null){
                    //     $('#total_advance').val(0.00);
                    // }else{
                    //     $('#total_advance').val(response.advance.remaining_balance);
                    // }
                    var list ='';
                    var subTotal = 0;
                    var afterDis = 0;
                    var totalVat = 0;
                    var totalDiscount = 0;
                    var firstDigi = nid.substring(0,1);
                    if(nationality.indexOf("Saudi") > -1 && firstDigi=="1"){
                        var vat = 0.00;
                        $('#vat_percent').val(vat);
                    }else{
                        var vat = parseFloat($('#default_vat').val());
                        $('#vat_percent').val(vat);
                    }
                    var Count = 1;
                    $.each(response.services, function (key, value) {
                        var gross = (value.remain_qty*parseFloat(value.price));
                        var overdis= value.remain_qty*((value.price*value.over_limit_discount)/100);
                        var Docdis = value.remain_qty*((value.price*value.doctor_discount)/100);
                        var Offdis = value.remain_qty*((value.price*value.packDis)/100);
                        var dis = parseFloat(Docdis) + parseFloat(Offdis) + parseFloat(overdis);
                        var disPrice = parseFloat(gross) - parseFloat(dis);
                        var vatCal = (parseFloat(disPrice)*vat)/100;
                        var netTotal = parseFloat(disPrice) + parseFloat(vatCal);
                        var dis_info = Offdis+'_'+Docdis+'_'+overdis;
                        
                        var NoCommAmt = 0.00;
                        if(value.remain_qty >1){
                            var remain = '';
                        }else{
                            var remain = 'readonly';
                        }
                        
                        subTotal += netTotal;
                        totalVat += parseFloat(vatCal);
                        totalDiscount += parseFloat(dis);
                        list += '<tr class="clickable-row">'+
                                   '<td class="text-success selectInvServ b"><input type="checkbox" name="ID['+Count+']" id="RowId-'+value.id+'" class="RowId" value="'+value.id+'"> <input type="hidden" name="service_id['+Count+']" value="'+value.service_id+'"><input type="hidden" name="offer_discount['+Count+']" class="offer_discount" value="'+Offdis+'"><input type="hidden" name="doctor_discount['+Count+']" class="doctor_discount" value="'+Docdis+'"><input type="hidden" name="over_discount['+Count+']" class="over_discount" value="'+overdis+'"><input type="hidden" name="no_comm_amount['+Count+']" value="'+NoCommAmt+'"><input type="hidden" name="isCommission['+Count+']" value="1">'+value.code_no+'</td>'+
                                   '<td class="text-success b"><input type="hidden" name="isOffer['+Count+']" value="1">'+value.nameE+'</td>'+
                                   '<td><input type="text" name="remain_qty['+Count+']" class="form-control form-control-small onlyNumber text-center remain_qty" value="'+value.remain_qty+'" readonly></td>'+
                                   '<td><input type="text" name="qty['+Count+']" class="form-control form-control-small onlyNumber qty-calc text-center" value="'+value.remain_qty+'" '+remain+'></td>'+
                                   '<td><input type="text" name="price['+Count+']" class="form-control form-control-small text-right price" value="'+value.price+'" readonly></td>'+
                                   '<td><input type="text" name="gross['+Count+']" class="form-control form-control-small text-right gross" value="'+gross.toFixed(2)+'" readonly></td>'+
                                   '<td><input type="text" name="points['+Count+']" class="form-control onlyNumber form-control-small text-right points" value="0" readonly><input type="hidden" name="point_info['+Count+']" class="point_info" value="0_00__0_0_0"></td>'+
                                   '<td><input type="hidden" name="dis_info['+Count+']" class="dis_info" value="'+dis_info+'"><input type="hidden" name="pre_discount['+Count+']" class="pre_discount" value="'+dis.toFixed(2)+'"><input type="text" name="discount['+Count+']" class="form-control form-control-small text-right discountT" value="'+dis.toFixed(2)+'" readonly></td>'+
                                   '<td><input type="hidden" name="redeem_discount['+Count+']" class="redeem_discount" value="0"><input type="text" name="after_dis['+Count+']" class="form-control form-control-small text-right" value="'+disPrice.toFixed(2)+'" readonly></td>'+
                                   '<td><input type="text" name="vat['+Count+']" class="form-control form-control-small vatT text-right" value="'+vatCal.toFixed(2)+'" readonly></td>'+
                                   '<td><input type="hidden" class="amount" value="'+netTotal.toFixed(2)+'"><input type="text" name="subtotal['+Count+']" class="form-control form-control-small onlyNumber subtotal text-right" value="'+netTotal.toFixed(2)+'" readonly></td>'+
                               '</tr>';
                               Count++;
                    });
                    $('#service_div').html('');
                    $('#service_div').html(list);
                }
            });  
        });

        // get services by doctor id
        $('#order_id').on('change', function(e){
            e.preventDefault();

            var order_id = $(this).val();
            var submit_url = _baseURL+"account/services/serviceByAppId/"+order_id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                async: false,
                success: function(response) {
                    // console.log(response);
                    $('#netAmount').val(0);
                    $('#grand_total').val(0);
                    $('#payable').val(0);
                    $('.pay_amount').val(0);
                    $('#total_price').val(0);
                    $('#sub_vat').val(0);
                    $('#total_discount').val(0);

                    $(".pm_name").val(response.payment_by).trigger('change');
                    var option = new Option('', '', true, true);
                    $("#package_id").append(option).trigger('change');
                    var nid = $('#nid_no').val();
                    var nationality = $('#nationality').val();
                    $('#doctor_id').val(response.created_by);
                    $('#doctor_name').val(response.doctor_name);
                    //$('#total_advance').val(0.00);
                    var list ='';
                    var subTotal = 0;
                    var afterDis = 0;
                    var totalVat = 0;
                    var totalDiscount = 0;
                    var firstDigi = nid.substring(0,1);

                    if(nationality.indexOf("Saudi") > -1 && firstDigi=="1"){
                        var vat = 0.00;
                        $('#vat_percent').val(vat);
                    }else{
                        var vat = parseFloat($('#default_vat').val());
                        $('#vat_percent').val(vat);
                    }

                    var Count = 1;
                    $.each(response.services, function (key, value) {
                        var gross = (value.qty*parseFloat(value.price));
                        var overdis= value.qty*((value.price*value.over_limit_discount)/100);
                        var Docdis = value.qty*((value.price*value.doctor_discount)/100);
                        var Offdis = value.qty*((value.price*value.discount)/100);
                        var dis = parseFloat(Docdis) + parseFloat(Offdis) + parseFloat(overdis);
                        var disPrice = parseFloat(gross) - parseFloat(dis);
                        var vatCal = (parseFloat(disPrice)*vat)/100;
                        var netTotal = parseFloat(disPrice) + parseFloat(vatCal);
                        var dis_info = Offdis+'_'+Docdis+'_'+overdis;
                        if(value.doctor_commission==0 && value.isOffer==1){
                            var NoCommAmt = (value.qty*parseFloat(value.price)) - parseFloat(Offdis);
                        }else{
                            var NoCommAmt = 0.00;
                        }

                        if(value.isPoint==1 && value.isOffer==0){
                            var pointAttr = '';
                        }else{
                            var pointAttr = 'readonly';
                        }

                        if(value.remain_qty >1){
                            var remain = '';
                        }else{
                            var remain = 'readonly';
                        }

                        subTotal += netTotal;
                        totalVat += parseFloat(vatCal);
                        totalDiscount += parseFloat(dis);
                        list += '<tr class="clickable-row">'+
                                   '<td class="selectInvServ"><input type="checkbox" name="ID['+Count+']" id="RowId-'+value.id+'" class="RowId" value="'+value.id+'"> <input type="hidden" name="service_id['+Count+']" value="'+value.service_id+'"><input type="hidden" name="offer_discount['+Count+']" class="offer_discount" value="'+Offdis+'"><input type="hidden" name="doctor_discount['+Count+']" class="doctor_discount" value="'+Docdis+'"><input type="hidden" name="over_discount['+Count+']" class="over_discount" value="'+overdis+'"><input type="hidden" name="no_comm_amount['+Count+']" value="'+NoCommAmt+'"><input type="hidden" name="isCommission['+Count+']" value="'+value.doctor_commission+'">'+value.code_no+'</td>'+
                                   '<td><input type="hidden" name="isOffer['+Count+']" value="'+value.isOffer+'">'+value.nameE+'</td>'+
                                   '<td><input type="text" name="remain_qty['+Count+']" class="form-control form-control-small onlyNumber text-center remain_qty" value="'+value.remain_qty+'" readonly></td>'+
                                   '<td><input type="text" name="qty['+Count+']" class="form-control form-control-small onlyNumber qty-calc text-center" value="'+value.remain_qty+'" '+remain+'></td>'+
                                   '<td><input type="text" name="price['+Count+']" class="form-control form-control-small text-right price" value="'+value.price+'" readonly></td>'+
                                   '<td><input type="text" name="gross['+Count+']" class="form-control form-control-small text-right gross" value="'+gross.toFixed(2)+'" readonly></td>'+
                                   '<td><input type="text" name="points['+Count+']" class="form-control onlyNumber form-control-small text-right points" value="0" '+pointAttr+'><input type="hidden" name="point_info['+Count+']" class="point_info" value="'+value.point_info+'"><input type="hidden" name="dis_info['+Count+']" class="dis_info" value="'+dis_info+'"></td>'+
                                   '<td><input type="hidden" name="redeem_discount['+Count+']" class="redeem_discount" value="0"><input type="hidden" name="pre_discount['+Count+']" class="pre_discount" value="'+dis+'"><input type="text" name="discount['+Count+']" class="form-control form-control-small text-right discountT" value="'+dis.toFixed(2)+'" readonly></td>'+
                                   '<td><input type="text" name="after_dis['+Count+']" class="form-control form-control-small text-right after_dis" value="'+disPrice.toFixed(2)+'" readonly></td>'+
                                   '<td><input type="text" name="vat['+Count+']" class="form-control form-control-small vatT text-right" value="'+vatCal.toFixed(2)+'" readonly></td>'+
                                   '<td><input type="hidden" class="amount" value="'+netTotal.toFixed(2)+'"><input type="text" name="subtotal['+Count+']" class="form-control form-control-small onlyNumber subtotal text-right" value="'+netTotal.toFixed(2)+'" readonly></td>'+
                               '</tr>';
                               Count++;
                    });
                    $('#service_div').html('');
                    $('#service_div').html(list);
                }
            }); 
        });

        // Get service assign id
        setTimeout(function(){
            var id = $('#order').val();
            if(id !=''){
                var submit_url = _baseURL+"account/services/getInvoiceInfoByOrder/"+id; 
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        var pnt = new Option(res.patient_name, res.patient_id, true, true);
                        $('#patient_id').append(pnt).trigger('change');
                        setTimeout(function(){
                            if(res.type==1){
                                $('#order_id').val(res.order_id).trigger('change');
                            }else{
                                $('#pack_appoint_id').val(res.appoint_id);
                                $('#package_id').val(res.package_id).trigger('change');
                            }
                        }, 2000);
                    }
                });
            }  
        }, 300);

        // get patient info by ID
        $(document).on('click', '#viewStatement', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
        
            var submit_url = _baseURL+"patient/viewServicesDetails/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    $('#spaModal').modal('show');
                    $('#viewPatientStatement').html('');
                    $('#viewPatientStatement').html(res.data);
                }
            });  
        });
        
        // checked unchecked services
        $(document).on('click', '#service_div .selectInvServ', function(event) {
            if (event.target.type !== 'checkbox') {
                $(':checkbox', this).trigger('click');
            }
        });

        //selected service calculation
        $(document).on('change', '.RowId', function() {
            var total= $('input:checkbox:checked').length;
            get_total_calculation();

            if(total > 0){
                var payable = parseFloat($('#payable').val());
                //total   
                var pay_amount = 0;
                $('.pay_amount').each(function(){ 
                    pay_amount  += parseFloat($(this).val());
                    if(pay_amount == payable){
                       $('#createInvoice').attr('disabled', false);
                    }else{
                       $('#createInvoice').attr('disabled', true);
                    }
                }); 
            }else{
                $('#createInvoice').attr('disabled', true);
            }
        });

        // Total calculate
        function get_total_calculation(){
            var total = 0;
            var vatT = 0;
            var disT = 0;
            var payable = 0;
            $('.RowId').each(function(){ 
                if($(this).prop("checked") == true){
                    var price = $(this).parent().parent().find(".price").val();
                    var qty = $(this).parent().parent().find(".qty-calc").val();
                    var amt = parseFloat($(this).parent().parent().find(".amount").val());
                    var vat = parseFloat($(this).parent().parent().find(".vatT").val());
                    var dis = parseFloat($(this).parent().parent().find(".discountT").val());

                    total += parseFloat(qty)*parseFloat(price);
                    vatT += parseFloat(vat);
                    disT += parseFloat(dis);
                    payable += parseFloat(amt);
                }
            }); 
            $('#total_price').val(total.toFixed(2));
            $('#sub_vat').val(vatT.toFixed(2));
            $('#total_discount').val(disT.toFixed(2));
            $('#pay_amount').val(payable.toFixed(2));
            $('#grand_total').val(payable.toFixed(2));
            $('#payable').val(payable.toFixed(2));
        }

         //points calculation 0=spend amount, 1=per point value, 2=min redeem, 3=max redeem, 4=min gain, 5=max gain
        $(document).on('keyup', '.points', function() {
            var point_value, min_redeem, max_redeem, total_points, assignPoint;
            var amount = $(this).val()==''? 0 : $(this).val();
            var point_info = $(this).parent().parent().find(".point_info").val();
            var info = point_info.split('_');
            
            point_value = parseFloat($.trim(info[1]));
            min_redeem = parseFloat($.trim(info[2]));
            max_redeem = parseFloat($.trim(info[3]));

            if($(this).parent().parent().find('.RowId').prop("checked") == true){
                total_points = $('#tPoints').val();
                 assignPoint = get_current_redeem_points();
                 $('#total_redeem').val(assignPoint);
                if(total_points > 0 && amount > 0){
                    if(assignPoint > total_points){
                        $(this).val('');
                        toastr.error('<?php echo get_notify('can_not_exists_total_points');?>');
                        return false;
                    }
                    if(amount >= min_redeem &&  amount <= max_redeem){
                        add_discount_by_point($(this), amount, point_value, true);
                        get_total_calculation();
                    }else if(amount < min_redeem){
                        add_discount_by_point($(this), amount, point_value, false);
                        get_total_calculation();
                        toastr.warning('Minimum redeem points '+min_redeem+' for this service');
                    }else{
                        add_discount_by_point($(this), amount, point_value, false);
                        get_total_calculation();
                        toastr.warning('Maximum redeem points '+max_redeem+' for this service');
                    }
                }else{
                    $(this).val('');
                }
            }else{
                toastr.warning('<?php echo get_notify('please_check_the_service_name');?> !');
            }

        });

        // Calculate by points
        function add_discount_by_point(e, points, value, flag){
            var vatPer, gross, preDis, disP, amt, sub, dis, vat, pointVal, totalDis, disPrice, vatCal, netTotal, redeem_dis;
            vatPer = parseFloat( $('#vat_percent').val());
            gross = e.parent().parent().find(".gross").val();
            preDis = e.parent().parent().find(".pre_discount").val();
            disP = e.parent().parent().find(".after_dis");
            redeem_dis = e.parent().parent().find(".redeem_discount");
            amt = e.parent().parent().find(".amount");
            sub = e.parent().parent().find(".subtotal");
            dis = e.parent().parent().find(".discountT");
            vat = e.parent().parent().find(".vatT");

            pointVal = value*parseFloat(points);
            totalDis = flag===true?parseFloat(preDis)+pointVal:parseFloat(preDis);
            disPrice = parseFloat(gross) - parseFloat(totalDis);
            vatCal = (parseFloat(disPrice)*vatPer)/100;
            netTotal = parseFloat(disPrice) + parseFloat(vatCal);

            vat.val(vatCal.toFixed(2));
            dis.val(totalDis.toFixed(2));
            redeem_dis.val(pointVal.toFixed(2));
            disP.val(disPrice.toFixed(2));
            amt.val(netTotal.toFixed(2));
            sub.val(netTotal.toFixed(2));
        }

        function get_current_redeem_points(){
            var points = 0;
            $('.points').each(function(){
                points += parseFloat($(this).val());
            });
            return points;
        }

         //qty total summation 
        $(document).on('keyup', '.qty-calc', function() {
            var qty = $(this).val();
            if($(this).parent().parent().find('.RowId').prop("checked") == true){
                if(qty > 0){
                    qtyCalculations($(this));
                    get_total_calculation();
                }
            }else{
                toastr.warning('<?php echo get_notify('please_check_the_service_name');?> !');
            }
        });

        // Calculate by qty
        function qtyCalculations(e){
            var pre_qty, qty, vatPer, price, gross, disP, preDis, amt, sub, dis, vat, totalGross, totalDis, disPrice, vatCal, netTotal, dis_all;
            dis_all = e.parent().parent().find(".dis_info").val();
            pre_qty = parseInt(e.parent().parent().find(".remain_qty").val());
            qty = parseInt(e.val());
            price = parseFloat(e.parent().parent().find(".price").val());
            vatPer = parseFloat( $('#vat_percent').val());
            preDis = e.parent().parent().find(".pre_discount");
            gross = e.parent().parent().find(".gross");
            disP = e.parent().parent().find(".after_dis");
            amt = e.parent().parent().find(".amount");
            sub = e.parent().parent().find(".subtotal");
            dis = e.parent().parent().find(".discountT");
            vat = e.parent().parent().find(".vatT");
            // discount info
            var offerDis = e.parent().parent().find(".offer_discount");
            var doctor_discount = e.parent().parent().find(".doctor_discount");
            var over_discount = e.parent().parent().find(".over_discount");
            var dis_info = dis_all.split('_');

            if(qty >=1 && qty <= pre_qty){
                totalGross = qty*price;
                var off   = disCalculate(parseFloat(dis_info[0]), pre_qty, qty);//discount calculate
                var doc   = disCalculate(parseFloat(dis_info[1]), pre_qty, qty);//discount calculate
                var extra = disCalculate(parseFloat(dis_info[2]), pre_qty, qty);//discount calculate
                totalDis = off+doc+extra;
                disPrice = parseFloat(totalGross) - parseFloat(totalDis);
                vatCal = (parseFloat(disPrice)*vatPer)/100;
                netTotal = parseFloat(disPrice) + parseFloat(vatCal);
                offerDis.val(off.toFixed(2));
                doctor_discount.val(doc.toFixed(2));
                over_discount.val(extra.toFixed(2));
                preDis.val(totalDis.toFixed(2));

                gross.val(totalGross.toFixed(2));
                vat.val(vatCal.toFixed(2));
                dis.val(totalDis.toFixed(2));
                disP.val(disPrice.toFixed(2));
                amt.val(netTotal.toFixed(2));
                sub.val(netTotal.toFixed(2));
            }else{
                e.val(pre_qty).trigger('keyup');
                toastr.warning('Total remaining qty '+pre_qty);
            }
        }

        // calculate discount
        function disCalculate(total, qty, countQty){
            return  parseFloat((total*countQty)/qty);
        }

        //due calculation
        $(document).on('keyup', '.pay_amount', function() {
            var payable = parseFloat($('#payable').val());
            //total   
            var amount = $(this);
            var pay_amount = 0;
            $('.pay_amount').each(function(){ 
                pay_amount  += parseFloat($(this).val());
                var net = payable - pay_amount;
                if(pay_amount > payable){
                    alert('<?php echo get_notify('Can_not_exceed_the_payable_amount');?>');
                    $('#due_total').val(0);
                    amount.val(0);
                }else{
                    $('#due_total').val(isNaN(net)?0.00:net.toFixed(2));
                }
            }); 

            if(pay_amount == payable){
               $('#createInvoice').attr('disabled', false);
            }else{
               $('#createInvoice').attr('disabled', true);
            }

        });
        var defaultPay = $('#payable').val();
        var paymentMHTML = '<tr>'+
                       '<td><select name="pm_name[]" id="pm_name" class="custom-select form-control-small pm_name"></select><div class="others"></div></td>'+
                       '<td><input type="text" name="pay_amount[]" id="pay_amount" class="form-control form-control-small pay_amount text-right" value="'+defaultPay+'"></td>'+
                       '<td><a href="javascript:void(0);" class="text-danger addPayBtn text-center"><i class="fa fa-minus fs-22"></i></a></td>'+
                   '</tr>';
        $('#payMethodTr').append(paymentMHTML);
        var countPayId =1;
        $('body').on('click', '.addPayM', function() {
            var restDue = $('#due_total').val();
            $("#payMethodTr").append('<tr>'+
                       '<td><select name="pm_name[]" id="pm_name_'+countPayId+'" class="custom-select form-control-small pm_name"></select><div class="others"></div></td>'+
                       '<td><input type="text" name="pay_amount[]" id="pay_amount_'+countPayId+'" class="form-control form-control-small pay_amount text-right" value="'+restDue+'"></td>'+
                       '<td><a href="javascript:void(0);" class="text-danger addPayBtn text-center"><i class="fa fa-minus fs-22"></i></a></td>'+
                   '</tr>'); 
            // payment method list
            $.ajax({
                type:'GET',
                url: _baseURL+'auth/select2List/list_payment_method/129-368',
                dataType: 'json',
                data:{'csrf_stream_name':csrf_val},
            }).done(function(data) {
                $(".pm_name").select2({
                    placeholder: '<?php echo get_phrases(['select', 'payment', 'method']);?>',
                    data: data
                });
            });
            var payable = $('#payable').val();
            var pay_amount = 0;
            $('.pay_amount').each(function(){ 
                pay_amount  += parseFloat($(this).val());
            }); 

            if(pay_amount == payable){
               $('#createInvoice').attr('disabled', false);
            }else{
               $('#createInvoice').attr('disabled', true);
            }
            $('#due_total').val(0.00);
            countPayId++;
        });
        var payNoDate = '<div class="row"><div class="col-md-6 mt-1"><input type="text" name="ac_no[]" class="form-control form-control-small onlyNumber" placeholder="<?php echo get_phrases(['card', 'number']);?>"></div><div class="col-md-6 mt-1"><input type="text" name="edate[]" class="form-control form-control-small onlyD"></div></div>';
        var payNoName = '<div class="row"><div class="col-md-6 mt-1"><input type="text" name="ac_no[]" class="form-control form-control-small onlyNumber" placeholder="<?php echo get_phrases(['account', 'no']);?>"></div><div class="col-md-6 mt-1"><input type="text" name="bank_name[]" class="form-control form-control-small" placeholder="<?php echo get_phrases(['bank', 'name']);?>"></div></div>';
        // get service list by appointment Id
        $(document).on('change', '.pm_name', function(e){
            e.preventDefault();
            var id = $(this).val();
            $('#isCredit').val(0);
            // $('.addPayM').attr('disabled', false);
            // $(this).parent().parent().find('td .pay_amount').attr('readonly', false);
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
            }else if(id=='127'){
                var payable = parseFloat($('#payable').val());
                var advance = parseFloat($('#total_advance').val());
                if(advance > 0){
                    if(advance > payable){
                        $(this).parent().parent().find('td .pay_amount').val(payable);
                    }else{
                        $(this).parent().parent().find('td .pay_amount').val(advance);
                    }
                    
                    //total   
                    var pay_amount = 0;
                    $('.pay_amount').each(function(){ 
                        pay_amount  += parseFloat($(this).val());
                        var net = payable - pay_amount;
                        $('#due_total').val(isNaN(net)?0.00:net.toFixed(2));
                    }); 
                }else{
                    alert('Not enough balance for paid advance!');
                    $(this).val('').trigger('change');
                }
                $(this).closest('td').find('.others').html('');
            }else if(id=='130' || id=='150'){
                $('#isCredit').val(1);
                $(this).closest('td').find('.others').html('');
                // $(this).parent().parent().nextAll('tr').remove();
                // $(this).parent().parent().prevAll('tr').remove();
                // $(this).parent().parent().find('td .pay_amount').val($('#payable').val());
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
                var payable = parseFloat($('#payable').val());
                //total   
                var pay_amount = 0;
                $('.pay_amount').each(function(){ 
                    pay_amount  += parseFloat($(this).val());
                    var net = payable - pay_amount;
                    $('#due_total').val(isNaN(net)?0.00:net.toFixed(2));
                }); 
                if(pay_amount == payable){
                   $('#createInvoice').attr('disabled', false);
                }else{
                   $('#createInvoice').attr('disabled', true);
                }
            }else{
                alert("There only one row you can't delete.");
            } 
        });

    });
</script>