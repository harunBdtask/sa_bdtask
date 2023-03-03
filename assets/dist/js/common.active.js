"use strict";

var timeText = new Date();
$('#testtitle').prepend('<p style="font-size:10px">Print Date: '+timeText.toLocaleString()+'</p>');
$('#printContent').prepend('<p style="font-size:10px">Print Date: '+timeText.toLocaleString()+'</p>');
$('#printC').prepend('<p style="font-size:10px">Print Date: '+timeText.toLocaleString()+'</p>');

// notification
toastr.options = {
    closeButton: true,
    progressBar: true,
    showMethod: 'slideDown',
    timeOut: 4000
    // positionClass: "toast-top-left"
};

function getPDF(id){
    var HTML_Width = $("#"+id).width();
    var HTML_Height = $("#"+id).height();
    var top_left_margin = 15;
    var PDF_Width = HTML_Width+(top_left_margin*2);
    var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;
    var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;
    html2canvas($("#"+id)[0],{allowTaint:true}).then(function(canvas) {
        canvas.getContext('2d');
        // console.log(canvas.height+"  "+canvas.width);
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
        for (var i = 1; i <= totalPDFPages; i++) { 
            pdf.addPage(PDF_Width, PDF_Height);
            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
        }
        pdf.save("HTML-Document.pdf");
        $('.page-loader-wrapper').hide();
    });
};

// Image preview and formatting check before submit
function imgPreview(input, formats){
    var ext = input.val().split('.').pop().toLowerCase();
    if(ext!=''){
        if ($.inArray(ext, formats) == -1) {
            var mess = "Allow only "+formats.join(', ');
            input.next('label').html('<i class="text-danger">'+mess+'</i>');
            input.val('');
        }else{
            var file = input[0].files[0].name;
            if(ext=='jpeg' || ext=='jpg' || ext=='png' || ext=='gif'){
                var html = '<img class="mt-reverse-5" src="'+URL.createObjectURL(event.target.files[0])+'" height="30">';
            }else{
                var html = '<i class="ti-file fs-23"></i>'+file+'';
            }
            input.next('label').html(html);
        }
    }
}
// image preview in div
// preview is view id or class
function image_preview(input, formats, preview){
    var ext = input.val().split('.').pop().toLowerCase();
    if(ext!=''){
        if ($.inArray(ext, formats) == -1) {
            var mess = "Allow only "+formats.join(', ');
            $(preview).html('<i class="text-danger">'+mess+'</i>');
            input.val('');
        }else{
            var html = '<img class="img-fluid" src="'+URL.createObjectURL(event.target.files[0])+'" width="120" height="80" data-lity>';
            $(preview).html(html);
        }
    }
}

//print a div
function printContent(el){
    // let printElement = document.getElementById(el);
    // var printWindow = window.open('', 'PRINT', 'left=0,top=0,width=900,height=700,toolbar=0,scrollbars=0,status=0');
    // printWindow.document.write(document.documentElement.innerHTML);
    
    // setTimeout(() => { // Needed for large documents
    //   printWindow.document.body.style.margin = '0 0';
    //   printWindow.document.body.innerHTML = printElement.outerHTML;
    //   printWindow.document.close(); // necessary for IE >= 10
    //   printWindow.focus(); // necessary for IE >= 10*/
    //   printWindow.print();
    //   printWindow.close();
    // }, 800)
    var restorepage  = $('body').html();
    var printcontent = $('#' + el).clone();
    $('body').empty().html(printcontent);
    window.print();
    $('body').html(restorepage);
    location.reload();
}

// file delete
function deleteExportExcel(){
    $.ajax({
        type: 'POST',
        url: _baseURL+'auth/deleteExcel',
        data: {'csrf_stream_name':csrf_val},
        dataType: 'JSON',
        success: function(response) {
            
        }
    });
}

// Globally form submit ajax call
(function() {
   'use strict';
    window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
        
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                toastr.warning('Please fulfill all required fields.', 'Required Missing!');
                $('.actionBtn').prop('disabled', false);
            }else {
                $('.actionBtn').prop('disabled', true);
               ajaxSubmit(event, $(this));
            }
    
            form.classList.add('was-validated');
        }, false);
    });
  }, false);
})();

