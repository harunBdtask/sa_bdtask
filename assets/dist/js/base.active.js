var csrf_val = $('#CSRF_TOKEN').val();
var pathname = window.location.pathname.split ('/') [1];
var pathurl ='';
if(pathname !=''){
	pathurl = pathname+'/';
}

var _baseURL;
_baseURL = window.location.origin + '/' + pathurl;

// Get Max column Id
// table string, column string, Id(where show result), prefix null
function getMAXID(table, column, Id, prefix=null){
    var value = '';
    $.ajax({
        type: 'POST',
        url: _baseURL+'auth/getMaxId/'+table+'/'+column,
        data: {'csrf_stream_name':csrf_val},
        dataType: 'JSON',
        async : false,
        success: function(response) {
            var Data;
            if(prefix !=null && prefix !=''){
                Data = prefix+response.ID;
            }else{
                Data = response.ID;
            }
            $('#'+Id).val(Data);
            value = Data;
        }
    });
    return value;
}

// table tr background color change button onclick id = tbody ID, obj= this button
function onclick_change_bg(id, obj, color){
    $(id+' tr').each(function(){
        $(this).css('background-color','');
    });
    $(obj).closest('tr').css('background-color', color);
}

// function numberWithCommas(x) {
//     return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
// }

function checkLoggedin(){
    $.ajax({
        type: 'GET',
        url: _baseURL+'auth/loggedin',
        dataType : 'text',
        data: {'csrf_stream_name':csrf_val},
        success: function(data) {
            if(data !='OK' ){
                window.location.reload();
            }

        }
    });   
}
function printDiv(divName) {


    var restorepage = $('body').html();
    var printcontent = $('#' + divName).clone();
    $('body').empty().html(printcontent);
    window.print();
    $('body').html(restorepage);

}

function select2_readonly(id,val){
    $("#"+id).on('select2:select',function(){               
        $(this).val(val).trigger('change');
    });
}

function select2_readonly_off(id){
    $("#"+id).off('select2:select');
}