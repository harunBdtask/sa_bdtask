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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'new']);?></button>
                       <?php } ?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="listsList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['list', 'name']);?></th>
                            <th><?php echo get_phrases(['name', 'english']);?></th>
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
<div class="modal fade bd-example-modal-lg" id="lists-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="listsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('settings/add_lists', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />

                <div class="row form-group">
                    <label for="list_id" class="col-sm-4 col-form-label font-weight-600"><?php echo get_phrases(['list', 'name']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <select name="list_id" id="list_id" class="form-control" required>
                            <?php if(!empty($list_tables)){ ?>
                                <option value=""></option>
                                <?php foreach ($list_tables as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->table_titleE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="nameE" class="col-sm-4 col-form-label font-weight-600"><?php echo get_phrases(['name','english']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <input type="text" name="nameE" placeholder="<?php echo get_phrases(['enter', 'name']);?>" class="form-control" id="nameE" data-toggle="tooltip" data-field="<?php echo get_phrases(['name']) ?>" title="<?php echo get_phrases(['name','english']) ?>" required>  
                    </div>
                </div>
                <div class="row form-group hidden">
                    <label for="nameA" class="col-sm-4 col-form-label font-weight-600"><?php echo get_phrases(['name','arabic']) ?></label>
                    <div class="col-sm-8">
                        <input type="text" name="nameA" placeholder="<?php echo get_phrases(['enter', 'name']);?>" class="form-control" id="nameA" data-toggle="tooltip" data-field="<?php echo get_phrases(['name']) ?>" title="<?php echo get_phrases(['name','arabic']) ?>" >  
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
        $('.ajaxForm')[0].reset();        
        $('#lists-modal').modal('hide');
        $('#listsList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";
    

        $('#listsList').DataTable({ 
            lengthChange: true,
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>{
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                <?php } if($hasExportAccess){ ?>{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                }
            <?php }?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'settings/getLists',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
             },
             { data: 'table_titleE' },
             { data: 'nameE' },
             { data: 'button'}
          ],
            rowGroup: {
                dataSrc: 'table_titleE',
                className: 'bg-green h6 text-white'
            },
        });

        $('#picture').on('change', function () {
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
            imgPreview($(this), fileExtension);
        }); 

        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');
            
            $('#list_id').val('').trigger('change');
            $('#nameE').val('');
            $('#nameA').val('');

            $('#listsModalLabel').text('<?php echo get_phrases(['add', 'list']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('.modal_action').prop('disabled', false);
            $('#lists-modal').modal('show');
        });

        $('#listsList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            //var id = data.split('##');
            var submit_url = _baseURL+'settings/getListsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#lists-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('update');
                    $('#listsModalLabel').text('<?php echo get_phrases(['update', 'list']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');
                    $('.modal_action').prop('disabled', false);
                    
                    $('#list_id').val(data.list_id).trigger('change');
                    $('#nameE').val(data.nameE);
                    $('#nameA').val(data.nameA);
                   

                },error: function() {

                }
            });   

        });
        // delete lists
        $('#listsList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            //var id = data.split('##');
            
            var submit_url = _baseURL+"settings/deleteLists/"+id;
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
                            $('#listsList').DataTable().ajax.reload(null, false);
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