$(document).ready(function(){
    //All Pop up window should be closed if only hit on “Close” or “Exit button” 		
    $('.modal').attr("data-backdrop","static");
    //tableBasic.initialize();
    $('.custool').tooltip();
	 // select 2 dropdown 
    $("select.form-control:not(.form-control-sm):not([required])").select2({
        placeholder: "Select option",
        allowClear: true
    });
    $("select.form-control:not(.form-control-sm)[required]").select2({
        placeholder: "Select option",
        allowClear: false
    });

    $('#change_language').on('change', function(){
        var language = $(this).val();

        preloader_ajax();
        $.ajax({
            type: 'POST',
            url: _baseURL+'auth/changelangauge',
            data: {'csrf_stream_name':csrf_val, language:language},
            dataType: 'JSON',
            success: function(response) {
                window.location.reload();
                // if(response.success==true){
                //     toastr.success(response.message, response.title);
                //     setTimeout(function(){
                //         window.location.reload();
                //     }, 3000);
                // }else{
                //     toastr.warning(response.message, response.title);
                // }
            }
        });
    });

    $('#top_branch').on('change', function(){
        var top_branch = $(this).val();
        if(top_branch == null || top_branch == '' ){
            return false;
        }
        preloader_ajax();
        $.ajax({
            type: 'POST',
            url: _baseURL+'auth/changeBranch',
            data: {'csrf_stream_name':csrf_val, top_branch:top_branch},
            dataType: 'JSON',
            async: false,
            success: function(response) {
                if(response.success==true){
                    toastr.success(response.message, response.title);
                    //setTimeout(function(){
                        window.location.reload();
                    //}, 1000);
                }else{
                    toastr.warning(response.message, response.title);
                }
            }
        });
    });

	// success or error alert message empty
    setTimeout(function(){
        $(".alert:not(.doctor-card)").fadeOut("slow");
    }, 8000);

});

 // only number insert in a fields call this class onlyNumber
$(document).on('keypress', '.onlyNumber', function(evt){
     var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
         return false;

      return true;
});

$(document).on('draw.dt', function() {
     $('.custool').tooltip(); 
});

function change_top_branch(top_branch){
    if(top_branch == null || top_branch == '' ){
        return false;
    }
    var success = false;
    preloader_ajax();
    $.ajax({
        type: 'POST',
        url: _baseURL+'auth/changeBranch',
        data: {'csrf_stream_name':csrf_val, top_branch:top_branch},
        dataType: 'JSON',
        async: false,
        success: function(response) {
            if(response.success !=true ){
                toastr.warning(response.message, response.title);
            }
            success = response.success;
        }
    });

    return success;
}



function dobValidator(id, min, max) {
    if( id =='' || min =='' || max =='' ){
        return '';
    }
    var birthday = document.getElementById(id).value; // Don't get Date yet...
    var regexVar = /^([0-9]{4})\-([0-9]{2})\-([0-9]{2})$/; // add anchors; use literal
    var regexVarTest = regexVar.test(birthday); // pass the string, not the Date
    var userBirthDate = new Date(birthday.replace(regexVar, "$1-$2-$3")); // Use YYYY-MM-DD format
    var todayYear = (new Date()).getFullYear(); // Always use FullYear!!
    var cutOff19 = new Date(); // should be a Date
    cutOff19.setFullYear(todayYear - min); // ...
    var cutOff95 = new Date();
    cutOff95.setFullYear(todayYear - max);
    var dobErrMsg;
    if (!regexVarTest) { // Test this before the other tests
        dobErrMsg = "enter date of birth as yyyy-mm-dd";
    } else if (isNaN(userBirthDate)) {
        dobErrMsg = "date of birth is invalid";
    } else if (userBirthDate > cutOff19) {
        dobErrMsg = "you have to be older than "+min;
    } else if (userBirthDate < cutOff95) {
        dobErrMsg = "you have to be younger than "+max;
    } else {
        dobErrMsg = "";
    }
    return dobErrMsg; // Return the date instead of an undefined variable
}

