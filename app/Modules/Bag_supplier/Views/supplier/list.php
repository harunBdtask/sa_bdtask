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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['new', 'supplier']);?></button>
                        <?php } ?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="supplierList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['supplier', 'code']);?></th>
                            <th><?php echo get_phrases(['name']);?></th>
                            <th><?php echo get_phrases(['phone']);?></th>
                            <th><?php echo get_phrases(['email']);?></th>
                            <th><?php echo get_phrases(['country']);?></th>
                            <th><?php echo get_phrases(['address']);?></th>
                            <th><?php echo get_phrases(['status']);?></th>
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
<!-- category modal button -->
<div class="modal fade bd-example-modal-lg" id="supplier-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="supplierModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('bag/addSupplier', 'class="needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="nameE" class="font-weight-600"><?php echo get_phrases(['name']);?> <i class="text-danger">*</i></label>
                             <input type="text" name="nameE" id="nameE" class="form-control" placeholder="<?php echo get_phrases(['enter', 'name']);?>" autocomplete="off" required="">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['supplier', 'code']);?> <i class="text-danger">*</i></label>
                             <input type="text" name="short_name" id="short_name" class="form-control" maxlength="30" autocomplete="off" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="phone" class="font-weight-600"><?php echo get_phrases(['phone', 'number']);?> <i class="text-danger">*</i></label>
                             <input type="text" name="phone" id="phone" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['enter', 'phone', 'number']);?>" autocomplete="off" required="">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="email" class="font-weight-600"><?php echo get_phrases(['email', 'address']);?></label>
                             <input type="text" name="email" id="email" class="form-control" placeholder="<?php echo get_phrases(['enter', 'email', 'address']);?>" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="country" class="font-weight-600"><?php echo get_phrases(['country']) ?> </label>
                            <select name="country" id="country" class="custom-select form-control" >
                                <option value=""><?php echo get_phrases(['country']) ?></option>
                                <?php
                                    $country_origin = countries(); 
                                    if(!empty($country_origin)){ ?>
                                    <?php foreach ($country_origin as $key => $value) {  ?>
                                        <option value="<?php echo $value->code; ?>"><?php echo $value->name; ?></option>
                                    <?php }?>
                                <?php }?>
                            </select>   
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['supplier', 'logo']);?></label>
                            <div class="custom-file">
                                <input type="file" name="logo" class="custom-file-input" id="logo" accept=".png, .jpg, .jpeg, .gif">
                                <label class="custom-file-label" id="imgPreview" for="logo"></label>
                            </div>
                            <input type="hidden" name="old_logo" id="old_logo">
                        </div>
                    </div>
                </div>
                
                <div class="row hidden">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="ssn" class="font-weight-600"><?php echo get_phrases(['SSN']);?></label>
                             <input type="text" name="ssn" id="ssn" class="form-control" placeholder="<?php echo get_phrases(['enter', 'SSN']);?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="credit_limit" class="font-weight-600"><?php echo get_phrases(['credit', 'limit']);?> ( <?php echo get_phrases(['amount']);?> ) </label>
                             <input type="text" name="credit_limit" id="credit_limit" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['enter', 'credit', 'limit']);?>" maxlength="20" autocomplete="off">
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="address" class="font-weight-600"><?php echo get_phrases(['address']);?></label>
                            <textarea name="address" class="form-control" id="address" rows="3" placeholder="<?php echo get_phrases(['enter','address']);?>"></textarea>
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
            <div class="modal-body" id="printContent">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="<?php echo base_url() . $setting->logo; ?>" class="img-responsive" alt=""><strong><?php echo $setting->title; ?></strong><br>
                        <?php echo $setting->address; ?>
                    </div>
                </div>
                <hr>
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
                <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                <button type="button" class="btn btn-info" onclick="makePdf('printContent')"><?php echo get_phrases(['download']);?></button>
            </div>
            
        </div>
    </div>
</div>

