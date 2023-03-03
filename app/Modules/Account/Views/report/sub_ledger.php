<link href="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<div class="row">
     <div class="col-sm-12 col-md-12">
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
                    <?php echo form_open_multipart('account/reports/sub_ledger_report', 'id="glForm"');?>
                    <div class="row" id="">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label"><?php echo get_phrases(['subtype'])?></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="subtype" id="subtype" onchange="showAccountSubhead(this.value);" required>
                                    <option></option>
                                    <?php
                                    foreach($general_ledger as $g_data){
                                        ?>
                                        <option value="<?php echo $g_data->id;?>"><?php echo $g_data->subtypeName;?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label"><?php  echo get_phrases(['account','head'])?></label>
                            <div class="col-sm-8">
                                <select name="accounthead" class="form-control" id="accounthead" onchange="showTransationSubhead(this.value);">

                                </select>
                            </div>
                        </div> 
                         <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label"><?php  echo get_phrases(['transaction','head'])?></label>
                            <div class="col-sm-8">
                                <select name="subcode" class="form-control" id="subcode">

                                </select>
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label"><?php echo get_phrases(['from','date']) ?></label>
                            <div class="col-sm-8">
                                <input type="text" name="dtpFromDate" value="<?php echo date('Y-m-d');?>" placeholder="<?php echo get_phrases(['date']) ?>" class="datepicker form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label"><?php echo get_phrases(['to','date']) ?></label>
                            <div class="col-sm-8">
                                <input type="text"  name="dtpToDate" value="<?php echo date('Y-m-d');?>" placeholder="<?php echo get_phrases(['date']) ?>" class="datepicker form-control">
                            </div>
                        </div>
                       
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo get_phrases(['find']) ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close();?>
                    </div> 
             </div>
       </div>
 </div>
 <script src="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
 <script>
       "use strict"; 
 function showTransationSubhead(id){  

 $('#subcode').html('');   
    var id = $("#subtype").val(); 
    var url = _baseURL + "account/reports/getSubcodes/" + id;   
   
    $.ajax({
        url : url,
        type: "GET",
        dataType: "json",
        success: function(data)
        {          
          if(data != '') {                      
            $('#subcode').html(data);            
          }            
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data for inner');
        }
    });
}

/*get account subhead*/
 "use strict"; 
 function showAccountSubhead(id){  
  $('#accounthead').html('');
    var url = _baseURL + "account/reports/getSubAccountHead/" + id;      
    $.ajax({
        url : url,
        type: "GET",
        dataType: "json",
        success: function(data)
        {        
           
          if(data != '') {    
                    
            $('#accounthead').html(data); 
            showTransationSubhead(id);           
          }            
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

$(document).ready(function() {
        "use strict";
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        });

    });
"use strict";
function printDiv() {
    var divName = "printArea";
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;

    window.print();
    document.body.innerHTML = originalContents;
}
 </script>