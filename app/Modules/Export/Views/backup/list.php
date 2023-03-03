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
                            <a href="<?php echo base_url(); ?>/data_export/sql_backup" class="btn btn-success btn-sm mr-1 ml-2" onclick="button_disabled(this)"><?php echo get_phrases(['create', 'backup']);?></a>
                        <?php } ?>
                       <a href="<?php echo base_url(); ?>/data_export/sql_backup" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="backupList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['file','name']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
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


<script type="text/javascript">

    function button_disabled(obj){
        $(obj).addClass('disabled');
    }

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();
        $('#branch-modal').modal('hide');
        $('#backupList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";
    
        $('#backupList').DataTable({ 
             responsive: true,
             lengthChange: false,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [3] },
            ],
            dom: "<'row'<'col-md-6'B><'col-md-6'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Backup_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Backup_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Backup_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Backup_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1]
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'data_export/getBackup',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'file_name' },
             { data: 'created_dt' },
             { data: 'button'}
          ],
        });

        $('#picture').on('change', function () {
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
            imgPreview($(this), fileExtension);
        }); 

        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#branch_name').val('');
            $('#branchModalLabel').text('<?php echo get_phrases(['add', 'branch']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('#branch-modal').modal('show');
        });

        $('#backupList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'data_export/getBackupById/'+id;

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
                    $('#branch_name').val(data.nameE);
                    $('#branch_nameA').val(data.nameA);

                },error: function() {

                }
            });   

        });
        // delete branch
        $('#backupList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"data_export/deleteBackup/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, 'Backup Record');
                            $('#backupList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, 'Backup Record');
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });
</script>