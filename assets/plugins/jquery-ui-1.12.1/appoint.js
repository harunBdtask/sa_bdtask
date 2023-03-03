
  $(document).ready(function() { 
     "use strict";

      $('.real, .waiting, .on_call').sortable({
          connectWith: '.list-group-sortable-connected',
          stop  : function(event, ui){
              var id = ui.item.attr("id");
              var attr = ui.item.attr('data');
              var parent = ui.item.parent().attr('data');
              if (typeof attr !== typeof undefined && attr !== false) {
                  // var page_id_array = new Array();
                  // $('.test li').each(function(){
                  //  page_id_array.push($(this).attr("id"));
                  // });
                  if(attr==2 || attr==0){
                      $.ajax({
                          url:_baseURL+"doctors/screens/appointConvert/"+id,
                          type:"POST",
                          dataType:'JSON',
                          data: {'csrf_stream_name':csrf_val, convert_value:attr, parent:parent},
                          success:function(data)
                          {
                              if(data.success==true){
                                  toastr.success(data.message, data.title);
                              }else{
                                  toastr.warning(data.message, data.title);
                              }
                          }
                      });
                  }
              }
          }
      });
      
  });