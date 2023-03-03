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
                         <?php if($permission->method('salary_generate','create')->access()){?>
                       <a href="<?php echo base_url('human_resources/payroll/salary_generate')?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['salary','generate'])?></a>
                   <?php }?>
                     
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table display table-bordered table-striped table-hover custom-table" cellspacing="0" width="100%" id="SalarySheet">
                        <thead>
                            <tr>
                        <th><?php echo get_phrases(['sl']) ?></th>
                        <th><?php echo get_phrases(['month','of','salary']) ?></th>
                        <th><?php echo get_phrases(['date']) ?></th>
                        <th><?php echo get_phrases(['department']) ?></th>
                        <th><?php echo get_phrases(['generated','by']) ?></th>
                        <th><?php echo get_phrases(['action']) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                          
                          
                        </tbody>
                         
                    </table>
                    
                </div>
            </div> 
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
    "use strict";
    var mydatatable = $('#SalarySheet').DataTable({
        responsive: true,
        dom: "<'row'<'col-md-6'B><'col-md-6'f>>rt<'bottom'ip><'clear'>",
        "aaSorting": [
            [2, "asc"]
        ],
        "columnDefs": [{
                "bSortable": false,
                "aTargets": [0, 1, 3, 4]
            },

        ],
        'processing': true,
        'serverSide': true,

        'lengthMenu': [
            [15, 25, 50, 100, 250, 500, -1],
            [15, 25, 50, 100, 250, 500, "All"]
        ],

        buttons: [ {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'Salary Genarate List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },{
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
            'url': _baseURL + 'human_resources/payroll/get_salary_sheet',
            "data": function(data) {
                data.csrf_stream_name = csrf_val;
            },
        },
        'columns': [
            { data: 'sl' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'gdate'
            },
            {
                data: 'date'
            },
            {
                data: 'department_name'
            },
            {
                data: 'generate_by'
            },
            {
                data: 'button'
            },
        ],

    });

});
</script>

 