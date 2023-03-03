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
                                <?php foreach($supplier_list as $value){?>
                                <option value="<?php echo $value->id;?>"><?php echo $value->code_no.'-'.$value->nameE;?></option>
                               <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2 text-right">
                        <div class="form-group">
                            <strong><?php echo get_phrases(['date']);?></strong>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>" required>
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
            <?php echo form_open_multipart('inventory/add_supplier_payment', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body" id="printContent">
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="action" id="action" value="add">
                <input type="hidden" name="due_total" id="due_total" value="">
                <input type="hidden" name="receipt" id="receipt" value="">
                <input type="hidden" name="due" id="due" value="">
                <input type="hidden" name="supplier_id" id="supplier_id" value="">
                <input type="hidden" name="paid_status" id="paid_status" value="1">
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['supplier']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_supplier_id" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_store_id"></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['receive','voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_receive_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['receive','date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_receive_date"></div>                        
                    </div>
                </div>
                <div class="row form-group payment_info">
                    <label for="voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['voucher','no'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="voucher_no"  type="text" class="form-control" id="voucher_no" placeholder="<?php echo get_phrases(['voucher','no'])?>" autocomplete="off" required >
                    </div>
                    <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="date"  type="text" class="form-control datepicker1" id="date" placeholder="<?php echo get_phrases(['date'])?>"  value="<?php echo date('d/m/Y')?>" autocomplete="off" readonly>
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
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['first', 'payment']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receipt" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['first', 'due']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_due" ></div>                        
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo ucwords(get_phrases(['already', 'paid'])) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="paid_amount" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['remaining','amount']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="total_due" ></div>                        
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-12">                        
                        <table class="table table-bordered table-striped" id="payment_table">
                            <thead>
                                <th><?php echo get_phrases(['payment', 'method'])?></th>
                                <th><?php echo get_phrases(['amount'])?></th>
                                <th><?php echo get_phrases(['action'])?></th>
                            </thead>
                            <tbody id="payment_method_div">
                               
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row form-group payment_details">
                    <label for="print_voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['payment','voucher','no'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="print_voucher_no" ></div>
                    </div>
                    <label for="print_date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['payment','date'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="print_date" ></div>
                    </div>
                </div>
                <div class="row payment_details">
                    <div class="col-sm-12">                        
                        <div id="payment_preview"></div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo ucwords(get_phrases(['current', 'payment'])) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="current_paid" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['current', 'due']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="current_due" ></div>                        
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" id="payment" class="btn btn-success"><?php echo get_phrases(['payment']);?></button>
                <button type="button" id="print" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
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
            if(supplier_id =='' ){
                toastr.warning('<?php echo get_notify('Select_supplier'); ?>');
                return
            }
            if(date =='' ){
                toastr.warning('<?php echo get_notify('Select_date_range'); ?>');
                return
            }
    
            var submit_url = _baseURL+"reports/inventory/get_supplier_payment"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, supplier_id:supplier_id, date_range:date},
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

    function preview(obj){
            //e.preventDefault();
            //$('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var receive_id = $(obj).attr('receive-id');
            var id = $(obj).attr('data-id');
            var purchase_id = $(obj).attr('purchase-id');
            var submit_url = _baseURL+'inventory/getItemReceiveDetailsById/'+purchase_id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#itemReceiveDetails-modal').modal('show');
                    $('#itemReceiveDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

                    $('#itemReceiveDetails_supplier_id').text(data.supplier_name);
                    $('#itemReceiveDetails_store_id').text(data.store_name);

                    $('#itemReceiveDetails_receive_voucher_no').text(data.receive_voucher_no);
                    $('#itemReceiveDetails_receive_date').text(data.receive_date);                    
                    $('#itemReceiveDetails_vat').text(data.vat);                    
                    $('#itemReceiveDetails_due').text(data.due);                    
                    $('#itemReceiveDetails_receipt').text(data.receipt);                    
                    //$('#itemReceiveDetails_payment_method').text(data.payment_method);                    
                    $('#itemReceiveDetails_receive_sub_total').text((data.receive_sub_total)?data.receive_sub_total:0);
                    $('#itemReceiveDetails_receive_grand_total').text((data.receive_grand_total)?data.receive_grand_total:0);

                    $('.payment_info').hide();  
                    $('#payment_table').hide();  
                    $('#payment').hide();  

                    get_item_details(purchase_id);

                    $('.payment_details').show();  
                    $('#print').show();  
                    get_payment_details(id);

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

    function get_payment_details(id){

        if(id !='' ){
            var submit_url = _baseURL+'inventory/getPaymentDetailsById';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'json',
                data: {'csrf_stream_name':csrf_val, 'id':id },
                success: function(data) {
                    $('#paid_amount').html(data.previous_paid);
                    $('#total_due').html(data.previous_due);
                    $("#current_paid").html(data.paid_amount);
                    $("#current_due").html(data.due);
                    $('#print_voucher_no').html(data.voucher_no);
                    $('#print_date').html(data.paid_date);

                    $('#payment_preview').html(data.html);
                }
            });
        } else {
            $('#payment_preview').html('');
        }
    }

</script>