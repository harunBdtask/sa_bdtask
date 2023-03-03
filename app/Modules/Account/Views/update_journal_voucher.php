<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="btn-group"> 
                    <a href="<?= base_url('accounts/accounts/aprove_v')?>" class="btn btn-primary"> <i class="fa fa-list"></i>  <?php echo makeString(['voucher_approval','list']) ?> </a>  
                </div>
            </div>
            <div class="panel-body">
                  
                <?= form_open_multipart('accounts/accounts/update_journal_voucher') ?>
                    <div class="row">
                      <div class="col-sm-6 col-md-6">
                           <div class="form-group row">
                              <label for="mainHead" class="col-sm-4 col-form-label"><?php echo display('head_of_account')?><i class="text-danger">*</i></label>
                              <div class="col-sm-8">
                                <?= form_dropdown('mainHead', $mainHead, $COAHC, 'class="form-control" id="mainHead" onchange="load_childCode(this.value,1)"');?>
                              </div>
                              <input type="hidden" name="ppCode" id="ppCode" value="<?= $COAHC;?>">
                          </div>
                      </div>
                      <div class="col-sm-3 col-md-3">
                          <div class="form-group row">
                            <label for="date" class="col-sm-2 col-form-label"> <?php echo display('date')?></label>
                            <div class="col-sm-10">
                                 <input type="text" name="dtpDate" id="dtpDate" class="form-control datepicker" value="<?php echo $journal_info[0]->VDate;?>">
                            </div>
                          </div> 
                        </div>
                        <div class="col-sm-3 col-md-3">
                          <div class="form-group row">
                            <label for="vo_no" class="col-sm-4 col-form-label"> <?php echo display('voucher_no')?></label>
                            <div class="col-sm-8">
                                 <input type="text" name="txtVNo" id="txtVNo" value="<?php echo $journal_info[0]->VNo; ?>" class="form-control" readonly>
                            </div>
                           </div> 
                        </div>
                     </div> 

                       <div class="table-responsive" style="margin-top: 10px">
                            <table class="table table-bordered table-hover" id="debtAccVoucher"> 
                                <thead>
                                     <tr>
                                        <th class="text-center"> <?php echo display('account_name')?></th>
                                        <th class="text-center"><?php echo makeString(['sub', 'account_name'])?></th>
                                        <th class="text-center"><?php echo makeString(['sub','sub', 'account_name'])?></th>
                                        <th class="text-center"> <?php echo display('code')?></th>
                                        <th class="text-center"> <?php echo display('debit')?></th>
                                        <th class="text-center"> <?php echo display('credit')?></th>
                                        <th class="text-center"> <?php echo display('action')?></th>  
                                      </tr>
                                </thead>
                                <tbody id="debitvoucher">
                                        <?php
                                        $sl = 1;
                                        $dbt = $cdt = 0;
                                        foreach ($journal_info as $single) {
                                            ?>
                                            <tr>
                                                 <td class="" style="width: 200px;">  
                                                 <?php echo form_dropdown('ppCode[]',$PPAccounts, $single->PPCCode,'class="form-control select2" id="ppCode_'.$sl.'" onchange="load_ccCode(this.value, '.$sl.')"') ?>
                                                 </td>

                                                 <td class="" style="width: 200px;">  
                                                 <?php echo form_dropdown('pppCode[]',$single->PPCC, $single->PPPCCode,'class="form-control select2" id="pppCode_'.$sl.'" onchange="load_cccCode(this.value, '.$sl.')"') ?>
                                                 </td>

                                                <td class="" style="width: 200px;">  
                                                   <?php echo form_dropdown('cmbCode[]',$single->PPPCC, $single->COAID,'class="form-control" id="cmbCode_'.$sl.'" onchange="load_code(this.value,'.$sl.')"') ?>
                                                 </td>
                                                <td><input type="text" name="txtCode[]"  class="form-control text-center"  id="txtCode_<?php echo $sl; ?>" required value="<?php echo $single->COAID; ?>"></td>
                                                <td><input type="text" name="txtAmount[]" class="form-control total_price text-right" value="<?php echo $single->Debit;
                                                            $dbt += $single->Debit; ?>" placeholder="0"  id="txtAmount_<?php echo $sl; ?>" onkeyup="calculation(<?php echo $sl; ?>)" >
                                                </td>
                                                <td>
                                                    <input type="text" name="txtAmountcr[]"  class="form-control total_price1 text-right" value="<?php echo $single->Credit;
                                                            $cdt += $single->Credit; ?>" placeholder="0"  id="txtAmount1_1" onkeyup="calculation(<?php echo $sl; ?>)" >
                                                </td>
                                                <td>
                                                    <button style="text-align: right;" class="btn btn-danger red" type="button" value="Delete" onclick="deleteRow(this)"><i class="fa fa-trash-o"></i></button>
                                                </td>
                                            </tr>                              
                                            <?php
                                            $sl++;
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td >
                                                <input type="button" id="add_more" class="btn btn-info" name="add_more"  onClick="addaccount('debitvoucher');" value="<?php echo display('add_more') ?>" />
                                            </td>
                                            <td colspan="2" class="text-right"><label  for="reason" class="  col-form-label"><?php echo display('total') ?></label>
                                            </td>
                                            <td class="text-right">
                                                <input type="text" id="grandTotal" class="form-control text-right " name="grand_total" value="<?php echo number_format($dbt, 2, '.', ','); ?>" readonly="readonly"  placeholder="0"/>
                                            </td>
                                            <td class="text-right">
                                                <input type="text" id="grandTotal1" class="form-control text-right " name="grand_total1" value="<?php echo number_format($cdt, 2, '.', ','); ?>" readonly="readonly" placeholder="0"/>
                                            </td>
                                        </tr>
                                    </tfoot>                               
                             
                            </table>
                        </div>

                        <div class="row">
                          <div class="col-md-7">
                              <div class="form-group row">
                                  <label for="txtRemarks" class="col-sm-2 col-form-label"><?php echo display('remark')?></label>
                                  <div class="col-sm-10">
                                    <textarea  name="txtRemarks" id="txtRemarks" class="form-control"><?php echo $journal_info[0]->Narration?></textarea>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-5">
                              <div class="form-group row">
                                  <div class="col-sm-12 text-center">
                                      <input type="submit" id="add_receive" class="btn btn-success btn-large" name="update" value="<?php echo display('update') ?>" tabindex="9"/>
                                  </div>
                              </div>
                          </div>
                        </div>
                  <?php echo form_close() ?>
            </div>  
        </div>
    </div>
</div>

<script type="text/javascript">

  function load_code(id,sl){

    $.ajax({
        url : "<?php echo site_url('accounts/accounts/debit_voucher_code/')?>" + id,
        type: "GET",
        dataType: "json",
        success: function(data)
        {
          
           $('#txtCode_'+sl).val(data);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
    function load_childCode(id, sl){
    $.ajax({
        url : "<?php echo site_url('accounts/accounts/getChildAccountByPCode/')?>" + id,
        type: "GET",
        dataType: "json",
        success: function(data){
          if(data.status==true){
            $('#ppCode').val(id);
            $('#ppCode_'+sl).html(data.message);
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
  }

  function load_ccCode(id, sl){
    
    $.ajax({
        url : "<?php echo site_url('accounts/accounts/getChildAccountByPCode/')?>" + id,
        type: "GET",
        dataType: "json",
        success: function(data){
          if(data.status==true){
            $('#ccCode').val(id);
           $('#pppCode_'+sl).html(data.message);
          }else{
            $('#pppCode_'+sl).html('');
            load_code(id,sl);
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
  }

  function load_cccCode(id, sl){
    
    $.ajax({
        url : "<?php echo site_url('accounts/accounts/getAllChildAccountByPCode/')?>" + id,
        type: "GET",
        dataType: "json",
        success: function(data){
          if(data.status==true){
            $('#cmbCode_'+sl).html(data.message);
          }else{
            $('#cmbCode_'+sl).html('');
            load_code(id,sl);
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
  }

    function addaccount(divName){
        var row = $("#debtAccVoucher tbody tr").length;
        var count = row + 1;
        var limits = 500;
        var tabin = 0;
        var hcode = document.getElementById('ppCode').value;
        if (hcode != ''){

          if (count == limits){ alert("You have reached the limit of adding " + count + " inputs");
          }else {
            var newdiv = document.createElement('tr');
            var tabin1="ppCode_"+count;
            var tabin="cmbCode_"+count;
            var tabindex = count * 2;
            newdiv = document.createElement("tr");
            newdiv.innerHTML = "<td><select name='ppCode[]' id='ppCode_"+count+"' class='form-control' onchange='load_ccCode(this.value,"+ count +")'></select></td><td><select name='pppCode[]' id='pppCode_"+count+"' class='form-control' onchange='load_cccCode(this.value,"+ count +")'></select></td><td><select name='cmbCode[]' id='cmbCode_"+count+"' class='form-control' onchange='load_code(this.value,"+ count +")'></select></td><td><input type='text' name='txtCode[]' class='form-control text-center'  id='txtCode_" + count + "' ></td><td><input type='text' name='txtAmount[]' class='form-control total_price text-right' value='' placeholder='0' id='txtAmount_" + count + "' onkeyup='calculation(" + count + ")'></td><td><input type='text' name='txtAmountcr[]' class='form-control total_price1 text-right' id='txtAmount1_" + count + "' value='' placeholder='0' onkeyup='calculation(" + count + ")'></td><td><button style='text-align: right;' class='btn btn-danger red' type='button' value='Delete' onclick='deleteRow(this)'><i class='fa fa-trash-o'></i></button></td>";
            
            document.getElementById(divName).appendChild(newdiv);
            // load child head code
            load_childCode(hcode, count);
            document.getElementById(tabin1).focus();
            count++;
             
            $("select.form-control:not(.dont-select-me)").select2({
                placeholder: "Select option",
                allowClear: true
            });
          }
        }else{
          alert("Please select head of accounts!");
          document.getElementById('mainHead').focus();
        }
    }

    function calculation(sl) {
        var gr_tot1 = 0;
        var gr_tot = 0;
        $(".total_price").each(function () {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });

        $(".total_price1").each(function () {
            isNaN(this.value) || 0 == this.value.length || (gr_tot1 += parseFloat(this.value))
        });
        $("#grandTotal").val(gr_tot.toFixed(2, 2));
        $("#grandTotal1").val(gr_tot1.toFixed(2, 2));
    }
    function deleteRow(e) {
        var t = $("#debtAccVoucher > tbody > tr").length;
        if (1 == t) alert("There only one row you can't delete.");
        else {
            var a = e.parentNode.parentNode;
            a.parentNode.removeChild(a)
        }
        calculation()
    }

</script>