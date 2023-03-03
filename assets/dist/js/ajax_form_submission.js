//Form Submition
function ajaxSubmit(e, form) {
    e.preventDefault();
  
    var action = form.attr('action');
    var CallBackFunction = eval(form.attr('data'));
    var form2 = e.target;
    var data = new FormData(form2);
    $.ajax({
        type: "POST",
        url: action,
        processData: false,
        contentType: false,
        dataType: 'json',
        data: data,
        success: function(response)
        {
            // console.log(response);
            
            $('.actionBtn').prop('disabled', false);
            if(response.success==true) {
                toastr.success(response.message, response.title);
                if (typeof CallBackFunction == 'function') {
                    if(response.hasOwnProperty('data')){
                        CallBackFunction(response.data);
                    }else{
                        CallBackFunction();
                    }
                }
            }else if(response.success=='exist'){
                toastr.warning(response.message, response.title);
            }else{
                toastr.error(response.message, response.title);
            }
        }
    });
}