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
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="module-tab" data-toggle="pill" href="#module" role="tab" aria-controls="module" aria-selected="false"><?php echo get_phrases(['module', 'list']);?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="sub_module-tab" data-toggle="pill" href="#sub_module" role="tab" aria-controls="sub_module" aria-selected="false"><?php echo get_phrases(['module', 'menu']);?></a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo previous_url();?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <!-- module -->
                    <div class="tab-pane fade show active" id="module" role="tabpanel" aria-labelledby="module-tab">
                        <table id="moduleList" class="table display table-bordered table-striped table-hover compact" width="100%">
                            <thead>
                                <tr>
                                    <th># <?php echo get_phrases(['sl']);?></th>
                                    <th><?php echo get_phrases(['module', 'name', 'english']);?></th>
                                    <th><?php echo get_phrases(['action']);?></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                     <!-- sub module -->
                    <div class="tab-pane fade" id="sub_module" role="tabpanel" aria-labelledby="sub_module-tab">
                        <button class="btn btn-success addSubM mb-2"><i class="fa fa-plus"></i> <?php echo get_phrases(['add', 'menu']); ?></button>
                        <table id="subList" class="table display table-bordered table-striped table-hover compact" width="100%">
                            <thead>
                                <tr>
                                    <th># <?php echo get_phrases(['sl']);?></th>
                                    <th><?php echo get_phrases(['module', 'name']);?></th>
                                    <th><?php echo get_phrases(['menu', 'name', 'english']);?></th>
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
    </div>
</div>

<!-- module update modal button -->
<div class="modal fade bd-example-modal-md" id="moduleModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="moduleModalLabel"><?php echo get_phrases(['update', 'module']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('permission/modules/update', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="module_id" id="module_id" />

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['module', 'name', 'english']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="nameE" id="nameE" class="form-control" placeholder="<?php echo get_phrases(['enter', 'english', 'name']);?>" maxlength="100" required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['module', 'name', 'arabic']);?></label>
                            <input type="text" name="nameA" id="nameA" class="form-control" placeholder="<?php echo get_phrases(['enter', 'arabic', 'name']);?>" maxlength="100" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success modal_action actionBtn"><?php echo get_phrases(['update']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<!-- module update modal button -->
<div class="modal fade bd-example-modal-md" id="addSubModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="addSubModalLabel"><?php echo get_phrases(['add', 'menu']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('permission/modules/updateSub', 'class="needs-validation" id="addSubForm" novalidate="" data="addCallBackMenu"');?>
            <div class="modal-body">
                <input type="hidden" name="action" value="add" />
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['module', 'name']);?></label>
                            <?php echo form_dropdown('moduleId', '', '', 'class="form-control custom-select" id="moduleId" required');?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['menu', 'name', 'english']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="menuE" id="m_menuE" class="form-control" placeholder="<?php echo get_phrases(['enter', 'english', 'name']);?>" maxlength="100" required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['menu', 'name', 'arabic']);?></label>
                            <input type="text" name="menuA" id="m_menuA" class="form-control" placeholder="<?php echo get_phrases(['enter', 'arabic', 'name']);?>" maxlength="100" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['directory', 'name', 'english']);?></label>
                            <input type="text" name="directory" id="directory" class="form-control" placeholder="<?php echo get_phrases(['enter', 'directory', 'name']);?>" maxlength="50" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success modal_action actionBtn"><?php echo get_phrases(['add', 'menu']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<!-- module update modal button -->
<div class="modal fade bd-example-modal-md" id="subModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="subModalLabel"><?php echo get_phrases(['update', 'module', 'menu']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('permission/modules/updateSub', 'class="ajaxForm needs-validation" novalidate="" data="CallBackMenu"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="sub_id" id="sub_id" />
                <input type="hidden" name="action" value="update" />
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['menu', 'name', 'arabic']);?></label>
                            <input type="text" name="module" id="moduleName" class="form-control" readonly="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['menu', 'name', 'english']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="menuE" id="menuE" class="form-control" placeholder="<?php echo get_phrases(['enter', 'english', 'name']);?>" maxlength="100" required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['menu', 'name', 'arabic']);?></label>
                            <input type="text" name="menuA" id="menuA" class="form-control" placeholder="<?php echo get_phrases(['enter', 'arabic', 'name']);?>" maxlength="100" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success modal_action actionBtn"><?php echo get_phrases(['update']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<script type="text/javascript">
    var showCallBackData = function () {
        $('#moduleModal').modal('hide');
        $('#moduleList').DataTable().ajax.reload(null, false);
        $('.ajaxForm').removeClass('was-validated');
    }

    var CallBackMenu = function () {
        $('#subModal').modal('hide');
        $('#subList').DataTable().ajax.reload(null, false);
        $('.ajaxForm').removeClass('was-validated');
    }

    var addCallBackMenu = function () {
        $('#addSubModal').modal('hide');
        $('#addSubForm')[0].reset();
        $('#addSubForm').removeClass('was-validated');
        $('#subList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";
    

        $('#moduleList').DataTable({ 
            lengthChange: false,
            dom: "<'row'<'col-md-6'B><'col-md-6'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Module_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Module_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Module_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Module_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2]
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'permission/modules/getList',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'nameE' },
             { data: 'button'}
          ],
        });

        // module list
        $.ajax({
            type:'GET',
            url: _baseURL+'permission/modules/mList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#moduleId").select2({
                placeholder: '<?php echo get_phrases(['select', 'module', 'name']);?>',
                data: data
            });
        });

        // add sub module info
        $('.addSubM').on('click', function(){
            $('#addSubForm').removeClass('was-validated');
            $('#moduleId').val('').trigger('change');
            $('#addSubModal').modal('show');
            $('.modal_action').prop('disabled', false);
        });
        
        // update module info
        $('#moduleList').on('click', '.editAction', function(e){
            e.preventDefault();
            $('#ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'permission/modules/getInfoById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#moduleModal').modal('show');
                    $('#module_id').val(data.id);
                    $('#nameE').val(data.nameE);
                    $('#nameA').val(data.nameA);
                    $('.modal_action').prop('disabled', false);
                },error: function() {

                }
            });   

        });

        // update module info
        $('#subList').on('click', '.editAction', function(e){
            e.preventDefault();
            $('#ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'permission/modules/getMenuById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#subModal').modal('show');
                    $('#sub_id').val(data.id);
                    $('#moduleName').val(data.mnameE);
                    $('#menuE').val(data.nameE);
                    $('#menuA').val(data.nameA);
                    $('.modal_action').prop('disabled', false);
                },error: function() {

                }
            });   

        });

        // GET ALL MODULES
        $('#subList').DataTable({ 
            lengthChange: false,
            dom: "<'row'<'col-md-6'B><'col-md-6'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Module_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 1, 2, 3 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Module_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 1, 2, 3 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Module_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 1, 2, 3 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    charset: 'utf-8',
                    title : 'Module_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 1, 2, 3]
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'permission/modules/getModules',
               'data':{'csrf_stream_name':csrf_val}
            },
            'columns': [
                { data: 'mid' },
                { data: 'mName' },
                { data: 'nameE' },
                { data: 'button'}
            ],
            rowGroup: {
                dataSrc: 'mName',
                className: 'bg-green h5 text-white'
            },
            pageLength: '20'
        });
    });
</script>