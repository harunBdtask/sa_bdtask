
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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'region']);?></button>
                        <?php } ?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="zonesList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['region','name']);?></th>
                            <th><?php echo get_phrases(['districts']);?></th>
                            <th><?php echo get_phrases(['division']);?></th>
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
<!-- zone modal button -->
<div class="modal fade bd-example-modal-xl" id="zone-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="zonesModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('sale/zone/add_zones', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />
                <div class="row form-group">
                    <label for="zone_name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['region', 'name']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input type="text" name="zone_name" placeholder="<?php echo get_phrases(['enter', 'region', 'name']);?>" class="form-control" id="zone_name" required>
                    </div>
                </div>

                 <div class="row form-group">
                    <label for="districts" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['districts']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input type="text" name="districts" placeholder="<?php echo get_phrases(['enter', 'districts']);?>" class="form-control" id="districts" required>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="division" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['division']) ?> <i class="text-danger"></i></label>
                    <div class="col-sm-6">
                        <input type="text" name="division" placeholder="<?php echo get_phrases(['enter', 'division']);?>" class="form-control" id="division">
                    </div>
                </div>


                <div class="row form-group">
                    <label for="status" class="col-sm-2 col-form-label font-weight-600 custom-control custom-radio  text-right"><?php echo get_phrases(['status']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                       
                       <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="zoneactive" name="status" class="custom-control-input" value="1" checked>
                                            <label class="custom-control-label" for="zoneactive">Active</label>
                        </div>
                         <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="zoneinactive" name="status" class="custom-control-input" value="0">
                                            <label class="custom-control-label" for="zoneinactive">Inactive</label>
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


<!-- zone modal button -->
<div class="modal fade bd-example-modal-xl" id="zoneDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="zoneDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['region', 'name']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="zoneDetails_zone_name" ></div>
                        
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['districts']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="zoneDetails_districts" ></div>
                        
                    </div>
                </div>

                  <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['division']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="zoneDetails_division" ></div>
                        
                    </div>
                </div>

              

                <div class="row form-group">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['status']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="zoneDetails_status" ></div>
                        
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
        $('#zone-modal').modal('hide');
        $('#zonesList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";
    
        $('#zonesList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'sale/zone/getzoneDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#zoneDetails-modal').modal('show');
                    $('#zoneDetailsModalLabel').text('<?php echo get_phrases(['region','details']);?>');

                    $('#zoneDetails_zone_name').text(data.zone_name);
                    $('#zoneDetails_districts').text(data.districts);
                    $('#zoneDetails_division').text(data.division);
                    var status = (data.status == 1?'Active':'Inactive');
                     $('#zoneDetails_status').text(status);

                },error: function() {

                }
            });   

        });

        $('#zonesList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "asc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [1,2,3,4,5] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'zones_List-<?php echo date('Y-m-d');?>',
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
                    title : 'zones_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1,2,3,4]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'zones_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1,2,3,4]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'zones_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1,2,3,4]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'zones_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1,2,3,4 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'sale/zone/getzones',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'zone_name' },
             { data: 'districts' },
             { data: 'division' },
             { data: 'status' },
             { data: 'button'}
          ],
        });


        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');

            $('#zone_name').val('');
            $('#nameA').val('');            

            $('#zonesModalLabel').text('<?php echo get_phrases(['add', 'region']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('#zone-modal').modal('show');
        });

        $('#zonesList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'sale/zone/getzonesById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#zone-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('update');
                    $('#zonesModalLabel').text('<?php echo get_phrases(['update', 'region']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');
                    $('#zone_name').val(data.zone_name);
                    $('#districts').val(data.districts);
                    $('#division').val(data.division);
                    var status = data.status;
                    if(status == 1){
                        $("#zoneactive").prop('checked', true);
                        $("#zoneinactive").prop('checked', false);  
                    }else{
                        $("#zoneactive").prop('checked', false);
                        $("#zoneinactive").prop('checked', true);      
                    }
                    

                },error: function() {

                }
            });   

        });
        // delete zones
        $('#zonesList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"sale/zone/deletezones/"+id;
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
                            $('#zonesList').DataTable().ajax.reload(null, false);
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