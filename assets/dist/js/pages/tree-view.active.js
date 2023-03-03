$(document).ready(function () {
    "use strict"; // Start of use strict
    $('#html').jstree();
    // ajax demo
    $('#ajax').jstree({
        'core': {
            'data': {
                "url": "http://localhost/ci_4_hmvc/account/accounts/test",
                "dataType": "json" // needed only if you do not supply JSON headers
            }
        }
    });
    // lazy demo
    $('#lazy').jstree({
        'core': {
            'data': {
                "url": "http://localhost/ci_4_hmvc/account/accounts/test",
                "data": function (node) {
                    return {"id": node.id};
                }
            }
        }
    });

    $(document).on('click', '.evt_data', function(){
        alert($(this).attr('data-id'));
    });

// html demo

});