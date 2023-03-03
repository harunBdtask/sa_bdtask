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
                        <!-- <h6 class="fs-17 font-weight-600 mb-0">Bootstrap 4 Print button</h6> -->
                    </div>
                    <div class="text-right">
                        <?php if( $hasCreateAccess ){ ?>
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'dealer']);?></button>
                        <?php } ?>
                       <a href="<?php echo previous_url();?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="dealerList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['dealer', 'name']);?></th>
                            <th><?php echo get_phrases(['reference', 'name']);?></th>
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
<!-- Large modal button -->
<div class="modal fade bd-example-modal-lg" id="dealer-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="dealerModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('section/add_dealer', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                

                <div class="row">
                    <div class="col-sm-12">
                         <div class="form-group">
                            <label for="name" class="font-weight-600"><?php echo get_phrases(['name']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="name" placeholder="<?php echo get_phrases(['enter', 'name']);?>" class="form-control" id="name" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="reference_id" class="font-weight-600"><?php echo get_phrases(['reference', 'name']) ?> <i class="text-danger">*</i> </label>
                            <select name="reference_id" id="reference_id" class="form-control custom-select" required>
                                <?php if(!empty($reference_list)){ ?>
                                    <option value=""></option>
                                    <?php foreach ($reference_list as $key => $value) {?>
                                        <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                    <?php }?>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="hidden" name="parent_id" id="parent_id" value="0">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success modal_action actionBtn"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<script type="text/javascript">

    function parent_reset(){
        $('#parent_id').val('').trigger('change');
    }

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();
        $('#dealer-modal').modal('hide');
        $('#dealerList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";

        $('option:first-child').val('').trigger('change');
        $('#dealerList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [3] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'Dealer_List-<?php echo date('Y-m-d');?>',
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
                    title : 'Dealer_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Dealer_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Dealer_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Dealer_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'section/getDealers',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
            { data: 'id' },
            { data: 'name'},
            { data: 'reference'},
            { data: 'button'}
          ],
        });

        // dealer list
        /*$.ajax({
            type:'GET',
            url: _baseURL+'human_resources/employees/getDealers',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#parent_id").select2({
                placeholder: '<?php //echo get_phrases(['select', 'dealer']);?>',
                data: data
            });
        });*/


        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');

            $('#name').val('');
            $('#reference_id').val('').trigger('change');

            $('#dealerModalLabel').text('<?php echo get_phrases(['add', 'dealer']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('.modal_action').prop('disabled', false);
            $('#dealer-modal').modal('show');
        });

        $('#dealerList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'section/getDealerById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#dealer-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('update');

                    $('#dealerModalLabel').text('<?php echo get_phrases(['update', 'dealer']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');
                    $('.modal_action').prop('disabled', false);

                    $('#name').val(data.name);
                    $('#reference_id').val(data.reference_id).trigger('change');
                },error: function() {

                }
            });   

        });
        // delete dealer
        $('#dealerList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"section/deleteDealer/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, 'Dealer Record');
                            $('#dealerList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, 'Dealer Record');
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });
</script>