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
                            <strong><?php echo get_phrases(['receive','date']);?></strong>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>" required>
                        </div>
                    </div>
                    <div class="col-sm-2 text-right">
                        <div class="form-group">
                            <strong><?php echo get_phrases(['supplier']);?></strong>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select name="supplier_id" id="supplier_id" class="form-control custom-select" required>
                                <option value="">Select</option>
                                <?php foreach($supplier_list as $value){?>
                                <option value="<?php echo $value->id;?>"><?php echo $value->code_no.'-'.$value->nameE;?></option>
                               <?php }?>
                            </select>
                        </div>
                    </div>
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


<!-- item receive modal button -->
<div class="modal fade bd-example-modal-xl" id="itemReceiveDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemReceiveDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printContent">
                
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['from','supplier']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_supplier_id" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['to','store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_store_id"></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_receive_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_receive_date"></div>                        
                    </div>
                </div>

                <div id="item_details_preview"></div>

                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['sub', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receive_sub_total" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['vat']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_vat" ></div>                        
                    </div>
                </div>
                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['grand', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receive_grand_total" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['total', 'paid']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receipt" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['total', 'due']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_due" ></div>                        
                    </div>
                </div>
                <!-- <div class="row form-group">
                    <label class="col-sm-10 text-right font-weight-600"><?php //echo get_phrases(['payment','method']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_payment_method" ></div>
                        
                    </div>
                </div> -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <?php if($permission->method('journal_voucher', 'read')->access() ==='9999999'){ ?>
                    <button type="button" class="btn btn-info viewJV" id="journal_voucher" data-id=""><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']); ?></button>
                <?php } ?><!-- 
                <button type="button" class="btn btn-success" onclick="printContent('printContent')"><?php //echo get_phrases(['print']);?></button> -->
                
            </div>
            
        </div>
    </div>
</div>


<!-- view voucher details -->
<div class="modal fade bd-example-modal-lg" id="jv-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="detailsModalLabel"><?php echo get_phrases(['view', 'voucher', 'details']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="viewDetails">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
        </div>
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
            var date = $('#reportrange1').val();
            /*if(supplier_id =='' ){
                toastr.warning('<?php //echo get_notify('Select_supplier'); ?>');
                return
            }*/
            if(date =='' ){
                toastr.warning('<?php echo get_notify('Select_date_range'); ?>');
                return
            }
    
            var submit_url = _baseURL+"reports/inventory/get_supplier_aging"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, supplier_id:supplier_id, date_range:date},
                dataType: 'JSON',
                success: function(res) {
                    $('#results').html('');
                    $('#results').html(res.data);
                    $('#title').text('');
                    $('#title').text('<?php echo get_notify('debt_aging_of_supplier_for_'); ?>'+date);
                }
            });  
        });


        $('.viewJV').on('click', function(e){
            e.preventDefault();
            var VNo = $(this).attr('data-id');
            $('#jv-modal').modal('show');
            var submit_url = _baseURL+"account/vouchers/getVoucherDetails/"+VNo; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(response) {
                    $('#viewDetails').html('');
                    $('#viewDetails').html(response.data);
                }
            });  
        });
        
    });

    function preview(obj){
        //e.preventDefault();
        //$('.ajaxForm').removeClass('was-validated');
        //$('#thumbpic').next('span').text('');
        
        var id = $(obj).attr('data-id');
        var submit_url = _baseURL+'inventory/getItemReceiveDetailsById/'+id;

        $.ajax({
            type: 'POST',
            url: submit_url,
            dataType : 'JSON',
            data: {'csrf_stream_name':csrf_val},
            success: function(data) {
                $('#itemReceiveDetails-modal').modal('show');
                $('#itemReceiveDetailsModalLabel').text('<?php echo get_phrases(['receive','details']);?>');

                $('#itemReceiveDetails_supplier_id').text(data.supplier_name);
                $('#itemReceiveDetails_store_id').text(data.store_name);

                $('#journal_voucher').attr('data-id', data.receive_voucher_no);
                $('#itemReceiveDetails_receive_voucher_no').text(data.receive_voucher_no);
                $('#itemReceiveDetails_receive_date').text(data.receive_date);                    
                $('#itemReceiveDetails_vat').text(data.vat);                    
                $('#itemReceiveDetails_due').text(data.due);                    
                $('#itemReceiveDetails_receipt').text(data.receipt);                    
                //$('#itemReceiveDetails_payment_method').text(data.payment_method);                    
                $('#itemReceiveDetails_receive_sub_total').text((data.receive_sub_total)?data.receive_sub_total:0);
                $('#itemReceiveDetails_receive_grand_total').text((data.receive_grand_total)?data.receive_grand_total:0);

                get_item_details(id);

            },error: function() {

            }
        });   

    }

    
    function get_item_details(purchase_id){

        if(purchase_id !='' ){
            var submit_url = _baseURL+'inventory/getItemPricingDetailsById';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'purchase_id':purchase_id },
                success: function(data) {
                    $('#item_details_preview').html(data);
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }


</script>