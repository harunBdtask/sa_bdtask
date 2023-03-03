 <link href="<?php echo base_url(); ?>/assets/plugins/vakata-jstree/dist/themes/default/style.min.css" rel="stylesheet">
 <style>
   .jstree-default .jstree-anchor {
     line-height: 34px;
     height: 34px;
   }

   .jstree-node {
     white-space: nowrap;
     padding-top: 5px;
   }

   .bal_opening,
   .bal_span {
     float: right;
     padding-left: 10px;
     padding-right: 10px;
     width: 140px;
     text-align: right;
     padding-right: 35px;
   }

   .bal_span_pre {
     float: right;
     text-align: right;
     width: 140px;
     padding-right: 35px;
   }
 </style>

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
           <div class="col-md-6">
       <div class="search sidebar-form">
                    <div class="search__inner tree-search">
                        <input id="treesearch" type="text" class="form-control search__text" placeholder="Tree Search..." autocomplete="off">
                        <i class="typcn typcn-zoom-outline search__helper" data-sa-action="search-close"></i>
                    </div>
                </div>
                </div>
                </div>
         <div class="row">
           <div class="col-md-6">
             <?php if ($permission->method('chart_of_account', 'read')->access()) { ?>
               <div id="html">
                 <ul>
                   <?php
                    $userModel = new \App\Modules\Account\Models\Bdtaskt1m8AccountModel();
                    $visit = array();
                    for ($i = 1; $i < count($userList); $i++) {
                      $visit[$i] = false;
                    }
                    $userModel->dfs("Chart Of Accounts", "0", $userList, $visit, 0);

                    ?>
                 </ul>
               </div>
             <?php } ?>
           </div>
           <?php if ($permission->method('chart_of_account', 'update')->access() || $permission->method('chart_of_account', 'create')->access()) : ?>
             <div class="col-md-6" id="newform" style="padding-left: 20px;">
               <form name="form" id="form" action="<?php echo base_url('account/accounts/insertCoa') ?>" method="post" enctype="multipart/form-data">
                 <div id="newData">
                   <table width="100%" border="0" cellspacing="0" cellpadding="5">

                     <tr>
                       <td><?php echo get_phrases(['head', 'code']); ?></td>
                       <td><input type="text" name="txtHeadCode" id="txtHeadCode" class="form-control" value="<?php echo $role_reult->HeadCode ?>" readonly="readonly" /></td>
                     </tr>
                     <tr>
                       <td><?php echo get_phrases(['head', 'name']); ?></td>
                       <td><input type="text" name="txtHeadName" id="txtHeadName" class="form-control" value="<?php echo $role_reult->HeadName ?>" />
                         <input type="hidden" name="HeadName" id="HeadName" class="form-control" value="<?php echo $role_reult->HeadName ?>" required="required" />
                       </td>
                     </tr>
                     <tr>
                       <td><?php echo get_phrases(['parent', 'head']); ?></td>
                       <td><input type="text" name="txtPHead" id="txtPHead" class="form-control" readonly="readonly" value="<?php echo $role_reult->PHeadName ?>" /></td>
                     </tr>
                     <tr>

                       <td><?php echo get_phrases(['head', 'label']); ?></td>
                       <td><input type="text" name="txtHeadLevel" id="txtHeadLevel" class="form-control" readonly="readonly" value="<?php echo $role_reult->HeadLevel ?>" /></td>
                     </tr>
                     <tr>
                       <td><?php echo get_phrases(['head', 'type']); ?></td>
                       <td><input type="text" name="txtHeadType" id="txtHeadType" class="form-control" readonly="readonly" value="<?php echo $role_reult->HeadType ?>" /></td>
                     </tr>

                     <!--  -->
                 

                   </table>
                 </div>
               </form>
               <br>
             <?php endif; ?>
             </div>
         </div>

       </div>
     </div>
   </div>
 </div>
 <script src="<?php echo base_url()?>/assets/plugins/jQuery/jquery.min.js"></script>
 <script type="text/javascript">
