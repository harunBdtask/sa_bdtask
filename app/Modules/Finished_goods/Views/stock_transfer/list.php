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
                      
                       <a href="<?php echo base_url('finished_goods/store_transfer/add_store_transfer'); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['add','transfer']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
     
                <table id="storeTransferList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['from', 'store']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
                            <th><?php echo get_phrases(['transfer','by']);?></th>
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


<!-- stock modal button -->
<div class="modal fade bd-example-modal-xl" id="stockTransferDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="stockDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
               <div id="transfer_details_modal">

               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
               
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">

    function reload_table(){
        $('#storeTransferList').DataTable().ajax.reload();
    }
    function reset_table(){
        $('#filter_store_id').val('').trigger('change');
        $('#filter_company_code').val('');
        $('#storeTransferList').DataTable().ajax.reload();
    }

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#main_stock-modal').modal('hide');
        $('#storeTransferList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";
    
        $('#storeTransferList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'finished_goods/store_transfer/transfer_detials/'+id;
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $("#transfer_details_modal").html(data);
                    $('#stockTransferDetails-modal').modal('show');
                    $('#stockDetailsModalLabel').text('<?php echo get_phrases(['stock','transfer','details']);?>');

                },error: function() {

                }
            });   

        });

        $('#storeTransferList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "lengthMenu": [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
             "aaSorting": [[ 2, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [0,1,3,4] },
                /*{ "visible": false, "targets": [0,3] }*/
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'MainStock_List-<?php echo date('Y-m-d');?>',
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
                    title : 'MainStock_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'MainStock_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'MainStock_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'MainStock_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'finished_goods/store_transfer/checkTransferList',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                    }
            },
          'columns': [
             { data: 'id' },
             { data: 'store_name' },
             { data: 'date' },
             { data: 'transfer_by' },
             { data: 'button'}
          ],
           /* rowGroup: {
                dataSrc: 'store_name',
                className: 'bg-info h6 text-white'
            },*/
        });

        $('#storeTransferList').on('draw.dt', function() {
             $('.custool').tooltip(); 
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


        // delete main_stock
        $('#storeTransferList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"finished_goods/deleteMainStock/"+id;
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
                            $('#storeTransferList').DataTable().ajax.reload(null, false);
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