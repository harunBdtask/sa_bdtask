<link href="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url()?>/assets/dist/css/lity.css" rel="stylesheet">
<div class="row">
    <div class="col-sm-12"> 
        <?php 
        $hasPrintAccess  = $permission->method('opening_balance', 'print')->access();
        $hasExportAccess = $permission->method('opening_balance', 'export')->access();
        if($permission->module('opening_balance')->access()){ ?>
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
                        <?php if($permission->method('opening_balance', 'create')->access()){ ?>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm mr-1 addJournal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['opening', 'balance']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">  
                    <div class="col-sm-2"></div>
                    <div class="col-sm-2">
                        <input type="text" name="search_date" id="search_date" class="form-control" placeholder="<?php echo get_phrases(['select', 'voucher', 'date']); ?>" autocomplete="off">
                    </div>
                    
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-warning resetBtn text-white"><?php echo get_phrases(['reset']);?></button>
                    </div> 
                </div>
                <table id="openingList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                           <th><?php echo get_phrases(['serial']);?></th>
                            <th><?php echo get_phrases(['Head', 'name']);?></th>
                            <th><?php echo get_phrases(['debit', 'amount']);?></th>
                            <th><?php echo get_phrases(['credit', 'amount']);?></th>
                            <th><?php echo get_phrases(['sub','head']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
                            <th><?php echo get_phrases(['opened', 'by']);?></th>
                            <th><?php echo get_phrases(['fyear']);?></th>
                            <th><?php echo get_phrases(['action']);?></th>

                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
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

<div class="modal fade bd-example-modal-lg" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="addModalLabel"><?php echo get_phrases(['add', 'opening', 'balance']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('account/accounts/saveOpeningBalance', 'class="needs-validation" id="jvForm" novalidate="" data="jvCallBackData"');?>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="action" value="add">
                    
                   <div class="col-md-6 col-sm-12">
                   <label class="font-weight-600 mb-0"><?php echo get_phrases(['select', 'financial','year']);?></label>
                        <?php echo form_dropdown('fiscalyear',$fiscalyears,null,'class="form-control"')?>
                   </div>

                   <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['voucher', 'date']);?></label>
                            <input type="text" name="voucher_date" id="voucher_date" class="form-control" value="<?php echo date('Y-m-d');?>" readonly="">
                        </div>
                    </div>

                   <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['remarks']);?></label>
                        <textarea name="remarks" class="form-control" id="remarks" rows="2"></textarea>
                    </div>
                   
                </div>

                <div class="row mt-2">
                   <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-stripped table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="30%"><?php echo get_phrases(['account','name']);?><i class="text-danger">*</i></th>
                                        <th class="text-center" width="20%"><?php echo get_phrases(['sub', 'code']);?></th>
                                        
                                        <th class="text-right" width="20%"><?php echo get_phrases(['debit', 'amount']);?></th>
                                        <th class="text-right" width="20%"><?php echo get_phrases(['credit', 'amount']);?></th>
                                       
                                        <th class="text-center" width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="service_div">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-right" colspan="2"><?php echo get_phrases(['total']);?></th>
                                        <th><input type="text" name="totalDebit" class="form-control onlyNumber text-right" id="totalDebit" readonly=""></th>
                                        <th><input type="text" name="totalCredit" class="form-control onlyNumber text-right" id="totalCredit" readonly=""></th>
                                       
                                        <th><button type="button" class="btn btn-success btn-sm addMore"><i class="fa fa-plus"></i></button></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                   </div>
                </div>
                
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success"><?php echo get_phrases(['save']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>



<!-- view voucher details -->
<div class="modal fade bd-example-modal-lg" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="detailsModalLabel"><?php echo get_phrases(['view', 'voucher', 'details']);?></h5>
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
                <button type="button" class="btn btn-purple" onclick="printContent('viewDetails')"><i class="fa fa-print"></i> <?php echo get_phrases(['print']);?></button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url()?>/assets/dist/js/lity.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var jvCallBackData = function () { 
        $('#add-modal, #edit-modal').modal('hide');   
        $('#jvForm')[0].reset();       
        $('#jvForm').removeClass('was-validated');    
        $('#openingList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
        "use strict";
        $('.modal').attr("data-backdrop","static");
        $('option:first-child').val('').trigger('change');
        $('#search_date, voucher_date_e').datepicker({dateFormat: 'yy-mm-dd'});

        $('#openingList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [8] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
            <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    className: 'btn-light',
                    title : 'Opening_balance_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                <?php } if($hasExportAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    className: 'btn-light',
                    title : 'Opening_balance_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    className: 'btn-light',
                    title : 'Opening_balance_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    className: 'btn-light',
                    title : 'Opening_balance_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    className: 'btn-light',
                    title : 'Opening_balance_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                    }
                }
            <?php }?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'account/accounts/getOpeningBalanceList',
               'data': function ( d ) {
                    d.csrf_stream_name = csrf_val;
                    d.search_date      = $('#search_date').val();
                    d.branch_id        = $('#branch_id').val();
                }
            },
          'columns': [
             { data: 'id' },
             { data: 'Headname'},
             { data: 'debit' },
             { data: 'credit' },
             { data: 'subhead' },
             { data: 'date' },
             { data: 'created_by'},
             { data: 'fyear'},
             { data: 'action' }
          ],
        });

        // custom search
        $("#search_date").on('change', function(){
            $('#openingList').DataTable().ajax.reload();
        });

     

        // reset button
        $('.resetBtn').on('click', function(e){
            //$('#branch_id').val('').trigger('change');
            $('#search_date').val('');
            $('#openingList').DataTable().ajax.reload();
        });

        // branch list 
        $.ajax({
            type:'GET',
            url: _baseURL+'auth/branchList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#branch_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'branch']);?>',
                data: data
            });
            $("#branch_id").val('<?php echo session('branchId');?>').trigger('change');
        });

        $('.addJournal').on('click', function(){
            $('#jvForm').removeClass('was-validated');
            $('#add-modal').modal('show');
            $('#head_code').val('').trigger('change');
            $('#service_div').html('');
            loadFirstRow(1);
            var submit_url = _baseURL+"auth/getMaxId/journal_vouchers/id"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    var Vid = 'JV-'+res.ID;
                    $('#voucher_no').val(Vid);
                }
            });  
        });

      
        $('#saveBtnRj').on('click', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            var submit_url = _baseURL+"account/vouchers/approvedVoucherById/"+id; 
            if(id != ''){
                if(confirm('<?php echo get_phrases(['are_you_sure', 'reject', 'this', 'voucher']);?>')){
                    $.ajax({
                        type: 'POST',
                        url: submit_url,
                        data: {'csrf_stream_name':csrf_val, status:2, type:'JV'},
                        dataType: 'JSON',
                        success: function(response) {
                            if(response.success==true){
                                $('#edit-modal').modal('hide');
                                toastr.success(response.message, response.title);
                                $('#openingList').DataTable().ajax.reload(null, false);
                            }else{
                                toastr.warning(response.message, response.title);
                            }
                        }
                    });  
                }
            }
            return true;
        });


 
        $('#openingList').on('click', '.viewAction', function(e) {
            e.preventDefault();
            var VNo = $(this).attr('data-id');
            $('#details-modal').modal('show');
            var submit_url = _baseURL + "account/accounts/getOpeningDetails/" + VNo;
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {
                    'csrf_stream_name': csrf_val
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#viewDetails').html('');
                    $('#viewDetails').html(response.data);
                }
            });
        });
        

       

        $('#openingList').on('click', '.deleteAction', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');

            var submit_url = _baseURL + "account/vouchers/deleteVoucher/" + id;
            if (confirm('<?php echo get_phrases(['are_you_sure', 'delete', 'this', 'voucher']); ?>')) {
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {
                        'csrf_stream_name': csrf_val,
                        status: 1
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success(response.message, response.title);
                            $('#openingList').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.warning(response.message, response.title);
                        }
                    }
                });
            }
            return true;
        });

        function loadFirstRow(id){
            var addHTML = '<tr>'+
                       '<td><select name="account_name[]" id="account_name_'+id+'" class="custom-select form-control account_name" required="required"></select><div class="others"><input type="hidden" name="head_code[]" id="headcode_' + id + '" class="form-control head_code" readonly><input type="hidden" name="sub_type[]" id="sub_type_' + id + '" class="form-control sub_type" readonly></div></td>'+
                       '<td><select name="sub_code[]" id="sub_code_' + id + '" class="custom-select form-control sub_code"></select></td>'+
                       '<td><input type="text" name="debit[]" class="form-control debit onlyNumber text-right" value="0" id="journal_debit_'+id+'" autocomplete="off" required></td>'+
                       '<td><input type="text" name="credit[]" class="form-control credit onlyNumber text-right" value="0" id="journal_credit_'+id+'" autocomplete="off" required></td>'+
                       '<td><a href="javascript:void(0);" class="btn btn-danger-soft btn-sm removeBtn text-center"><i class="far fa-trash-alt fs-20"></i></a></td>'+
                   '</tr>';
            $('#service_div').append(addHTML);
            // search account
            $('#account_name_'+id).select2({
                placeholder: '<?php echo get_phrases(['select', 'account', 'name']);?>',
                minimumInputLength: 1,
                    ajax: {
                        url: _baseURL+'account/accounts/getAllOpeningHead',
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
         
        }
        
        var countPayId =2;
        $('body').on('click', '.addMore', function() {
            loadFirstRow(countPayId);
            countPayId++;
        });
        
        $('body').on('click', '.removeBtn', function() {
            var rowCount = $('#service_div >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
                balanceBtn();
               
            }else{
                alert("There only one row you can't delete.");
            } 
        });

        $('body').on('change', '.account_name', function(e) {
            e.preventDefault();
            var id = $(this).val();
            $(this).parent().parent().find(".head_code").val(id);
            var nameid = $(this).attr('id');
            var splitid = nameid.split("_");

            $.ajax({
                type: 'POST',
                url: _baseURL + 'account/vouchers/searchSubcode',
                dataType: 'json',
                data: {
                    'csrf_stream_name': csrf_val,
                    headcode: id
                },
            }).done(function(data) {
                var listdata = JSON.stringify(data.list);
                var sub_type = data.subtype;
                $("#sub_type_" + splitid[2]).val(sub_type);
                if (listdata == '[]') {
                    $("#sub_code_" + splitid[2]).empty();
                } else {
                    $("#sub_code_" + splitid[2]).select2({
                        placeholder: 'Select Subcode',
                        data: data.list
                    });
                    $("#sub_code_" + splitid[2]).val('').trigger('change');
                }

            });
        });



        $(document).on('keyup', '.debit', function(e) {
            e.preventDefault();
            $(this).parent().parent().find('.credit').val(0);
            balanceBtn();
           
        });

        $(document).on('keyup', '.credit', function(e) {
            e.preventDefault();
            $(this).parent().parent().find('.debit').val(0);

            balanceBtn(); 
        });

      


        function balanceBtn(){
            // //total   
            var total = 0;
            $('.credit').each(function(){ 
                total  += parseFloat($(this).val());
            }); 
            $('#totalCredit').val(isNaN(total)?0.00:total.toFixed(2));

            // debit
            var total1 = 0;
            $('.debit').each(function(){ 
                total1  += parseFloat($(this).val());
            }); 
            $('#totalDebit').val(isNaN(total1)?0.00:total1.toFixed(2));
        }

       

 


    });
</script>