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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'pricing']);?></button>
                        <?php } ?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="pricingList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['goods', 'name']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
                            <th><?php echo get_phrases(['price']);?></th>
                            <th><?php echo get_phrases(['increase/Decrease','percentage']);?></th>
                            <th><?php echo get_phrases(['created','by']);?></th>
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
<!-- pricing modal button -->
<div class="modal fade bd-example-modal-xl" id="pricing-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="pricingsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('finished_goods/add_pricing', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                <div class="row form-group">
                    <label for="nameE" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['goods', 'name']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                       <?php echo form_dropdown('product_id',$item_list,null,'class="form-control select2" id="product_id"')?>
                        
                    </div>
                    <label for="code_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="date" placeholder="<?php echo get_phrases(['date']);?>" class="form-control" id="date" value="<?php echo date('Y-m-d')?>" readonly required>
                        
                    </div>
                </div>

                    <div class="row form-group">
                    <label for="latest_price" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['latest', 'price']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="latest_price" placeholder="<?php echo get_phrases(['enter', 'latest', 'price']);?>" class="form-control" id="latest_price" required readonly>
                        
                    </div>
                    <label for="new_price" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['new', 'price']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="new_price" placeholder="<?php echo get_phrases(['enter', 'new', 'price']);?>" class="form-control" id="new_price" onkeyup="priceCalculation()" required>
                        
                    </div>
                </div>

                       <div class="row form-group">
                    <label for="nameE" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['increase/Decrease', 'percentage']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="increase_percentage" placeholder="<?php echo get_phrases(['increase', 'percentage']);?>" class="form-control" id="increase_percentage" required readonly>
                        
                    </div>
                  
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success modal_action actionBtn"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>


<!-- pricing modal button -->
<div class="modal fade bd-example-modal-xl" id="pricingDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="pricingDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['goods', 'name']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="goodsnamedetails" ></div>
                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['previous', 'price']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="previousPriceDeatails"></div>
                        
                    </div>
                </div>
                  <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['new', 'price']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="newPriceDetails" ></div>
                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['increase/Decrease', 'percentage']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="increasePercentageDetails"></div>
                        
                    </div>
                </div>
                    <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="dateDetails" ></div>
                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['created', 'by']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="createByDetails"></div>
                        
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
        $('.ajaxForm')[0].reset();        
        $('#pricing-modal').modal('hide');
        $('#pricingList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";
    
        $('#pricingList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'finished_goods/getpricingDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#pricingDetails-modal').modal('show');
                    $('#pricingDetailsModalLabel').text('<?php echo get_phrases(['pricing','details']);?>');
    
                    $('#goodsnamedetails').text(data.item_name);
                    $('#previousPriceDeatails').text(data.previous_price);
                    $('#newPriceDetails').text(data.price);
                    $('#increasePercentageDetails').text(data.increase_percentagte);
                    $('#dateDetails').text(data.date);
                    $('#createByDetails').text(data.fullname);


                },error: function() {

                }
            });   

        });

        $('#pricingList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 2, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [0,1,3,4,5,6] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'pricings_List-<?php echo date('Y-m-d');?>',
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
                    title : 'pricings_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'pricings_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'pricings_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'pricings_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'finished_goods/getpricings',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'nameE' },
             { data: 'date' },
             { data: 'price' },
             { data: 'increase_percentagte' },
             { data: 'create_by' },
             { data: 'button'}
          ],
        });


        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');
            $('#product_id').val('').trigger('change'); 
            $('#new_price').val('');
            $('#latest_price').val('');
            $('#increase_percentage').val('');
            $('#pricingsModalLabel').text('<?php echo get_phrases(['add', 'pricing']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('#pricing-modal').modal('show');
        });

        $('#pricingList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'finished_goods/getpricingsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#pricing-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('formdata');
                    $('#pricingsModalLabel').text('<?php echo get_phrases(['update', 'pricing']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');

                    $('#product_id').val(data.product_id).trigger('change');
                    $('#date').val(data.date); 
                    $('#new_price').val(data.price);
                    $('#latest_price').val(data.previous_price);
                    $('#increase_percentage').val(data.increase_percentagte);
                    $('#action').val('update');
                },error: function() {

                }
            });   

        });
        // delete pricings
        $('#pricingList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"finished_goods/deletepricings/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, '<?php echo get_phrases(["record"])?>');
                            $('#pricingList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, '<?php echo get_phrases(["record"])?>');
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });


    $('body').on('change', '#product_id', function() {
        var val = this.value;
        var action =  $('#action').val();
        if(action != 'formdata'){
          $.ajax({
                    type: 'POST',
                    url: _baseURL+"finished_goods/goods_previousprice/"+val,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(data) {
                     var latest_price = (data?data.price:0);
                      $('#latest_price').val(latest_price);
                       priceCalculation();
                    },error: function() {

                    }
                });
        }

    });


    function priceCalculation() {
     var old_price = $('#latest_price').val();
     var new_price = $('#new_price').val();
     if(old_price == 0){
       $("#increase_percentage").val(100); 
     }else{
     var diff = (new_price?parseFloat(new_price):0) - (old_price?parseFloat(old_price):0);
     var calamount = (diff * 100)/old_price;
     $("#increase_percentage").val(calamount); 
     }
    }
</script>