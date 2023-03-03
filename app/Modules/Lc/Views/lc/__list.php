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
                    <?php if ($hasCreateAccess) { ?>
                        <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['create', 'LC']);?></button>
                    <?php } ?>
                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>

            </div>

            <div class="card-body">
                <table id="lclist"  class="table display table-bordered table-striped table-hover compact" width="100%">
                    
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['LC', 'Number']);?></th>
                            <th>LC Open Date</th>
                            <th>LC Bank Name</th>
                            <th>LC Margin</th>
                            <th>LC Amount</th>
                            <th>LC Create Date</th>
                            <th>Acction</th>
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
<div class="modal fade bd-example-modal-lg" id="lc-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="lcModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
    
            <?php echo form_open_multipart('lc/add_lc', 'class="needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            
            <div class="modal-body">
                
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="lc_number" class="font-weight-600">LC Number <i class="text-danger">*</i></label>
                             <input type="text" name="lc_number" id="lc_number" class="form-control" placeholder="<?php echo get_phrases(['enter', 'lC','number']);?>" autocomplete="off" required="">
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600">LC Open Date <i class="text-danger">*</i></label>
                             <input type="date" name="lc_open_date" id="lc_open_date" class="form-control" maxlength="30" autocomplete="off" required="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="lc_bank_id" class="font-weight-600">LC Bank Name <i class="text-danger">*</i></label>
                            <select name="lc_bank_id" id="lc_bank_id" class=" form-control" >
                                <option value=""><?php echo get_phrases(['bank']) ?></option>
                                <?php
                                    if(!empty($banks)){ ?>
                                    <?php foreach ($banks as $key => $value) {  ?>
                                        <option value="<?php echo $value->bank_id; ?>"><?php echo $value->bank_name; ?></option>
                                    <?php }?>
                                <?php }?>
                            </select>   

                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600">LC Margin (%)<i class="text-danger">*</i></label>
                            <input type="number" name="lc_margin" id="lc_margin" class="form-control" maxlength="30" autocomplete="off" placeholder="LC margin" required="" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="lc_amount" class="font-weight-600">LC amount USD <i class="text-danger">*</i></label>
                             <input type="text" name="lc_amount" id="lc_amount" class="form-control onlyNumber" placeholder="LC amount" autocomplete="off" required="">
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="country" class="font-weight-600"><?php echo get_phrases(['country']) ?> </label>

                            <select name="country_code" id="country_code" class="custom-select form-control" >
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
                </div>

                <div class="row">

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="bdt_rate" class="font-weight-600">USD To BDT Rate <i class="text-danger">*</i></label>
                            <input type="text" name="bdt_rate" id="bdt_rate" class="form-control onlyNumber " onkeyup="rateCal()" placeholder="BDT Rate" autocomplete="off" required="">
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="lc_amount_bdt" class="font-weight-600"><?php echo get_phrases(['BDT Amount']) ?> </label>
                            <input type="text" name="lc_amount_bdt" id="lc_amount_bdt" class="form-control " placeholder="LC BDT amount" autocomplete="off"  readonly required="">
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="lc_amount" class="font-weight-600">Attachment <i class="text-danger">*</i> <button type="button" class="input-group-text btn btn-success addRow" id="btnGroupAddon"><i class="fa fa-plus"></i></button></label>
                            <div id='attc'>
                               
                            </div>
                                
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



<!-- item modal button -->
<div class="modal fade bd-example-modal-xl" id="itemDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">

            <div class="table-responsive">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <input type="hidden" value="" name="item_count" id="item_count">
                                <tbody id="viewLc">

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
        $('#lc-modal').modal('hide'); 
        $('#ajaxForm')[0].reset();        
        $('#lclist').DataTable().ajax.reload(null, false);
        //$("#millList").load(" #millList > *");
    }

    $(document).ready(function() { 
       "use strict";
    
        $('.addShowModal').on('click', function(){

            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');     
            $('#lc_number').val('');
            $('#lc_open_date').val('');
            $('#lc_bank_id').val('');
            $('#lc_margin').val('');
            $('#lc_amount').val('');
            $('#lcModalLabel').text('Add New LC')

            //add spr modal first row
            var html = '<div class="input-group" style="margin-top:5px;">'+
                            '<input type="text" name="name[]" class="form-control" placeholder="Attachment Name" required="">'+
                            '<input type="file" name="lc_attc[]" class="form-control" required="">'+
                            '<div class="input-group-prepend">'+
                                '<button type="button" class="input-group-text btn btn-success addRow" id="btnGroupAddon"><i class="fa fa-plus"></i></button>'+
                            '</div>'+
                        '</div>';
            $("#attc").html(html); 

            $('.modal_action').text('Add LC');
            $('#lc-modal').modal('show');
        });



        $('body').on('click', '.addRow', function() {
            // add spr modal tbody new item
            var html = '<div class="input-group" style="margin-top:5px;">'+
                            '<input type="text" name="name[]" class="form-control" placeholder="Attachment Name" required="">'+
                            '<input type="file" name="lc_attc[]" class="form-control" required="">'+
                            '<div class="input-group-prepend">'+
                                '<button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button>'+
                            '</div>'+
                        '</div>';
            $("#attc").append(html); 
        });

        $('body').on('click', '.removeRow', function() {
            var rowCount = $('#attc >div >div').length;
            if(rowCount > 0){
                $(this).parent().parent().remove();
            }else{
                toastr.warning('<?php echo get_notify("There_only_one_row_you_can_not_delete."); ?>');
            } 
        });



        $('#lclist').on('click', '.actionEdit', function(e){

            e.preventDefault();
            $('#ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'lc/get_lcrow/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},

                success: function(data) {

                    $('#id').val(data.row_id);
                    $('#action').val('update');
                    $('.modal_action').text('Update LC');
                    $('#lcModalLabel').text('Update LC')

                    $('#lc_number').val(data.lc_number);
                    $('#lc_open_date').val(data.lc_open_date);
                    $('#lc_bank_id').val(data.lc_bank_id);
                    $('#lc_margin').val(data.lc_margin);
                    $('#lc_amount').val(data.lc_amount);
                    $('#bdt_rate').val(data.bdt_rate);
                    $('#lc_amount_bdt').val(data.bdt_amount);
                    
                
                    get_attachment(id);
                    
                    $('#lc-modal').modal('show');

                }
            });   

        });



        function get_attachment(id){
            
            if(id !='' ){
                var submit_url = _baseURL+'lc/get_attachment/'+id;
                preloader_ajax();
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    dataType : 'JSON',
                    data: {'csrf_stream_name':csrf_val},
                    success: function(response) {
                        $('#attc').html(response.data);
                    },error: function() {
                    }
                }); 
                
            } else {
               
            }
        }

        

        $('#lclist').on('click', '.actionPreview', function(e){

            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'lc/get_lc_details/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(response) {

                    $('#itemDetails-modal').modal('show');

                    $('#itemDetailsModalLabel').text('<?php echo get_phrases(['LC','details']);?>');

                    $('#viewLc').html(response.data);

                },error: function() {

                }
            });   

        });




        $('#lclist').on('click', '.actionDelete', function(e){
            e.preventDefault();
            $('#ajaxForm').removeClass('was-validated');
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'lc/deleteLc';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val,'lc_number':id},
                success: function(res) {
                    if(res.success==true){
                        toastr.success(res.message, res.title);
                        $('#lclist').DataTable().ajax.reload(null, false);
                    }else{
                        toastr.error(res.message, res.title);
                    }
                }
            });   
        });





        $('#lclist').DataTable({

            responsive: true,
            lengthChange: true,
            "aaSorting": [[ 0, "desc" ]],
            "columnDefs": [
                { "bSortable": false, "aTargets": [6] },
            ],
            dom: "<'row'<?php if(@$hasExportAccess || @$hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
               
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6]
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6] 
                    }
                },

                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6] 
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'lc/getList',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'lc_number' },
             { data: 'lc_open_date' },
             { data: 'lc_bank_name' },
             { data: 'lc_margin' },
             { data: 'lc_amount' },
             { data: 'create_date' },
             { data: 'button'}
          ],
        });

    });


    function rateCal(){

        var usd = $('#lc_amount').val();
        var rate = $('#bdt_rate').val();
        var bdtamount = (usd*rate);
        $("#lc_amount_bdt").val(bdtamount);

    }


</script>