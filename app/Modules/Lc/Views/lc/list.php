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
                        <a href="<?php echo base_url('lc/lc_info')?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['create', 'LC']);?></a>
                    <?php } ?>
                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>

            </div>

            <div class="card-body">
                <table id="lclist"  class="table display table-bordered table-striped table-hover compact" width="100%">
                    
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
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
            <?php echo form_open_multipart('lc/approve', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body" id="printContent">
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="purchase_id" id="purchase_id" value="">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="<?php echo base_url() . $setting->logo; ?>" class="img-responsive" alt=""><strong><?php echo $setting->title; ?></strong><br>
                        <?php echo $setting->address; ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <table class="table table-bordered">
                            <input type="hidden" value="" name="item_count" id="item_count">
                            <tbody id="viewLc">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                    <button type="submit" class="btn btn-success actionBtn" id="approve"><?php echo get_phrases(['approve']);?></button>
                    <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                    <button type="button" class="btn btn-info" onclick="makePdf('printContent')"><?php echo get_phrases(['download']);?></button>
                </div>
            </div>
            <?php echo form_close();?>
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
    
    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');       
        $('#itemDetails-modal').modal('hide'); 
        $('#ajaxForm')[0].reset();        
        $('#lclist').DataTable().ajax.reload(null, false);
    }

    function makePdf(id) {
        preloader_ajax();
        $.ajax({
            async: true,
            success: function(data) {
                getPDF(id);
            }
        }); 
    }

    $(document).ready(function() { 
       "use strict";

        $('#lclist').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('#ajaxForm').removeClass('was-validated');
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'lc/lc_info/'+id;
            window.open(submit_url, "_self");
            
        });

        $('#lclist').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            var id = $(this).attr('data-id');
            $('#id').val('');
            $('#purchase_id').val('');
            var submit_url = _baseURL+'lc/get_lc_details/'+id;
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(response) {
                    $('#approve').hide();
                    $('#itemDetails-modal').modal('show');
                    $('#itemDetailsModalLabel').text('<?php echo get_phrases(['LC','details']);?>');
                    $('#viewLc').html(response.data);

                }
            });   
        });

        $('#lclist').on('click', '.actionApprove', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            var id = $(this).attr('data-id');
            var purchase_id = $(this).attr('data-purchase');
            $('#id').val(id);
            $('#purchase_id').val(purchase_id);
            var submit_url = _baseURL+'lc/get_lc_details/'+id;
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(response) {
                    $('#approve').show();
                    $('#approve').prop('disabled', false);
                    $('#itemDetails-modal').modal('show');
                    $('#itemDetailsModalLabel').text('<?php echo get_phrases(['LC','pending','approval']);?>');
                    $('#viewLc').html(response.data);

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
                data: {'csrf_stream_name':csrf_val,'lc_id':id},
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

        var title = $("#testtitle").html();
        $('#lclist').DataTable({
            responsive: true,
            lengthChange: true,
            "aaSorting": [[ 0, "desc" ]],
            "columnDefs": [
                { "bSortable": false, "aTargets": [7] },
            ],
            dom: "<'row'<?php if(@$hasExportAccess || @$hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                {
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
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7] 
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
             { data: 'row_id' },
             { data: 'lc_number' },
             { data: 'lc_open_date' },
             { data: 'lc_bank_id' },
             { data: 'lc_margin' },
             { data: 'lc_amount' },
             { data: 'lc_open_date' },
             { data: 'button'}
          ],
        });


    });


</script>