$(document).on("submit", "form", function(event)
{
    event.preventDefault();        
    $.ajax({
        url: $(this).attr("action"),
        type: $(this).attr("method"),
        dataType: "JSON",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function (data, status)
        {
          var status = data.status;
         if(status == 1){
           var id = '"'+data.id+'"';
           var str = "'";
           var loadid = id.trim();
          var htmldata = '<i class="presentation"></i>'+ data.HeadName +'<span class="bal_opening"></span><span class="bal_span_pre"></span>';
           var htmlinserted = '<li role="treeitem" aria-selected="true" aria-level="'+ data.level +'" aria-levelledby="'+data.id +'_anchor" id="'+data.id +'" class="jstree-node jstree-leaf"><i class="jstree-icon jstree-ocl" role="presentation"></i><a class="jstree-anchor form-control jstree-clicked" href="javascript:" tabindex="-1" onclick="loadData('+str+data.id+str+')" id="'+data.id +'_anchor"><i class="presentation"></i>'+ data.HeadName +'<span class="bal_opening"></span><span class="bal_span_pre"></span></a></li>';
           if(data.type == 2){
           $('#'+data.id + "_anchor").html(htmldata);
           }
           if(data.type == 1){
            $('#'+data.PHeadCode+'_anchor').removeClass("jstree-clicked");
            $("ul>li#"+data.PHeadCode).append(htmlinserted);
            $('#btnSave').hide();
            $('#btnUpdate').show();
            $('#btnDelete').show();
            $('#btnNew').removeAttr("onclick");
            $('#btnDelete').removeAttr("onclick");
            $("#btnNew").attr("onclick","newdata("+data.id+")");
            $("#btnDelete").attr("onclick","deleteCoa("+data.id+")");
           }
        toastr.success(data.message);
         }else{
          toastr.error(data.message);
         }
        },
        error: function (xhr, desc, err)
        {


        }
    });        
});
   

   var AppServCallData = function() {

   }

   function loadData(id) {
     $.ajax({
       url: _baseURL + 'account/accounts/selectedForm/' + id,
       type: "GET",
       dataType: "json",
       success: function(data) {
         $('#newform').html(data);
         $('#btnSave').hide();
       },
       error: function(jqXHR, textStatus, errorThrown) {
         alert('Error get data from ajax');
       }
     });
   }

   function newdata(id) {
     $.ajax({
       url: _baseURL + 'account/accounts/newForm/' + id,
       type: "GET",
       dataType: "json",
       success: function(data) {
         var headlabel = data.headlabel;
         $('#txtHeadCode').val(data.headcode);
         document.getElementById("txtHeadName").value = '';
         $('#txtPHead').val(data.rowdata.HeadName);
         $('#txtPHeadCode').val(data.rowdata.HeadCode);
         $('#txtHeadLevel').val(headlabel);
         $(".select2").select2();
         $('#btnSave').prop("disabled", false);
         $('#btnSave').show();
         $('#btnUpdate').hide();
         $('#btnDelete').hide();
         
       },
       error: function(jqXHR, textStatus, errorThrown) {
         alert('Error get data from ajax');
       }
     });
   }

   function deleteCoa(id){
    if (confirm("Are You Sure To Want to Delete ?")) {
      $.ajax({
       url: _baseURL + 'account/accounts/deletecoa/' + id,
       type: "GET",
       dataType: "json",
       success: function(data) {
         var status = data.success;
         if(status == true){
           toastr.success(data.message);
           $('#'+id).remove();
           $("#newform").html('<h1>Tree child Deleted</h1>');
         }else{
          toastr.error(data.message);
          location.reload();
         }
       
         
       },
       error: function(jqXHR, textStatus, errorThrown) {
         alert('Error get data from ajax');
       }
     });
    }else{
      return false;
    }
   }



     $("#IsTransaction").change(function() {
       var checked = $(this).is(':checked');
       if (checked) {

         $(this).prop("checked", true);
         $("#IsTransaction").val(1);

       } else {
         $(this).prop("checked", false);
         $("#IsTransaction").val('');
       }
     });



   


     $("#IsGL").change(function() {
       var checked = $(this).is(':checked');
       if (checked) {

         $(this).prop("checked", true);
         $("#IsGL").val(1);

       } else {
         $(this).prop("checked", false);
         $("#IsGL").val('');
       }
     });
   

/*chart of account subtype*/
"use strict";
  function isSubType_change(stype){
     if($('#' + stype).is(":checked")) {
        $.ajax({
            url : _baseURL + "account/accounts/getsubtype/",
            type: "GET",
            dataType: "json",
            success: function(data)
            {   
                if(data == "") {
                     $('#subtypeContent').html('');
                     $('#subtypeContent').hide(); 
                }    else {
                      $('#subtypeContent').html(data);
                      $('#subtypeContent').show();    
                }     
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    } else {
         $('#subtypeContent').html('');
         $('#subtypeContent').hide(); 
    }
 }

 $(document).ready(function () {

    $("#treesearch").on("keyup", function() {
      $("#html").jstree("open_all"); 
  var value = this.value.toLowerCase().trim();
  $(".jstree-children,.jstree-node").show().filter(function() {
    return $(this).text().toLowerCase().trim().indexOf(value) == -1;
  }).hide();
});
});



 </script>
 <script src="<?php echo base_url() ?>/assets/plugins/vakata-jstree/dist/jstree.js"></script>
 <!--Page Active Scripts(used by this page)-->
 <script src="<?php echo base_url(); ?>/assets/dist/js/pages/tree-view.active.js?v=1.1"></script>