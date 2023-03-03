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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['new', 'template']);?></button>
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
                            <th><?php echo get_phrases(['template', 'name']);?></th>
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
<div class="modal fade bd-example-modal-lg" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="supplierModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('template/addTemplate', 'class="needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                <input type="hidden" name="template_file_name" id="template_file_name" />

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="template_name" class="font-weight-600"><?php echo get_phrases(['template', 'name']);?><i class="text-danger">*</i></label>
                            <input type="text" class="form-control" id="template_name" autocomplete="off" onkeyup="stringReplace()" />
                            <span id="actual_template_name"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="template_header" class="font-weight-600"><?php echo get_phrases(['template', 'header']);?></label>
                            <input type="number" class="form-control" name="template_header" id="template_header" autocomplete="off"/>
                            <span>Pixel</span>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="template_footer" class="font-weight-600"><?php echo get_phrases(['template', 'footer']);?></label>
                            <input type="number" class="form-control" name="template_footer" id="template_footer" autocomplete="off"/>
                            <span>Pixel</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['template', 'details']);?><i class="text-danger">*</i></label>
                            <textarea name="template_details" id="summernote" rows="10" cols="80" required></textarea>
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


<div class="modal fade bd-example-modal-xl" id="supDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="ModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('template/approveAction', 'class="needs-validation" id="ajaxFormApprove" novalidate="" data="showCallBackDataApprove"');?>
            <div class="modal-body">
                <input type="hidden" name="approve_id" id="approve_id" />
                <div class="row">
                    <label class="col-md-2 col-sm-12 text-right font-weight-600"><?php echo get_phrases(['template', 'name']) ?> : </label>
                    <div class="col-md-4 col-sm-12">
                        <div id="supDetails_template_name"></div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2 col-sm-12 text-right font-weight-600"><?php echo get_phrases(['template', 'header']) ?> : </label>
                    <div class="col-md-4 col-sm-12">
                        <div id="supDetails_template_header"></div>
                    </div>
                    <label class="col-md-2 col-sm-12 text-right font-weight-600"><?php echo get_phrases(['template', 'footer']) ?> : </label>
                    <div class="col-md-4 col-sm-12">
                        <div id="supDetails_template_footer"></div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2 col-sm-12 text-right font-weight-600"><?php echo get_phrases(['details']) ?> : </label>
                    <div class="col-md-8 col-sm-12">
                        <div id="supDetails_template_details"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success actionBtn" id="approve"><?php echo get_phrases(['approve']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');       
        $('#add-modal').modal('hide'); 
        $('#ajaxForm')[0].reset();        
        $('#supplierList').DataTable().ajax.reload(null, false);
    }
    
    var showCallBackDataApprove = function () {
        $('#supDetails-modal').modal('hide'); 
        $('#ajaxFormApprove')[0].reset();        
        $('#supplierList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
    

        $('#supplierList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'template/getDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                async: false,
                data: {'csrf_stream_name':csrf_val},
                success: function(response) {
                    $('#supDetails-modal').modal('show');
                    $('#ModalLabel').text('Details');
                    $('#approve').hide();
                    $('#approve_id').val('');
                    $('#supDetails_template_details').html(response.template_details);
                    $('#supDetails_template_name').html(response.template_name);
                    $('#supDetails_template_header').html(response.template_header+"px");
                    $('#supDetails_template_footer').html(response.template_footer+"px");
                }
            });   

        });

        $('#supplierList').on('click', '.actionApprove', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'template/getDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                async: false,
                data: {'csrf_stream_name':csrf_val},
                success: function(response) {
                    $('#supDetails-modal').modal('show');
                    $('#ModalLabel').text('Approve');
                    $('#approve').show();
                    $('#approve').removeAttr('disabled');
                    $('#approve_id').val(id);
                    $('#supDetails_template_details').html(response.template_details);
                    $('#supDetails_template_name').html(response.template_name);
                    $('#supDetails_template_header').html(response.template_header+"px");
                    $('#supDetails_template_footer').html(response.template_footer+"px");
                }
            });   

        });

        $('#supplierList').DataTable({ 
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
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Supplier_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'template/getList',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'template_name' },
             { data: 'status' },
             { data: 'button'}
          ],
        });

        //summernote
        $('#summernote').summernote({
            placeholder: 'Template Description',
            tabsize: 2,
            height: 300, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true // set focus to editable area after initializing summernote
        });

        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');  
            $('#template_name').val('');
            $('#template_header').val('');
            $('#template_footer').val('');
            $('#actual_template_name').html('');
            $('#template_file_name').val('');
            $("#summernote").summernote("code", "");

            $('#supplierModalLabel').text('<?php echo get_phrases(['add', 'template', 'information']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('#add-modal').modal('show');
        });

        //update
        $('#supplierList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('#ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'template/getDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#id').val(id);
                    $('#action').val('update');
                    $('#supplierModalLabel').text('<?php echo get_phrases(['update', 'template']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');
                    $('#add-modal').modal('show');

                    $("#summernote").summernote("code", data.template_details);
                    $('#template_header').val(data.template_header);
                    $('#template_footer').val(data.template_footer);
                    $('#template_name').val(data.template_name);
                    $('#actual_template_name').html('Generated File Name: '+data.template_name);
                    $('#template_file_name').val(data.template_name);

                }
            });   

        });
        // delete categories
        $('#supplierList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            var submit_url = _baseURL+"template/deleteRecord/"+id;
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

    function stringReplace() {
        var str = $("#template_name").val();
        var newString = str.replace(/[^A-Z0-9]/ig, "_");
        $('#actual_template_name').html('Generated File Name: '+newString);
        $('#template_file_name').val(newString);
    }
</script>