<span style="display:none;" id="testtitle">
    <div class="row">
        <div class="col-md-12 text-center">
            <img src="<?php echo base_url() . $setting->logo; ?>" class="img-responsive" alt=""><strong><?php echo $setting->title; ?></strong><br>
            <?php echo $setting->address; ?>
        </div>
    </div>
    <hr>
    <h4>
        <center><?php echo $title; ?></center>
    </h4>
</span>

<script type="text/javascript">
    
    function makePdf(id) {
        preloader_ajax();
        $.ajax({
            async: true,
            success: function(data) {
                getPDF(id);
            }
        }); 
    }

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');       
        $('#supplier-modal').modal('hide'); 
        $('#ajaxForm')[0].reset();        
        $('#supplierList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
    
        $('#supplierList').on('click', '.actionPreview', function(e){
            e.preventDefault();
           
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'bag/supplierDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(response) {
                   $('#viewSupplier').html(response.data);
                   $('#supDetails-modal').modal('show');
                },error: function() {

                }
            });   

        });

        var title = $("#testtitle").html();
        $('#supplierList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [8] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>{
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title: '',
                    messageTop: title,
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'bag/getAllSupplier',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'code_no' },
             { data: 'nameE' },
             { data: 'phone' },
             { data: 'email' },
             { data: 'country' },
             { data: 'address' },
             { data: 'status' },
             { data: 'button'}
          ],
        });

        $('#agree_type').on('change', function () {

            var agree_type = $(this).val();
            
            $('#credit_limit').val('');
            $('#credit_period').val('');

            if(agree_type == '3'){
                $('#credit_limit').attr('readonly', false); 
                $('#credit_period').attr('readonly', false); 
            } else {
                $('#credit_limit').attr('readonly', true); 
                $('#credit_period').attr('readonly', true); 
            }

        }); 

        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');        
            $('#imgPreview').html('');   
            $('#nameE').val('');
            $('#nameA').val('');
            $('#short_name').val('');
            $('#phone').val('');
            $('#email').val('');
            $('#address').val('');
            $('#vat_no').val('');
            $('#cr_no').val('');
            $('#bank_name').val('');
            $('#bank_ac_no').val('');
            $('#bank_iban').val('');
            $('#agree_type').val('').trigger('change');
            $('#credit_limit').val('');
            $('#credit_period').val('');
            $('#old_logo').val('');
            $('#supplierModalLabel').text('<?php echo get_phrases(['add', 'supplier', 'information']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add', 'supplier']);?>');
            $('#imgPreview').text('<?php echo get_phrases(['please', 'choose', 'a', 'logo']);?>');

            getMAXID('wh_bag_supplier_information','id','short_name','SUP-');

            $('#supplier-modal').modal('show');
        });

        //update
        $('#supplierList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('#ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'bag/getSupplierById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#id').val(data.id);
                    $('#action').val('update');
                    $('#supplierModalLabel').text('<?php echo get_phrases(['update', 'supplier']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update', 'supplier']);?>');
                    var logo = _baseURL+data.logo;
                    var img = '<img class="mt-reverse-5 imgLoad" height="30">';
                    $('#nameE').val(data.nameE);
                    $('#nameA').val(data.nameA);
                    $('#short_name').val(data.code_no);
                    $('#phone').val(data.phone);
                    $('#email').val(data.email);
                    $('#address').val(data.address);
                    $('#credit_limit').val(data.credit_limit);
                    $('#ssn').val(data.ssn);
                    $('#country').val(data.country).trigger('change');
                    $('#old_logo').val(data.logo);
                    $('#imgPreview').html(img);
                    $('.imgLoad').attr('src', logo);
                    $('#supplier-modal').modal('show');

                }
            });   

        });
        // delete categories
        $('#supplierList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            var acc_head = $(this).attr('data-head');
            var submit_url = _baseURL+"bag/deleteSupplier/"+id+'/'+acc_head;
            var check = confirm('Are you sure delete this supplier and all records permanently?');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            $('#supplierList').DataTable().ajax.reload(null, false);
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