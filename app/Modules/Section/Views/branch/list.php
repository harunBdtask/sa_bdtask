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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'branch']);?></button>
                        <?php } ?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i>Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="branchList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['name']);?></th>
                            <th><?php echo get_phrases(['name']);?></th>
                            <th><?php echo get_phrases(['vat', 'number']);?></th>
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
<div class="modal fade bd-example-modal-lg" id="branch-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="branchModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('section/add_branch', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                <div class="row">
                    <div class="col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['branch', 'name', 'english']);?>  <i class="text-danger">*</i></label>
                            <input type="text" name="branch_name" placeholder="<?php echo get_phrases(['enter', 'branch', 'name']);?>" class="form-control" id="branch_name" data-toggle="tooltip" data-field="<?php echo get_phrases(['branch', 'name']);?>" title="<?php echo get_phrases(['branch', 'name', 'english']);?>" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['branch', 'name', 'arabic']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="branch_nameA" placeholder="<?php echo get_phrases(['enter', 'branch', 'name']);?>" class="form-control" id="branch_nameA" data-toggle="tooltip" data-field="<?php echo get_phrases(['branch', 'name']);?>" title="<?php echo get_phrases(['branch', 'name', 'arabic']);?>" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['vat', 'number']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="vat_no" placeholder="<?php echo get_phrases(['enter', 'vat', 'number']);?>" class="form-control" id="vat_no" required>
                        </div>
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

<script type="text/javascript">
    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('#branch-modal').modal('hide');
        $('.ajaxForm')[0].reset();
        $('#branchList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";
    

        $('#branchList').DataTable({ 
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
                    title : 'Branch_List-<?php echo date('Y-m-d');?>',
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
                    title : 'Branch_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Branch_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Branch_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Branch_List-<?php echo date('Y-m-d');?>',
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
               'url': _baseURL + 'section/getBranchs',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'nameE' },
             { data: 'nameA' },
             { data: 'vat_no' },
             { data: 'button'}
          ],
        });

        $('#picture').on('change', function () {
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
            imgPreview($(this), fileExtension);
        }); 

        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            emptyBranchFields();

            $('#branchModalLabel').text('<?php echo get_phrases(['add', 'branch']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('.modal_action').prop('disabled', false);
            $('#branch-modal').modal('show');
        });

        $('#branchList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            emptyBranchFields();
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'section/getBranchById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#branch-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('update');
                    $('#branchModalLabel').text('<?php echo get_phrases(['update', 'branch']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');
                    $('.modal_action').prop('disabled', false);
                    $('#branch_name').val(data.nameE);
                    $('#branch_nameA').val(data.nameA);
                    $('#vat_no').val(data.vat_no);

                },error: function() {

                }
            });   

        });

        function emptyBranchFields(){
            $('#id').val('');
            $('#branch_name').val('');
            $('#branch_nameA').val('');
            $('#vat_no').val('');
        }
        // delete branch
        $('#branchList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"section/deleteBranch/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, 'Branch Record');
                            $('#branchList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, 'Branch Record');
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });
</script>