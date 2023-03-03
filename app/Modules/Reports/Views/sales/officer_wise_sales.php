<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb" class="order-sm-last p-0">
                            <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                                <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle; ?></a></li>
                                <li class="breadcrumb-item active"><?php echo $title; ?></li>
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
                            <strong><?php echo get_phrases(['date']); ?></strong> <i class="text-danger">*</i>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input type="text" name="date" id="date" class="form-control reportrange1" required>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1 userIBtn"><?php echo get_phrases(['filter']); ?></button>
                            <button type="button" class="btn btn-warning rounded-pill mt-1" onclick="reset_table()"><?php echo get_phrases(['reset']); ?></button>
                        </div>
                    </div>
                </div>

                <div class="row hidden" id="printC">
                    <div class="col-md-12">
                        <div id="results"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var showCallBackData = function() {
        $('#id').val('');
        $('#action').val('add');
        $('.ajaxForm')[0].reset();
        $('#invoices-modal').modal('hide');
        $('#invoicesList').DataTable().ajax.reload(null, false);
    }

    function reset_table() {
        location.reload();
    }

    $(document).ready(function() {
        "use strict";
        $('option:first-child').val('').trigger('change');

        $('.userIBtn').on('click', function(e) {
            e.preventDefault();

            var date = $('#date').val();
            if (date == '') {
                toastr.warning('<?php echo get_notify('Select_date'); ?>');
                return
            }

            var submit_url = _baseURL + "reports/sales/officer_wise_sales_table";
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {
                    'csrf_stream_name': csrf_val,
                    date: date,
                },
                dataType: 'JSON',
                success: function(res) {
                    $("#printC").removeClass("hidden");
                    $('#results').html('');
                    $('#results').html(res.data);

                }
            });
        });



    });
</script>