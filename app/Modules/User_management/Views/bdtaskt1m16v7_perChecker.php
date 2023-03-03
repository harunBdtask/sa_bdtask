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
                       <a href="<?php echo previous_url();?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                
                <?php echo form_open('permission/checker/get_check_result', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
                <div class="row form-group">               
                    <div class="col-sm-3">
                        <label for="module" class="font-weight-600"><?php echo get_phrases(['module']) ?> </label>
                        <select name="module" id="module" class="custom-select form-control" required>
                            <option value=""></option>
                            <?php if(!empty($module_list)){ ?>
                                <?php foreach ($module_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>                                
                    <div class="col-sm-3">
                        <label for="sub_module" class="font-weight-600"><?php echo get_phrases(['sub','module']) ?> </label>
                        
                        <?php echo form_dropdown('sub_module','','','class="custom-select" id="sub_module" required="required"');?>
                    </div>                     
                    <div class="col-sm-3 results"><br>
                        Module Access (Logged in user): <span id="mod"></span>
                    </div>                  
                    <div class="col-sm-3 results"><br>
                        Sub Module Access (Logged in user): <span id="sub"></span>
                    </div>      
                </div>

                <div class="row form-group ">     

                    <div class="col-sm-4">
                        <label for="emp_id" class="font-weight-600"><?php echo get_phrases(['user']) ?> </label>
                        <select name="emp_id" id="emp_id" class="custom-select form-control" required>
                            <option value=""></option>
                            <?php if(!empty($users)){ ?>
                                <?php foreach ($users as $key => $value) {?>
                                    <option value="<?php echo $value->emp_id;?>"><?php echo $value->fullname;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>     
                    <div class="col-sm-2">
                        <br>
                        <button type="submit" class="btn btn-sm btn-success mt-2 actionBtn" ><?php echo get_phrases(['check']);?></button>
                        <button type="button" class="btn btn-sm btn-warning mt-2" onclick="reset_form()"><?php echo get_phrases(['reset']);?></button>
                    </div>               
                    <div class="col-sm-1 results"><br>
                        Create : <span id="create"></span>
                    </div>
                    <div class="col-sm-1 results"><br>
                        Read : <span id="read"></span>
                    </div>
                    <div class="col-sm-1 results"><br>
                        Update : <span id="update"></span>
                    </div>
                    <div class="col-sm-1 results"><br>
                        Delete : <span id="delete"></span>
                    </div>
                    <div class="col-sm-1 results"><br>
                        Print : <span id="print"></span>
                    </div>
                    <div class="col-sm-1 results"><br>
                        Export : <span id="export"></span>
                    </div>             

                </div>  

                <?php echo form_close();?>   
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function reset_form(){
        $('#emp_id').val('').trigger('change');
        $('#module').val('').trigger('change');
        $('#sub_module').val('').trigger('change');
        $('.results').hide();
    }

    var showCallBackData = function (response) {
        //$('#id').val('');        
        //$('#action').val('add');        
        //$('.ajaxForm')[0].reset();        
        //$('#item_request-modal').modal('hide');
        //$('#item_requestList').DataTable().ajax.reload(null, false);
        if(response){
            $('.results').show();
            $('#mod').html((response.mod=='1')?'<i class="text-success">Yes</i>':'<i class="text-danger">No</i>');
            $('#sub').html((response.sub=='1')?'<i class="text-success">Yes</i>':'<i class="text-danger">No</i>');
            $('#create').html((response.create=='1')?'<i class="text-success">Yes</i>':'<i class="text-danger">No</i>');
            $('#read').html((response.reade=='1')?'<i class="text-success">Yes</i>':'<i class="text-danger">No</i>');
            $('#update').html((response.updatee=='1')?'<i class="text-success">Yes</i>':'<i class="text-danger">No</i>');
            $('#delete').html((response.deletee=='1')?'<i class="text-success">Yes</i>':'<i class="text-danger">No</i>');
            $('#print').html((response.printe=='1')?'<i class="text-success">Yes</i>':'<i class="text-danger">No</i>');
            $('#export').html((response.exporte=='1')?'<i class="text-success">Yes</i>':'<i class="text-danger">No</i>');
        }
    }
    $(document).ready(function() { 
       "use strict";
        $('.results').hide();
        $('option:first-child').val('').trigger('change');
        $('#emp_id').val('<?php echo session('id'); ?>').trigger('change');
        // delete department
        $('.actionBtn').on('click', function(e){
            $('.results').hide();
        });
               
        $("#sub_module").select2({
            placeholder: '<?php echo get_phrases(['select', 'sub', 'module']);?>',
            data: []
        });

        $('#sub_module,#emp_id').on('select2:select', function(e){
            $('.results').hide();
        });

        $('#module').on('select2:select', function(e){
            e.preventDefault();
            var id = $(this).val();
            $("#sub_module").val('').trigger('change');
            $('.results').hide();
            var submit_url = _baseURL+"permission/checker/getSubModule/"+id;
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(data) {  
                    $("#sub_module").empty();
                    //$("#sub_module").select2();                   
                    $("#sub_module").select2({
                        placeholder: '<?php echo get_phrases(['select', 'sub', 'module']);?>',
                        data: data
                    });
                    var newOption = new Option('', '', true, true);
                    $('#sub_module').append(newOption).trigger('change');
                },error: function() {

                }
            });
              
        });
    });
</script>