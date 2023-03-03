<div class="row">
    <div class="col-sm-12">
        <?php if($permission->method('doctor_revenue_report', 'create')->access() || $permission->method('doctor_revenue_report', 'read')->access()){ ?>
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
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <input type="text" name="date_range" id="reportrange1" class="form-control" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>" required>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
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
<!-- view all invoices modal -->
<div class="modal fade bd-example-modal-xl" id="viewVoucherModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="viewVoucherModalLabel"><?php echo get_phrases(['view', 'all', 'invoice']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
               <div class="row">
                   <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo get_phrases(['voucher', 'no']);?></th>
                                        <th><?php echo get_phrases(['patient', 'name']);?></th>
                                        <th><?php echo get_phrases(['total', 'amount']);?></th>
                                        <th><?php echo get_phrases(['total', 'discount']);?>%</th>
                                        <th><?php echo get_phrases(['discount', 'amount']);?></th>
                                        <th><?php echo get_phrases(['total', 'vat']);?></th>
                                        <th><?php echo get_phrases(['total', 'paid']);?></th>
                                        <th><?php echo get_phrases(['total', 'due']);?></th>
                                        <th><?php echo get_phrases(['created', 'by']);?></th>
                                        <th><?php echo get_phrases(['created', 'date']);?></th>
                                    </tr>
                                </thead>
                                <tbody id="viewAllResult">
                                    
                                </tbody>
                            </table>
                        </div>
                   </div>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
            
        </div>
    </div>
</div>

<!-- view voucher details modal -->
<div class="modal fade bd-example-modal-xl" id="viewDModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="viewDModalLabel"><?php echo get_phrases(['view', 'details']);?></h5>
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

    $(document).ready(function() { 
       "use strict";

        // get patient info by ID
        $('.userIBtn').on('click', function(e){
            e.preventDefault();

            var date = $('#reportrange1').val();
            var submit_url = _baseURL+"reports/management/getServicesRevenue"; 
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, date_range:date},
                dataType: 'JSON',
                success: function(res) {
                    $('#results').html('');
                    $('#results').html(res.data);
                    $('#title').text('');
                    $('#title').text(date);
                }
            });  
        });

        // service revenue data export
        $(document).on('click', '.export', function(e){
            e.preventDefault();

            var date       = $('#reportrange1').val();
            var submit_url = _baseURL+"reports/management/exportServiceRevenue"; 
            if(date){
                preloader_ajax();
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, date_range:date},
                    dataType: 'JSON',
                    success: function(response) {
                        window.open(response.url, '_self');
                    }
                });  
            }else{
                alert('Please select the date range!');
            }
        });

        // view all invoices info
        $(document).on('click', '.allInvoices', function(e){
            e.preventDefault();
            $('#viewVoucherModal').modal('show');
            var date = $('#reportrange1').val();
            var doctorId = $(this).attr('data-doctor');
            var type = $(this).attr('data-type');
            //alert(date+doctorId+type);
            if(date && doctorId){
                var submit_url = _baseURL+"reports/management/getReportInvoices"; 
                preloader_ajax();
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, doctor_id:doctorId, date_range:date, type:type},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#viewAllResult').html('');
                        $('#viewAllResult').html(res.data);
                    }
                });  
            }else{
                alert('Wrong Invoice!')
            }
        });

        // view details invoice info
        $(document).on('click', '.viewDetails', function(e){
            e.preventDefault();
            $('#viewDModal').modal('show');
            var Id = $(this).attr('data-id');
            var type = $(this).attr('data-type');
            var typeId = type+'-'+Id;
            if(Id && type){
                var submit_url = _baseURL+"reports/management/getVoucherDetails"; 
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, voucherId:typeId},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#viewDetails').html('');
                        $('#viewDetails').html(res.data);
                    }
                });  
            }else{
                alert('Wrong Invoice!')
            }
        });

    });
</script>