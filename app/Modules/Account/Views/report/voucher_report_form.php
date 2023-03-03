<div class="row">
    <div class="col-sm-12">
        <?php if($permission->method('service_invoices', 'create')->access() || $permission->method('service_invoices', 'read')->access()){ ?>
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
                    <div class="col-md-7 col-sm-12">
                        <input type="hidden" name="action" id="action" value="add">
                        <div class="form-group">
                            <?php echo form_dropdown('voucher_id','','','class="custom-select" id="voucher_id"');?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-stripped table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="10%"><?php echo get_phrases(['account', 'code']);?></th>
                                         <th class="text-center" width="15%"><?php echo get_phrases(['name']);?></th>
                                        <th class="text-center" width="28%"><?php echo get_phrases(['description']);?></th>
                                       
                                        <th class="text-right" width="10%"><?php echo get_phrases(['debit']);?></th>
                                        <th class="text-right" width="10%"><?php echo get_phrases(['credit']);?></th>
                                        <th class="text-center" width="12%"><?php echo get_phrases(['created', 'date']);?></th>
                                        <th class="text-center" width="15%"><?php echo get_phrases(['created', 'by']);?></th>
                                    </tr>
                                </thead>
                                <tbody id="service_div">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th class="text-right" id="debitT"></th>
                                        <th class="text-right" id="creditT"></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
      
        </div>
        <?php } else if( session('branchId') == '' || session('branchId') == 0 ){ ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong class="fs-20 text-danger"><?php echo get_notify('you_have_to_switch_to_a_specific_branch');?></strong>
                    </div>
                </div>
            </div>
        </div>
        <?php }else{ ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong class="fs-20 text-danger"><?php echo get_phrases(['you_do_not_have_permission_to_access_please_contact_with_administrator']);?></strong>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
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

        // payment method list
        $.ajax({
            type:'GET',
            url: _baseURL+'account/reports/getAllVList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#voucher_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'voucher', 'no']);?>',
                data: data
            });
        });

         // get service list by appointment Id
        $('#voucher_id').on('change', function(e){
            e.preventDefault();

            var id = $(this).val();
            var submit_url = _baseURL+"account/reports/detailsByVId/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    $('#service_div').html(res.data);
                    $('#debitT').text(res.debit);
                    $('#creditT').text(res.credit);
                }
            });  
        });

    });
</script>