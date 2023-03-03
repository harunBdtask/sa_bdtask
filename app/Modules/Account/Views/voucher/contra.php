<link href="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<div class="row">
   <div class="col-sm-12">
      <?php
         $hasPrintAccess  = $permission->method('contra_voucher', 'print')->access();
         $hasExportAccess = $permission->method('contra_voucher', 'export')->access();
         if ($permission->module('contra_voucher')->access()) { ?>
      <div class="card">
         <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <nav aria-label="breadcrumb" class="order-sm-last p-0">
                     <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                        <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                     </ol>
                  </nav>
               </div>
               <div class="text-right">
                  <?php if ($permission->method('contra_voucher', 'create')->access()) { ?>
                  <a href="javascript:void(0);" class="btn btn-success btn-sm mr-1 addDebit"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['contra', 'voucher']); ?></a>
                  <?php } ?>
                  <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']); ?></a>
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
                  <button type="button" class="btn btn-warning resetBtn text-white"><?php echo get_phrases(['reset']); ?></button>
               </div>
            </div>
            <table id="approvalList" class="table display table-bordered table-striped table-hover compact" width="100%">
               <thead>
                  <tr>
                     <th><?php echo get_phrases(['serial']); ?></th>
                     <th><?php echo get_phrases(['reverse', 'head']); ?></th>
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
      <?php } else { ?>
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-md-12">
                  <strong class="fs-20 text-danger"><?php echo get_phrases(['you_do_not_have_permission_to_access_please_contact_with_administrator']); ?></strong>
               </div>
            </div>
         </div>
      </div>
      <?php } ?>
   </div>
</div>
<div class="modal fade bd-example-modal-lg" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
   <div class="modal-dialog modal-xl c">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title font-weight-600" id="addModalLabel"><?php echo get_phrases(['add', 'contra', 'voucher']); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <?php echo form_open_multipart('account/vouchers/saveContraVoucher', 'class="needs-validation" id="contraForm" novalidate="" data="contraCallBackData"'); ?>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6 col-sm-12">
                  <div class="form-group">
                     <label class="font-weight-600 mb-0"><?php echo get_phrases(['reverse', 'account', 'head']); ?> <i class="text-danger">*</i></label>
                     <?php echo form_dropdown('reverse_head', '', '', 'class="form-control custom-select" id="reverse_head" required'); ?>
                  </div>
               </div>
               <div class="col-md-3 col-sm-12">
                  <div class="form-group">
                     <label class="font-weight-600 mb-0"><?php echo get_phrases(['voucher', 'date']); ?></label>
                     <input type="text" name="voucher_date" id="voucher_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly="">
                  </div>
               </div>
            </div>
            <div class="table-responsive">
               <table class="table table-stripped table-sm">
                  <thead>
                     <tr>
                        <th class="text-center" width="45%"><?php echo get_phrases(['account', 'head']); ?><i class="text-danger">*</i></th>
                        <th class="text-center" width="20%"><?php echo get_phrases(['ledger', 'comments']); ?></th>
                        <th class="text-center" width="25%"><?php echo get_phrases(['debit']); ?></th>
                        <th class="text-center" width="25%"><?php echo get_phrases(['credit']); ?></th>
                        <th class="text-center" width="10%"></th>
                     </tr>
                  </thead>
                  <tbody id="service_div">
                  </tbody>
                  <tfoot>
               </table>
            </div>
            <div class="row">
               <div class="col-md-12 col-sm-12">
                  <div class="row mb-2">
                     <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['remarks']); ?></label>
                        <textarea name="remarks" class="form-control" id="remarks" rows="2"></textarea>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']); ?></button>
            <button type="submit" id="saveBtn" class="btn btn-success"><?php echo get_phrases(['create', 'voucher']); ?></button>
         </div>
         <?php echo form_close(); ?>
      </div>
   </div>
