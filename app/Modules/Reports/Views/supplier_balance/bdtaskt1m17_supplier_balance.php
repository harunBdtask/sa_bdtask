<div class="row">
    <div class="col-sm-12">
        <?php //if($permission->method('user_income_reports', 'create')->access()){ ?>
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
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i>Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-2 text-right">
                        <div class="form-group">
                            <strong><?php echo get_phrases(['supplier']);?></strong>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select name="supplier_id" id="supplier_id" class="form-control custom-select" required>
                                <option value="">Select</option>
                                <option value="0"><?php echo get_phrases(['all','supplier']);?></option>
                                <?php foreach($supplier_list as $value){?>
                                <option value="<?php echo $value->id;?>"><?php echo $value->code_no.'-'.$value->nameE;?></option>
                               <?php }?>
                            </select>
                        </div>
                    </div><!-- 
                    <div class="col-sm-2 text-right">
                        <div class="form-group">
                            <strong><?php //echo get_phrases(['date']);?></strong>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php //echo get_phrases(['select', 'date']);?>" required>
                        </div>
                    </div> -->
                    <div class="col-sm-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1 userIBtn"><?php echo get_phrases(['filter']);?></button>
                        </div>
                    </div>
                </div>

                <div class="row" id="printC">
                    <div class="col-md-12">
                        <div id="results"></div>
                    </div>
                </div>
            </div>

        </div>
        <?php //}else{ 
        /* <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong class="fs-20 text-danger"><?php echo get_phrases(['you_do_not_have_permission_to_access_please_contact_with_administrator']);?></strong>
                    </div>
                </div>
            </div>
        </div>*/
         //} ?>
    </div>
</div>


<script type="text/javascript">

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#invoices-modal').modal('hide');
        $('#invoicesList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');

         // get patient info by ID
        $('.userIBtn').on('click', function(e){
            e.preventDefault();

            var supplier_id = $('#supplier_id').val();
            /*var date = $('#reportrange1').val();
            if(supplier_id =='' ){
                toastr.warning('<?php //echo get_notify('Select_supplier'); ?>');
                return
            }
            if(date =='' ){
                toastr.warning('<?php //echo get_notify('Select_date_range'); ?>');
                return
            }*/
    
            var submit_url = _baseURL+"reports/inventory/get_supplier_balance"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, supplier_id:supplier_id},//, date_range:date
                dataType: 'JSON',
                success: function(res) {
                    $('#results').html('');
                    $('#results').html(res.data);
                    $('#title').text('');
                    $('#title').text(date);
                }
            });  
        });


        
    });

</script>