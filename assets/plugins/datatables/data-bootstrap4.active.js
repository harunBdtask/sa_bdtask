(function($) {
    "use strict";
    var tableBootstrap4Style = {
        initialize: function() {
            this.bootstrap4Styling();
            this.bootstrap4Modal();
            this.print();
        },
        bootstrap4Styling: function() {
            $('.bootstrap4-styling').DataTable();
        },
        bootstrap4Modal: function() {
            $('.bootstrap4-modal').DataTable({
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Details for ' + data[0] + ' ' + data[1];
                            }
                        }),
                        renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                            tableClass: 'table'
                        })
                    }
                }
            });
        },
        print: function() {
            var table = $('#example').DataTable({
                lengthChange: false,
                buttons: [{
                        extend: 'copyHtml5',
                        text: '<i class="far fa-copy fa-2x text-warning"></i>',
                        titleAttr: 'Copy',
                        className: 'btn-light'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="far fa-file-excel fa-2x text-info"></i>',
                        titleAttr: 'Excel',
                        className: 'btn-light'
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="far fa-file-alt fa-2x text-success"></i>',
                        titleAttr: 'CSV',
                        className: 'btn-light'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="far fa-file-pdf fa-2x text-danger"></i>',
                        titleAttr: 'PDF',
                        className: 'btn-light'
                    }
                ]

            });

            table.buttons().container()
                .appendTo('#example_wrapper .col-md-6:eq(0)');
        }

    };
    // Initialize
    $(document).ready(function() {
        "use strict"; // Start of use strict
        tableBootstrap4Style.initialize();
    });

}(jQuery));