</div>
<!-- view voucher details -->
<div class="modal fade bd-example-modal-lg" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title font-weight-600" id="detailsModalLabel"><?php echo get_phrases(['view', 'voucher', 'details']); ?></h5>
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
            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']); ?></button>
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
                <?php echo form_open('account/vouchers/updateContraVoucher', 'class="needs-validation" id="contraForm" novalidate="" data="contraCallBackData"')?>
                    <div class="col-md-12" id="editDetails">

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
<script src="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
   var contraCallBackData = function() {
       $('#add-modal').modal('hide');
       $('#edit-modal').modal('hide');
       $('#editDetails').html('');
       $('#contraForm')[0].reset();
       $('#contraForm').removeClass('was-validated');
       $('#approvalList').DataTable().ajax.reload(null, false);
   }
   
   $(document).ready(function() {
       "use strict";
       $('.modal').attr("data-backdrop","static");
       $('option:first-child').val('').trigger('change');
       $('#search_date').datepicker({
           dateFormat: 'yy-mm-dd'
       });
   
       $('#approvalList').DataTable({
           responsive: true,
           lengthChange: true,
           "aaSorting": [
               [0, "desc"]
           ],
           "columnDefs": [{
               "bSortable": false,
               "aTargets": [9]
           }, ],
           dom: "<'row'<?php if ($hasExportAccess || $hasPrintAccess) {
      echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>";
      } else {
      echo "<'col-md-6'l><'col-md-6'f>";
      } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
           buttons: [
               <?php if ($hasPrintAccess) { ?> {
                       extend: 'print',
                       text: '<i class="fa fa-print custool" title="Print"></i>',
                       className: 'btn-light',
                       customize: function (doc) {
            $(doc.document.body)                                
            .prepend('<div class="row"><div class="col-md-3"><img style="position:absolute; top:10; left:50;width:50;height:40px;" src='+$logo+'></div>')
            .prepend('<div  class="col-md-6 text-center" style="position:absolute; top:10; left:80px;"><h6>'+$companytitle+'</h6><strong><u class="pt-4">Contra Voucher</u></strong></div>')
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
                       title: '',
                       exportOptions: {
                           columns: [0, 1, 2, 3, 4, 5, 6, 7]
                       }
                   },
               <?php }
      if ($hasExportAccess) { ?> {
                       extend: 'copyHtml5',
                       text: '<i class="far fa-copy custool" title="Copy"></i>',
                       className: 'btn-light',
                       title: 'Contra_voucher_list-<?php echo date('Y-m-d'); ?>',
                       exportOptions: {
                           columns: [0, 1, 2, 3, 4, 5, 6, 7]
                       }
                   },
                   {
                       extend: 'excelHtml5',
                       text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                       className: 'btn-light',
                       title: 'Contra_voucher_list-<?php echo date('Y-m-d'); ?>',
                       exportOptions: {
                           columns: [0, 1, 2, 3, 4, 5, 6, 7]
                       }
                   },
                   {
                       extend: 'csvHtml5',
                       text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                       className: 'btn-light',
                       title: 'Contra_voucher_list-<?php echo date('Y-m-d'); ?>',
                       exportOptions: {
                           columns: [0, 1, 2, 3, 4, 5, 6, 7]
                       }
                   },
                   {
                       extend: 'pdfHtml5',
                       text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
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
                       title: 'Contra Voucher',
                       exportOptions: {
                           columns: [0, 1, 2, 3, 4, 5, 6, 7]
                       }
                   }
               <?php } ?>
           ],
           'processing': true,
           'serverSide': true,
           'serverMethod': 'post',
           'ajax': {
               'url': _baseURL + 'account/vouchers/getVoucherList/TV',
               'data': function(d) {
                   d.csrf_stream_name = csrf_val;
                   d.search_date = $('#search_date').val();
                   d.branch_id = $('#branch_id').val();
               }
           },
           'columns': [{
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
       $("#search_date, #branch_id").on('change', function() {
           $('#approvalList').DataTable().ajax.reload();
       });
   
       // reset button
       $('.resetBtn').on('click', function(e) {
           $('#branch_id').val('').trigger('change');
           $('#search_date').val('');
       });
   
       // branch list
       $.ajax({
           type: 'GET',
           url: _baseURL + 'auth/branchList',
           dataType: 'json',
           data: {
               'csrf_stream_name': csrf_val
           },
       }).done(function(data) {
           $("#branch_id").select2({
               placeholder: '<?php echo get_phrases(['select', 'branch']); ?>',
               data: data
           });
       });
   
       $('.addDebit').on('click', function() {
           $('#contraForm').removeClass('was-validated');
           $('#add-modal').modal('show');
           $('#head_code').val('').trigger('change');
           $('#service_div').html('');
           loadFirstRow(1);
           var submit_url = _baseURL + "account/vouchers/maxVNo/DV";
           $.ajax({
               type: 'POST',
               url: submit_url,
               data: {
                   'csrf_stream_name': csrf_val
               },
               dataType: 'JSON',
               success: function(res) {
                   var Vid = 'DV-' + res.id;
                   $('#voucher_no').val(Vid);
               }
           });
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
   
       // credit account head 
       $.ajax({
           type: 'GET',
           url: _baseURL + 'account/vouchers/debOrCHead',
           dataType: 'json',
           data: {
               'csrf_stream_name': csrf_val
           },
       }).done(function(data) {
           $("#reverse_head").select2({
               placeholder: '<?php echo get_phrases(['select', 'credit', 'account']); ?>',
               data: data
           });
       });
   
   
       $('#approvalList').on('click', '.statusAction', function(e) {
           e.preventDefault();
           var id = $(this).attr('data-id');
   
           var submit_url = _baseURL + "account/vouchers/approvedVoucher/" + id;
           if (confirm('<?php echo get_phrases(['are_you_sure', 'approved', 'this', 'voucher']); ?>')) {
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
   
       $('#approvalList').on('click', '.viewAction', function(e) {
           e.preventDefault();
           var VNo = $(this).attr('data-id');
           $('#details-modal').modal('show');
           var submit_url = _baseURL + "account/vouchers/getVoucherDetails/" + VNo;
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
   
       function loadFirstRow(id) {
           var addHTML = '<tr>' +
               '<td><select name="account_name" id="account_name" class="custom-select form-control account_name" required="required"></select><input type="hidden" name="head_code" class="form-control head_code" readonly><input type="hidden" name="sub_type" id="sub_type_id" class="form-control sub_type" readonly></td>'  +
               '<td><input type="text" name="ledger_comments" class="form-control " autocomplete="off" ></td>' + '<td><input type="text" name="debit" class="form-control debit onlyNumber text-right" value="" autocomplete="off" required></td>'+'<td><input type="text" name="credit" class="form-control credit onlyNumber text-right" value="" autocomplete="off" required></td>' +
               '</tr>';
           $('#service_div').append(addHTML);
           // search account
           $('#account_name').select2({
               placeholder: '<?php echo get_phrases(['select', 'debit', 'account']); ?>',
               minimumInputLength: 1,
               ajax: {
                   url: _baseURL + 'account/vouchers/debOrCHead',
                   dataType: 'json',
                   delay: 250,
                   processResults: function(data) {
   
                       return {
                           results: $.map(data, function(item) {
   
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
   
       var countPayId = 2;
       $('body').on('click', '.addMore', function() {
           loadFirstRow(countPayId);
           countPayId++;
       });
   
       $('body').on('click', '.removeBtn', function() {
           var rowCount = $('#service_div >tr').length;
           if (rowCount > 1) {
               $(this).parent().parent().remove();
   
   
           } else {
               alert("There only one row you can't delete.");
           }
       });
   
       $('body').on('change', '#account_name', function(e) {
           e.preventDefault();
           var id = $(this).val();
           var reverse_head = $("#reverse_head").val();
           $(this).parent().parent().find(".head_code").val(id);
           
           if(reverse_head == ''){
           $(this).parent().parent().find(".head_code").val('');
            toastr.error('Please Select Reverse Head');
             $('#reverse_head').focus();
              return false;
           }
            if(reverse_head == id){
              toastr.error('Reverse Head and Transactional Head can not be same');
              $('#account_name').val('').trigger('change');
              $(this).parent().parent().find(".head_code").val('');
              $('#account_name').focus();    
            }
   
            $("#sub_type_id").val(0);
           
         
   
       });
   
   });

    $logo ="<?php echo base_url($setting->logo);?>";
    $base64 = "<?php echo base64_encode(file_get_contents(base_url($setting->logo))) ?>";
    $companytitle ="<?php echo $setting->title;?>";
   
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
          
   
           // debit
           var total1 = 0;
           $('.debit').each(function(){ 
               total1  += parseFloat($(this).val());
           }); 
          
       }
   
       function activeBtn() {
           var credit = $('#totalCredit').val();
           var debit = $('#totalDebit').val();
           if(credit==debit){
               $('#saveBtn').attr('disabled', false);
           }else{
               $('#saveBtn').attr('disabled', true);
           }
       }
</script>