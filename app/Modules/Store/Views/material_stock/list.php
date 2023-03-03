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
                      
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">  
                    <div class="col-sm-3">
                        <label for="filter_store_id" class="font-weight-600"><?php echo get_phrases(['store']) ?> </label>
                        <select name="filter_store_id" id="filter_store_id" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($store_list)){ ?>
                                <?php foreach ($store_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="filter_company_code" class="font-weight-600"><?php echo get_phrases(['company','code','/','item','name']) ?> </label>
                        
                        <select name="filter_company_code" id="filter_company_code" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($item_list)){ ?>
                                <?php foreach ($item_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE.' - '.$value->item_code;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <br>
                        <button type="button" class="btn btn-success mt-2" onclick="reload_table()"><?php echo get_phrases(['filter']);?></button>
                        <button type="button" class="btn btn-warning mt-2" onclick="reset_table()"><?php echo get_phrases(['reset']);?></button>
                    </div>
                </div>
                <table id="main_stockList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']); ?></th>
                            <th><?php echo get_phrases(['item','code']); ?></th>
                            <th><?php echo get_phrases(['item', 'name']); ?></th>
                            <th><?php echo get_phrases(['stock', 'balance']); ?></th>
                            <th><?php echo get_phrases(['store']); ?></th>
                            <th><?php echo get_phrases(['alert']); ?></th>
                            <th><?php echo get_phrases(['action']); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- stock modal button -->
<div class="modal fade bd-example-modal-xl" id="stockDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="stockDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['item', 'name']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="stockDetails_item_nameE" ></div>
                        
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="stockDetails_warehouse" ></div>
                        
                    </div>
                    
                </div>
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['stock']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="stockDetails_stock" ></div>
                        
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
               
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

    function reload_table(){
        $('#main_stockList').DataTable().ajax.reload();
    }
    function reset_table(){
        $('#filter_store_id').val('').trigger('change');
        $('#filter_company_code').val('').trigger('change');
        $('#main_stockList').DataTable().ajax.reload();
    }

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#main_stock-modal').modal('hide');
        $('#main_stockList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";
    
        $('#main_stockList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'store/getMainStockDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#stockDetails-modal').modal('show');
                    $('#stockDetailsModalLabel').text('<?php echo get_phrases(['stock','details']);?>');

                    $('#stockDetails_item_nameE').text(data.item_nameE);
                    $('#stockDetails_item_nameA').text(data.item_nameA);
                    $('#stockDetails_warehouse').text(data.store_name);
                    $('#stockDetails_stock').text(data.stock+' '+data.unit_name);
                    

                },error: function() {

                }
            });   

        });

        var title = $("#testtitle").html();
        $('#main_stockList').DataTable({ 
            responsive: true,
            lengthChange: true,
            "aaSorting": [[ 0, "DESC" ]],
            "columnDefs": [
                { "bSortable": false, "aTargets": [5,6] },
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
                    title : 'MainStock_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'MainStock_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'store/getMainStock',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.store_id = $('#filter_store_id').val();
                        d.company_code = $('#filter_company_code').val();
                    }
            },
          
          "createdRow": function( row, data) {
                console.log(data.alert1);
                if (data.alert1 == "1") {
                    $(row).addClass('btn-danger-soft');
                }
            },


            'columns': [
                { 
                    data: 'id'
                },
                {
                    data: 'item_code'
                },
                {
                    data: 'item_name'
                },
                {
                    data: 'stock'
                },
                {
                    data: 'store_name'
                },
                {
                    data: 'alert'
                },
                {
                    data: 'button'
                }
            ]
           /* rowGroup: {
                dataSrc: 'store_name',
                className: 'bg-info h6 text-white'
            },*/
        });

        $('#main_stockList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        
        $('#picture').on('change', function () {
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
            imgPreview($(this), fileExtension);
        }); 

        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');

            $('#nameE').val('');
            $('#nameA').val('');   

            $('#main_stockModalLabel').text('<?php echo get_phrases(['add', 'stock']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('#main_stock-modal').modal('show');
        });

        $('#main_stockList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'store/getMainStockById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#main_stock-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('update');
                    $('#main_stockModalLabel').text('<?php echo get_phrases(['update', 'stock']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');

                    $('#nameE').val(data.nameE);
                    $('#nameA').val(data.nameA);

                },error: function() {

                }
            });   

        });
        // delete main_stock
        $('#main_stockList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"store/deleteMainStock/"+id;
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
                            $('#main_stockList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, '<?php echo get_phrases(["record"])?>');
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });
</script>