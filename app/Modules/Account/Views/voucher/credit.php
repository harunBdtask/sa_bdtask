<link href="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<div class="row">
    <div class="col-sm-12">
        <?php 
        $hasPrintAccess  = $permission->method('credit_voucher', 'print')->access();
        $hasExportAccess = $permission->method('credit_voucher', 'export')->access();
        if($permission->module('credit_voucher')->access()){ ?>
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
                        <?php if($permission->method('credit_voucher', 'create')->access()){ ?>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm mr-1 addCredit"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['credit', 'voucher']);?></a>
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
                <table id="approvalList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                        <th><?php echo get_phrases(['serial']); ?></th>
                                <th><?php echo get_phrases(['debit', 'head']); ?></th>
                                <th><?php echo get_phrases(['voucher', 'no']); ?></th>
                                <th><?php echo get_phrases(['debit', 'amount']); ?></th>
                                <th><?php echo get_phrases(['credit', 'amount']); ?></th>
                                <th><?php echo get_phrases(['remarks']); ?></th>
                                <th><?php echo get_phrases(['voucher', 'date']); ?></th>
                                <th><?php echo get_phrases(['created', 'by']); ?></th>
                                <th><?php echo get_phrases(['status']); ?></th>
                                <th><?php echo get_phrases(['action']); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
        <?php }?>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="addModalLabel"><?php echo get_phrases(['add', 'credit', 'voucher']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('account/vouchers/saveCreditVoucher', 'class="needs-validation" id="creditForm" novalidate="" data="creditCallBackData"');?>
            <div class="modal-body">
                <div class="row">
                   <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['debit', 'account', 'head']);?> <i class="text-danger">*</i></label>
                             <?php echo form_dropdown('credit_head', '', '', 'class="form-control select2" id="credit_head" required');?>
                        </div>
                   </div>
                   <div class="col-md-3 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['voucher', 'date']);?></label>
                            <input type="text" name="voucher_date" id="voucher_date" class="form-control" value="<?php echo date('Y-m-d');?>" readonly="">
                        </div>
                   </div>
                  
                </div>
                <div class="row mb-2">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['remarks']);?></label>
                            <textarea name="remarks" class="form-control" id="remarks" rows="2"></textarea>
                    </div>
                </div>
                <div class="row">
                   <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-stripped table-sm">
                                <thead>
                                    <tr>
                                    <th class="text-center" width="45%"><?php echo get_phrases(['credit', 'head']); ?><i class="text-danger">*</i></th>
                                        <th class="text-center" width="20%"><?php echo get_phrases(['sub', 'code']); ?></th>
                                        <th class="text-center" width="20%"><?php echo get_phrases(['ledger', 'comments']); ?></th>
                                        <th class="text-center" width="25%"><?php echo get_phrases(['amount']); ?></th>
                                        <th class="text-center" width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="service_div">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><button type="button" class="btn btn-success btn-sm addMore"><i class="fa fa-plus"></i></button></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                   </div>
                </div>
                
               
            </div>
            <div class="modal-footer">
            
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success"><?php echo get_phrases(['create', 'voucher']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<!-- view voucher details -->
<div class="modal fade bd-example-modal-lg" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
            <button class="btn btn-warning" onclick="printContent('printArea');">print</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="detailsModalLabel"><?php echo get_phrases(['edit', 'voucher']); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                <?php echo form_open_multipart('account/vouchers/updateCreditVoucher', 'class="needs-validation" id="creditForm" novalidate="" data="creditCallBackData"');?>
                    <div class="col-md-12 col-sm-12" id="editDetails">

                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <div class="modal-footer">
              
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="restore_credithead()"><?php echo get_phrases(['close']); ?></button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var creditCallBackData = function () { 
        $('#add-modal').modal('hide');
        $('#edit-modal').modal('hide'); 
        $('#editDetails').html('');
        $('#creditForm')[0].reset();       
        $('#creditForm').removeClass('was-validated');    
        $('#approvalList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
        "use strict";
        $('.modal').attr("data-backdrop","static");
        $('option:first-child').val('').trigger('change');
        $('#search_date').datepicker({dateFormat: 'yy-mm-dd'});

        $('#approvalList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [9] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    customize: function (doc) {
            $(doc.document.body)                                
            .prepend('<div class="row"><div class="col-md-3"><img style="position:absolute; top:10; left:50;width:50;height:40px;" src='+$logo+'></div>')
            .prepend('<div  class="col-md-6 text-center" style="position:absolute; top:10; left:80px;"><h6>'+$companytitle+'</h6><strong><u class="pt-4">Credit Voucher</u></strong></div>')
            .prepend('<div class="col-md-3"></div></div>');

            $(doc.document.body).find('table')            			
            			.removeClass('dataTable')
                  .css('font-size','12px') 
            			.css('margin-top','65px')
                  .css('margin-bottom','60px')
         		$(doc.document.body).find('th').each(function(index){
                  $(this).css('font-size','18px');
                  $(this).css('color','#000');
                  $(this).css('background-color','#fff');
            });                
        },
                    className: 'btn-light',
                    title : '',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                <?php } if($hasExportAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Credit_voucher_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Credit_voucher_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Credit_voucher_list-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    customize: function ( doc ) {
                    doc.content.splice( 1, 0, {
                        margin: [ 0, 0, 0, 12 ],
                        alignment: 'center',
                        image:  'data:image/png;base64,'+$base64,
                        height:90,
                        width:90
                    } );
                }, 
                    title : 'Credit Voucher',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                }
            <?php }?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'account/vouchers/getVoucherList/CV',
               'data': function ( d ) {
                    d.csrf_stream_name = csrf_val;
                    d.search_date      = $('#search_date').val();
                    d.branch_id        = $('#branch_id').val();
                }
            },
          'columns': [
            {
                    data: 'ID'
                },
                {
                    data: 'branch_name'
                },
                {
                    data: 'VNo'
                },
                {
                    data: 'totaldebit'
                },
                {
                    data: 'totalcredit'
                },
                {
                    data: 'Narration'
                },
                {
                    data: 'VDate'
                },
                {
                    data: 'created_by'
                },
                {
                    data: 'button'
                },
                {
                    data: 'action'
                }
          ],
        });

        // custom search
        $("#search_date, #branch_id").on('change', function(){
            $('#approvalList').DataTable().ajax.reload();
        });

        // reset button
        $('.resetBtn').on('click', function(e){
            $('#branch_id').val('').trigger('change');
            $('#search_date').val('');
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
        });

        $('.addCredit').on('click', function(){
            $('#creditForm').removeClass('was-validated');
            $('#add-modal').modal('show');
            $('#head_code').val('').trigger('change');
            $('#service_div').html('');
            loadFirstRow(1);
            var submit_url = _baseURL+"account/vouchers/maxVNo/CV"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    var Vid = 'CV-'+res.id;
                    $('#voucher_no').val(Vid);
                }
            });  
        });

        // credit account head
        $.ajax({
            type:'GET',
            url: _baseURL+'account/vouchers/debOrCHead',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#credit_head").select2({
                placeholder: '<?php echo get_phrases(['select', 'debit', 'account']);?>',
                data: data
            });
        });

        $('#approvalList').on('click', '.statusAction', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            var submit_url = _baseURL+"account/vouchers/approvedVoucher/"+id; 
            if(confirm('<?php echo get_phrases(['are_you_sure', 'approved', 'this', 'voucher']);?>')){
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, status:1},
                    dataType: 'JSON',
                    success: function(response) {
                        if(response.success==true){
                            toastr.success(response.message, response.title);
                            $('#approvalList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.warning(response.message, response.title);
                        }
                    }
                });  
            }
            return true;
        });

        $('#approvalList').on('click', '.editAction', function(e) {
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            var id    = $(this).attr('data-id');
            var vtype = $(this).attr('data-type');
            
            var submit_url = _baseURL + 'account/vouchers/voucherEditform/' + id + '/' + vtype;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType: 'JSON',
                data: {
                    'csrf_stream_name': csrf_val
                },
                success: function(response) {
                    $('#editDetails').html(response.data);
                    $('#edit-modal').modal('show');
                    

                },
                error: function() {

                }
            });

        });

        $('#approvalList').on('click', '.ReverseAction', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');

            var submit_url = _baseURL + "account/vouchers/reverseVoucher/" + id;
            if (confirm('<?php echo get_phrases(['are_you_sure', 'reverse', 'this', 'voucher']); ?>')) {
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
                            $('#approvalList').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.warning(response.message, response.title);
                        }
                    }
                });
            }
            return true;
        });
        $('#approvalList').on('click', '.deleteAction', function(e) {
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
                            $('#approvalList').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.warning(response.message, response.title);
                        }
                    }
                });
            }
            return true;
        });

        $('#approvalList').on('click', '.viewAction', function(e){
            e.preventDefault();
            var VNo = $(this).attr('data-id');
            $('#details-modal').modal('show');
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

        function loadFirstRow(id){
            var addHTML = '<tr>' +
                '<td><select name="account_name[]" id="account_name_' + id + '" class="custom-select form-control account_name" required="required"></select><input type="hidden" name="head_code[]" class="form-control head_code" readonly><input type="hidden" name="sub_type[]" id="sub_type_' + id + '" class="form-control sub_type" readonly></td>' +
                '<td><select name="sub_code[]" id="sub_code_' + id + '" class="custom-select form-control sub_code"></select></td>' +
                '<td><input type="text" name="ledger_comments[]" class="form-control " autocomplete="off" ></td>' +
                '<td><input type="text" name="amount[]" class="form-control amount onlyNumber text-right" autocomplete="off" onkeyup="totalCalcuation()" required></td>' +
                '<td><a href="javascript:void(0);" class="btn btn-danger-soft btn-sm removeBtn text-center"><i class="far fa-trash-alt fs-22"></i></a></td>' +
                '</tr>';
            $('#service_div').append(addHTML);
            // search account
            $('#account_name_'+id).select2({
                placeholder: '<?php echo get_phrases(['select', 'credit', 'account']);?>',
                minimumInputLength: 1,
                    ajax: {
                        url: _baseURL+'account/vouchers/searchTransactionCredit',
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            var listdata = JSON.stringify(data);
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

    });

    function restore_credithead(){
        $("#credit_head").empty();
        $.ajax({
            type: 'GET',
            url: _baseURL + 'account/vouchers/debOrCHead',
            dataType: 'json',
            data: {
                'csrf_stream_name': csrf_val
            },
        }).done(function(data) {
            $("#credit_head").select2({
                placeholder: '<?php echo get_phrases(['select', 'credit', 'account']); ?>',
                data: data
            });
        }); 
    }

    function totalCalcuation() {
        var gr_tot = 0;
        $(".amount").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });

        $("#total_amount").val(gr_tot.toFixed(2, 2));
    }
    $logo ="<?php echo base_url($setting->logo);?>";
    $base64 = "<?php echo base64_encode(file_get_contents(base_url($setting->logo))) ?>";
    $companytitle ="<?php echo $setting->title;?>";
</script>