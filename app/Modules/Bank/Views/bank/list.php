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
                        <?php if( $hasCreateAccess ){ ?>
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['new', 'bank']);?></button>
                        <?php } ?>
                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table id="bankList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['Bank Name']);?></th>
                            <th><?php echo get_phrases(['Account Name']);?></th>
                            <th><?php echo get_phrases(['Account Number']);?></th>
                            <th><?php echo get_phrases(['branch', 'name']);?></th>
                            <th><?php echo get_phrases(['address']);?></th>
                            <th><?php echo get_phrases(['action']);?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>


<!-- modal button -->
<div class="modal fade bd-example-modal-lg" id="bank-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="bankModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 

            <?php echo form_open_multipart('bank/add_bank', 'class="needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="bank_name" class="font-weight-600">Bank Name <i class="text-danger">*</i></label>
                             <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="<?php echo get_phrases(['enter', 'name']);?>" autocomplete="off" required="">
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600">Bank Account name <i class="text-danger">*</i></label>
                             <input type="text" name="account_name" id="account_name" class="form-control" maxlength="30" autocomplete="off" placeholder="<?php echo get_phrases(['Account name']);?>" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="account_number" class="font-weight-600">Account Number<i class="text-danger">*</i></label>
                             <input type="text" name="account_number" id="account_number" class="form-control" placeholder="<?php echo get_phrases(['Account Number']);?>" autocomplete="off" required="">
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600">Bank branch name <i class="text-danger">*</i></label>
                             <input type="text" name="branch_name" id="branch_name" class="form-control" maxlength="30"  autocomplete="off" placeholder="<?php echo get_phrases(['Branch Name']);?>" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="address" class="font-weight-600">Address<i class="text-danger">*</i></label>
                            <input type="text" name="address" id="address" class="form-control" placeholder="<?php echo get_phrases(['address']);?>" autocomplete="off" required="">
                        </div>
                    </div>

                </div>

            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success modal_action"></button>
            </div>

            <?php echo form_close();?>

        </div>
    </div>
</div>


<!-- category modal button -->
<div class="modal fade bd-example-modal-xl" id="supDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="ModalLabel"><?php echo get_phrases(['supplier', 'details']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody id="viewSupplier">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
               
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">
    
    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');       
        $('#bank-modal').modal('hide'); 
        $('#ajaxForm')[0].reset();        
        $('#bankList').DataTable().ajax.reload(null, false);
        // $("#bankList").load(" #bankList > *");
    }

    $(document).ready(function() { 
       "use strict";

        $('#bankList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [6] },
            ],
            
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                <?php } if($hasExportAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'bank/getAllBank',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'bank_name' },
             { data: 'account_name' },
             { data: 'account_number' },
             { data: 'branch_name' },
             { data: 'address' },
             { data: 'button'}
          ],
        });
    

        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add'); 

            $('#bank_name').val('');
            $('#account_name').val('');
            $('#account_number').val('');
            $('#branch_name').val('');
            $('#address').val('');

            $('.modal_action').text('Add Bank');
            $('#bankModalLabel').text('Add New Bank');
            $('#bank-modal').modal('show');

        });
        

        $('#bankList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('#ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'bank/getBankById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},

                success: function(data) {
                    $('#id').val(data.bank_id);
                    $('#action').val('update');
                    $('.modal_action').text('Update Bank');
                    $('#bankModalLabel').text('Update Bank');
                    $('#bank_name').val(data.bank_name);
                    $('#account_name').val(data.account_name);
                    $('#account_number').val(data.account_number);
                    $('#branch_name').val(data.branch_name);
                    $('#address').val(data.address);
                    $('#bank-modal').modal('show');

                }
            });   

        });


        // delete
        $('#bankList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            var acc_head = $(this).attr('data-head');
            var submit_url = _baseURL+"bank/deleteBank/"+id+'/'+acc_head;
            var check = confirm('Are you sure delete this and all records permanently?');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            $('#bankList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, res.title);
                        }
                    },error: function() {

                    }
                });
            }   
        });

    });
</script>