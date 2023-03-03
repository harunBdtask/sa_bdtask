<div class="row">
    <div class="col-md-12 col-md-12">
        <div class="card ">

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
                      
                     
                    </div>
                </div>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover custom-table text-nowrap" cellspacing="0" width="100%" id="SalaryPayment">
                        <thead>
                            <tr>
                        <th><?php echo get_phrases(['sl']) ?></th>
                        <th><?php echo get_phrases(['employee']) ?></th>
                        <th><?php echo get_phrases(['salary','month']) ?></th>
                        <th><?php echo get_phrases(['total','salary']) ?></th>
                        <th><?php echo get_phrases(['total','working','hours']) ?></th>
                        <th><?php echo get_phrases(['total','working','day']) ?></th>
                        <th><?php echo get_phrases(['date']) ?></th>
                        <th><?php echo get_phrases(['status']) ?></th>
                        <th><?php echo get_phrases(['paid','by']) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                          
                          
                        </tbody>
                         
                    </table>
                    
                </div>
            </div> 

        </div>
    </div>


     <div class="modal fade" id="paymentModal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                     <h3 class="modal-title"><?php echo get_phrases(['pay','now']) ?></h3>
                    <a href="#" class="close  md-close" data-dismiss="modal">&times;</a>
                   
                </div>
                
                <div class="modal-body">
                   
               <?php echo form_open('human_resources/payroll/pay_confirm', array('class' => 'form-vertical')) ?>
            <div class="panel-body">
                  <input name="emp_sal_pay_id" id="salType" type="hidden" value="">
                 <div class="form-group row">
                    <label for="employee_id" class="col-sm-3 col-form-label"><?php echo get_phrases(['employee']) ?> </label>
                    <div class="col-sm-9">
                        <input type="text" name="empname" class="form-control" id="employee_name" value="" readonly>
                        <input type="hidden" name="employee_id" class="form-control" id="employee_id" value="">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="total_salary" class="col-sm-3 col-form-label"><?php echo get_phrases(['total','salary']) ?> </label>
                    <div class="col-sm-9">
                        <input type="text" name="total_salary" class="form-control" id="total_salary" value="" readonly>
                    </div>
                </div> 

               <div class="form-group row">
                    <label for="total_working_hours" class="col-sm-3 col-form-label"><?php echo get_phrases(['total','working','hours']) ?> </label>
                    <div class="col-sm-9">
                        <input type="text" name="total_working_minutes" class="form-control" id="total_working_minutes" value="" readonly>
                    </div>
                </div> 
                 <div class="form-group row">
                    <label for="total_working_day" class="col-sm-3 col-form-label"><?php echo get_phrases(['total','working','day']) ?> </label>
                    <div class="col-sm-9">
                        <input type="text" name="working_period" class="form-control" id="working_period" value="" readonly>
                         
                    </div>
                </div> 
                <div class="form-group row">
                    <label for="salary_month" class="col-sm-3 col-form-label"><?php echo get_phrases(['salary','month']) ?> </label>
                    <div class="col-sm-9">
                       
                         <input type="text" name="salary_month" class="form-control" id="salary_month" value="" readonly>
                    </div>
                </div> 


              
            </div>
            
                </div>

                <div class="modal-footer">
                    
                    <a href="#" class="btn btn-danger" tabindex="5" data-dismiss="modal">Close</a>
                    
                    <input type="submit" tabindex="6" class="btn btn-success" value="Submit">
                </div>
                <?php echo form_close() ?>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>

<script type="text/javascript">
    $(document).ready(function() {
    "use strict";
    var mydatatable = $('#SalaryPayment').DataTable({
        responsive: true,
        dom: "<'row'<'col-md-6'B><'col-md-6'f>>rt<'bottom'ip><'clear'>",
        "aaSorting": [
            [6, "asc"]
        ],
        "columnDefs": [{
                "bSortable": false,
                "aTargets": [0, 1, 2, 3, 4, 5, 7, 8]
            },

        ],
        'processing': true,
        'serverSide': true,


        'lengthMenu': [
            [15, 25, 50, 100, 250, 500, -1],
            [15, 25, 50, 100, 250, 500, "All"]
        ],

        buttons: [{
                extend: 'copyHtml5',
                text: '<i class="far fa-copy"></i>',
                titleAttr: 'Copy',
                title: " salry List",
                className: 'btn-light'
            },
            {
                extend: 'excelHtml5',
                text: '<i class="far fa-file-excel"></i>',
                titleAttr: 'Excel',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                },
                title: "salry List",
                className: 'btn-light'
            },
            {
                extend: 'csvHtml5',
                text: '<i class="far fa-file-alt"></i>',
                titleAttr: 'CSV',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                },
                title: "salry List",
                className: 'btn-light'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="far fa-file-pdf"></i>',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                },
                title: "salry List",
                titleAttr: 'PDF',
                className: 'btn-light'
            }
        ],
        'serverMethod': 'post',
        'ajax': {
            'url': _baseURL + 'human_resources/payroll/get_salary_paymentlist',
            "data": function(data) {
                data.csrf_stream_name = csrf_val
            },
        },
        'columns': [
            { data: 'sl' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'employee'
            },
            {
                data: 'salary_month'
            },
            {
                data: 'total_salary'
            },
            {
                data: 'total_working_minutes'
            },
            {
                data: 'working_period'
            },
            {
                data: 'payment_date'
            },
            {
                data: 'payment_due'
            },
            {
                data: 'paid_by'
            },
        ],

    });

});

// function payment_modal(salpayid, employee_id, TotalSalary, WorkHour, Period, salary_month) {
//     var sal_id = salpayid;
//     var employee_id = employee_id;
//     var base_url = $("#base_url").val();
//     var csrf_test_name = $('[name="csrf_test_name"]').val();
//     $.ajax({
//         url: _baseURL + "payroll/employee_paydata/",
//         method: 'post',
//         dataType: 'json',
//         data: {
//             'sal_id': sal_id,
//             'employee_id': employee_id,
//             'totalamount': TotalSalary,
//             'csrf_stream_name' : csrf_val,
//         },
//         success: function(data) {
//             document.getElementById('employee_name').value = data.Ename;
//             document.getElementById('employee_id').value = data.employee_id;
//             document.getElementById('salType').value = salpayid;
//             document.getElementById('total_salary').value = TotalSalary;
//             document.getElementById('total_working_minutes').value = WorkHour;
//             document.getElementById('working_period').value = Period;
//             document.getElementById('salary_month').value = salary_month;
//             $("#paymentModal").modal('show');
//         },
//         error: function(jqXHR, textStatus, errorThrown) {
//             alert('Error get data from ajax');
//         }

//     });


// }

</script>

